<?php

namespace BET\Models\Services;

use Slim\Container;
use BET\Models\Services\AbstractServiceModel;
use BET\Models\Contracts\IModel;

class ServiceUser extends AbstractServiceModel
{
    public function __construct( Container $c, IModel $user )
    {
        parent::__construct( $c, $user );
    }


    /**
     * Retorna o usuário que tem email cadastrado e ativo
     * @param
     * @return type $array como os dados do usuário
     */
    public function getUserByEmail()
    {
        try {
            $sql = "SELECT * FROM {$this->model->getTable()} WHERE use_email = ? ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue( 1, $this->model->getEmail() );
            $stmt->execute();

            $user = $stmt->fetchAll(\PDO::FETCH_OBJ);

            if ( $user ?? null ) {
                $arr = $this->model->getFieldList();
                $metodo = $this->getMetodoName( "set", $arr[$this->model->getPk()] );
                $this->model->{$metodo}( $user[0]->use_id );

                return $this->find();
            }

            return null;

        } catch ( \Exception $e ) {
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível obter usuário por email!' );
            return null;
        }
    }


    /**
     * Retorna os usuários em ordem decrescente
     * @param
     * @return type $array como os dados dos usuários
     */
    public function listarUsuarios( $pagina )
    {
        try {
            //Calcula à partir de qual valor será exibido
            $inicio = ( $this->model->getQtd() * $pagina ) - $this->model->getQtd();

            $this->numTotal = $this->count();

            // $sql = "SELECT * FROM {$this->model->getTable()} LIMIT ?, ?";

            $sql = "";
            $sql .= "SELECT u.use_id, u.use_first_name, u.use_last_name, u.use_gender, u.use_birthday, c.cid_id, c.cid_desc, u.use_email, u.use_status, r.rol_desc, u.use_created_at, u.use_created_by, u.use_updated_at, u.use_updated_by ";
            $sql .= "FROM tbl_users AS u ";
            $sql .= "JOIN tbl_cidades AS c ON c.cid_id = u.cid_id ";
            $sql .= "JOIN tbl_roles AS r ON r.rol_id = u.rol_id ";
            $sql .= "ORDER BY u.use_first_name, u.use_last_name ";
            $sql .= "LIMIT ?, ? ";

            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $inicio, \PDO::PARAM_INT );
            $stmt->bindValue( 2, $this->model->getQtd(), \PDO::PARAM_INT );

            $this->makeArrayPagination( $pagina );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os usuários!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os usuários!' );
            return null;
        }

    }

}
