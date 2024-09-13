<?php
class Expediente
{
    // Atributos da classe
    private $id;
    private $id_semana;
    private $hr_inicio;
    private $hr_fim;
    private $hr_incio_itv;
    private $hr_fim_itv;

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