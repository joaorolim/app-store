<?php
$config = parse_ini_file("../appstore.ini", TRUE);

/*
 * Lista com os hostnames (nome do computador) dos ambientes de
 * desenvolvimento. Sempre que o sistema for executado em uma máquina
 * de desenvolvimento, as devidas configurações do PHP (como exibição de
 * erros e logs) serão usadas. Caso contrário, serão usadas configurações de
 * ambiente de produção
 */
//$devHostnames = [
//    'nome_do_seu_computador',
//];
$devHostnames = $config['HOSTNAMES'];

// pega o hostname da máquina em que a aplicação está rodando
$hostname = gethostname();

if ( in_array( $hostname, $devHostnames ) ) {
    // ambiente de desenvolvimento
    $displayErrors = true;
} else {
    // ambiente de produção
    $displayErrors = false;
}

return [
    'settings' => [
        'displayErrorDetails' => $displayErrors, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        'determineRouteBeforeAppMiddleware' => true,

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database settings
        'db' => [
            'driver' => 'mysql',
            'host' => $config['DATABASE']['server'],
            'database' => $config['DATABASE']['dbname'],
            'username' => $config['DATABASE']['user'],
            'password' => $config['DATABASE']['password'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'options' => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ),
        ]
    ],
];
