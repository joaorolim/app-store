<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// database
$container['db'] = function ($c) {
    return new \BET\Conn($c);
};

// classes da aplicação
$container['AtendimentoController'] = function ($c) {
    return new \BET\Controllers\AtendimentoController($c);
};

$container['Atendimento'] = function () {
    return new \BET\Models\Atendimento();
};

$container['ServiceAtendimento'] = function ($c) {
    return new \BET\Models\Services\ServiceAtendimento( $c, $c['Atendimento'] );
};

$container['Bcrypt'] = function () {
    return new \BET\Auth\Bcrypt();
};

$container['CSRF'] = function () {
    return new \BET\Auth\CSRF();
};

$container['AuthMiddleware'] = function ($c) {
    return new \BET\Auth\AuthMiddleware($c);
};

$container['CorsMiddleware'] = function ($c) {
    return new \BET\Auth\CorsMiddleware($c);
};

$container['Gump'] = function ($c) {
    return new \BET\Auth\MyGump( $c, 'pt-br' );
};

$container['UserController'] = function ($c) {
    return new \BET\Controllers\UserController($c);
};

$container['RoleController'] = function ($c) {
    return new \BET\Controllers\RoleController($c);
};

$container['PermissionController'] = function ($c) {
    return new \BET\Controllers\PermissionController($c);
};

$container['PerfilController'] = function ($c) {
    return new \BET\Controllers\PerfilController($c);
};

$container['User'] = function () {
    return new \BET\Models\User();
};

$container['Role'] = function ($c) {
    return new \BET\Models\Role();
};

$container['Permission'] = function ($c) {
    return new \BET\Models\Permission();
};

$container['ServiceUser'] = function ($c) {
    return new \BET\Models\Services\ServiceUser( $c, $c['User'] );
};

$container['ServiceRole'] = function ($c) {
    return new \BET\Models\Services\ServiceRole( $c, $c['Role'] );
};

$container['ServicePermission'] = function ($c) {
    return new \BET\Models\Services\ServicePermission( $c, $c['Permission'] );
};

