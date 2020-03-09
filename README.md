# desafio_php
desafio php

localhost/json?id=1 -> gera json do colaborador 

localhost/cadastrarApi?url=localhost/json?id=1&cpf=12345678998 -> cadastra via os dados do json e troca o cpf

# VENDOR
caso você use o projeto no IIS, após baixar a vendo alterar o arquivo composer/autoload_namespaces.php
de:
'App' => array('/'),
para:
'App' => array($baseDir . '/'),

e o arquivo composer/autoload_static.php
de:
'App' =>
    array(
        0 => '/',
    ),
para:

'App' =>
    array(
        0 => '../',
    ),
