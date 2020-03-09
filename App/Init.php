<?php

namespace App;

use SON\Init\Bootstrap;


class Init extends Bootstrap
{

    protected function initRoutes()
    {
        $ar['home'] = array('route' => '/', 'controller' => 'index', 'action' => 'index');
        $ar['colaborador'] = array('route' => '/colaborador', 'controller' => 'index', 'action' => 'colaborador');
        $ar['cadastrar'] = array('route' => '/cadastrar', 'controller' => 'index', 'action' => 'cadastrar');
        $ar['relatorio'] = array('route' => '/relatorio', 'controller' => 'index', 'action' => 'relatorio');
        $ar['json'] = array('route' => '/json', 'controller' => 'index', 'action' => 'json');
        $ar['gerarPdf'] = array('route' => '/gerarPdf', 'controller' => 'index', 'action' => 'gerarPdf');
        $ar['cadastrarApi'] = array('route' => '/cadastrarApi', 'controller' => 'index', 'action' => 'cadastrarApi');
        $ar['cargo'] = array('route' => '/cargo', 'controller' => 'index', 'action' => 'cargo');


        $this->setRoutes($ar);
    }
    public static function getDb()
    {
        $db = new \PDO('mysql:host=216.172.172.89;port=3306;dbname=jessmede_desafio_php', 'jessmede_dp', '$-Ko[}KQsr@q');

        // $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        // $db->exec('set names utf8');
        return $db;
    }
}
