<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class RoleController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        //a pagina atual
        $pagina = ( isset( $args['page'] ) ) ? (int)$args['page'] : 1;
        $_SESSION['pagina'] = $pagina;

        $csrf = $this->c->CSRF;
        $csrf::clearFakeId();

        $objServiceRole = $this->c->ServiceRole;
        $result = $objServiceRole->list( $pagina );

        foreach ($result as $key => $obj) {
            if ( (int)$obj->rol_id === 1 OR (int)$obj->rol_id === 2 ) {  // Exclui o Admin e o Usuário Padrão, de tal forma que as configurações são realizadas
                unset($result[$key]);                                    // somente através do Banco
            }
        }

        $columns = array( "Papel","Observação","Criado em", "Criado por", "Alterado em", "Alterado por" );
        $table = $this->makeTable( $columns, $result, $objServiceRole->getModel()->getPk(), 'adm/role/excluir/', 'adm/role/alterar/' );

        $arrayPaginacao = $objServiceRole->getArrayPaginacao();

        $paginacao = $this->makePageControllers( $arrayPaginacao, 'adm/role' );

        $objServiceRole->closeConn();

        return $this->c->renderer->render($response, 'template_admin.php', [
            'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
            'paginacao' => $paginacao,
            'viewName' => 'role/cadastro-role',
            'table' => $table,
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

        $role = $postVars['papel'] ?? null;
        setOld( 'role', $role );

        $obs = $postVars['observacao'] ?? null;
        setOld( 'observacao', $obs );

        // Validações - start
        $fields = array(
            'role'        => $role,
            'observacao'  => $obs,
        );

        // Let's define the rules and filters

        $rules = array(
            'role'        => 'required|max_len,100|min_len,2',
            'observacao'  => 'required|max_len,250|min_len,2',
        );

        $filters = array(
            'role'        => 'trim',
            'observacao'  => 'trim',
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
            return $response->withRedirect('/adm/role');
        }
        // Validações - finish

        $date = date( 'Y-m-d H:i:s' );

        $objRole = $this->c->Role;
        $objServiceRole = $this->c->ServiceRole;

        $pagina = $_SESSION['pagina'] ?? 1;

        if ( ! ($fakeId ?? null) ) {
            // INSERT
            $objRole->setName( $fields['role'] )
                    ->setObs( $fields['observacao'] )
                    ->setCreatedAt( $date )
                    ->setCreatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] )
                    ->setUpdatedAt( $date )
                    ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );

            $result = $objServiceRole->insert();

            if ( $result && $result > 0 ) {
                setMessage('Papel cadastrado com sucesso!', 'success');
                clearOld();
            }
        }

        $objServiceRole->closeConn();
        return $response->withRedirect('/adm/role/pagina/'.$pagina);
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
                setMessage('Este país não pode ser alterado!', 'danger');
                return $response->withRedirect('/adm/pais');
            }

            $role = $postVars['papel'] ?? null;
            setOld( 'role', $role );

            $obs = $postVars['observacao'] ?? null;
            setOld( 'observacao', $obs );

            // Validações - start
            $fields = array(
                'role'        => $role,
                'observacao'  => $obs,
            );

            // Let's define the rules and filters

            $rules = array(
                'role'        => 'required|max_len,100|min_len,2',
                'observacao'  => 'required|max_len,250|min_len,2',
            );

            $filters = array(
                'role'        => 'trim',
                'observacao'  => 'trim',
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
                $route = ( $fakeId ?? null ) ? ('/adm/role/alterar/'.$fakeId) : ('/adm/role');
                return $response->withRedirect( $route );
            }
            // Validações - finish

            $date = date( 'Y-m-d H:i:s' );

            $objRole = $this->c->Role;
            $objServiceRole = $this->c->ServiceRole;

            $pagina = $_SESSION['pagina'] ?? 1;

            if ( $fakeId ?? null ) {
                $id = $csrf::checkPostFakeId();

                $objRole->setId($id);
                $objServiceRole = $objServiceRole->find();

                if ( $objServiceRole ?? null ) {

                    $objServiceRole->getModel()->setName( $fields['role'] )
                                               ->setObs( $fields['observacao'] )
                                               ->setUpdatedAt( $date )
                                               ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );


                    if ( $objServiceRole->update() ) {
                        $objServiceRole->closeConn();
                        setMessage('Papel alterado com sucesso!', 'success');
                        clearOld();
                        return $response->withRedirect('/adm/role/pagina/'.$pagina);
                    }
                }
            }

        } elseif ($request->getMethod() === "GET") {
            // mostra formulário de alteração

            $fakeId = $args['id'] ?? 0;

            $csrf = $this->c->CSRF;
            $id = $csrf::getRealId( $fakeId );
            setOld( 'id', $id );

            $objRole = $this->c->Role;
            $objRole->setId( $id );

            $objServiceRole = $this->c->ServiceRole;
            $objServiceRole = $objServiceRole->find();

            if ( $objServiceRole ?? null ) {

                $role = getOld('role');
                $papel['nome'] = ($role) ? $role : $objServiceRole->getModel()->getName();

                $obs = getOld('observacao');
                $papel['obs'] = ($obs) ? $obs : $objServiceRole->getModel()->getObs();

                $objServiceRole->closeConn();

                $hiddenFormFakeId = '<input type="hidden" name="fakeId" value="' . $fakeId . '">';

                return $this->c->renderer->render($response, 'template_admin.php', [
                    'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
                    'viewName' => 'role/alterar-role',
                    'hiddenFormInput' => $csrf::generateHiddenFormInput(),
                    'hiddenFormFakeId' => $hiddenFormFakeId,
                    'papel' => $papel
                ]);
            }
        }

        setMessage('Papel não encontrado!', 'danger');
        return $response->withRedirect('/adm/role');
    }


    public function deletar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $fakeId = $args['id'] ?? 0;

        $csrf = $this->c->CSRF;
        $id = $csrf::getRealId( $fakeId );

        $objRole = $this->c->Role;
        $objRole->setId( $id );

        $objServiceRole = $this->c->ServiceRole;
        $objServiceRole = $objServiceRole->find();

        $pagina = $_SESSION['pagina'] ?? 1;

        if ( $objServiceRole != null AND $objServiceRole->delete() ) {
            setMessage('Papel deletado com sucesso!', 'success');
            $objServiceRole->closeConn();
        }

        if ( ! hasMessage() ) {
            setMessage('Erro ao tentar deletar papel!', 'danger');
        }

        return $response->withRedirect('/adm/role/pagina/'.$pagina);
    }

}
