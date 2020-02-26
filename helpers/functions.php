<?php
/**
 * Ultimate PHPerguntas
 *
 * Este script faz parte do Projeto Prático do curso Ultimate PHP.
 * O Ultimate PHP é um curso voltado para iniciantes e intermediários em PHP.
 * Conheça o curso Ultimate PHP acessando http://www.ultimatephp.com.br
 *
 * O projeto completo está disponível no Github: https://github.com/beraldo/UltimatePHPerguntas
 *
 * @author: Roberto Beraldo Chaiben
 * @package Ultimate PHPerguntas
 * @link http://www.ultimatephp.com.br
 */

/**
 * Arquivo de funções para uso geral
 */

/**
 * Verifica se o ambiente atual é de desenvolvimento
 * @return boolean Retorna TRUE se for ambiente de desenvolvimento, FALSE caso contrário
 */
function isDevEnv()
{
    return ENV == 'dev';
}

/**
 * Retorna o caminho para o diretório com as views
 * @return string caminho para o diretório com as views
 */
function viewsPath()
{
    return APP_ROOT_PATH . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
}

/**
 * Retorna o caminho para o diretório de logs
 * @return string caminho para o diretório de logs
 */
function logsPath()
{
    return APP_ROOT_PATH . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;
}

/**
 * Retorna a URL base da aplicação
 * @return string URL base da aplicação
 */
function getBaseURL()
{
    return sprintf(
        "%s://%s%s/",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['SERVER_PORT'] == 80 ? '' : ':' . $_SERVER['SERVER_PORT']
    );
}

/**
 * Retorna a URL atual
 * @return string URL atual
 */
function getCurrentURL()
{
    return getBaseURL() . $_SERVER['REQUEST_URI'];
}


/**
 * Função de redirecionamento HTTP
 * @param  string $url URL de destino
 */
function redirect( $url )
{
    header( 'Location: ' . $url );
    exit;
}

function reais($decimal) {
    return "R$" . number_format($decimal,2,",",".");
}


function dataBr_to_dataMySQL($data) {
    $campos = explode("/", $data);
    return date("Y-m-d", strtotime($campos[2]."/".$campos[1]."/".$campos[0]));
}


function dataMySQL_to_dataBr($data) {
    return date("d/m/Y", strtotime($data));
}

function setMessage( $message, $class, $col=12 )
{
    $msg = '<div class="row">
                <div class="col-md-'.$col.'">
                    <div class="alert alert-'.$class.' alert-dismissable">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                        <h5>'.$message.'</h5>
                    </div>
                </div>
            </div>';

    $_SESSION['message'] = $msg;

    return true;
}

function getMessage()
{
    $msg = $_SESSION['message'] ?? null;

    $_SESSION['message'] = null;
    unset($_SESSION['message']);

    return $msg;
}

function hasMessage()
{
    return ( ( isset($_SESSION['message']) ) ? true : false);
}

/**
 * Verifica se dois arrays unidimensionais são iguais em tamanho e conteúdo
 * @param  array $array_1 primeiro argumento da função array_diff do PHP
 * @param  array $array_2 segundo argumento da função array_diff do PHP
 * @return boolean true se os arrays são iguais e false caso contrário
 */
function comparaArrays( $array_1, $array_2)
{
    $n1 = count($array_1);
    $n2 = count($array_2);

    if ( ! ($n1 == $n2) ) {
        return false;
    }

    $diff = array_diff( $array_1, $array_2 );

    if ( $diff ) {
        return false;
    }

    return true;
}

function getUltimoDiaMes( $mes, $ano = null )
{
    if ( ! $ano ) {
        $ano = date('Y');
    }

    // 31 or 30 days?
    if($mes == 1 || $mes == 3 || $mes == 5 || $mes == 7 || $mes == 8 || $mes == 10 || $mes == 12) {
        $numDias = 31;
    } else if ($mes == 4 || $mes == 6 || $mes == 9 || $mes == 11) {
        $numDias = 30;
    } else {
        // If month is February, calculate whether it is a leap year or not
        $numDias = ( ( $ano - 2016 ) % 4 === 0 ) ? 29 : 28;
    }

    return $numDias;
}


function getAllMeses( $num = null, $desc = null )
{
    $meses = [
        1 => 'janeiro',
        2 => 'fevereiro',
        3 => 'março',
        4 => 'abril',
        5 => 'maio',
        6 => 'junho',
        7 => 'julho',
        8 => 'agosto',
        9 => 'setembro',
        10 => 'outubro',
        11 => 'novembro',
        12 => 'dezembro',
    ];

    if ( $num ) {
        foreach ( $meses as $key => $value ) {
            if ( $key == $num ) {
                return array($key => $value);
                exit();
            }
        }
    }

    if ( $desc ) {
        foreach ( $meses as $key => $value ) {
            if ( $value == $desc ) {
                return array($key => $value);
                exit();
            }
        }
    }

    return $meses;

}

/**
 * Gravar na Session o valor digitado no campo
 * @param String $filed - o nome do campo
 * @param mixed $value - o valor digitado no campo
 */
function setOld( $filed, $value )
{
    $_SESSION['old'][$filed] = $value;
}

/**
 * Recuperar da Session o valor antigo digitado em um campo
 * @param String $filed - o nome do campo
 * @return mixed $value - o valor antigo digitado no campo
 */
function getOld( $filed )
{
    $value = $_SESSION['old'][$filed] ?? null;

    $_SESSION['old'][$filed] = null;
    unset( $_SESSION['old'][$filed] );

    return $value;
}

/**
 * Apaga o vetor old da Session
 */
function clearOld()
{
    $_SESSION['old'] = null;
    unset( $_SESSION['old'] );
}

/**
 * Retornar um array com os gêneros
 * @return array $arrGender - array com os gêneros
 */
function getGenderArray()
{
    $arrGender = array(
        'Masculino' => 'm',
        'Feminino' => 'f',
        'Outros' => 'o'
    );

    return $arrGender;
}

/**
 * Retornar um array com os estados civis
 * @return array $arrEstCivil - array com os estados civis
 */
function getEstadoCivilArray()
{
    $arrEstCivil = array(
        'Casado' => 'c',
        'Divorciado' => 'd',
        'Separado' => 'se',
        'Solteiro' => 's',
        'União Estável' => 'u',
        'Viúvo' => 'v'
    );

    return $arrEstCivil;
}

/**
 * Retornar um array com os status possíveis para os munícipes
 * @return array $arrStatusMunicipe - array com os status
 */
function getStatusMunicipeArray()
{
    $arrStatusMunicipe = array(
        'Aguardando Vaga' => 'a',
        'Encaminhado' => 'e',
        'Inativo' => 'i'
    );

    return $arrStatusMunicipe;
}

/**
 * Retornar um array com os status possíveis para as vagas
 * @return array $arrStatusVaga - array com os status
 */
function getStatusVagaArray()
{
    $arrStatusVaga = array(
        'Ativa'   => 'a',
        'Inativa' => 'i',
    );

    return $arrStatusVaga;
}

/**
 * Retornar um array com os status possíveis para os empregadores
 * @return array $arrStatusEmp - array com os status
 */
function getStatusEmpArray()
{
    $arrStatusEmp = array(
        'Ativo'   => 'a',
        'Inativo' => 'i',
    );

    return $arrStatusEmp;
}

/**
 * Retornar um array com os status possíveis para as vagas exclusivas para PcD
 * @return array $arrPcd - array com os status
 */
function getPcdArray()
{
    $arrPcd = array(
        'Não' => 'n',
        'Sim' => 's',
    );

    return $arrPcd;
}

/**
 * Retornar um array com os ID's das deficiências possíveis para os munícipes
 * @return array $arrStatusMunicipe - array com ID's das deficiências
 */
function getDefIdsArray()
{
    $arrDefIds = array(
        'Auditiva'    => 1,
        'Fisica'      => 2,
        'Intelectual' => 4,
        'Mental'      => 5,
        'Mudez'       => 6,
        'Visual'      => 7
    );

    return $arrDefIds;
}

/**
 * Retornar um array com os ID's das deficiências possíveis para os munícipes
 * @return array $arrStatusMunicipe - array com ID's das deficiências
 */
function getGrausIdsArray()
{
    $arrGrausIds = array(
        'leve'     => 1,
        'moderado' => 2,
        'grave'    => 3,
        'completo' => 4
    );

    return $arrGrausIds;
}

/**
 * Retornar um array com os graus de deficiência
 * @return array $arrGr - array com os graus de deficiência
 */
function preencheArrDef( $arrGrausDefic, $oldGrau )
{
    $arrGr = array();

    foreach ($arrGrausDefic as $obj) {
        if ( $obj->gra_desc === "Indiferente" ) {
            continue;
        }

        $selected = "";
        if ( $obj->gra_id == $oldGrau ) {
            $selected = "selected";
        }
        $id = BET\Auth\CSRF::generateFakeId( $obj->gra_id );
        $opt = "<option value=\"{$id}\" title=\"{$obj->gra_obs}\" $selected >{$obj->gra_desc}</option>";
        array_push($arrGr, $opt);
    }

    return $arrGr;
}

/**
 * Retornar um array com os ID's das escolaridades possíveis para os munícipes
 * @return array $arrStatusMunicipe - array com ID's das escolaridades
 */
function getEscIdsArray()
{
    $arrEscIds = array(
        'infantil'     => 1,
        'fundamental'  => 2,
        'medio'        => 3,
        'profissional' => 4,
        'graduacao'    => 5,
        'posgraduacao' => 6,
        'mestrado'     => 7,
        'doutorado'    => 8
    );

    return $arrEscIds;
}

/**
 * Retornar um array com os ID's dos Status possíveis para as escolaridades
 * @return array $arrStatusIds - array com ID's dos Status
 */
function getStatusIdsArray()
{
    $arrStatusIds = array(
        'completo'   => 1,
        'cursando'   => 2,
        'incompleto' => 3
    );

    return $arrStatusIds;
}

/**
 * Retornar o cep, cpf ou cnpj sem pontos e traços - somente números
 * @return String $res - cpf ou cnpj limpo (somente números)
 */
function limpaCepCpfCnpj( $value )
{
    $chars = array("(",")"," ","-",".","/");
    $res = str_replace($chars,"",$value);

    return $res;
}

/**
 * Retornar o cpf no formato xxx.xxx.xxx-xx
 * @return String $monta_cpf - cpf no formato xxx.xxx.xxx-xx
 */
function montarCpf( $value )
{
    if ( $value == null ) {
        return null;
    }

    $parte_um     = substr($value, 0, 3);
    $parte_dois   = substr($value, 3, 3);
    $parte_tres   = substr($value, 6, 3);
    $parte_quatro = substr($value, 9, 2);

    $monta_cpf = "${parte_um}.${parte_dois}.${parte_tres}-${parte_quatro}";

    return $monta_cpf;
}

/**
 * Retornar o cep no formato xx.xxx-xxx
 * @return String $monta_cep - cep no formato xx.xxx-xxx
 */
function montarCep( $value )
{
    if ( $value == null ) {
        return null;
    }

    $parte_um     = substr($value, 0, 2);
    $parte_dois   = substr($value, 2, 3);
    $parte_tres   = substr($value, 5, 3);

    $monta_cep = "${parte_um}.${parte_dois}-${parte_tres}";

    return $monta_cep;
}

/**
 * Retornar o cnpj no formato xx.xxx.xxx/xxxx-xx
 * @return String $monta_cpf - cnpj no formato xx.xxx.xxx/xxxx-xx
 */
function montarCnpj( $value )
{
    if ( $value == null ) {
        return null;
    }

    $parte_um     = substr($value, 0, 2);
    $parte_dois   = substr($value, 2, 3);
    $parte_tres   = substr($value, 5, 3);
    $parte_quatro = substr($value, 8, 4);
    $parte_cinco = substr($value, 12, 2);

    $monta_cnpj = "${parte_um}.${parte_dois}.${parte_tres}/${parte_quatro}-${parte_cinco}";

    return $monta_cnpj;
}

/**
 * Retornar o valor no formato decimal correto para ser inserido no BD
 * @return String $value - valor decimal, pronto para ser incluído no BD
 */
function decimalBr_to_decimalMySQL( $value )
{
    $chars = array(".");
    $res = str_replace($chars,"",$value);

    $chars = array(",");
    $res = str_replace($chars,".",$res);

    return $res;
}

/**
 * Retornar o tempo de experiência em Anos
 * @param int $experiencia - valor total da experiência, em meses
 * @return int $anos - valor da experiência em anos (parte inteira)
 */
function getExperAnos( $experiencia )
{
    $anos = (int)($experiencia / 12);

    return $anos;
}

/**
 * Retornar o tempo de experiência em Meses
 * @param int $experiencia - valor total da experiência, em meses
 * @return int $meses - valor parcial da experiência em meses
 */
function getExperMeses( $experiencia )
{
    $meses = (int)($experiencia % 12);

    return $meses;
}
