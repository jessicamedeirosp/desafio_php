<?php

namespace App\Controllers;

use SON\Controller\Action;
use SON\Di\Container;

class Index extends Action
{

    public function index()
    {
        $model =  Container::getClass("Colaborador");


        $colaboradores = $model->listaColaboradores();
        $this->view->colaboradores = $colaboradores;


        $this->render('index');

        unset($_SESSION['mensagem']);
    }


    public function colaborador()
    {

        $model =  Container::getClass("Colaborador");
        $cargos = $model->listaCargos();
        $this->view->cargos = $cargos;
        $this->render('colaborador');
    }
    public function json()
    {
        $model =  Container::getClass("Colaborador");
        $relatorio = $model->geraRelatorio($_GET);
        $relatorio[0]['fgts'] = $this->gerarFgts($relatorio[0]['salario_bruto']);
        $relatorio[0]['inss'] = $this->gerarInss($relatorio[0]['salario_bruto']);
        $relatorio[0]['ir'] = $this->gerarIR($relatorio[0]['salario_bruto']);
        $relatorio[0]['salario_liquido'] =  $relatorio[0]['salario_bruto'] - ($relatorio[0]['inss'] + $relatorio[0]['fgts'] + $relatorio[0]['ir']);
        echo (json_encode($relatorio));
        return;
    }

    public function gerarPdf()
    {
        $r = $this->relatorio($_POST['id']);

        $html = '
        <div  style="border:1px solid #cacaca;width:100%">
        <table style="width:100%;color: #212529;border: 0;">
        <tbody>
            <tr>
                <td style="text-transform:uppercase;padding:10px;">' . $r[0]['nome'] . '</td>
                <td style="text-transform:uppercase;padding:10px;"><b>RG: </b>' . $r[0]['rg'] . '</td>
                <td style="text-transform:uppercase;padding:10px;"><b>CPF: </b>' . $r[0]['cpf'] . '</td>
            </tr>
            <tr>
                <td style="text-transform:uppercase;padding:10px;"><b>Dt Nasc: </b>' . date('d/m/Y', strtotime($r[0]['data_nascimento'])) . '</td>
                <td style="text-transform:uppercase;padding:10px;"  colspan="2"><b>Cargo: </b>' . $r[0]['cargo_nome'] . '</td>
            </tr>
            <tr>
                <td style="text-transform:uppercase;padding:10px;">Salário Bruto</td>
                <td style="text-transform:uppercase;padding:10px;;padding:10px;text-align:right;"  colspan="2">R$ ' . $r[0]['salario_bruto'] . '</td>
            </tr>
            <tr>
                <td style="text-transform:uppercase;padding:10px;">FGTS</td>
                <td style="text-transform:uppercase;padding:10px;;padding:10px;text-align:right;"  colspan="2">R$ ' . $r[0]['fgts'] . '</td>
            </tr>
            <tr>
                <td style="text-transform:uppercase;padding:10px;">INSS</td>
                <td style="text-transform:uppercase;padding:10px;;padding:10px;text-align:right;"  colspan="2">R$ ' . $r[0]['inss'] . '</td>
            </tr>
            <tr>
                <td style="text-transform:uppercase;padding:10px;">IR</td>
                <td style="text-transform:uppercase;padding:10px;;padding:10px;text-align:right;"  colspan="2">R$ ' . $r[0]['ir'] . '</td>
            </tr>
            <tr>
                <td style="text-transform:uppercase;padding:10px;">Sálario Líquido</td>
                <td style="text-transform:uppercase;padding:10px;;padding:10px;text-align:right;"  colspan="2">R$ ' . $r[0]['salario_liquido'] . '</td>
            </tr>
        </tbody>
        </table>
        </div>';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);

        $mpdf->Output();
    }
    public function gerarFgts($s)
    {
        return $s * 0.08;
    }
    public function gerarInss($s)
    {

        if ($s <= 1830.29) {
            $inss = $s * 0.08;
        } else if (
            $s > 1830.30 and
            $s <= 3050.52
        ) {
            $inss = $s * 0.09;
        } else {
            $inss = $s * 0.11;
        }
        return $inss;
    }
    public function gerarIR($s)
    {



        if ($s >= 1903.99 and $s <= 2826.65) {
            $ir = ($s * 0.075) - 142.80;
        } else if ($s >= 2826.66 and $s <= 3751.05) {
            $ir = ($s * 0.15) - 354.80;
        } else if ($s >= 3751.06 and $s <= 4664.68) {
            $ir = ($s *  0.225) - 636.13;
        } else if ($s > 4664.68) {
            $ir = ($s * 0.275) - 869.36;
        }

        return $ir;
    }
    public function relatorio($id = 0)
    {

        if ($_POST || $id !== 0) {
            if ($id !== 0) {
                $colaborador = array('id' => $id);
            } else {
                $colaborador = $_POST;
            }
            $model =  Container::getClass("Colaborador");
            $relatorio = $model->geraRelatorio($colaborador);
            $relatorio[0]['fgts'] = $this->gerarFgts($relatorio[0]['salario_bruto']);
            $relatorio[0]['inss'] = $this->gerarInss($relatorio[0]['salario_bruto']);
            $relatorio[0]['ir'] = $this->gerarIR($relatorio[0]['salario_bruto']);
            $relatorio[0]['salario_liquido'] =  $relatorio[0]['salario_bruto'] - ($relatorio[0]['inss'] + $relatorio[0]['fgts'] + $relatorio[0]['ir']);

            if ($id !== 0) {
                return $relatorio;
            } else {
                $this->view->relatorio = $relatorio;
                $this->render('relatorio');
            }
        } else {
            echo "selecione o colaborador!";
            exit();
        }
    }


    public function cadastrar()
    {


        $model =  Container::getClass("Colaborador");
        if ($_POST) {
            $_POST['rg'] = preg_replace('/[^0-9a-zA-z]/', '', $_POST['rg']);
            $_POST['cpf'] = preg_replace('/[^0-9a-zA-z]/', '', $_POST['cpf']);
            $_POST['data_nascimento'] =  date('Y-m-d', strtotime($_POST['data_nascimento']));
            $_POST['salario_bruto'] = str_replace(',', '.', $_POST['salario_bruto']);
            $result = $model->cadastraColaborador($_POST);
        }
        $mensagem  = array(
            'codigo' => $result[0]['codigo'],
            'mensagem' => $result[0]['mensagem'],
            'contador' => 0
        );
        //  $this->view->mensagem =  $mensagem;
        header('Location:/');
    }
    public function cargo()
    {


        $model =  Container::getClass("Colaborador");
        // if ($_POST) {

        $result = $model->cadastraCargo($_POST);
        //var_dump($result[0]);
        echo json_encode($result[0]);
        //}
        //return false;
    }
    public function cadastrarApi()
    {
        $model =  Container::getClass("Colaborador");
        $api = $_GET['url'];

        $ch = curl_init();


        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $output = curl_exec($ch);
        $output = json_decode($output, true);

        $output[0]['cpf'] = $_GET['cpf'];
        unset($output[0]['inss']);
        unset($output[0]['fgts']);
        unset($output[0]['ir']);
        unset($output[0]['salario_liquido']);
        unset($output[0]['cargo_nome']);
        unset($output[0]['status']);
        unset($output[0]['id']);
        unset($output[0]['data_cadastro']);
        //var_dump($output[0]);
        //exit();

        //http://localhost:8065/cadastrarApi?url=http://localhost:8065/json?id=1&cpf=55486798236
        $result = $model->cadastraColaborador($output[0]);

        echo ($result[0]['mensagem']);


        curl_close($ch);
        exit();
    }
}
