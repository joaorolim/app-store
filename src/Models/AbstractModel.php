<?php

namespace BET\Models;

use BET\Models\Contracts\IModel;

abstract class AbstractModel implements IModel
{
    protected $table;
    protected $fieldList = array(); // array com os campos da Tabela do Banco
    protected $pk = 'id';
    protected $qtd = 5;     // A quantidade de linhas a serem exibidas por página (Atributos para paginação)
    protected $created_at;
    protected $created_by;
    protected $updated_at;
    protected $updated_by;


    /**
     * @return String
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return array
     */
    public function getFieldList()
    {
        return $this->fieldList;
    }

    /**
     * @return String Nome do campo id, conforme Tabela do Banco
     */
    public function getPk()
    {
        return $this->pk;
    }


   /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     *
     * @return self
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     *
     * @return self
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQtd()
    {
        return $this->qtd;
    }

    /**
     * @param mixed $qtd
     *
     * @return self
     */
    public function setQtd($qtd)
    {
        $this->qtd = $qtd;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * @param mixed $qtd
     *
     * @return self
     */
    public function setCreatedBy($criado_por)
    {
        $this->created_by = $criado_por;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * @param mixed $qtd
     *
     * @return self
     */
    public function setUpdatedBy($alterado_por)
    {
        $this->updated_by = $alterado_por;

        return $this;
    }
}
