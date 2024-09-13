<?php
class DtIndisponivel
{
    // Atributos da classe
    private $id;
    private $hora_fim;
    private $id_semana;
    private $hora_inicio;
    private $data_inicio;
    private $id_barbeiro;
    private $data_fim_regra;
    private $repetir;

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