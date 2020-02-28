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


    public function getModulosByClient(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $idUser = 1; // Em um app real, seria o ID do usuário logado

        $csrf = $this->c->CSRF;

        $objAtendimento = $this->c->Atendimento;
        $objServiceAtendimento = $this->c->ServiceAtendimento;
        $response = $objServiceAtendimento->getModulosByClient( $idUser );

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
            $response = "Data not found";
        }

        ob_clean();
        return json_encode( $response );
        die();
    }


    public function desativaModulo(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $idMod = $args['id'] ?? 0;

        $idUser = 1; // Em um app real, seria o ID do usuário logado

        $csrf = $this->c->CSRF;

        $objAtendimento = $this->c->Atendimento;
        $objServiceAtendimento = $this->c->ServiceAtendimento;
        $response = $objServiceAtendimento->desativaModulo( $idUser, $idMod );

        if ( $response === false ) {
            $response = "Data not found.";
        }

        ob_clean();
        return json_encode( $response );
        die();
    }


}
