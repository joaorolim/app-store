<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class AtendimentoController extends ControllerAbstract
{
    public function getModulos(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $objAtendimento = $this->c->Atendimento;
        $objServiceAtendimento = $this->c->ServiceAtendimento;
        $response = $objServiceAtendimento->getModulos();

        if ( $response == null ) {
            $response = "Dados não encontrados!";
        }

        ob_clean();
        return json_encode( $response );
        die();
    }


    public function ativaModulo(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $idMod = $args['id'] ?? 0;

        $idUser = 1; // Em um app real, seria o ID do usuário logado

        $csrf = $this->c->CSRF;

        $objAtendimento = $this->c->Atendimento;
        $objServiceAtendimento = $this->c->ServiceAtendimento;
        $response = $objServiceAtendimento->ativaModulo( $idUser, $idMod );

        if ( $response === false ) {
            $response = "Dados não encontrados!";
        }

        ob_clean();
        return json_encode( $response );
        die();
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
