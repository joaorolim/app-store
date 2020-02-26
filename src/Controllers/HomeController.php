<?php

namespace BET\Controllers;

use BET\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController extends ControllerAbstract
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $objServiceAtendimento = $this->c->ServiceAtendimento;

        // $mes = date('m');
        // $mesSemZero = date('n');
        // $ano = date('Y');

        // $dataIni = "{$ano}-{$mes}-01";
        // $dataFim = "{$ano}-{$mes}-31";

        // $descMes = getAllMeses( $mesSemZero, null );
        // $header = ucfirst($descMes[$mesSemZero])." de {$ano}";

        /**** ini - Buscar atendimentos por tipo ****/
        $result = $objServiceAtendimento->getAtendimentosByTipo();

        $arrLabels = array();
        $arrDados = array();
        foreach ($result as $key => $obj) {
            array_push( $arrLabels, "'".$obj->tipo."'" );
            array_push( $arrDados, (int)$obj->qtd );
        }

        $labels = implode(",", $arrLabels);
        $dados = implode(",", $arrDados);
        /**** fim - Buscar atendimentos por tipo ****/

        /**** ini - Buscar atendimentos por Assistencia ****/
        $result = $objServiceAtendimento->getAtendimentosByAssistencia();

        $arrLabels2 = array();
        $arrDados2 = array();
        foreach ($result as $key => $obj) {
            array_push( $arrLabels2, "'".$obj->assistencia."'" );
            array_push( $arrDados2, (int)$obj->qtd );
        }

        $labels2 = implode(",", $arrLabels2);
        $dados2 = implode(",", $arrDados2);
        /**** fim - Buscar atendimentos por Assistencia ****/

        /**** ini - Buscar atendimentos por Valor do Produto ****/
        $result = $objServiceAtendimento->getAtendimentosByValor();

        $arrLabels3 = array();
        $arrDados3 = array();
        foreach ($result as $key => $obj) {
            array_push( $arrLabels3, "'".$obj->range."'" );
            array_push( $arrDados3, (int)$obj->qtd );
        }

        $labels3 = implode(",", $arrLabels3);
        $dados3 = implode(",", $arrDados3);
        /**** fim - Buscar atendimentos por Valor do Produto ****/

        /**** ini - Buscar atendimentos por Modelo do Produto ****/
        $result = $objServiceAtendimento->getAtendimentosByModelo();

        $arrLabels4 = array();
        $arrDados4 = array();
        foreach ($result as $key => $obj) {
            array_push( $arrLabels4, "'".$obj->modelo."'" );
            array_push( $arrDados4, (int)$obj->qtd );
        }

        $labels4 = implode(",", $arrLabels4);
        $dados4 = implode(",", $arrDados4);
        /**** fim - Buscar atendimentos por Modelo do Produto ****/

        return $this->c->renderer->render($response, 'template_admin.php', [
            'name' => $_SESSION['user']['first_name'] ?? 'Guest ?',
            'viewName' => 'dashboard',
            'header' => "",
            'labels' => $labels,
            'dados' => $dados,
            'labels2' => $labels2,
            'dados2' => $dados2,
            'labels3' => $labels3,
            'dados3' => $dados3,
            'labels4' => $labels4,
            'dados4' => $dados4
        ]);
    }
}
