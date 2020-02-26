<?php
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
 * Classe estendida da classe PDO para manipulação do banco de dados
 */
class DB extends \PDO
{
    protected $dsn  = 'mysql:dbname=bd_balcao_empregos;host=localhost';
    protected $username = 'root';
    protected $password = 'mysql';

    public function __construct( $dsn = null, $username = null, $password = null, $options = array() )
    {
        $dsn = ( $this->dsn != null ) ? $this->dsn : sprintf( 'mysql:dbname=%s;host=%s', MYSQL_DBNAME, MYSQL_HOST );
        $username = ( $this->username != null ) ? $this->username : MYSQL_USER;
        $password = ( $this->password != null ) ? $this->password : MYSQL_PASS;
        $options = array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        );

        parent::__construct( $dsn, $username, $password, $options );
    }
}
