<?php

namespace BET\Auth;

/**
 * Ultimate PHPerguntas
 *
 * Este script faz parte do Projeto Prático do curso Ultimate PHP.
 * O Ultimate PHP é um curso voltado para iniciantes e intermediários em PHP.
 * Conheça o curso Ultimate PHP acessando http://www.ultimatephp.com.br
 *
 * O projeto completo está disponível no Github: https://github.com/beraldo/UltimatePHPerguntas
 *
 * @author: Roberto Beraldo Chaiben
 * @package Ultimate PHPerguntas
 * @link http://www.ultimatephp.com.br
 */

/**
 * Classe para proteção contra CSRF
 */
class CSRF
{
    /**
     * Nome do campo oculto do formulário que receberá o token gerado
     */
    const HIDDEN_FORM_INPUT_NAME = "_token";


    /**
     * Nome da variável de sessão onde será armazenado o token gerado
     */
    const SESSION_KEY_NAME = "_csrf_token";



    /**
     * Gera um token
     */
    public static function generateToken()
    {
        $token = md5( uniqid( microtime( true ) ) );

        if ( isset( $_SESSION ) )
        {
            $_SESSION[self::SESSION_KEY_NAME] = $token;
        }

        return $token;
    }


    /**
     * Gera o HTML do campo oculto do formulário, com um valor válido para o token
     * @return string String HTML do campo oculto
     */
    public static function generateHiddenFormInput()
    {
        $token = self::generateToken();

        $input = '<input type="hidden" name="' . self::HIDDEN_FORM_INPUT_NAME . '" value="' . $token . '">';

        return $input;
    }



    /**
     * Compara os tokens da sessão e do POST. Se forem diferentes, é uma tentativa de ataque CSRF.
     */
    public static function check()
    {
        $postedToken = isset( $_POST[self::HIDDEN_FORM_INPUT_NAME] ) ? $_POST[self::HIDDEN_FORM_INPUT_NAME] : null;
        $sessionToken = isset( $_SESSION[self::SESSION_KEY_NAME] ) ? $_SESSION[self::SESSION_KEY_NAME] : null;

        // remove o token da sessão, pois só deve ser usado uma vez
        $_SESSION[self::SESSION_KEY_NAME] = null;

        if ( $postedToken != $sessionToken )
        {
            // echo "Tentativa de ataque por CSRF";
            // exit;
            setMessage('Tentativa de ataque por CSRF!', 'danger');
            redirect( getBaseURL().'adm/home' );
        }
    }

    /**
     * Compara os tokens da sessão e do GET. Se forem diferentes, é uma tentativa de ataque CSRF.
     */
    public static function getMethodCheck()
    {
        $postedToken = isset( $_GET[self::HIDDEN_FORM_INPUT_NAME] ) ? $_GET[self::HIDDEN_FORM_INPUT_NAME] : null;
        $sessionToken = isset( $_SESSION[self::SESSION_KEY_NAME] ) ? $_SESSION[self::SESSION_KEY_NAME] : null;

        // remove o token da sessão, pois só deve ser usado uma vez
        $_SESSION[self::SESSION_KEY_NAME] = null;

        if ( $postedToken != $sessionToken )
        {
            //echo "Tentativa de ataque por CSRF";
            //exit;
            setMessage('Tentativa de ataque por CSRF!', 'danger');
            redirect( getBaseURL().'adm/home' );
        }
    }


    /**
     * Gera um id falso para exibir no formulário de alteração
    */
    public static function generateFakeId( $idReal )
    {
        $fakeId = md5( uniqid( microtime( true ) ) );

        if ( isset( $_SESSION ) )
        {
            $_SESSION['fake']['fakeId'][$fakeId] = $fakeId;
            $_SESSION['fake']['idReal'][$fakeId] = $idReal;
        }

        return $fakeId;
    }


    /**
     * Gera um id falso para exibir no formulário de alteração
    */
    public static function clearFakeId()
    {
        if ( isset( $_SESSION ) )
        {
            // remove o fakeId da sessão, pois só deve ser usado uma vez
            $_SESSION['fake'] = null;
            unset($_SESSION['fake']);
        }
    }


    /**
     * Compara os fakeId's da sessão e do POST. Se forem diferentes, é uma tentativa de alterar o id via inspetor do Browser
     */
    public static function checkPostFakeId()
    {
        $postedFakeId = isset( $_POST['fakeId'] ) ? $_POST['fakeId'] : null;
        $sessionFakeId = isset( $_SESSION['fake']['fakeId'][$postedFakeId] ) ? $_SESSION['fake']['fakeId'][$postedFakeId] : null;

        $idReal = isset( $_SESSION['fake']['idReal'][$postedFakeId] ) ? $_SESSION['fake']['idReal'][$postedFakeId] : null;

        // remove o fakeId da sessão, pois só deve ser usado uma vez
        $_SESSION['fake'] = null;
        unset($_SESSION['fake']);

        if ( $postedFakeId != $sessionFakeId )
        {
            // echo "Alteração de ID não permitida!";
            // exit;
            setMessage('Alteração de ID não permitida!', 'danger');
            redirect( getBaseURL().'adm/home' );
        }

        return $idReal;
    }


    /**
     * Retorna o "id real", dado um "fakeId"
     */
    public static function getRealId( $fakeId = null )
    {

        $idReal = isset( $_SESSION['fake']['idReal'][$fakeId] ) ? $_SESSION['fake']['idReal'][$fakeId] : null;

        if ( $idReal ?? null ) {
            return $idReal;
        }

        return null;
    }

}
