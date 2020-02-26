<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class PermissionController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        //a pagina atual
        $pagina = ( isset( $args['page'] ) ) ? (int)$args['page'] : 1;
        $_SESSION['pagina'] = $pagina;

        $csrf = $this->c->CSRF;
        $csrf::clearFakeId();

        $objServicePermission = $this->c->ServicePermission;
        $objServiceRole = $this->c->ServiceRole;
        $result = $objServiceRole->list( $pagina );

        $arrIdRoles = array();
        foreach ( $result as $k => $v ) {
            if ( $v->rol_id != 1 AND $v->rol_id != 2 ) {  // exclui o Admine usuário Padrão, de tal forma que as configurações só podem ser realizadas diretamente no Banco
                $arrIdRoles[] = $v->rol_id;
            }
        }

        $roles = $objServicePermission->getPermissionsByRoles( $arrIdRoles );

        $arrayPaginacao = $objServiceRole->getArrayPaginacao();
        $paginacao = $this->makePageControllers( $arrayPaginacao, 'adm/permission' );

        $objServiceRole->closeConn();

        return $this->c->renderer->render($response, 'template_admin.php', [
            'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
            'paginacao' => $paginacao,
            'viewName' => 'permissao/alterar-permissao',
            'roles' => $roles,
            'hiddenFormInput' => $csrf::generateHiddenFormInput()
        ]);
    }

    public function alterar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        // Verifica se há ataque por CSRF
        $csrf = $this->c->CSRF;
        $csrf::check();

        // recebe todas as variáveis que vieram por $_POST
        $postVars = $request->getParsedBody();
        $fakeId = $postVars['fakeId'] ?? null;
        $permissions = $postVars['permissions'] ?? null;

        $objServicePermission = $this->c->ServicePermission;

        $pagina = $_SESSION['pagina'] ?? 1;

        if ( $fakeId ?? null ) {

            $roleId = $csrf::getRealId( $fakeId );  // id real do role
            $date = date( 'Y-m-d H:i:s' );

            $idsReais = []; // id's reais de cada permissão
            if ( $permissions ) {
                foreach ($permissions as $key => $value) {
                    $idsReais[] = $csrf::getRealId( $value );
                }
            } else {
                $idsReais[] = 1; // seta pelo menos 1 permissão para o papel (Não faz sentido existir um papel sem permissão nenhuma )
            }

            $csrf::checkPostFakeId(); // remove o fakeId da sessão

            $dadosPerm = [];
            foreach ( $idsReais as $idPerm ) {
                $array = [
                    'rol_id' => $roleId,
                    'per_id' => $idPerm,
                    'pmr_created_at' => $date,
                    'pmr_updated_at' => $date
                ];
                array_push($dadosPerm, $array);
            }

            $result = $objServicePermission->setPermissionByRole( $dadosPerm );
            $objServicePermission->closeConn();

            if ( $result ) {
                setMessage('Permissões alteradas com sucesso!', 'success');
            } else {
                setMessage('Erro ao tentar alterar as permissões!', 'danger');
            }

        } else {
            setMessage('Erro ao tentar realizar essa operação!', 'danger');
        }

        return $response->withRedirect('/adm/permission/pagina/'.$pagina);
    }

}
