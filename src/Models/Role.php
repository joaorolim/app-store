<?php
declare(strict_types=1);

namespace BET\Models;

use BET\Models\AbstractModel;

class Role extends AbstractModel
{
    /**
     * Atributos obrigatórios a todo Model
     */
    protected $table = 'tbl_roles';

    // array para mapear os atributos da classe com os campos da Tabela do Banco
    protected $fieldList = array(
        'rol_id' => 'id',
        'rol_desc' => 'name',
        'rol_obs' => 'obs',
        'rol_created_at' => 'created_at',
        'rol_created_by' => 'created_by',
        'rol_updated_at' => 'updated_at',
        'rol_updated_by' => 'updated_by'
    );
    protected $pk = 'rol_id'; // igual ao da Tabela do Banco
    protected $qtd = 10;     // A quantidade de linhas a serem exibidas por página (Atributos para paginação)

    /**
     * Atributos particulares de cada Model
     */
    private $id;
    private $name;
    private $obs;

    // private $permissions;

    public function __construct(string $name = null)
    {
        $this->name = $name;
        $this->permissions = [];
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
    public function setId(int $id): Role
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
     * @return Role
     */
    public function setName(string $name): Role
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
    public function setObs(string $obs): Role
    {
        $this->obs = $obs;
        return $this;
    }

    /**
     * @return array
     */
    // public function getPermissions(): array
    // {
    //     return $this->permissions;
    // }

    /**
     * @param Permission $permission
     * @return Role
     */
    // public function setPermissions(Permission $permission): Role
    // {
    //     $this->permissions[] = $permission;
    //     return $this;
    // }

}
