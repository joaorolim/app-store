<?php

namespace BET\Models;

use BET\Models\AbstractModel;

class Atendimento extends AbstractModel
{
    /**
     * Atributos obrigatórios a todo Model
     */
	protected $table = 'tbl_atendimentos';
    // array para mapear os atributos da classe com os campos da Tabela do Banco
    protected $fieldList = array(
        'ate_id' => 'id',
        'use_id' => 'user',
        'mun_id' => 'municipe',
        'ate_obs' => 'observacao',
        'ate_data_fim' => 'finalizado',
        'ate_created_at' => 'created_at',
        'ate_created_by' => 'created_by',
        'ate_updated_at' => 'updated_at',
        'ate_updated_by' => 'updated_by',
    );
    protected $pk = 'ate_id';
    protected $qtd = 100;     // A quantidade de linhas a serem exibidas por página (Atributos para paginação)

    /**
     * Atributos particulares de cada Model
     */
    private $id;
    private $user;
    private $municipe;
    private $observacao;
    private $finalizado;  // data em que o atendimento foi encerrado


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int  - id do usuário
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $id_user - id do usuário
     *
     * @return self
     */
    public function setUser( $id_user )
    {
        $this->user = $id_user;

        return $this;
    }

    /**
     * @return int  - id do munícipe
     */
    public function getMunicipe()
    {
        return $this->municipe;
    }

    /**
     * @param int $id_municipe - id do munícipe
     *
     * @return self
     */
    public function setMunicipe( $id_municipe )
    {
        $this->municipe = $id_municipe;

        return $this;
    }

    /**
     * @return String  - observação sobre o munícipe
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param String $observacao - observação sobre o munícipe
     *
     * @return self
     */
    public function setObservacao( $observacao )
    {
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * @return String - data de encerramento do atendimento
     */
    public function getFinalizado()
    {
        return $this->finalizado;
    }

    /**
     * @param String $data_fim - data de encerramento do atendimento
     *
     * @return self
     */
    public function setFinalizado( $data_fim )
    {
        $this->finalizado = $data_fim;

        return $this;
    }

}
