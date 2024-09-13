<?php
// Verifica se o ID do barbeiro foi fornecido na URL
if(isset($_GET['id'])) {
    ob_start(); // Inicia o buffer de saída

    $id_agendamento = $_GET['id'];

    include_once "include/menu.php";
    include_once "../Model/Agenda.php";
    include_once "../Model/AgendaDAO.php";
    
    $Agenda = new Agenda();
    $AgendaDAO = new AgendaDAO();

    $Agenda->id = $id_agendamento;

    $resultado = $AgendaDAO->excluirAgendamentoDAO($Agenda);

    // Redireciona de volta para a página anterior após a exclusão
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=realizada");
    exit;
    ob_end_flush(); // Limpa o buffer de saída e envia a saída para o navegador

} else {
    // Se nenhum ID foi fornecido, redireciona de volta à página anterior sem fazer nada
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=nao-realizada");
    exit;
}
?>