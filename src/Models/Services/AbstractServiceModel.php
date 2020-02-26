<?php

namespace BET\Models\Services;

use Slim\Container;
use BET\Models\Contracts\IServiceModel;
use BET\Models\Contracts\IModel;

abstract class AbstractServiceModel implements IServiceModel
{
    /**
     * @var Container
     */
    protected $c;

    protected $model;
	protected $db;

    /**
     * Atributos para paginação
     */
    protected $numTotal;        // Total de registro da tabela (do banco de dados)
    protected $exibir = 3;      // Define o valor máximo a ser exibida na página tanto para direita quando para esquerda (class="navegação")
    protected $arrayPaginacao;  // Array com os itens de paginação que serão enviados para a view


	public function __construct(Container $c, IModel $model)
	{
        $this->c = $c;
        $this->model = $model;
		$this->db = $this->c->db->connect();
	}


    public function getArrayPaginacao()
    {
        return $this->arrayPaginacao;
    }


    public function getModel()
    {
        return $this->model;
    }


    public function closeConn()
    {
        $this->db = null;
    }

    /**
     * @param string $type    - Tipo do método ("get" ou "set")
     *
     * @param string $field   - Nome do campo (exemplo: "first_name")
     *
     * @return string $metodo - Nome do método que será chamado (exemplo: "getFirstName")
     */
    protected function getMetodoName( $type, $field )
    {
        $arr = str_split($field);
        $name = "";
        $caps = true; //captalize
        foreach ($arr as $key => $value) {
            if ( $value == "_" ) {
                $caps = true;
                continue;
            } else {
                if ( $caps ) {
                    $name .= ucfirst($value);
                    $caps = false;
                } else {
                    $name .= $value;
                }
            }
        }

        $metodo = $type . $name;

        return $metodo;
    }

    public function getLastId()
    {
        try {

            $sql = "SELECT MAX({$this->model->getPk()}) FROM {$this->model->getTable()}";
            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            $lastId = $stmt->fetchColumn();

            $this->c->logger->addInfo("lastId: {$sql} ({$lastId})");

            return $lastId;

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível encontrar o último registro!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível encontrar o último registro!' );
            return null;
        }
    }

    public function count( $date_from = null, $date_to = null )
    {
        try {
            if ($date_from && $date_to) {
                $sql = "SELECT COUNT(*) FROM {$this->model->getTable()} WHERE (data BETWEEN ? AND ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue( 1, $date_from );
                $stmt->bindValue( 2, $date_to );
            } else {
                $sql = "SELECT COUNT(*) FROM {$this->model->getTable()}";
                $stmt = $this->db->prepare($sql);
            }

            $stmt->execute();

            return $stmt->fetchColumn();

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível contar os registros!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível contar os registros!' );
            return null;
        }
    }

    public function countWithFilter( $sql )
    {
        try {

            $stmt = $this->db->prepare($sql);

            $stmt->execute();

            return $stmt->fetchColumn();

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível contar os registros!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível contar os registros!' );
            return null;
        }
    }

	public function list( $pagina, $date_from = null, $date_to = null, $orderBy = null )
	{
        try {
            //Calcula à partir de qual valor será exibido
            $inicio = ( $this->model->getQtd() * $pagina ) - $this->model->getQtd();

            if ( $orderBy ) {
                $orderBy = " ORDER BY {$orderBy} ";
            }

            if ($date_from && $date_to) {
                $this->numTotal = $this->count( $date_from, $date_to );

                $sql = "SELECT * FROM {$this->model->getTable()} WHERE (data BETWEEN ? AND ?) $orderBy LIMIT ?, ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue( 1, $date_from );
                $stmt->bindValue( 2, $date_to );
                $stmt->bindValue( 3, $inicio, \PDO::PARAM_INT );
                $stmt->bindValue( 4, $this->model->getQtd(), \PDO::PARAM_INT );
            } else {
                $this->numTotal = $this->count();

                $sql = "SELECT * FROM {$this->model->getTable()} $orderBy LIMIT ?, ?";
                $stmt = $this->db->prepare( $sql );
                $stmt->bindValue( 1, $inicio, \PDO::PARAM_INT );
                $stmt->bindValue( 2, $this->model->getQtd(), \PDO::PARAM_INT );
            }

            $this->makeArrayPagination( $pagina );

            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar os registros!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar os registros!' );
            return null;
        }

	}

    public function listAll( $orderBy=null )
    {
        try {
            if ( $orderBy ) {
                $orderBy = " ORDER BY {$orderBy} ";
            }
            $sql = "SELECT * FROM {$this->model->getTable()} {$orderBy} ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível listar todos os registros!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível listar todos os registros!' );
            return null;
        }
    }

    public function search( $pagina, $column, $search, $orderBy=null )
    {
        try {
            $inicio = ( $this->model->getQtd() * $pagina ) - $this->model->getQtd();

            if ( $orderBy ) {
                $orderBy = " ORDER BY {$orderBy} ";
            }

            $this->numTotal = $this->countWithFilter( "SELECT COUNT(*) FROM {$this->model->getTable()} WHERE {$column} LIKE '%{$search}%' {$orderBy}" );

            $sql = "SELECT * FROM {$this->model->getTable()} WHERE {$column} LIKE '%{$search}%' {$orderBy} LIMIT ?, ?";
            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $inicio, \PDO::PARAM_INT );
            $stmt->bindValue( 2, $this->model->getQtd(), \PDO::PARAM_INT );

            $this->makeArrayPagination( $pagina );

            $stmt->execute();

            $this->c->logger->addInfo("search: {$sql} ({$inicio}, {$this->model->getQtd()})");

            return $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível procurar pelos registros!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível procurar pelos registros!' );
            return null;
        }
    }

    public function makeArrayPagination( $pagina )
    {
        //Monta o array que será enviado para a view

        //O calculo do Total de página ser exibido
        $totalPagina= ceil( $this->numTotal / $this->model->getQtd() );

        /**
        * Aqui montará o link que voltará uma página
        * Caso o valor seja zero, por padrão ficará o valor 1
        */
        $anterior  = ( ( $pagina - 1 ) == 0 ) ? 1 : $pagina - 1;

        /**
        * Aqui montará o link que vai para próxima página
        * Caso página +1 for maior ou igual ao total, ele terá o valor do total
        * caso contrário, ele pega o valor da página + 1
        */
        $posterior = ( ( $pagina+1 ) >= $totalPagina ) ? $totalPagina : $pagina+1;

        $this->arrayPaginacao = [
            'anterior' => $anterior,
            'pagina' => $pagina,
            'exibir' => $this->exibir,
            'totalPagina' => $totalPagina,
            'posterior' => $posterior
        ];

        return true;
    }

    public function find()
	{
        try {

            $arr = $this->model->getFieldList();
            $metodo = $this->getMetodoName( "get", $arr[$this->model->getPk()] );
            $value = $this->model->{$metodo}();

            $field = $this->model->getPk();
            $sql = "SELECT * FROM {$this->model->getTable()} WHERE {$field} = ?";
            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $value, \PDO::PARAM_INT );
            $stmt->execute();

            $rows = $stmt->fetchAll( \PDO::FETCH_OBJ );

            if ( count( $rows ) <= 0 )
            {
                setMessage('Registro não encontrado!', 'danger');
                return null;
            }

            $model = $rows[0];

            foreach ( $model as $modelField => $modelValue )
            {
                $metodo = $this->getMetodoName( "set", $arr[$modelField] );
                $this->model->{$metodo}( $modelValue );
            }

            return $this;

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível encontrar o registro!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível encontrar o registro!' );
            return null;
        }
	}

    public function insert()
    {
        try {
            //"INSERT INTO {$this->table} (campo1, campo2, campo3) VALUES (?, ?, ?)";
            $sql = "INSERT INTO {$this->model->getTable()} (";

            $placeHolders = [];
            foreach ( $this->model->getFieldList() as $tableField => $attrField ) {
                if ( $tableField != $this->model->getPk() ) {
                    $sql .= "{$tableField}, ";
                    array_push( $placeHolders, '?' );
                }
            }

            $sql = rtrim( $sql, ', ' );
            $placeHolders = " (".implode( ', ', $placeHolders ).") ";

            $sql .= ") VALUES ".$placeHolders;

            $stmt = $this->db->prepare( $sql );

            $pos = 0; // placeholder position
            $fields = "";
            $arr = $this->model->getFieldList();
            foreach ( $arr as $tableField => $attrField ) {
                if ( $tableField != $this->model->getPk() ) {
                    $metodo = $this->getMetodoName( "get", $attrField );
                    $value = $this->model->{$metodo}();
                    $fields .= $tableField . "=" . $value . ", ";
                    $stmt->bindValue( ++$pos, $value );
                }
            }

            $stmt->execute();

            $lastId = $this->db->lastInsertId();

            $fields .= $this->model->getPk() . "=" . $lastId . ", ";
            $fields = rtrim($fields, ', ');
            $this->c->logger->addInfo('insert: ' . $sql . " ({$fields})");

            return $lastId;

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível inserir o registro!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível inserir o registro!' );
            return false;
        }
    }


    public function insertBatch( string $table, array $arrayFieldsValues ): bool
    {
        try {
            //INSERT INTO table (fielda, fieldb, ... ) VALUES (?,?...), (?,?...)....;
            $sql = "INSERT INTO {$table} (";

            $qM = "("; // questionMarks
            foreach ( $arrayFieldsValues[0] as $key => $value ) {
                $sql .= "{$key}, ";
                $qM .= "?, ";
            }
            $qM = rtrim( $qM, ', ' );
            $qM .= ")";

            $sql = rtrim( $sql, ', ' );
            $sql .= ") VALUES ";

            $n = count($arrayFieldsValues);
            for ($i=0; $i < $n; $i++) {
                $sql .= "{$qM}, ";
            }
            $sql = rtrim( $sql, ', ' );


            $stmt = $this->db->prepare( $sql );

            $fields = "";
            $pos = 0; // questionMark position
            foreach ( $arrayFieldsValues as $array ) {
                foreach ($array as $key => $value) {
                    $fields .= $key . "=" . $value . ", ";
                    $stmt->bindValue( ++$pos, $value );
                }
            }
            $fields = rtrim($fields, ', ');

            $res = $stmt->execute();

            $this->c->logger->addInfo('insertBatch: ' . $sql . " ({$fields}) - result={$res}");

            return $res;

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível inserir o lote de registros!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível inserir o lote de registros!' );
            return false;
        }
    }


    public function update()
    {
        try {
            //"UPDATE {$this->table} SET campo1=?, campo2=? WHERE campo3=?";
            $sql = "UPDATE {$this->model->getTable()} SET";

            foreach ( $this->model->getFieldList() as $tableField => $attrField ) {
                if ( $tableField != $this->model->getPk() ) {
                    $sql .= " {$tableField}=?,";
                }
            }

            $sql = rtrim($sql, ', ');
            $sql .= " WHERE {$this->model->getPk()}=?";

            $stmt = $this->db->prepare($sql);

            $pos = 0; // placeholder position
            $fields = "";
            $arr = $this->model->getFieldList();
            foreach ( $arr as $tableField => $attrField ) {
                $metodo = $this->getMetodoName( "get", $attrField );
                $value = $this->model->{$metodo}();
                $fields .= $tableField . "=" . $value . ", ";
                if ( $tableField != $this->model->getPk() ) {
                    $stmt->bindValue( ++$pos, $value );
                }
            }
            $fields = rtrim($fields, ', ');

            $metodo = $this->getMetodoName( "get", $arr[$this->model->getPk()] );
            $value = $this->model->{$metodo}();

            $stmt->bindValue( ++$pos, $value, \PDO::PARAM_INT );

            $this->c->logger->addInfo('update: ' . $sql . " ({$fields})");

            return $stmt->execute();

        } catch (\Exception $e) {
            setMessage( 'Não foi possível atualizar o registro!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível atualizar o registro!' );
            return false;
        }
    }


    public function updateBatch( string $table, array $arrayFieldsValues ): bool
    {
        try {
            //INSERT INTO table (fielda, fieldb, ... ) VALUES (?,?...), (?,?...)....;
            $sql = "INSERT INTO {$table} (";

            $qM = "("; // questionMarks
            foreach ( $arrayFieldsValues[0] as $key => $value ) {
                $sql .= "{$key}, ";
                $qM .= "?, ";
            }
            $qM = rtrim( $qM, ', ' );
            $qM .= ")";

            $sql = rtrim( $sql, ', ' );
            $sql .= ") VALUES ";

            $n = count($arrayFieldsValues);
            for ($i=0; $i < $n; $i++) {
                $sql .= "{$qM}, ";
            }
            $sql = rtrim( $sql, ', ' );

            $sql .= " ON DUPLICATE KEY UPDATE ";
            $jumpFisrtKey = true;
            foreach ( $arrayFieldsValues[0] as $key => $value ) {
                if ( $jumpFisrtKey ) {
                    $jumpFisrtKey = false;
                    continue;
                }
                $sql .= "{$key} = VALUES({$key}), ";
            }
            $sql = rtrim( $sql, ', ' );

            $stmt = $this->db->prepare( $sql );

            $fields = "";
            $pos = 0; // questionMark position
            foreach ( $arrayFieldsValues as $array ) {
                foreach ($array as $key => $value) {
                    $fields .= $key . "=" . $value . ", ";
                    $stmt->bindValue( ++$pos, $value );
                }
            }
            $fields = rtrim($fields, ', ');

            $this->c->logger->addInfo('insertBatch: ' . $sql . " ({$fields})");

            $res = $stmt->execute();

            $this->c->logger->addInfo('insertBatch: ' . $sql . " ({$fields}) - result={$res}");

            return $res;

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível atualizar o lote de registros!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível atualizar o lote de registros!' );
            return false;
        }
    }


    public function delete()
    {
        try {
            //"DELETE FROM {$this->table} WHERE campo=?";
            $sql = "DELETE FROM {$this->model->getTable()} WHERE {$this->model->getPk()} = ?";

            $stmt = $this->db->prepare($sql);

            $arr = $this->model->getFieldList();
            $metodo = $this->getMetodoName( "get", $arr[$this->model->getPk()] );
            $value = $this->model->{$metodo}();

            $stmt->bindValue( 1, $value, \PDO::PARAM_INT );

            $this->c->logger->addInfo('delete: ' . $sql . " (" . $this->model->getPk() . "=" . $value . ")");

            return $stmt->execute();

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível excluir o registro!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível excluir o registro!' );
            return false;
        }
    }
}
