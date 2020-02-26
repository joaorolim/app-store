<?php

namespace BET\Models\Services;

use Slim\Container;
use BET\Models\Services\AbstractServiceModel;
use BET\Models\Contracts\IModel;

class ServicePermission extends AbstractServiceModel
{
    public function __construct( Container $c, IModel $permission )
    {
        parent::__construct( $c, $permission );
    }

    /**
     * Retorna um array com o papel e as permissões desse papel
     * @param int $id - id do papel
     * @return array $permissions - array com o papel e as permissões desse papel
     */
    public function getPermissionsByRole( int $id )
    {
        try {

            $sql =  "SELECT r.rol_id AS 'role_id', r.rol_desc, r.rol_obs, p.per_id AS 'perm_id', p.per_desc, p.per_obs, pr.pmr_id, pr.rol_id, pr.per_id ";
            $sql .= "FROM tbl_roles AS r ";
            $sql .= "JOIN tbl_permissions AS p ";
            $sql .= "LEFT JOIN tbl_permissions_roles as pr ON (pr.rol_id = r.rol_id AND pr.per_id = p.per_id) ";
            $sql .= "WHERE r.rol_id = ? ";
            $sql .= "ORDER BY r.rol_id, p.per_id, pr.pmr_id ";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue( 1, $id );

            $fields = "r.rol_id = {$id}";

            $stmt->execute();

            $permissions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->c->logger->addInfo('getPermissionsByRole: ' . $sql . " ({$fields})");

            $permissions = $this->makeArrayPermissionsByRoles( $permissions );

            return $permissions;

        } catch ( \Exception $e ) {
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível obter array com o papel e as permissões desse papel!' );
            return null;
        }
    }


    /**
     * Retorna um array com os papéis e as permissões de cada papel
     * @param array $arr - Array com os id's dos papéis
     * @return array $permissions - array com os papéis e as permissões de cada papel
     */
    public function getPermissionsByRoles( array $arr )
    {
        try {

            $n = count($arr);

            $sql =  "SELECT r.rol_id AS 'role_id', r.rol_desc, r.rol_obs, p.per_id AS 'perm_id', p.per_desc, p.per_obs, pr.pmr_id, pr.rol_id, pr.per_id ";
            $sql .= "FROM tbl_roles AS r ";
            $sql .= "JOIN tbl_permissions AS p ";
            $sql .= "LEFT JOIN tbl_permissions_roles as pr ON (pr.rol_id = r.rol_id AND pr.per_id = p.per_id) ";
            $sql .= "WHERE r.rol_id IN ";

            $placeHolders = [];
            for ($i=0; $i < $n; $i++) {
                array_push( $placeHolders, '?' );
            }

            $placeHolders = " (".implode( ', ', $placeHolders ).") ";
            $sql .= $placeHolders;
            $sql .= "ORDER BY r.rol_id, p.per_id, pr.pmr_id ";

            $stmt = $this->db->prepare($sql);

            $fields = "";
            for ($i=0; $i < $n; $i++) {
                $fields .= $arr[$i] . ", ";
                $stmt->bindValue( $i + 1, $arr[$i] );
            }
            $fields = rtrim($fields, ', ');

            $stmt->execute();

            $permissions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $this->c->logger->addInfo('getPermissionsByRoles: ' . $sql . " ({$fields})");

            $permissions = $this->makeArrayPermissionsByRoles( $permissions );

            return $permissions;

        } catch ( \Exception $e ) {
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível obter array com os papéis e as permissões de cada papel!' );
            return null;
        }
    }


    /**
     * Método organizar o vetor com papéis e permissões
     * @param array $result - Array com o retorno da pesquisa do banco (tbl_permissions_roles)
     * @return array  $roles - Retorna um array de papéis e permissões organizado, prontos para usar nas views
     */
    private function makeArrayPermissionsByRoles( array $result ): array
    {
        $csrf = $this->c->CSRF;

        $permNames = array(
            'ocupacao'        => 'Visualizar Ocupações',
            'ocupacao-alt'    => 'Alterar Ocupações',
            'ocupacao-exc'    => 'Excluir Ocupações',
            'ocupacao-cad'    => 'Cadastrar Ocupações',
            'role'            => 'Visualizar Papéis',
            'role-alt'        => 'Alterar Papéis',
            'role-exc'        => 'Excluir Papéis',
            'role-cad'        => 'Cadastrar Papéis',
            'permission'      => 'Visualizar Permissões',
            'permission-alt'  => 'Alterar Permissões',
            'user'            => 'Visualizar Usuários',
            'user-alt'        => 'Alterar Usuários',
            'user-exc'        => 'Excluir Usuários',
            'user-cad'        => 'Cadastrar Usuários',
            'pais'            => 'Visualizar Países',
            'pais-alt'        => 'Alterar Países',
            'pais-exc'        => 'Excluir Países',
            'pais-cad'        => 'Cadastrar Países',
            'cidade'          => 'Visualizar Cidades',
            'cidade-alt'      => 'Alterar Cidades',
            'cidade-exc'      => 'Excluir Cidades',
            'cidade-cad'      => 'Cadastrar Cidades',
            'bairro'          => 'Visualizar Bairros',
            'bairro-alt'      => 'Alterar Bairros',
            'bairro-exc'      => 'Excluir Bairros',
            'bairro-cad'      => 'Cadastrar Bairros',
            'bairrocid'       => 'Visualizar Bairros por Cidade',
            'bairrocid-alt'   => 'Alterar Bairros por Cidade',
            'bairrocid-exc'   => 'Excluir Bairros por Cidade',
            'bairrocid-cad'   => 'Cadastrar Bairros por Cidade',
            'idioma'          => 'Visualizar Idiomas',
            'idioma-alt'      => 'Alterar Idiomas',
            'idioma-exc'      => 'Excluir Idiomas',
            'idioma-cad'      => 'Cadastrar Idiomas',
            'atendimento'     => 'Visualizar Atendimentos',
            'atendimento-alt' => 'Alterar Atendimentos',
            'atendimento-exc' => 'Excluir Atendimentos',
            'atendimento-cad' => 'Iniciar Atendimentos',
            'atendimento-fim' => 'Finalizar Atendimentos',
            'atendimento-all' => 'Exibir Todos',
            'municipe'        => 'Visualizar Munícipe',
            'municipe-alt'    => 'Alterar Munícipe',
            'municipe-exc'    => 'Excluir Munícipe',
            'municipe-cad'    => 'Cadastrar Munícipe',
            'vaga'            => 'Visualizar Vaga',
            'vaga-alt'        => 'Alterar Vaga',
            'vaga-exc'        => 'Excluir Vaga',
            'vaga-cad'        => 'Cadastrar Vaga',
            'formacao'        => 'Visualizar Formação',
            'formacao-alt'    => 'Alterar Formação',
            'formacao-exc'    => 'Excluir Formação',
            'formacao-cad'    => 'Cadastrar Formação',
            'empregador'      => 'Visualizar Empregador',
            'empregador-alt'  => 'Alterar Empregador',
            'empregador-exc'  => 'Excluir Empregador',
            'empregador-cad'  => 'Cadastrar Empregador',
            'contato'         => 'Visualizar Contato',
            'contato-alt'     => 'Alterar Contato',
            'contato-exc'     => 'Excluir Contato',
            'contato-cad'     => 'Cadastrar Contato',
            'area'            => 'Visualizar Área Atuação',
            'area-alt'        => 'Alterar Área Atuação',
            'area-exc'        => 'Excluir Área Atuação',
            'area-cad'        => 'Cadastrar Área Atuação',
            'relAtend'        => 'Rel. Atendimentos',
            'relEncam'        => 'Rel. Encaminhamentos',
            'relMunic'        => 'Rel. Munícipes',
            'relVagas'        => 'Rel. Vagas',
        );

        $roles = [];
        $role['roleFakeId'] = "";
        $role['roleName'] = "";
        $role['roleObs'] = "";
        $role['permissions'] = [];
        $item = "new";
        foreach ($result as $arr => $r) {
            if ( ! ($item == $r['role_id']) ) {

                if ( $role['permissions'] != null ) {
                    array_push( $roles, $role );

                    $role = [];
                    $role['roleFakeId'] = "";
                    $role['roleName'] = "";
                    $role['roleObs'] = "";
                    $role['permissions'] = [];
                }

                $item = $r['role_id'];

                $role['roleFakeId'] = $csrf::generateFakeId( $r['role_id'] );
                $role['roleName'] = $r['rol_desc'];
                $role['roleObs'] = $r['rol_obs'];
            }

            $perm['permFakeId'] = $csrf::generateFakeId( $r['perm_id'] );
            $perm['permRoute'] = $r['per_desc'];
            $perm['permName'] = $permNames[ $r['per_desc'] ];

            $pieces = explode("-", $r['per_desc']);
            $perm['permScreen'] = $pieces[0];

            $perm['permObs'] = $r['per_obs'];
            $perm['checked'] = ( ($r['perm_id'] == $r['per_id']) AND ($r['role_id'] == $r['rol_id']) ) ? "checked" : " ";

            array_push( $role['permissions'], $perm );

        }
        array_push( $roles, $role );

        return $roles;
    }


    /**
     * Método para setar as permissões de acordo com o id do papel
     * @param array $dadosPerm - Array com o id do papel e os id's das permissões que serão atribuídas a esse papel
     * @return bool  - Retorna true se a transação foi completada com sucesso
     */
    public function setPermissionByRole( array $dadosPerm ): bool
    {
        $this->db->beginTransaction();

        if ( $this->deletePermissionsByRole( $dadosPerm[0]['rol_id'] ) ) {

            if ( parent::insertBatch( "tbl_permissions_roles", $dadosPerm ) ) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }

        }

        $this->db->rollback();
        return false;

    }


    /**
     * Método para deletar todas as permissões, de acordo com o id do papel
     * @param int $id - id do papel
     * @return bool - Retorna true se a exclusão foi completada com sucesso
     */
    public function deletePermissionsByRole( int $id ) : bool
    {
        try {
            //"DELETE FROM {$table} WHERE campo=?";
            $sql = "DELETE FROM tbl_permissions_roles WHERE rol_id = ?";

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue( 1, $id, \PDO::PARAM_INT );

            $this->c->logger->addInfo('deletePermissionsByRole: ' . $sql . " (rol_id = " . $id . ")");

            return $stmt->execute();

        } catch ( \Exception $e ) {
            setMessage( 'Não foi possível excluir as permissões deste usuário!', 'danger' );
            $this->c->logger->addAlert( $e->getMessage().' - Não foi possível excluir as permissões deste usuário! ( deletePermissionsByRole() )' );
            return false;
        }

    }

}
