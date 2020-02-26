<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class PerfilController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $csrf = $this->c->CSRF;
        $csrf::clearFakeId();

        $fakeId = $csrf::generateFakeId( $_SESSION['user']['id'] );

        $user['nome'] = $_SESSION['user']['first_name'];

        $user['sobrenome'] = $_SESSION['user']['last_name'];

        switch ( $_SESSION['user']['gender'] ) {
            case 'm':
                $user['sexo'] = 'Masculino';
                break;

            case 'f':
                $user['sexo'] = 'Feminino';
                break;

            case 'o':
                $user['sexo'] = 'Outros';
                break;
        }

        $user['dt_nasc'] = dataMySQL_to_dataBr( $_SESSION['user']['birthday'] );

        $user['email'] = $_SESSION['user']['email'];

        $user['status'] = ( (int)$_SESSION['user']['status'] === 1) ? 'Ativo' : 'Inativo';

        $objRole = $this->c->Role;
        $objRole->setId( $_SESSION['user']['role'] );

        $objServiceRole = $this->c->ServiceRole;
        $objServiceRole = $objServiceRole->find();

        $user['role'] = $objServiceRole->getModel()->getName();

        $hiddenFormFakeId = '<input type="hidden" name="fakeId" value="' . $fakeId . '">';


        return $this->c->renderer->render($response, 'template_admin.php', [
            'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
            'viewName' => 'perfil/alterar-perfil',
            'user' => $user,
            'hiddenFormFakeId' => $hiddenFormFakeId,
            'hiddenFormInput' => $csrf::generateHiddenFormInput()
        ]);
    }

    public function alterar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if ($request->getMethod() === "POST") {
            // UPDATE

            // Verifica se há ataque por CSRF
            $csrf = $this->c->CSRF;
            $csrf::check();

            // recebe todas as variáveis que vieram por $_POST
            $postVars = $request->getParsedBody();
            $fakeId = $postVars['fakeId'] ?? null;

            $senha = $postVars['senha'] ?? null;
            $senha2 = $postVars['senha2'] ?? null;

            // Validações - start
            $fields = array(
                'senha'     => $senha,
                'senha2'    => $senha2
            );

            // Let's define the rules and filters

            $rules = array(
                'senha'     => 'required|max_len,100|min_len,7',
                'senha2'    => 'equalsfield,senha'
            );

            $filters = array(
                'senha'     => 'trim|sanitize_string',
                'senha2'    => 'trim|sanitize_string'
            );

            $validation = $this->Validation( $fields, $rules, $filters );

            if ( $validation['result'] === true ) {

                $fields = $validation['fields'] ; // You can now use POST data safely

            } else {

                $errors = $validation['errors'];
                $message = "";
                foreach ($errors as $key => $value) {
                    $message .= $value . "<br>";
                }

                setMessage( $message, 'danger');

                if ( $fakeId ?? null ) {
                    return $response->withRedirect( '/adm/perfil' );
                }

                clearOld();
                return $response->withRedirect( '/adm/home' );
            }
            // Validações - finish

            $bcrypt = $this->c->Bcrypt;
            $passwordHash = $bcrypt->hash_password( $fields['senha'] );

            $date = date( 'Y-m-d H:i:s' );

            $objUser = $this->c->User;
            $objServiceUser = $this->c->ServiceUser;

            if ( $fakeId ?? null ) {
                $id = $csrf::checkPostFakeId();

                $objUser->setId( $id );
                $objServiceUser = $objServiceUser->find();

                if ( $objServiceUser ?? null ) {

                    $objServiceUser->getModel()->setSenha( $passwordHash )
                                               ->setUpdatedAt( $date )
                                               ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );

                    if ( $objServiceUser->update() ) {
                        $objServiceUser->closeConn();
                        setMessage('Senha do usuário alterada com sucesso!', 'success');
                        clearOld();
                        return $response->withRedirect('/adm/home');
                    }
                }
            }

        }

        setMessage('Não foi possível alterar a senha do usuário!', 'danger');
        return $response->withRedirect('/adm/perfil');
    }

}
