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
     * Retorna a quantidade de atendimentos por tipo
     * @param
     * @return type $array como os dados dos atendimentos
     */
    public function getAtendimentosByTipo( )
    {
        try {

            $sql = "";
            $sql .= "SELECT tipo, COUNT(tipo) AS 'qtd' ";
            $sql .= "FROM dashboard ";
            $sql .= "GROUP BY tipo ";

            $stmt = $this->db->prepare( $sql );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os atendimentos deste usuário!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os atendimentos deste usuário!' );
            return null;
        }

    }


    /**
     * Retorna a quantidade de atendimentos por Assistência
     * @param
     * @return type $array como os dados dos atendimentos
     */
    public function getAtendimentosByAssistencia( )
    {
        try {

            $sql = "";
            $sql .= "SELECT assistencia, COUNT(assistencia) AS 'qtd' ";
            $sql .= "FROM dashboard ";
            $sql .= "GROUP BY assistencia ";
            $sql .= "ORDER BY qtd DESC ";

            $stmt = $this->db->prepare( $sql );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os atendimentos deste usuário!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os atendimentos deste usuário!' );
            return null;
        }

    }

    /**
     * Retorna a quantidade de atendimentos por valor do produto
     * @param
     * @return type $array como os dados dos atendimentos
     */
    public function getAtendimentosByValor( )
    {
        try {

            $sql = "";
            $sql .= "SELECT new_range as 'range', COUNT(*) AS 'qtd' ";
            $sql .= "FROM ( ";
            $sql .= "SELECT CASE ";
            $sql .= "WHEN valor_roduto BETWEEN 0 AND 1000 THEN ' 0-1000' ";
            $sql .= "WHEN valor_roduto BETWEEN 1001 AND 2000 THEN '1001-2000' ";
            $sql .= "WHEN valor_roduto BETWEEN 2001 AND 3000 THEN '2001-3000' ";
            $sql .= "ELSE '3001-191000' END AS new_range ";
            $sql .= "FROM dashboard) t ";
            $sql .= "GROUP BY new_range ";

            $stmt = $this->db->prepare( $sql );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os atendimentos deste usuário!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os atendimentos deste usuário!' );
            return null;
        }

    }

    /**
     * Retorna a quantidade de atendimentos por modelo do produto
     * @param
     * @return type $array como os dados dos atendimentos
     */
    public function getAtendimentosByModelo( )
    {
        try {

            $sql = "";
            $sql .= "SELECT new_range as 'modelo', COUNT(*) AS 'qtd' ";
            $sql .= "FROM ( ";
            $sql .= "SELECT CASE ";
            $sql .= "WHEN modelo = 'F3116' THEN 'F3116' ";
            $sql .= "WHEN modelo = 'G3116' THEN 'G3116' ";
            $sql .= "WHEN modelo = 'F3313' THEN 'F3313' ";
            $sql .= "WHEN modelo = 'F3216' THEN 'F3216' ";
            $sql .= "WHEN modelo = 'E5653' THEN 'E5653' ";
            $sql .= "WHEN modelo = 'E5643' THEN 'E5643' ";
            $sql .= "WHEN modelo = 'G3226' THEN 'G3226' ";
            $sql .= "WHEN modelo = 'F3115' THEN 'F3115' ";
            $sql .= "ELSE 'Outros' END AS new_range ";
            $sql .= "FROM dashboard) t ";
            $sql .= "GROUP BY new_range ";
            $sql .= "ORDER BY qtd DESC ";

            $stmt = $this->db->prepare( $sql );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os atendimentos deste usuário!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os atendimentos deste usuário!' );
            return null;
        }

    }

}
