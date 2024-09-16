<?php
// Inicia a sessão
session_start();

// Verifica se o formulário foi enviado
if (isset($_POST['cadastrar'])) {
    cadastrarBonus();
} elseif (isset($_POST['buscar'])) {
    buscarBonus();
} elseif (isset($_POST['editar'])) {
    editarBonus();
} elseif (isset($_POST['excluir'])) {
    excluirHorario();
} else {
    header("Location: ../View/home.php");
    exit();
}

function cadastrarBonus() {
    include_once "../Model/BonusDAO.php";
    include_once "../Model/BonusService.php";
    include_once "../Model/Bonus.php";

    // Cria os objetos
    $bonusService = new BonusService();
    $bonus = new Bonus();
    $bonusDAO = new BonusDAO();

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

    // Verificar se a data final é maior que a data inicial
    if (strtotime($dt_final) <= strtotime($dt_inicio)) {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => 'A data final deve ser maior que a data inicial!'
        ));
        exit();
    }

    // Verifica se o bônus já está cadastrado para o serviço
    $stFiltro = " WHERE id_servico = ".$servico;
    $resultado = $bonusDAO->recuperaTodos($stFiltro);

    if ($resultado && $resultado->num_rows > 0) {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => 'Esse serviço já está cadastrado no bônus!',
        ));
        exit();
    }

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

function buscarBonus() {
    include_once "../Model/BonusDAO.php";

    // Validação e sanitização de dados recebidos via POST
    $idBonus = isset($_POST['id_servico']) ? intval($_POST['id_servico']) : null;

    // Verificação básica dos campos obrigatórios
    if (!$idBonus) {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => 'Bônus não encontrado!'
        ));
        exit();
    }

    // Cria os objetos
    $bonusDAO = new BonusDAO();

    // Preenche os objetos
    $stFiltro = " WHERE id_servico = ".$idBonus;

    // Envia os objetos e recebe a resposta
    $response = $bonusDAO->recuperaTodos($stFiltro);

    // Verifica o tipo de retorno e envia a resposta em JSON
    if ($response && $response->num_rows > 0) {
        // Extrai os dados do resultado
        $bonusData = $response->fetch_assoc();

        // Formatar as datas para 'YYYY-MM-DD' (se necessário)
        $dt_inicio_formatada = date('Y-m-d', strtotime($bonusData['data_inicio']));
        $dt_final_formatada = date('Y-m-d', strtotime($bonusData['data_fim']));

        echo json_encode(array(
            'sucesso' => true,
            'id_servico' => $bonusData['id_servico'], 
            'nome_servico' => $bonusData['nome'],
            'meta' => $bonusData['meta'],
            'dt_inicio' => $dt_inicio_formatada,
            'dt_final' => $dt_final_formatada,
        ));
        exit();
    } else {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => 'Bônus não encontrado!'
        ));
        exit();
    }
}

function editarBonus() {
    include_once "../Model/BonusService.php";
    include_once "../Model/Bonus.php";

    // Validação e sanitização de dados recebidos via POST
    $servico = isset($_POST['id_servico']) ? intval($_POST['id_servico']) : null;
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
    $response = $bonusService->bonusUpdate($bonus);

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

function excluirHorario() {
    include_once "../Model/ExpedienteDAO.php";
    $idHorario = isset($_POST['id_horario']) ? intval($_POST['id_horario']) : null;

    if (!$idHorario) {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => 'ID do serviço inválido!'
        ));
        exit();
    }

    $expedienteDAO = new ExpedienteDAO();
    $deletou = $expedienteDAO->excluirHorarioDAO($idHorario);

    if ($deletou) {
        echo json_encode(array(
            'sucesso' => true,
            'mensagem' => 'Expediente excluído com sucesso!'
        ));
    } else {
        echo json_encode(array(
            'sucesso' => false,
            'mensagem' => 'Erro ao excluir o expediente.'
        ));
    }
}