<?php

namespace BET\Models;

use BET\Models\AbstractModel;

class Atendimento extends AbstractModel
{
    /**
     * Atributos obrigatórios a todo Model
     */
	//protected $table = 'tbl_atendimentos';

    // array para mapear os atributos da classe com os campos da Tabela do Banco
    // protected $fieldList = array(
    //     'ate_id' => 'id',
    //     'use_id' => 'user',
    //     'mun_id' => 'municipe',
    //     'ate_obs' => 'observacao',
    //     'ate_data_fim' => 'finalizado',
    //     'ate_created_at' => 'created_at',
    //     'ate_created_by' => 'created_by',
    //     'ate_updated_at' => 'updated_at',
    //     'ate_updated_by' => 'updated_by',
    // );
    // protected $pk = 'ate_id';
    // protected $qtd = 100;     // A quantidade de linhas a serem exibidas por página (Atributos para paginação)

    /**
     * Atributos particulares de cada Model
     */
    // private $id;
    // private $user;
    // private $municipe;
    // private $observacao;
    // private $finalizado;  // data em que o atendimento foi encerrado


    /**
     * @return mixed
     */
    // public function getId()
    // {
    //     return $this->id;
    // }

    /**
     * @param mixed $id
     *
     * @return self
     */
    // public function setId($id)
    // {
    //     $this->id = $id;

    //     return $this;
    // }


}
