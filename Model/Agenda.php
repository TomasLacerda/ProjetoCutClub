<?php
class Agenda
{
    // Atributos da classe
    private $id;
    private $id_servico;
    private $dthora_agendamento;
    private $dthora_execucao;
    private $dthora_consumo;
    private $confirmado;
    private $descricao;
    private $preco_atendimento;
    private $id_barbeiro;
    private $id_cliente;

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