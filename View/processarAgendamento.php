<?php
// Verifica se os dados foram enviados via m�todo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos necess�rios foram preenchidos
    if (isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["data"])) {
        // Obt�m os valores dos campos do formul�rio
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $data = $_POST["data"];

        // Aqui voc� pode realizar qualquer processamento adicional necess�rio, como valida��es, sanitiza��es, etc.

        // Por exemplo, voc� pode armazenar os dados em um banco de dados
        // Substitua essas linhas pelo c�digo espec�fico para o seu banco de dados
        include_once "../Model/AgendaDAO.php";        
        
        $ContatoDAO = new ContatoDAO();
        $resultado = $ContatoDAO->cadastrarAgendamentoDAO($stFiltro);

        // Ap�s processar os dados, voc� pode redirecionar o usu�rio para uma p�gina de confirma��o
        header("Location: agendamento.php");
        exit;
    } else {
        // Se algum campo estiver faltando, exiba uma mensagem de erro
        echo "Por favor, preencha todos os campos do formul�rio.";
    }
} else {
    // Se os dados n�o foram enviados via m�todo POST, redirecione o usu�rio de volta para o formul�rio
    header("Location: agendamento.php");
    exit;
}
?>