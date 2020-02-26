<?php

namespace BET\Models;

use BET\Models\AbstractModel;
use BET\Models\Contracts\UserAcl;

class User extends AbstractModel
{
    /**
     * Atributos obrigatórios a todo Model
     */
    protected $table = 'tbl_users';

    // array para mapear os atributos da classe com os campos da Tabela do Banco
    protected $fieldList = array(
        'use_id' => 'id',
        'use_first_name' => 'first_name',
        'use_last_name' => 'last_name',
        'use_gender' => 'gender',
        'use_birthday' => 'birthday',
        'use_email' => 'email',
        'use_senha' => 'senha',
        'use_status' => 'status',
        'rol_id' => 'role',
        'use_created_at' => 'created_at',
        'use_created_by' => 'created_by',
        'use_updated_at' => 'updated_at',
        'use_updated_by' => 'updated_by'
    );
    protected $pk = 'use_id'; // igual ao da Tabela do Banco
    protected $qtd = 10;     // A quantidade de linhas a serem exibidas por página (Atributos para paginação)

    /**
     * Atributos particulares de cada Model
     */
    private $id;
    private $first_name;
    private $last_name;
    private $gender;
    private $birthday;
    private $city;
    private $email;
    private $senha;
    private $status;
    private $role;


    /**
     * @return int $id
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
    public function setId( $id )
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     *
     * @return self
     */
    public function setFirstName( $first_name )
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     *
     * @return self
     */
    public function setLastName( $last_name )
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     *
     * @return self
     */
    public function setGender( $gender )
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     *
     * @return self
     */
    public function setBirthday( $birthday )
    {
        $this->birthday = $birthday;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return self
     */
    public function setEmail( $email )
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param mixed $senha
     *
     * @return self
     */
    public function setSenha( $senha )
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return self
     */
    public function setStatus( $status )
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int $id
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @param int $id_role
     *
     * @return self
     */
    public function setRole( $id_role )
    {
        $this->role = $id_role;

        return $this;
    }
}
