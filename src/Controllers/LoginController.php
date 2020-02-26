<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // Se usuário já estiver logado, não permite voltar à página de login
        if ( isset($_SESSION['user']) ) {
            return $response->withRedirect('/adm/home');
        }

        if ($request->getMethod() === "POST") {
            $this->login($request, $response, $args);
        } elseif ($request->getMethod() === "GET") {
            $this->formLogin($request, $response, $args);
        }
    }


    private function formLogin(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->c->renderer->render($response, 'template_login.php', [
            'viewName' => 'login'
        ]);
    }


    public function login(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $allPostVars = $request->getParsedBody(); // recupera todas a variáveis do vetor $_POST
        $email = filter_var( strtolower( trim($allPostVars['email']) ), FILTER_SANITIZE_EMAIL );
        $pass = trim( $allPostVars['pass'] );

        if ( ! $this->validateEmail($email) ) {
            setMessage('Email inválido!', 'danger');
            redirect( getBaseURL().'adm/login' );
        }

        $objUser = $this->c->User;
        $objUser->setEmail( $email );

        $objServiceUser = $this->c->ServiceUser;
        $objServiceUser = $objServiceUser->getUserByEmail();

        if ( $objServiceUser ?? null ) {

            if ( ! ((int)$objServiceUser->getModel()->getStatus() === 1) ) {
                setMessage('Cadastro ainda não confirmado! Por favor, contate o administrador.', 'info');
                $objServiceUser->closeConn();
                redirect( getBaseURL().'adm/login' );
            }

            $bcrypt = $this->c->Bcrypt;

            if ( $bcrypt->check_password($pass, $objServiceUser->getModel()->getSenha()) ) {

                $userData['id'] = $objServiceUser->getModel()->getId();
                $userData['first_name'] = $objServiceUser->getModel()->getFirstName();
                $userData['last_name'] = $objServiceUser->getModel()->getLastName();
                $userData['email'] = $objServiceUser->getModel()->getEmail();
                $userData['gender'] = $objServiceUser->getModel()->getGender();
                $userData['birthday'] = $objServiceUser->getModel()->getBirthday();
                $userData['status'] = $objServiceUser->getModel()->getStatus();
                $userData['role'] = $objServiceUser->getModel()->getRole();
                $userData['logged'] = 1;

                $_SESSION['user'] = $userData;

                $objServiceUser->closeConn();

                // Verifica se há atendimento ativo
                // $objAtendimento = $this->c->Atendimento;
                // $objServiceAtendimento = $this->c->ServiceAtendimento;
                // $idAtendimento = $objServiceAtendimento->getAtendimentoAtivo( $userData['id'] );
                // $_SESSION['idAtendimento'] = ( isset($idAtendimento[0]->ate_id) ) ? $idAtendimento[0]->ate_id : null;

                redirect( getBaseURL().'adm/home' );
            }
        }

        setMessage('Email e/ou Senha inválido(s)!', 'danger');
        redirect( getBaseURL().'adm/login' );
    }


    public function logout(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $_SESSION['user'] = null;
        unset($_SESSION['user']);
        return $response->withRedirect('/adm/login');
        exit();
    }


    /**
     * stackoverflow.com/questions/19522092/should-i-use-filter-var-to-validate-email
    */
    private function validateEmail($email = '')
    {
        if (empty($email)) {
            return false;
        }

        if ( ! filter_var((string) $email, FILTER_VALIDATE_EMAIL) ) {
            return false;
        }

        return true;
    }
}
