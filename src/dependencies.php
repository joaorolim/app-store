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
$container['Bcrypt'] = function () {
    return new \BET\Auth\Bcrypt();
};

$container['CSRF'] = function () {
    return new \BET\Auth\CSRF();
};

$container['AuthMiddleware'] = function ($c) {
    return new \BET\Auth\AuthMiddleware($c);
};

$container['Gump'] = function ($c) {
    return new \BET\Auth\MyGump( $c, 'pt-br' );
};

$container['LoginController'] = function ($c) {
    return new \BET\Controllers\LoginController($c);
};

$container['HomeController'] = function ($c) {
    return new \BET\Controllers\HomeController($c);
};

$container['OcupacaoController'] = function ($c) {
    return new \BET\Controllers\OcupacaoController($c);
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

$container['PaisController'] = function ($c) {
    return new \BET\Controllers\PaisController($c);
};

$container['CidadeController'] = function ($c) {
    return new \BET\Controllers\CidadeController($c);
};

$container['BairroController'] = function ($c) {
    return new \BET\Controllers\BairroController($c);
};

$container['BairroCidadeController'] = function ($c) {
    return new \BET\Controllers\BairroCidadeController($c);
};

$container['IdiomaController'] = function ($c) {
    return new \BET\Controllers\IdiomaController($c);
};

$container['AtendimentoController'] = function ($c) {
    return new \BET\Controllers\AtendimentoController($c);
};

$container['MunicipeController'] = function ($c) {
    return new \BET\Controllers\MunicipeController($c);
};

$container['VagaController'] = function ($c) {
    return new \BET\Controllers\VagaController($c);
};

$container['FormacaoController'] = function ($c) {
    return new \BET\Controllers\FormacaoController($c);
};

$container['EmpregadorController'] = function ($c) {
    return new \BET\Controllers\EmpregadorController($c);
};

$container['ContatoController'] = function ($c) {
    return new \BET\Controllers\ContatoController($c);
};

$container['AreaAtuacaoController'] = function ($c) {
    return new \BET\Controllers\AreaAtuacaoController($c);
};

$container['PerfilController'] = function ($c) {
    return new \BET\Controllers\PerfilController($c);
};

$container['PdfController'] = function ($c) {
    return new \BET\Controllers\PdfController($c);
};

$container['RelatorioController'] = function ($c) {
    return new \BET\Controllers\RelatorioController($c);
};

$container['User'] = function () {
    return new \BET\Models\User();
};

$container['Ocupacao'] = function () {
    return new \BET\Models\Ocupacao();
};

$container['Role'] = function ($c) {
    return new \BET\Models\Role();
};

$container['Permission'] = function ($c) {
    return new \BET\Models\Permission();
};

$container['Pais'] = function ($c) {
    return new \BET\Models\Pais();
};

$container['Cidade'] = function ($c) {
    return new \BET\Models\Cidade();
};

$container['Bairro'] = function ($c) {
    return new \BET\Models\Bairro();
};

$container['BairroCidade'] = function ($c) {
    return new \BET\Models\BairroCidade();
};

$container['Idioma'] = function ($c) {
    return new \BET\Models\Idioma();
};

$container['Atendimento'] = function ($c) {
    return new \BET\Models\Atendimento();
};

$container['Municipe'] = function ($c) {
    return new \BET\Models\Municipe();
};

$container['Vaga'] = function ($c) {
    return new \BET\Models\Vaga();
};

$container['Formacao'] = function ($c) {
    return new \BET\Models\Formacao();
};

$container['Empregador'] = function ($c) {
    return new \BET\Models\Empregador();
};

$container['Contato'] = function ($c) {
    return new \BET\Models\Contato();
};

$container['AreaAtuacao'] = function ($c) {
    return new \BET\Models\AreaAtuacao();
};

$container['Encaminhamento'] = function ($c) {
    return new \BET\Models\Encaminhamento();
};

$container['Relatorio'] = function ($c) {
    return new \BET\Models\Relatorio();
};

$container['ServiceUser'] = function ($c) {
    return new \BET\Models\Services\ServiceUser( $c, $c['User'] );
};

$container['ServiceOcupacao'] = function ($c) {
    return new \BET\Models\Services\ServiceOcupacao( $c, $c['Ocupacao'] );
};

$container['ServiceRole'] = function ($c) {
    return new \BET\Models\Services\ServiceRole( $c, $c['Role'] );
};

$container['ServicePermission'] = function ($c) {
    return new \BET\Models\Services\ServicePermission( $c, $c['Permission'] );
};

$container['ServicePais'] = function ($c) {
    return new \BET\Models\Services\ServicePais( $c, $c['Pais'] );
};

$container['ServiceCidade'] = function ($c) {
    return new \BET\Models\Services\ServiceCidade( $c, $c['Cidade'] );
};

$container['ServiceBairro'] = function ($c) {
    return new \BET\Models\Services\ServiceBairro( $c, $c['Bairro'] );
};

$container['ServiceBairroCidade'] = function ($c) {
    return new \BET\Models\Services\ServiceBairroCidade( $c, $c['BairroCidade'] );
};

$container['ServiceIdioma'] = function ($c) {
    return new \BET\Models\Services\ServiceIdioma( $c, $c['Idioma'] );
};

$container['ServiceAtendimento'] = function ($c) {
    return new \BET\Models\Services\ServiceAtendimento( $c, $c['Atendimento'] );
};

$container['ServiceMunicipe'] = function ($c) {
    return new \BET\Models\Services\ServiceMunicipe( $c, $c['Municipe'] );
};

$container['ServiceVaga'] = function ($c) {
    return new \BET\Models\Services\ServiceVaga( $c, $c['Vaga'] );
};

$container['ServiceFormacao'] = function ($c) {
    return new \BET\Models\Services\ServiceFormacao( $c, $c['Formacao'] );
};

$container['ServiceEmpregador'] = function ($c) {
    return new \BET\Models\Services\ServiceEmpregador( $c, $c['Empregador'] );
};

$container['ServiceContato'] = function ($c) {
    return new \BET\Models\Services\ServiceContato( $c, $c['Contato'] );
};

$container['ServiceAreaAtuacao'] = function ($c) {
    return new \BET\Models\Services\ServiceAreaAtuacao( $c, $c['AreaAtuacao'] );
};

$container['ServiceEncaminhamento'] = function ($c) {
    return new \BET\Models\Services\ServiceEncaminhamento( $c, $c['Encaminhamento'] );
};

$container['ServiceRelatorio'] = function ($c) {
    return new \BET\Models\Services\ServiceRelatorio( $c, $c['Relatorio'] );
};
