<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    return $response->write(
        '<!DOCTYPE html>
        <html>
        <head>
        <title>App-Store</title>
        </head>
        <body>
        <h1 style="text-align:center;padding:20%">Site em construção!</h1>
        </body>
        </html>'
    );
})->setName('site');


// Grupo de rotas do ADM
$app->group('/adm', function() use ($app) {
    $app->get('[/]', 'LoginController:index')->setName('root');

    $app->map(['GET', 'POST'], '/login', 'LoginController:index')->setName('login');

    $app->get('/logout', 'LoginController:logout')->setName('logout');

    $app->get('/home', 'HomeController:index')->setName('home')->add( $app->getContainer()['AuthMiddleware'] );


    // $app->group('/atendimento', function() use ($app) {
    //     $app->get('', 'AtendimentoController:index')->setName('atendimento');
    //     $app->get('/pagina/{page}', 'AtendimentoController:index')->setName('atendimento');
    //     $app->map(['GET', 'POST'], '/alterar[/{id}]', 'AtendimentoController:alterar')->setName('atendimento-alt');
    //     $app->get('/excluir/{id}', 'AtendimentoController:deletar')->setName('atendimento-exc');
    //     $app->post('/cadastrar', 'AtendimentoController:cadastrar')->setName('atendimento-cad');
    //     $app->get('/finalizar', 'AtendimentoController:finalizar')->setName('atendimento-fim');

    //     $app->group('/api', function() use ($app) {
    //         $app->get('/municipe/{cpf}', 'AtendimentoController:getMunicipeByCpf')->setName('atendimento-cad');
    //     });
    // })->add( $app->getContainer()['AuthMiddleware'] );

    $app->group('/perfil', function() use ($app) {
        $app->get('', 'PerfilController:index')->setName('perfil');
        $app->post('/alterar', 'PerfilController:alterar')->setName('perfil-alt');
    })->add( $app->getContainer()['AuthMiddleware'] );

    // $app->group('/permission', function() use ($app) {
    //     $app->get('', 'PermissionController:index')->setName('permission');
    //     $app->get('/pagina/{page}', 'PermissionController:index')->setName('permission');
    //     // $app->get('/alterar/{id}', 'PermissionController:alterar')->setName('permission-alt');
    //     // $app->get('/excluir/{id}', 'PermissionController:deletar')->setName('permission-exc');
    //     // $app->post('/cadastrar', 'PermissionController:cadastrar')->setName('permission-cad');
    //     $app->post('/alterar', 'PermissionController:alterar')->setName('permission-alt');
    // })->add( $app->getContainer()['AuthMiddleware'] );


    // $app->group('/role', function() use ($app) {
    //     $app->get('', 'RoleController:index')->setName('role');
    //     $app->get('/pagina/{page}', 'RoleController:index')->setName('role');
    //     $app->map(['GET', 'POST'], '/alterar[/{id}]', 'RoleController:alterar')->setName('role-alt');
    //     $app->get('/excluir/{id}', 'RoleController:deletar')->setName('role-exc');
    //     $app->post('/cadastrar', 'RoleController:cadastrar')->setName('role-cad');
    // })->add( $app->getContainer()['AuthMiddleware'] );

    // $app->group('/user', function() use ($app) {
    //     $app->get('', 'UserController:index')->setName('user');
    //     $app->get('/pagina/{page}', 'UserController:index')->setName('user');
    //     $app->map(['GET', 'POST'], '/alterar[/{id}]', 'UserController:alterar')->setName('user-alt');
    //     $app->get('/excluir/{id}', 'UserController:deletar')->setName('user-exc');
    //     $app->post('/cadastrar', 'UserController:cadastrar')->setName('user-cad');
    //     $app->get('/reset/{id}', 'UserController:resetar')->setName('user-alt');
    // })->add( $app->getContainer()['AuthMiddleware'] );

});
