<?php
class Servico
{
    // Atributos da classe
    private $id;
    private $valor;
    private $nome;
    private $duracao;
    private $descricao;
    private $imagem;

    // GET && SET
    public function __get($valor)
    {
        return $this->$valor;
    }

    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }
}