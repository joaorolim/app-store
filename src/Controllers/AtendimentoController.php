<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AtendimentoController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        //a pagina atual
        $pagina = ( isset( $args['page'] ) ) ? (int)$args['page'] : 1;
        $_SESSION['pagina'] = $pagina;

        $csrf = $this->c->CSRF;
        $csrf::clearFakeId();

        $objServiceAtendimento = $this->c->ServiceAtendimento;

        if ( in_array('atendimento-all', $_SESSION['permissions']) ) {
            $result = $objServiceAtendimento->listarAtendimentos( $pagina );
        } else {
            $result = $objServiceAtendimento->listarAtendimentos( $pagina, $_SESSION['user']['id'] );
        }

        $columns = array( "Usuário","Munícipe","Data Fim","Criado em","Criado por","Alterado em","Alterado por","Observação" );
        $jump = array('use_id','mun_id');
        $table = $this->makeTable( $columns, $result, $objServiceAtendimento->getModel()->getPk(), 'adm/atendimento/excluir/', 'adm/atendimento/alterar/', $jump );

        $arrayPaginacao = $objServiceAtendimento->getArrayPaginacao();

        $paginacao = $this->makePageControllers( $arrayPaginacao, 'adm/atendimento' );

        $objServiceAtendimento->closeConn();

        return $this->c->renderer->render($response, 'template_admin.php', [
            'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
            'paginacao' => $paginacao,
            'viewName' => 'atendimento/cadastro-atend',
            'table' => $table,
            'hiddenFormInput' => $csrf::generateHiddenFormInput()
        ]);
    }

    public function cadastrar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        if ( isset( $_SESSION['idAtendimento'] ) && $_SESSION['idAtendimento'] > 0 ) {
            // Não permite cadastrar outro atendimento, enquanto o atendimento corrente não for finalizado
            setMessage( "Finalize o atendimento atual, antes de abrir outro!", 'danger');
            return $response->withRedirect('/adm/atendimento');
        }

        // Verifica se há ataque por CSRF
        $csrf = $this->c->CSRF;
        $csrf::check();

        // recebe todas as variáveis que vieram por $_POST
        $postVars = $request->getParsedBody();
        $fakeId = $postVars['fakeId'] ?? null;

        $cpf_atend = $postVars['cpf_atend'] ?? null;
        setOld( 'cpf_atend', $cpf_atend);
        $cpf_atend = limpaCepCpfCnpj( $cpf_atend );

        $obs_atend = $postVars['obs_atend'] ?? null;
        setOld( 'obs_atend', $obs_atend );

        // Validações - start
        $fields = array(
            'cpf_atend' => $cpf_atend,
            'obs_atend' => $obs_atend
        );

        // Let's define the rules and filters
        $rules = array(
            'cpf_atend' => 'exact_len,11|numeric|valid_cpf',
            'obs_atend' => 'max_len,300'
        );

        $filters = array(
            'cpf_atend' => 'trim',
            'obs_atend' => 'trim'
        );

        $date = date( 'Y-m-d H:i:s' );
        $objAtendimento = $this->c->Atendimento;
        $objServiceAtendimento = $this->c->ServiceAtendimento;
        $pagina = $_SESSION['pagina'] ?? 1;

        $objAtendimento->setUser( $_SESSION['user']['id'] )
                       ->setCreatedAt( $date )
                       ->setCreatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );


        // Caso o CPF não seja preenchido, entende-se que será realizada uma rotina administrativa
        if ( ! $cpf_atend == null ) {
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
                return $response->withRedirect('/adm/atendimento');
            }
            // Validações - end

            if ( ! ($fakeId ?? null) ) {
                // INSERT
                $municipe = $objServiceAtendimento->getMunicipeToApi( $fields['cpf_atend'] );

                $obs_atend = "(CPF: {$fields['cpf_atend']} ) {$fields['obs_atend']}";
                $objAtendimento->setObservacao( $obs_atend );

                if ( $municipe ) {
                    $objAtendimento->setMunicipe( $municipe[0]->mun_id );
                }

                $result = $objServiceAtendimento->insert();

                if ( $result && $result > 0 ) {
                    $_SESSION['idAtendimento'] = $result;
                    setMessage('Atendimento cadastrado com sucesso!', 'success');
                    clearOld();
                    $objServiceAtendimento->closeConn();

                    if ( isset($municipe[0]->mun_id) && $municipe[0]->mun_id > 0 ) {
                        $idMun = $csrf::generateFakeId( $municipe[0]->mun_id );
                        return $response->withRedirect('/adm/municipe/exibir/'.$idMun);
                    }
                    return $response->withRedirect('/adm/municipe/cpf/'.$fields['cpf_atend']);
                }
            }


        } else {
            // Rotina administrativa
            if ( ! ($fakeId ?? null) ) {
                // INSERT
                $obs_atend = "** Rotina Administrativa ** ".$fields['obs_atend'];

                $objAtendimento->setObservacao( $obs_atend );

                $result = $objServiceAtendimento->insert();

                if ( $result && $result > 0 ) {
                    $_SESSION['idAtendimento'] = $result;
                    setMessage('Atendimento cadastrado com sucesso!', 'success');
                    clearOld();
                    $objServiceAtendimento->closeConn();
                    return $response->withRedirect('/adm/municipe');
                }
            }
        }

        $objServiceAtendimento->closeConn();
        return $response->withRedirect('/adm/atendimento/pagina/'.$pagina);
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
                setMessage('Este atendimento não pode ser alterado!', 'danger');
                return $response->withRedirect('/adm/atendimento');
            }

            $observacao = $postVars['obs_atend'] ?? null;
            setOld( 'observacao', $observacao );

            // Validações - start
            $fields = array(
                'observacao' => $observacao,
            );

            // Let's define the rules and filters

            $rules = array(
                'observacao' => 'max_len,300',
            );

            $filters = array(
                'observacao' => 'trim',
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
                $route = ( $fakeId ?? null ) ? ('/adm/atendimento/alterar/'.$fakeId) : ('/adm/atendimento');
                return $response->withRedirect( $route );
            }
            // Validações - finish

            $date = date( 'Y-m-d H:i:s' );

            $objAtendimento = $this->c->Atendimento;
            $objServiceAtendimento = $this->c->ServiceAtendimento;

            $pagina = $_SESSION['pagina'] ?? 1;

            if ( $fakeId ?? null ) {
                $id = $csrf::checkPostFakeId();

                $objAtendimento->setId($id);
                $objServiceAtendimento = $objServiceAtendimento->find();

                if ( $objServiceAtendimento ?? null ) {

                    $objServiceAtendimento->getModel()->setObservacao( $fields['observacao'] )
                                                      ->setUpdatedAt( $date )
                                                      ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );


                    if ( $objServiceAtendimento->update() ) {
                        $objServiceAtendimento->closeConn();
                        setMessage('Atendimento alterado com sucesso!', 'success');
                        clearOld();
                        return $response->withRedirect('/adm/atendimento/pagina/'.$pagina);
                    }
                }
            }

        } elseif ($request->getMethod() === "GET") {
            // mostra o formulário de alteração

            $fakeId = $args['id'] ?? 0;

            $csrf = $this->c->CSRF;
            $id = $csrf::getRealId( $fakeId );
            setOld( 'id', $id );

            $objAtendimento = $this->c->Atendimento;
            $objAtendimento->setId( $id );

            $objServiceAtendimento = $this->c->ServiceAtendimento;
            $objServiceAtendimento = $objServiceAtendimento->find();

            if ( $objServiceAtendimento ?? null ) {

                $observacao = getOld('observacao');
                $observacao = ($observacao) ? $observacao : $objServiceAtendimento->getModel()->getObservacao();

                $objServiceAtendimento->closeConn();

                $hiddenFormFakeId = '<input type="hidden" name="fakeId" value="' . $fakeId . '">';

                return $this->c->renderer->render($response, 'template_admin.php', [
                    'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
                    'viewName' => 'atendimento/alterar-atend',
                    'hiddenFormInput' => $csrf::generateHiddenFormInput(),
                    'hiddenFormFakeId' => $hiddenFormFakeId,
                    'observacao' => $observacao
                ]);
            }
        }

        setMessage('Atendimento não encontrado!', 'danger');
        return $response->withRedirect('/adm/atendimento');
    }


    public function deletar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $fakeId = $args['id'] ?? 0;

        $csrf = $this->c->CSRF;
        $id = $csrf::getRealId( $fakeId );

        if ( isset( $_SESSION['idAtendimento'] ) && $_SESSION['idAtendimento'] > 0 && (int)$_SESSION['idAtendimento'] === (int)$id ) {
            // Não permite cadastrar outro atendimento, enquanto o atendimento corrente não for finalizado
            setMessage( "Finalize o atendimento atual, antes de deletá-lo!", 'danger');
            return $response->withRedirect('/adm/atendimento');
        }

        $objAtendimento = $this->c->Atendimento;
        $objAtendimento->setId( $id );

        $objServiceAtendimento = $this->c->ServiceAtendimento;
        $objServiceAtendimento = $objServiceAtendimento->find();

        $pagina = $_SESSION['pagina'] ?? 1;

        if ( $objServiceAtendimento != null AND $objServiceAtendimento->delete() ) {
            setMessage('Atendimento deletado com sucesso!', 'success');
            $objServiceAtendimento->closeConn();
        }

        if ( ! hasMessage() ) {
            setMessage('Erro ao tentar deletar atendimento!', 'danger');
        }

        return $response->withRedirect('/adm/atendimento/pagina/'.$pagina);
    }


    public function finalizar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $date = date( 'Y-m-d H:i:s' );
        $objAtendimento = $this->c->Atendimento;
        $objServiceAtendimento = $this->c->ServiceAtendimento;

        $objAtendimento->setId( $_SESSION['idAtendimento'] );
        $objServiceAtendimento = $objServiceAtendimento->find();

        if ( $objServiceAtendimento ?? null ) {

            $objServiceAtendimento->getModel()->setFinalizado( $date )
                                              ->setUpdatedAt( $date )
                                              ->setUpdatedBy( $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name'] );


            if ( $objServiceAtendimento->update() ) {
                $objServiceAtendimento->closeConn();
                $_SESSION['idAtendimento'] = null;
                unset($_SESSION['idAtendimento']);
                setMessage('Atendimento finalizado com sucesso!', 'success');
                clearOld();
                return $response->withRedirect('/adm/atendimento');
            }
        }

        setMessage('Erro ao tentar finalizar atendimento!', 'danger');
        return $response->withRedirect('/adm/atendimento');
    }


    // Retorna o munícipe de acordo com o cpf passado
    public function getMunicipeByCpf(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $cpf = $args['cpf'] ?? 0;

        $objAtendimento = $this->c->Atendimento;
        $objServiceAtendimento = $this->c->ServiceAtendimento;
        $response = $objServiceAtendimento->getMunicipeToApi( $cpf );

        if ( $response == null ) {
            $response = "Munícipe não encontrado!";
        }

        ob_clean();
        return json_encode( $response );
        die();
    }

}
