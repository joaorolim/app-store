<?php

namespace BET\Models\Services;

use Slim\Container;
use BET\Models\Services\AbstractServiceModel;
use BET\Models\Contracts\IModel;

class ServiceAtendimento extends AbstractServiceModel
{
    public function __construct( Container $c, IModel $atendimento )
    {
        parent::__construct( $c, $atendimento );
    }

    /**
     * Retorna os módulos cadastrados
     * @param
     * @return String
     */
    public function getModulos()
    {
        try {

            $sql = 'SELECT * FROM tbl_modulos ';

            $stmt = $this->db->prepare( $sql );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os dados!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os dados!' );
            return null;
        }

    }

    /**
     * Ativa o módulo de acordo com o ID
     * @param  $idMod  - ID do módulo que será ativado
     * @param  $idUser - ID do usuário/cliente que está logado na aplicação
     * @return boolean
     */
    public function ativaModulo( $idUser, $idMod )
    {
        try {

            $this->db->beginTransaction();

            $res = $this->getModuloByClient($idUser, $idMod);

            if ( isset($res[0]->mcl_ativo) ) {
                // UPDATE
                if ( (int)$res[0]->mcl_ativo === 0 ) {

                    $status = 1; // ativar

                    if ( ! $this->updateModuloByClient($idUser, $idMod, $status) ) {
                        $this->db->rollback();
                        return false;
                    }

                } else {
                    $this->db->rollback();
                    return false;
                }

            } else {
                // INSERT
                $resp = $this->getModuloById($idMod);

                if ( isset($resp[0]->mod_id) && (int)$resp[0]->mod_id === (int)$idMod ) {

                    $date = date( 'Y-m-d H:i:s' );
                    $user = "Rolim";  // Em um app real, seria o nome do usuário logado

                    $dadosModCli = array();

                    $array = [
                        'use_id'         => $idUser,
                        'mod_id'         => $idMod,
                        'mcl_ativo'      => 1,
                        'mcl_created_at' => $date,
                        'mcl_created_by' => $user
                    ];

                    array_push($dadosModCli, $array);

                    if ( ! $this->saveModuloByClient( $dadosModCli ) ) {
                        $this->db->rollback();
                        return false;
                    }

                } else {
                    $this->db->rollback();
                    return false;
                }

            }

            $this->db->commit();

            return true;

        } catch ( \Exception $e ) {
            setMessage( 'Problemas na transaction!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Problemas na transaction!' );
            return null;
        }

    }


    /**
     * Desativa o módulo de acordo com o ID
     * @param  $idMod  - ID do módulo que será desativado
     * @param  $idUser - ID do usuário/cliente que está logado na aplicação
     * @return boolean
     */
    public function desativaModulo( $idUser, $idMod )
    {
        $this->db->beginTransaction();

        $res = $this->getModuloByClient($idUser, $idMod);

        if ( isset($res[0]->mcl_ativo) ) {
            // UPDATE
            if ( (int)$res[0]->mcl_ativo === 1 ) {

                $status = 0; // desativar

                if ( ! $this->updateModuloByClient($idUser, $idMod, $status) ) {
                    $this->db->rollback();
                    return false;
                }

            } else {
                $this->db->rollback();
                return false;
            }

        } else {
            $this->db->rollback();
            return false;
        }

        $this->db->commit();
        return true;
    }


    /**
     * Retorna o módulo contratado pelo cliente/usuário
     * @param $idUser - ID do cliente/usuário
     * @param $idMod  - ID do módulo
     * @return object
     */
    public function getModuloByClient( $idUser, $idMod )
    {
        try {

            $sql = 'SELECT * FROM tbl_modulos_clientes WHERE use_id = ? AND mod_id = ? ';

            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $idUser,  \PDO::PARAM_INT );
            $stmt->bindValue( 2, $idMod,   \PDO::PARAM_INT );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os dados!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os dados!' );
            return null;
        }

    }


    /**
     * Retorna todos os módulos contratados pelo cliente/usuário
     * @param $idUser - ID do cliente/usuário
     * @return object
     */
    public function getModulosByClient( $idUser )
    {
        try {

            $sql = 'SELECT * FROM tbl_modulos_clientes WHERE use_id = ?  ';

            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $idUser,  \PDO::PARAM_INT );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os dados!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os dados!' );
            return null;
        }

    }


    /**
     * Retorna o módulo cadastrado de acordo com o ID
     * @param $idMod - ID do módulo
     * @return obj
     */
    public function getModuloById( $idMod )
    {
        try {

            $sql = 'SELECT * FROM tbl_modulos WHERE mod_id = ? ';

            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $idMod, \PDO::PARAM_INT );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os dados!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os dados!' );
            return null;
        }

    }


    /**
     * Ativa os módulos cadastrados de acordo com o cliente/usuário
     * @param
     * @return boolean
     */
    public function updateModuloByClient( $idUser, $idMod, $status )
    {
        try {

            $sql = 'UPDATE tbl_modulos_clientes SET mcl_ativo = ? WHERE use_id = ? AND mod_id = ? ';

            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $status,  \PDO::PARAM_INT );
            $stmt->bindValue( 2, $idUser,  \PDO::PARAM_INT );
            $stmt->bindValue( 3, $idMod,   \PDO::PARAM_INT );

            return $stmt->execute();

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível atualizar os dados!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível atualizar os dados!' );
            return null;
        }

    }


    /**
     * Método para salvar um ou mais relacionamentos entre módulos e clientes
     * @param array $dados - Array com os id's dos clientes e os id's dos módulos
     * @return bool  - Retorna true se a transação foi completada com sucesso
     */
    public function saveModuloByClient( array $dados ): bool
    {
        if ( parent::insertBatch( "tbl_modulos_clientes", $dados ) ) {
            return true;
        }

        return false;
    }

}
