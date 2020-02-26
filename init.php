<?php
/*
 * Script de inicialização (Bootstrapping)
 *
 * Leia mais sobre Bootstrapping e Arquivos de Inicialização no link abaixo
 * http://rberaldo.com.br/bootstrapping-php-arquivo-inicializacao/
 */

// mantém a sessão sempre ativa
session_start();
session_regenerate_id();

// desabilitar esta diretiva para prevenir ataque chamado XXE.
libxml_disable_entity_loader(TRUE);

// define o diretório base da aplicação
define( 'APP_ROOT_PATH', dirname( __FILE__ ) );

// define a versão para atualizar CSS e JS
// define('VERSION', '1.9');
define('VERSION', '0.1.0'); // alterado em 25/11/2019

// Fuso-Horário (Timezone)
// Lista de timezones suportador pelo PHP: http://php.net/manual/pt_BR/timezones.php
date_default_timezone_set( 'America/Sao_Paulo' );

// Habilite todos os níveis de exibição de erros.
// essa configuração é geral, tanto para produção quanto
// para desenvolvimento, pois desejamos exibir todos os níveis
// de erro, seja na tela ou no arquivo de log.
// O que muda conforme o ambiente é a diretiva display_errors
error_reporting( E_ALL | E_STRICT );

// inclui o autoloader do Composer
require_once 'vendor/autoload.php';
