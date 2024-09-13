<?php
class Bonus
{
    // Atributos da classe
    private $id;
    private $id_servico;
    private $data_inicio;
    private $data_fim;
    private $meta;

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