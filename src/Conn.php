<?php
namespace BET;

use Slim\Container;

class Conn implements IConn
{
	private $settings;
    private $c;

	function __construct( Container $c )
	{
		$this->settings = $c->get('settings')['db'];
        $this->c = $c;
	}

    public function connect()
    {
        if ($this->settings['driver'] == 'mysql') {
            $db = $this->connectMysql();
        }

        return $db;
    }

	protected function connectMysql()
	{
		try
		{
            $options = array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            );
            $dbName = $this->settings['database'];
            $host = $this->settings['host'];
            $username = $this->settings['username'];
            $password = $this->settings['password'];

            $dsn = "mysql:dbname={$dbName};host={$host}";

			$conn = new \PDO($dsn,$username,$password,$options);
            $conn->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

			return $conn;
		}
		catch (\Exception $e)
		{
            $code = $e->getCode();

            if ( $code == 2002 ) {
                $this->c->logger->addAlert( $e->getMessage().' - It was not possible to connect to MySQL database' );
            } else {
                $this->c->logger->addAlert( $e->getMessage() );
            }

            setMessage( 'Não foi possível conectar ao Banco de Bados', 'danger' );
			redirect( getBaseURL().'adm/login' );
            exit();
		}
	}
}
