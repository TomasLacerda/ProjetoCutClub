<?php
// Verifica se o ID do barbeiro foi fornecido na URL
if(isset($_GET['id'])) {
    ob_start(); // Inicia o buffer de sa�da

    $id_agendamento = $_GET['id'];

    include_once "include/menu.php";
    include_once "../Model/Agenda.php";
    include_once "../Model/AgendaDAO.php";
    
    $Agenda = new Agenda();
    $AgendaDAO = new AgendaDAO();

    $Agenda->id = $id_agendamento;

    $resultado = $AgendaDAO->excluirAgendamentoDAO($Agenda);

    // Redireciona de volta para a p�gina anterior ap�s a exclus�o
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=realizada");
    exit;
    ob_end_flush(); // Limpa o buffer de sa�da e envia a sa�da para o navegador

} else {
    // Se nenhum ID foi fornecido, redireciona de volta � p�gina anterior sem fazer nada
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=nao-realizada");
    exit;
}
?>