<?php

namespace BET\Controllers;

use Slim\Container;

abstract class ControllerAbstract
{
    /**
     * @var Container
     */
    protected $c;

    /**
     * ControllerAbstract constructor.
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
    }


    protected function makeTable($columns, $rows, $desc_id, $rota_exc, $rota_alt, $jump=array(), $notShowId = true )
    {
        $table = '<table>';

        // head of the table
        $table .=  '<tr>';
        $table .= '<th> </th>';
        $table .= '<th> </th>';
        foreach ($columns as $column)
        {
            $table .= '<th>'.$column.'</th>';
        }
        $table .=  '</tr>';

        $csrf = $this->c->CSRF;

        if ( isset($rows) ) {
            // body of the table
            foreach ($rows as $obj)
            {
                // Não exibe o Admin
                if ( isset( $obj->rol_desc ) AND $obj->rol_desc === 'Admin' ) {
                    continue;
                }

                $table .=  '<tr>';

                $fakeId = $csrf::generateFakeId( $obj->$desc_id );

                if ( ! ($rota_exc === null) ) {
                    $table .= '<td><a href="#" data-href="'.getBaseURL().$rota_exc.$fakeId.'" title="Excluir" data-toggle="modal" data-target="#confirm-delete" class="btn btn-xs"><span class="glyphicon glyphicon-trash"></span></a></td>';
                } else {
                    $table .= '<td>&nbsp</td>';
                }

                if ( ! ($rota_alt === null) ) {
                    $table .= '<td><a class="btn btn-xs" title="Alterar" href="'.getBaseURL().$rota_alt.$fakeId.'"><span class="glyphicon glyphicon-pencil"></span></td>';
                } else {
                    $table .= '<td>&nbsp</td>';
                }

                foreach ($obj as $key => $value)
                {
                    $jumpId = false;

                    if ( $key === $desc_id ) {
                        if ( $notShowId ) {
                            $jumpId = true;
                        }
                    }

                    if ( $jumpId OR in_array($key, $jump) ) {
                        continue;
                    } elseif ( $key == 'use_gender' ) {
                        if ( $value == "m" ) {
                            $sex = 'Masculino';
                        } elseif ( $value == "f" ) {
                            $sex = 'Feminino';
                        } else {
                            $sex = 'Outro';
                        }
                        $table .= '<td>'.htmlspecialchars( $sex ).'</td>';
                    } elseif ( $key == 'use_birthday' ) {
                        $table .= '<td>'.htmlspecialchars( dataMySQL_to_dataBr($value) ).'</td>';
                    } elseif ( $key == 'use_status' ) {
                        $table .= '<td>'.htmlspecialchars( (($value == 1) ? 'Ativo' : 'Inativo') ).'</td>';
                    }else {
                        $table .= '<td>'.htmlspecialchars( $value ).'</td>';
                    }
                }

                $table .=  '</tr>';
            }
        }

        $table .=  '</table>';

        return $table;
    }


    protected function makePageControllers( $array, $rota )
    {
        $paginacao = '<ul class="pagination">';
        $paginacao .= '<li><a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/1'.'" title="primeira">&lt;&lt;</a></li>';
        $paginacao .= '<li><a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['anterior'].'" title="anterior">&lt;</a></li>';

        /**
        * O loop para exibir os valores à esquerda
        */
        for($i = $array['pagina']-$array['exibir']; $i <= $array['pagina']-1; $i++){
            if($i > 0) {
                $paginacao .= '<li><a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$i.'"> '.$i.' </a></li>';
            }
        }

        /**
        * Depois o link da página atual
        */
        $paginacao .= '<li><a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['pagina'].'" style="background-color:lightgrey"><strong>'.$array['pagina'].'</strong></a></li>';

        /**
        * O loop para exibir os valores à direita
        */
        for($i = $array['pagina']+1; $i < $array['pagina']+$array['exibir']; $i++){
            if($i <= $array['totalPagina']) {
                $paginacao .= '<li><a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$i.'"> '.$i.' </a></li>';
            }
        }

        /**
        * Agora monta o Link para Próxima Página
        * Depois O link para Última Página
        */
        $paginacao .= '<li><a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['posterior'].'" title="próxima">&gt;</a></li>';
        $paginacao .= '<li><a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['totalPagina'].'" title="última">&gt;&gt;</a></li>';
        $paginacao .= '</ul>';

        return $paginacao;
    }

    /**
    * Validações
    */
    public function Validation( $fields, $rules, $filters )
    {
        $validator = $this->c->Gump;

        $post = $validator->filter( $fields, $filters );

        // You can run filter() or validate() first

        $validated = $validator->validate(
            $post, $rules
        );

        // Check if validation was successful

        if( $validated === true )
        {
            //Successful Validation

            $validation = array(
                'result' => true,
                'fields' => $post,
                'errors' => null
            );

            return $validation;
        }
        else
        {
            // Validation Failed

            // echo "<pre>post<br>";
            // print_r( $post );
            // echo "</pre>";

            // echo "<pre>validated<br>";
            // print_r( $validated ); // Shows all the rules that failed along with the data
            // echo "</pre>";

            // echo "<pre>validator->get_errors_array()<br>";
            // print_r( $validator->get_errors_array() ); // Shows all the rules that failed along with the data
            // echo "</pre>";
            // exit();

            $validation = array(
                'result' => false,
                'fields' => $post,
                'errors' => $validator->get_errors_array() // Shows all the rules that failed along with the data
            );

            return $validation;
        }

    }


    protected function isItemSelected ( $value, $field, $type='n'  ) : bool
    {
        if ( $type === 'n' ) {
            // Espera-se que o valor passado em $value seja do tipo numérico
            if ( is_numeric($value) ) {
                if ( empty($value) OR $value <= 0 ) {
                    setMessage('Por favor, selecione um valor válido para o campo "'.$field.'" !', 'danger');
                    return false;
                }
            } else {
                setMessage('Por favor, selecione um valor válido para o campo "'.$field.'" !', 'danger');
                return false;
            }

            return true;

        } elseif ( $type === 's' ) {
            // Espera-se que o valor passado em $value seja do tipo String

        }

        return false;
    }
}
