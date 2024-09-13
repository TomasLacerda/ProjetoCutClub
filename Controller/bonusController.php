<?php
// Inicia a sessão
session_start();

// Verifica se o formulário foi enviado
if (isset($_POST['cadastrar'])) {
    cadastrarDatas();
} else {
    header("Location: ../View/home.php");
    exit();
}

function cadastrarDatas() {
    include_once "../Model/BonusService.php";
    include_once "../Model/Bonus.php";

    // Validação e sanitização de dados recebidos via POST
    $servico = isset($_POST['servico']) ? intval($_POST['servico']) : null;
    $meta = isset($_POST['meta']) ? floatval($_POST['meta']) : null;
    $dt_inicio = isset($_POST['dt_inicio']) ? $_POST['dt_inicio'] : null;
    $dt_final = isset($_POST['dt_final']) ? $_POST['dt_final'] : null;

    // Verificação básica dos campos obrigatórios
    if (!$servico || !$meta || !$dt_inicio || !$dt_final) {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => 'Por favor, preencha todos os campos obrigatórios!'
        ));
        exit();
    }

    // Cria os objetos
    $bonusService = new BonusService();
    $bonus = new Bonus();

    // Preenche os objetos
    $bonus->id_servico = $servico;
    $bonus->meta = $meta;
    $bonus->data_inicio = $dt_inicio;
    $bonus->data_fim = $dt_final;

    // Envia os objetos e recebe a resposta
    $response = $bonusService->bonusService($bonus);

    // Verifica o tipo de retorno e envia a resposta em JSON
    if ($response['sucesso']) {
        echo json_encode(array(
            'sucesso' => true,
            'mensagem' => $response['mensagem'],
        ));
    } else {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => $response['mensagem'],
            'campo' => $response['campo']
        ));
    }
}