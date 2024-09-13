<?php
// Verifica se os dados foram enviados via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos necessários foram preenchidos
    if (isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["data"])) {
        // Obtém os valores dos campos do formulário
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        $data = $_POST["data"];

        // Aqui você pode realizar qualquer processamento adicional necessário, como validações, sanitizações, etc.

        // Por exemplo, você pode armazenar os dados em um banco de dados
        // Substitua essas linhas pelo código específico para o seu banco de dados
        include_once "../Model/AgendaDAO.php";        
        
        $ContatoDAO = new ContatoDAO();
        $resultado = $ContatoDAO->cadastrarAgendamentoDAO($stFiltro);

        // Após processar os dados, você pode redirecionar o usuário para uma página de confirmação
        header("Location: agendamento.php");
        exit;
    } else {
        // Se algum campo estiver faltando, exiba uma mensagem de erro
        echo "Por favor, preencha todos os campos do formulário.";
    }
} else {
    // Se os dados não foram enviados via método POST, redirecione o usuário de volta para o formulário
    header("Location: agendamento.php");
    exit;
}
?>