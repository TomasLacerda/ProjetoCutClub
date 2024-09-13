<?php
class Semana
{
    // Atributos da classe
    private $id;
    private $dia_semana;

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