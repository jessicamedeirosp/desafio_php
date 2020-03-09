<?php

namespace App\Models;



class Colaborador
{
    protected $db;
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function listaColaboradores()
    {
        $query = "SELECT c.*, ca.nome as cargo_nome FROM colaborador as c inner join cargo ca on ca.id = c.cargo";

        $query =  $this->db->query($query);

        return  $query->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function listaCargos()
    {
        $query = "SELECT * from cargo";

        $query =  $this->db->query($query);

        return  $query->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function geraRelatorio($parametros)
    {



        $stmt = $this->db->prepare("SELECT c.*, ca.nome as cargo_nome FROM colaborador as c inner join cargo ca on ca.id = c.cargo where c.id = :id");
        $stmt->bindParam('id', $parametros['id']);
        $stmt->execute();
        $resposta =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $resposta;
    }
    public function cadastraColaborador($parametros)
    {


        $stmt = $this->db->prepare("call colaborador_inserir(
           :nome,
           :data_nascimento,
           :rg,
           :cpf,
           :cep,
           :logradouro,
           :numero,
           :bairro,
           :cidade,
           :estado,
           :cargo,
           :salario_bruto
            
        )");
        $this->preparaBindParam($stmt, $parametros);
        $stmt->execute();
        $resposta =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $resposta;
    }
    public function cadastraCargo($parametros)
    {


        $stmt = $this->db->prepare("call cargo_inserir(:nome)");
        $this->preparaBindParam($stmt, $parametros);
        $stmt->execute();
        $resposta =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $resposta;
    }
    public function preparaBindParam($stmt, $parametros)
    {

        foreach ($parametros as $key => $value) {
            $stmt->bindParam($key, $parametros[$key]);
        }
        return;
    }
}
