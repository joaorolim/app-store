<?php

namespace BET\Auth;

use Slim\Container;
use \GUMP;

class MyGump extends GUMP
{
	/**
     * @var Container
     */
    protected $c;


    public function __construct( Container $c, String $lang ) {
        parent::__construct( $lang );
        $this->c = $c;

        GUMP::set_field_name( "dt_nasc", "Data de Nascimento" );
        GUMP::set_field_name( "senha2", "Confirmar Senha" );
        GUMP::set_field_name( "role", "Papel" );
        GUMP::set_field_name( "uf", "UF" );
        GUMP::set_field_name( "observacao", "Observação" );
        GUMP::set_field_name( "obs_atend", "Observação" );
        GUMP::set_field_name( "obs", "Observação" );
        GUMP::set_field_name( "cpf", "CPF" );
        GUMP::set_field_name( "cpf_atend", "CPF" );
        GUMP::set_field_name( "cpf_pesq", "CPF" );
        GUMP::set_field_name( "rg", "RG" );
        GUMP::set_field_name( "cep", "CEP" );
        GUMP::set_field_name( "emailMun", "Email" );
        GUMP::set_field_name( "emailEmp", "Email" );
        GUMP::set_field_name( "conEmail", "Email" );
        GUMP::set_field_name( "bairro_mun", "Bairro" );
        GUMP::set_field_name( "civil", "Estado Civil" );
        GUMP::set_field_name( "tel", "Telefone" );
        GUMP::set_field_name( "conTel", "Telefone" );
        GUMP::set_field_name( "cel", "Celular" );
        GUMP::set_field_name( "conCel", "Celular" );
        GUMP::set_field_name( "conFax", "Fax" );
        GUMP::set_field_name( "munEmp", "Empresa" );
        GUMP::set_field_name( "munCont", "Contato" );
        GUMP::set_field_name( "munExpObs", "Observações" );
        GUMP::set_field_name( "munSal", "Salário" );
        GUMP::set_field_name( "munDataAdm", "Admissão" );
        GUMP::set_field_name( "munDataDem", "Demissão" );
        GUMP::set_field_name( "munMot", "Motivo da Saída" );
        GUMP::set_field_name( "conNome", "Nome" );
        GUMP::set_field_name( "cnpj", "CNPJ" );
        GUMP::set_field_name( "cnpj_pesq", "CNPJ" );
        GUMP::set_field_name( "descricao", "Descrição" );
        GUMP::set_field_name( "ocupacao", "Ocupação" );
        GUMP::set_field_name( "id_ocp", "ID Ocupação" );
        GUMP::set_field_name( "idaMin", "Idade Mín." );
        GUMP::set_field_name( "idaMax", "Idade Máx." );
        GUMP::set_field_name( "salario", "Salário" );
        GUMP::set_field_name( "qtdSol", "Qtd. Solicitada" );
        GUMP::set_field_name( "qtdEnc", "Qtd. Encaminhada" );
        GUMP::set_field_name( "suspensao", "Data de Suspensão" );
        GUMP::set_field_name( "pcd", "Exclusiva para PcD" );


        GUMP::set_error_messages(array(
            "validate_valid_eng_per_pas_name"  => "O campo {field} não é válido",
        ));
    }


    /**
     * Determina se o id da cidade existe (é maior que 0)
     *
     * Usage: '<index>' => 'exist_city_id'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_exist_city_id( $field, $input, $param = null )
    {
        GUMP::set_error_messages(array(
            "validate_exist_city_id"  => "A {field} não foi encontrada.",
        ));

        if ( !isset($input[$field]) || $input[$field] === '' ) {
            return;
        }

        if (is_numeric($input[$field]) && is_numeric($param) && ($input[$field] >= $param)) {
            return;
        }

        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule'  => __FUNCTION__,
            'param' => $param,
        );
    }


     /**
     * Determina se o valor atual já está cadastrado no banco (serve para email, cpf, etc.)
     *
     * Usage: '<index>' => 'unique'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_unique( $field, $input, $param = null )
    {
        GUMP::set_error_messages(array(
            "validate_unique"  => "Já existe um cadastro para o valor do campo {field}",
        ));

        if ( !isset($input[$field]) || $input[$field] === '' ) {
            return;
        }

        if ( $field === 'email' ) {
            // Verifica se o email é único

            $objUser = $this->c->User;
            $objUser->setEmail( $input[$field] );

            $objServiceUser = $this->c->ServiceUser;
            $objServiceUser = $objServiceUser->getUserByEmail();

             if ( ! ($objServiceUser ?? null) ) {
                return;
             }

             $userId = $objServiceUser->getModel()->getId();
             $userEmail = $objServiceUser->getModel()->getEmail();

             if ( ($userId === getOld('id')) AND ($userEmail === $input[$field]) ) {
                 return;
             }
        } elseif ( $field === 'emailMun' ) {
            // implementar validação
            return;
        } elseif ( $field === 'cpf' ) {
            // implementar validação
            return;
        }

        return array(
            'field' => $field,
            'value' => $input[$field],
            'rule'  => __FUNCTION__,
            'param' => $param,
        );
    }


/**
 * Determina se o cpf é valido
 * https://pt.stackoverflow.com/questions/280429/validando-data-e-cpf-no-gump
 *
 * Usage: '<index>' => 'valid_cpf'
 *
 * @param string $field
 * @param array  $input
 * @param int   $param
 * @return mixed
 */
protected function validate_valid_cpf( $field, $input, $param = null )
{
    GUMP::set_error_messages(array(
        "validate_valid_cpf"  => "Este {field} não é válido.",
    ));

    if ( !isset($input[$field]) || $input[$field] === '' ) {
        return;
    }

    function digitos( $cpf )
    {
        // Verifica se foi informado todos os digitos corretamente
        if ( strlen($cpf) != 11 ) {
            return false;
        }
        return true;
    }

    function digitosRepetidos( $cpf )
    {
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        return true;
    }

    function calculaCpf( $cpf )
    {
        // Faz o calculo para validar o CPF
        for ( $t = 9; $t < 11; $t++ ) {
            for ( $d = 0, $c = 0; $c < $t; $c++ ) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }

    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $input[$field] );

    if ( digitos($cpf) && digitosRepetidos($cpf) && calculaCpf($cpf) ) {
        return;
    }

    return array(
        'field' => $field,
        'value' => $input[$field],
        'rule'  => __FUNCTION__,
        'param' => $param,
    );
}


/**
* Determina se o cnpj é valido
* https://gist.github.com/guisehn/3276302
*
* Usage: '<index>' => 'valid_cnpj'
*
* @param string $field
* @param array  $input
* @param int   $param
* @return mixed
*/
protected function validate_valid_cnpj( $field, $input, $param = null )
{
    GUMP::set_error_messages(array(
        "validate_valid_cnpj"  => "Este {field} não é válido.",
    ));

    if ( !isset($input[$field]) || $input[$field] === '' ) {
        return;
    }

    function tamanhoCnpj( $cnpj )
    {
        // Valida tamanho
        if ( strlen($cnpj) != 14 ) {
            return false;
        }

        return true;
    }

    function priDigito( $cnpj )
    {
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ( $cnpj{12} != ($resto < 2 ? 0 : 11 - $resto) ) {
            return false;
        }

        return true;
    }

    function segDigito( $cnpj )
    {
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ( $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto) ) {
            return true;
        }

        return false;
    }

    function digitosRepetidosCnpj( $cnpj )
    {
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 11.111.111/1111-11
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        return true;
    }

    // Extrai somente os números
    $cnpj = preg_replace('/[^0-9]/', '', (string) $input[$field]);

    if ( tamanhoCnpj($cnpj) && digitosRepetidosCnpj($cnpj) && priDigito($cnpj) && segDigito($cnpj) ) {
        return;
    }

    return array(
        'field' => $field,
        'value' => $input[$field],
        'rule'  => __FUNCTION__,
        'param' => $param,
    );
}


    /**
     * Determina se o salário é valido
     * https://pt.stackoverflow.com/questions/280429/validando-data-e-cpf-no-gump
     *
     * Usage: '<index>' => 'valid_wage'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_valid_wage( $field, $input, $param = null )
    {
        GUMP::set_error_messages(array(
            "validate_valid_wage"  => "O campo {field} não é válido",
        ));

        if ( !isset($input[$field]) || empty($input[$field]) ) {
            return;
        }

        $arr = $input[$field];


        if ( is_array($arr) ) {
            foreach ($arr as $key => $value) {
                if ( empty($value) ) {
                    continue;
                }

                // Verifica se o salário está no formato correto. Ex: 11.345,89
                if ( ! preg_match('/^[-+]?\d{1,3}(\.\d{3})*,\d{2}$/', $value) )
                {
                    return array(
                        'field' => $field,
                        'value' => $input[$field],
                        'rule'  => __FUNCTION__,
                        'param' => $param,
                    );
                }
            }
        } else {
            if ( ! preg_match('/^[-+]?\d{1,3}(\.\d{3})*,\d{2}$/', $arr) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
            return;
        }

    }


    /**
     * Determina se todas as datas do array são válidas
     *
     * Usage: '<index>' => 'date_arr'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_date_arr($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_date_arr"  => "Um dos campos {field} não é válido",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            if ( empty($value) ) {
                continue;
            }

            $date = \DateTime::createFromFormat($param, $value);

            if ($date === false || $value != date($param, $date->getTimestamp()))
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }


    /**
     * Determina se todas os motivos são válidos
     *
     * Usage: '<index>' => 'valid_motivo'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_valid_motivo($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_valid_motivo"  => "Um dos campos {field} não é válido",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            if ( empty($value) ) {
                continue;
            }

            $data = array(
                'motivo' => $value
            );

            $validated = GUMP::is_valid($data, array(
                'motivo' => "required|exact_len,2|contains,'sj' 'cj'"
            ));

            if ( is_array($validated) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }


/**
     * Determina se todas os itens do array estão preenchidos
     *
     * Usage: '<index>' => 'required_arr'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_required_arr($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_required_arr"  => "O preenchimento do campo {field} é obrigatório",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            $data = array(
                'item' => $value
            );

            $validated = GUMP::is_valid($data, array(
                'item' => "required"
            ));

            if ( is_array($validated) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }


    /**
     * Determina se todas os itens do array são números inteiros
     *
     * Usage: '<index>' => 'integer_arr'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_integer_arr($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_integer_arr"  => "O campo {field} deve ser um número inteiro",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            if ( empty($value) ) {
                continue;
            }

            $data = array(
                'item' => $value
            );

            $validated = GUMP::is_valid($data, array(
                'item' => "integer"
            ));

            if ( is_array($validated) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }


    /**
     * Determine if the provided values lengths of ecah array element is less or equal to a specific value.
     *
     * Usage: '<index>' => 'max_len_arr,240'
     *
     * @param string $field
     * @param array  $input
     * @param null   $param
     *
     * @return mixed
     */
    protected function validate_max_len_arr($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_max_len_arr"  => "Os campos {field} devem conter no máximo {param} caracteres",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            if ( empty($value) ) {
                continue;
            }

            $data = array(
                'item' => $value
            );


            $validated = GUMP::is_valid($data, array(
                'item' => "max_len,{$param}"
            ));

            if ( is_array($validated) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }


    /**
     * Determina se todas os itens do array são emails válidos
     *
     * Usage: '<index>' => 'email_arr'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_valid_email_arr($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_valid_email_arr"  => "O campo {field} deve ser um email válido",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            if ( empty($value) ) {
                continue;
            }

            $data = array(
                'item' => $value
            );

            $validated = GUMP::is_valid($data, array(
                'item' => "valid_email"
            ));

            if ( is_array($validated) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }


    /**
     * Determina se todas os itens do array tem exatamento o tamanho do parâmetro
     *
     * Usage: '<index>' => 'exact_len_arr'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_exact_len_arr($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_exact_len_arr"  => "O campo {field} precisa conter exatamente {$param} caracteres",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            if ( empty($value) ) {
                continue;
            }

            $data = array(
                'item' => $value
            );

            $validated = GUMP::is_valid($data, array(
                'item' => "exact_len,{$param}"
            ));

            if ( is_array($validated) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }

    /**
     * Determina se todas os itens do array tem exatamento o tamanho do parâmetro
     *
     * Usage: '<index>' => 'cel_len_arr'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_cel_len_arr($field, $input, $param = null)
    {
        GUMP::set_error_messages(array(
            "validate_cel_len_arr"  => "O campo {field} precisa conter exatamente {$param} caracteres",
        ));

        $arr = $input[$field];

        foreach ($arr as $key => $value) {
            if ( empty($value) ) {
                continue;
            }

            $data = array(
                'item' => $value
            );

            $validated = GUMP::is_valid($data, array(
                'item' => "exact_len,{$param}"
            ));

            if ( is_array($validated) )
            {
                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule'  => __FUNCTION__,
                    'param' => $param,
                );
            }
        }
    }

    /**
     * Determina a extensão do curriculo é .pdf
     *
     * Usage: '<index>' => 'valid_curriculo'
     *
     * @param string $field
     * @param array  $input
     * @param int   $param
     * @return mixed
     */
    protected function validate_valid_curriculo( $field, $input, $param = null )
    {
        if ( !isset($input[$field]) || empty($input[$field]) ) {
            return;
        }

        GUMP::set_error_messages(array(
            "validate_valid_curriculo"  => "O campo Anexar {field} deve conter um arquivo em .pdf",
        ));

        $arquivo = $input[$field];

        $data = array(
            'item' => $arquivo
        );

        $validated = GUMP::is_valid($data, array(
            'item' => "extension,pdf"
        ));

        if ( is_array($validated) )
        {
            return array(
                'field' => $field,
                'value' => $input[$field],
                'rule'  => __FUNCTION__,
                'param' => $param,
            );
        }

        return;
    }

}
