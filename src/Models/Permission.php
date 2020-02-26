<?php
declare(strict_types=1);

namespace BET\Models;

use BET\Models\AbstractModel;

class Permission extends AbstractModel
{
    /**
     * Atributos obrigatórios a todo Model
     */
    protected $table = 'tbl_permissions';

    // array para mapear os atributos da classe com os campos da Tabela do Banco
    protected $fieldList = array(
        'per_id' => 'id',
        'per_desc' => 'name',
        'per_obs' => 'obs',
        'per_created_at' => 'created_at',
        'per_updated_at' => 'updated_at'
    );
    protected $pk = 'per_id'; // igual ao da Tabela do Banco
    protected $qtd = 5;     // A quantidade de linhas a serem exibidas por página (Atributos para paginação)

    /**
     * Atributos particulares de cada Model
     */
    private $id;
    private $name;
    private $obs;


    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Role
     */
    public function setId(int $id): Permission
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Permission
     */
    public function setName(string $name): Permission
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getObs(): string
    {
        return $this->obs;
    }

    /**
     * @param string $obs
     * @return Role
     */
    public function setObs(string $obs): Permission
    {
        $this->obs = $obs;
        return $this;
    }

}
