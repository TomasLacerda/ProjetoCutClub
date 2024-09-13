<?php
include_once "../Model/Contato.php";
include_once "../Model/ContatoDAO.php";

if (isset($_GET['id'])) {
    $id_barbeiro = $_GET['id'];
    $contato = new Contato();
    $ContatoDAO = new ContatoDAO();

    $contato->id = $id_barbeiro;
    $contato->barbeiro = 0;

    if (isset($_GET['location'])) {
        $resultado = $ContatoDAO->excluirCadastroDAO($contato);
    } else {
        $resultado = $ContatoDAO->salvarFuncionarioDAO($contato);
    }

    // Redireciona de volta para a p�gina anterior ap�s a exclus�o
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=realizada");
    exit;
} else {
    // Se nenhum ID foi fornecido, redireciona de volta � p�gina anterior sem fazer nada
    header("Location: {$_SERVER['HTTP_REFERER']}?exclusao=nao-realizada");
    exit;
}
