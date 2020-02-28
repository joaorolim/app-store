<?php
// Routes

// Grupo de rotas da API
$app->group('/api', function() use ($app) {
    //$app->get('[/]', 'LoginController:index')->setName('root');

    $app->get('/modulos', 'AtendimentoController:getModulos')->setName('root')->add( $app->getContainer()['CorsMiddleware'] );
    $app->get('/modulos/cliente', 'AtendimentoController:getModulosByClient')->setName('root')->add( $app->getContainer()['CorsMiddleware'] );
    $app->get('/modulo/ativar/{id}', 'AtendimentoController:ativaModulo')->setName('root')->add( $app->getContainer()['CorsMiddleware'] );
    $app->get('/modulo/desativar/{id}', 'AtendimentoController:desativaModulo')->setName('root')->add( $app->getContainer()['CorsMiddleware'] );


    // $app->group('/perfil', function() use ($app) {
    //     $app->get('', 'PerfilController:index')->setName('perfil');
    //     $app->post('/alterar', 'PerfilController:alterar')->setName('perfil-alt');
    // })->add( $app->getContainer()['AuthMiddleware'] );


});

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
