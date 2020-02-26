<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        //a pagina atual
        $pagina = ( isset( $args['page'] ) ) ? (int)$args['page'] : 1;
        $_SESSION['pagina'] = $pagina;

        $csrf = $this->c->CSRF;
        $csrf::clearFakeId();

        $objUser = $this->c->User;
        $objServiceUser = $this->c->ServiceUser;
        $result = $objServiceUser->listarUsuarios( $pagina );

        $columns = array( "Nome","Sobrenome","Sexo","Data Nasc.","Cidade","Email","Status","Papel","Criado em", "Criado por", "Alterado em", "Alterado por" );
        $jump = array('cid_id');
        $table = $this->makeTable( $columns, $result, $objServiceUser->getModel()->getPk(), 'adm/user/excluir/', 'adm/user/alterar/', $jump );

        $arrayPaginacao = $objServiceUser->getArrayPaginacao();

        $paginacao = $this->makePageControllers( $arrayPaginacao, 'adm/user' );

        $objServiceUser->closeConn();

        return $this->c->renderer->render($response, 'template_admin.php', [
            'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
            'paginacao' => $paginacao,
            'viewName' => 'user/cadastro-user',
            'table' => $table,
            'arrGender' => $arrGender = getGenderArray(),
            'hiddenFormInput' => $csrf::generateHiddenFormInput()
        ]);
    }

    public function cadastrar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // Verifica se há ataque por CSRF
        $csrf = $this->c->CSRF;
        $csrf::check();

        // recebe todas as variáveis que vieram por $_POST
        $postVars = $request->getParsedBody();
        $fakeId = $postVars['fakeId'] ?? null;

        $nome = $postVars['nome'] ?? null;
        setOld( 'nome', $nome);

        $sobrenome = $postVars['sobrenome'] ?? null;
        setOld( 'sobrenome', $sobrenome);

        $sexo = $postVars['gender'] ?? null;
        setOld( 'gender', $sexo);

        $dt_nasc = $postVars['dt_nasc'] ?? null;
        setOld( 'dt_nasc', $dt_nasc);

        $strCidade = $postVars['cidade'] ?? null;
        setOld( 'cidade', $strCidade);

        $email = $postVars['email'] ?? null;
        setOld( 'email', $email);

        $senha = $postVars['senha'] ?? null;
        $senha2 = $postVars['senha2'] ?? null;

        $status = 1;
        $role = 2; // Usuário padrão

        $pieces = explode(",", $strCidade);
        $strCidade = trim( $pieces[0] );
        $uf = trim( $pieces[1] );
        $pais = trim( $pieces[2] );

        $objCidade = $this->c->Cidade;
        $objServiceCidade = $this->c->ServiceCidade;
        $cidade = $objServiceCidade->getIdCidadeFromApi( $strCidade, $uf, $pais );

        // Validações - start
        $fields = array(
            'nome'      => $nome,
            'sobrenome' => $sobrenome,
            'sexo'      => $sexo,
            'dt_nasc'   => $dt_nasc,
            'email'     => $email,
            'senha'     => $senha,
            'senha2'    => $senha2,
            'cidade'    => $cidade
        );

        // Let's define the rules and filters

        $rules = array(
            'nome'      => 'required|max_len,30|min_len,2',
            'sobrenome' => 'required|max_len,150|min_len,2',
            'sexo'      => "required|exact_len,1|contains,'m' 'f' 'o'",
            'dt_nasc'   => 'required|date,d/m/Y',
            'email'     => 'required|valid_email|unique',
            'senha'     => 'required|max_len,100|min_len,7',
            'senha2'    => 'equalsfield,senha',
            'cidade'    => 'required|integer|exist_city_id,1',
        );

        $filters = array(
            'nome'      => 'trim',
            'sobrenome' => 'trim',
            'sexo'      => 'trim|sanitize_string',
            'dt_nasc'   => 'trim',
            'email'     => 'trim|sanitize_email',
            'senha'     => 'trim|sanitize_string',
            'senha2'    => 'trim|sanitize_string',
            'cidade'    => 'trim|sanitize_string',
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
            return $response->withRedirect('/adm/user');
        }
        // Validações - finish

        $bcrypt = $this->c->Bcrypt;
        $passwordHash = $bcrypt->hash_password( $fields['senha'] );

        $date = date( 'Y-m-d H:i:s' );


        $objUser = $this->c->User;
        $objServiceUser = $this->c->ServiceUser;

        $pagina = $_SESSION['pagina'] ?? 1;

        if ( ! ($fakeId ?? null) ) {
            // INSERT

            $objUser->setFirstName( $fields['nome'] )
                    ->setLastName( $fields['sobrenome'] )
                    ->setGender( $fields['sexo'] )
                    ->setBirthday( dataBr_to_dataMySQL($fields['dt_nasc']) )
                    ->setCity( $fields['cidade'] )
                    ->setEmail( $fields['email'] )
                    ->setSenha( $passwordHash )
                    ->setStatus( $status )
                    ->setRole( $role )
                    ->setCreatedAt( $date )
                    ->setCreatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] )
                    ->setUpdatedAt( $date )
                    ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );

            $result = $objServiceUser->insert();

            if ( $result && $result > 0 ) {
                setMessage('Usuário cadastrado com sucesso!', 'success');
                clearOld(); // apaga os valores antigos dos campos de um formulário
            }
        }

        $objServiceUser->closeConn();
        return $response->withRedirect('/adm/user/pagina/'.$pagina);
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

            $oldId = getOld( 'id' );
            $id = $csrf::getRealId( $fakeId );
            setOld( 'id', $id);

            // Não permite a alteração caso o id tenha sido modificado
            if ( ! ($id === $oldId) ) {
                setMessage('Este usuário não pode ser alterado!', 'danger');
                return $response->withRedirect('/adm/user');
            }

            $nome = $postVars['nome'] ?? null;
            setOld( 'nome', $nome);

            $sobrenome = $postVars['sobrenome'] ?? null;
            setOld( 'sobrenome', $sobrenome);

            $sexo = $postVars['gender'] ?? null;
            setOld( 'gender', $sexo);

            $dt_nasc = $postVars['dt_nasc'] ?? null;
            setOld( 'dt_nasc', $dt_nasc);

            $strCidade = $postVars['cidade'] ?? null;
            setOld( 'cidade', $strCidade);

            $email = $postVars['email'] ?? null;
            setOld( 'email', $email);

            $status = ( (int)$postVars['status'] === 1 ) ? 1 : 0;
            setOld( 'status', (int)$status);

            $role = $postVars['role'] ?? null;
            $role = $csrf::getRealId( $role );
            setOld( 'role', $role);

            $pieces = explode(",", $strCidade);
            $strCidade = trim( $pieces[0] );
            $uf = trim( $pieces[1] );
            $pais = trim( $pieces[2] );

            $objCidade = $this->c->Cidade;
            $objServiceCidade = $this->c->ServiceCidade;
            $cidade = $objServiceCidade->getIdCidadeFromApi( $strCidade, $uf, $pais );

            // Validações - start
            $fields = array(
                'nome'      => $nome,
                'sobrenome' => $sobrenome,
                'sexo'      => $sexo,
                'dt_nasc'   => $dt_nasc,
                'email'     => $email,
                'status'    => $status,
                'role'      => $role,
                'cidade'    => $cidade
            );

            // Let's define the rules and filters

            $rules = array(
                'nome'      => 'required|max_len,30|min_len,2',
                'sobrenome' => 'required|max_len,150|min_len,2',
                'sexo'      => "required|exact_len,1|contains,'m' 'f' 'o'",
                'dt_nasc'   => 'required|date,d/m/Y',
                'email'     => 'required|valid_email|unique',
                'status'    => "required|exact_len,1|integer|contains_list,0;1",
                'role'      => 'required|integer|min_numeric,2',
                'cidade'    => 'required|integer|exist_city_id,1',
            );

            $filters = array(
                'nome'      => 'trim',
                'sobrenome' => 'trim',
                'sexo'      => 'trim|sanitize_string',
                'dt_nasc'   => 'trim',
                'email'     => 'trim|sanitize_email',
                'status'    => 'trim|sanitize_string',
                'role'      => 'trim|sanitize_string',
                'cidade'    => 'trim|sanitize_string',
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
                $route = ( $fakeId ?? null ) ? ('/adm/user/alterar/'.$fakeId) : ('/adm/user');
                return $response->withRedirect( $route );
            }
            // Validações - finish

            $date = date( 'Y-m-d H:i:s' );

            $objUser = $this->c->User;
            $objServiceUser = $this->c->ServiceUser;

            $pagina = $_SESSION['pagina'] ?? 1;

            if ( $fakeId ?? null ) {
                $id = $csrf::checkPostFakeId();

                $objUser->setId( $id );
                $objServiceUser = $objServiceUser->find();

                if ( $objServiceUser ?? null ) {

                    $objServiceUser->getModel()->setFirstName( $nome )
                                               ->setLastName( $sobrenome )
                                               ->setGender( $sexo )
                                               ->setBirthday( dataBr_to_dataMySQL($dt_nasc) )
                                               ->setCity( $cidade )
                                               ->setEmail( $email )
                                               ->setStatus( $status )
                                               ->setRole( $role )
                                               ->setUpdatedAt( $date )
                                               ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );

                    if ( $objServiceUser->update() ) {
                        $objServiceUser->closeConn();
                        setMessage('Usuário alterado com sucesso!', 'success');
                        clearOld(); // apaga os valores antigos dos campos de um formulário
                        return $response->withRedirect('/adm/user/pagina/'.$pagina);
                    }
                }
            }

        } elseif ($request->getMethod() === "GET") {
            // mostra o formulário de alteração

            $fakeId = $args['id'] ?? 0;

            $csrf = $this->c->CSRF;
            $id = $csrf::getRealId( $fakeId );
            setOld( 'id', $id );

            $user['id'] = $fakeId;

            $objUser = $this->c->User;
            $objUser->setId( $id );

            $objServiceUser = $this->c->ServiceUser;
            $objServiceUser = $objServiceUser->find();

            if ( $objServiceUser ?? null ) {

                $nome = getOld('nome');
                $user['nome'] = ($nome) ? $nome : $objServiceUser->getModel()->getFirstName();

                $sobrenome = getOld('sobrenome');
                $user['sobrenome'] = ($sobrenome) ? $sobrenome : $objServiceUser->getModel()->getLastName();

                $gender = getOld('gender');
                $user['gender'] = ($gender) ? $gender : $objServiceUser->getModel()->getGender();

                $dt_nasc = getOld('dt_nasc');
                $user['dt_nasc'] = ($dt_nasc) ? $dt_nasc : dataMySQL_to_dataBr( $objServiceUser->getModel()->getBirthday() );

                $idCidade = $objServiceUser->getModel()->getCity();
                $objServiceCidade = $this->c->ServiceCidade;
                $strCidade = $objServiceCidade->getStrCidade( $idCidade );

                $cidade = getOld('cidade');
                $user['strCidade'] = ($cidade) ? $cidade : $strCidade;

                $email = getOld('email');
                $user['email'] = ($email) ? $email : $objServiceUser->getModel()->getEmail();

                $status = getOld('status');
                $user['status'] = ( isset($status) ) ? (int)$status : $objServiceUser->getModel()->getStatus();

                $userRole = getOld('role');
                $userRole = ($userRole) ? $userRole : $objServiceUser->getModel()->getRole();

                // Não permite a alteração do Admin via Sistema
                if ( $userRole === 1 ) {
                    setMessage('Este usuário não pode ser alterado!', 'info');
                    return $response->withRedirect('/adm/user');
                }

                $objRole = $this->c->Role;
                $objServiceRole = $this->c->ServiceRole;
                $roles = $objServiceRole->listAll();


                foreach ($roles as $key => $role) {
                    // Exclui o Admin
                    if ( $role['rol_id'] === '1' ) {
                        unset( $roles[$key] );
                        continue;
                    }

                    if ( (int)$role['rol_id'] === (int)$userRole ) {
                        $roles[$key]['selected'] = true;
                    } else {
                        $roles[$key]['selected'] = false;
                    }

                    $roles[$key]['rol_id'] = $csrf::generateFakeId( $role['rol_id'] );
                    unset( $roles[$key]['rol_created_at'] );
                    unset( $roles[$key]['rol_created_by'] );
                    unset( $roles[$key]['rol_updated_at'] );
                    unset( $roles[$key]['rol_updated_by'] );
                }

                $objServiceUser->closeConn();
                $objServiceCidade->closeConn();
                $objServiceRole->closeConn();

                $arrStatus = array(
                    'Ativo' => 1,
                    'Inativo' => 0
                );

                $hiddenFormFakeId = '<input type="hidden" name="fakeId" value="' . $fakeId . '">';

                return $this->c->renderer->render($response, 'template_admin.php', [
                    'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
                    'viewName' => 'user/alterar-user',
                    'hiddenFormInput' => $csrf::generateHiddenFormInput(),
                    'hiddenFormFakeId' => $hiddenFormFakeId,
                    'arrGender' => getGenderArray(),
                    'arrStatus' => $arrStatus,
                    'arrRoles' => $roles,
                    'user' => $user
                ]);
            }

        }

        setMessage('Usuário não encontrado!', 'danger');
        return $response->withRedirect('/adm/user');
    }


    public function deletar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $fakeId = $args['id'] ?? 0;

        $csrf = $this->c->CSRF;
        $id = $csrf::getRealId( $fakeId );

        $objUser = $this->c->User;
        $objUser->setId( $id );

        $objServiceUser = $this->c->ServiceUser;
        $objServiceUser = $objServiceUser->find();

        $pagina = $_SESSION['pagina'] ?? 1;

        if ( $objServiceUser != null AND $objServiceUser->delete() ) {
            setMessage('Usuário deletado com sucesso!', 'success');
            $objServiceUser->closeConn();
        }

        if ( ! hasMessage() ) {
            setMessage('Erro ao tentar deletar usuário!', 'danger');
        }

        return $response->withRedirect('/adm/user/pagina/'.$pagina);
    }


    public function resetar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if ($request->getMethod() === "GET") {

            $fakeId = $args['id'] ?? 0;

            $bcrypt = $this->c->Bcrypt;
            $passwordHash = $bcrypt->hash_password( '1234567' );

            $date = date( 'Y-m-d H:i:s' );

            $objUser = $this->c->User;
            $objServiceUser = $this->c->ServiceUser;

            if ( $fakeId ?? null ) {
                $csrf = $this->c->CSRF;
                $id = $csrf::getRealId( $fakeId );

                $objUser->setId( $id );
                $objServiceUser = $objServiceUser->find();

                if ( $objServiceUser ?? null ) {

                    $objServiceUser->getModel()->setSenha( $passwordHash )
                                               ->setUpdatedAt( $date )
                                               ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );

                    if ( $objServiceUser->update() ) {
                        $objServiceUser->closeConn();
                        $userName = $objServiceUser->getModel()->getFirstName() .' '. $objServiceUser->getModel()->getLastName();
                        setMessage("Senha do usuário ({$userName}) resetada com sucesso!<br/>A nova senha é: <strong>1234567</strong><br/>Por favor, alterar a senha no primeiro acesso!", 'success');
                        clearOld();
                        return $response->withRedirect('/adm/user');
                    }
                }
            }

        }

        setMessage('Não foi possível resetar a senha do usuário!', 'danger');
        return $response->withRedirect('/adm/user');
    }

}
