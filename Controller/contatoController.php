<?php
session_start();

// Cadastro
if (isset($_POST['cadastrar'])) {
    cadastrarContato();
// Buscar
} elseif (isset($_POST['cadastrarBarbeiro'])) {
    cadastrarBarbeiro();
// Buscar
} elseif (isset($_POST['buscar'])) {
    //buscarContato();
// Editar
} elseif (isset($_POST['editar'])) {
    editarContato();
// Excluir 
} elseif (isset($_POST['excluir'])) {
    //excluirContato();
// Login
} elseif (isset($_POST['login'])) {
    efetuarLogin();
// Logout
} elseif (isset($_POST['logout'])) {
    efetuarLogout();
} else {
    header("Location: ../View/home.php");
}
    // Nenhuma das alternativas 


// Functions

function efetuarLogin()
{
    // Incluir arquivos
    include_once "../Model/Contato.php";
    include_once "../Model/ContatoService.php";

    // Retorno Json - validar
    header('Content-Type: application/json');

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Cria os objetos
    $contato = new Contato();
    $service = new ContatoService();

    // Preenche os objetos
    $contato->email = $email;
    $contato->senha = $senha;

    // Envia os objetos
    $response = $service->efetuarLoginService($contato);

    // Verifica o tipo de retorno
    if ($response['sucesso']) {
        $resultado = $response['resultado'];

        // Guarda os dados na Session
        $_SESSION['id'] = $resultado['id'];
        $_SESSION['nome'] = $resultado['nome'];
        $_SESSION['email'] = $resultado['email'];

        // Mostra mensagem de sucesso
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'codigo' => 1
        ));
        exit();
    } else {
        // Mostra mensagem de erro
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'campo' => $response['campo'],
            'codigo' => 0
        ));
        exit();
    }
}

function efetuarLogout()
{
    session_destroy();
}

// Functions
function cadastrarContato()
{
    // Incluir arquivos
    include_once "../Model/Contato.php";
    include_once "../Model/ContatoService.php";

    // Retorno Json - validar
    header('Content-Type: application/json');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $sobrenome = $_POST['sobrenome'];

    // Cria os objetos
    $contato = new Contato();
    $service = new ContatoService();

    // Preenche os objetos
    $contato->nome = $nome;
    $contato->email = $email;
    $contato->senha = $senha;
    $contato->telefone = $telefone;
    $contato->sobrenome = $sobrenome;
    $contato->barbeiro = 0;

    // Envia os objetos
    $response = $service->cadastrarContatoService($contato);

    // Verifica o tipo de retorno
    if ($response['sucesso']) {
        // Mostra mensagem de sucesso
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'codigo' => 1
        ));
        exit();
    } else {
        // Mostra mensagem de erro
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'campo' => $response['campo'],
            'codigo' => 0
        ));
        exit();
    }
}

// Functions
function cadastrarBarbeiro()
{
    // Incluir arquivos
    include_once "../Model/Contato.php";
    include_once "../Model/ContatoService.php";

    // Retorno Json - validar
    header('Content-Type: application/json');

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['telefone'];
    $sobrenome = $_POST['sobrenome'];

    // Cria os objetos
    $contato = new Contato();
    $service = new ContatoService();

    // Preenche os objetos
    $contato->nome = $nome;
    $contato->email = $email;
    $contato->senha = $senha;
    $contato->telefone = $telefone;
    $contato->sobrenome = $sobrenome;
    $contato->barbeiro = 1;

    // Envia os objetos
    $response = $service->cadastrarContatoService($contato);

    // Verifica o tipo de retorno
    if ($response['sucesso']) {
        // Mostra mensagem de sucesso
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'codigo' => 1
        ));
        exit();
    } else {
        // Mostra mensagem de erro
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'campo' => $response['campo'],
            'codigo' => 0
        ));
        exit();
    }
}

function editarContato()
{
    // Incluir arquivos
    include_once "../Model/Contato.php";
    include_once "../Model/ContatoService.php";

    // Retorno Json - validar
    header('Content-Type: application/json');

    if (!empty($_POST['boBarbeiro'])) {
        $idBarbeiro = $_POST['boBarbeiro']; // Atribui o array à variável $boBarbeiro
        foreach ($idBarbeiro as $id) {
            // Verifica se o checkbox foi marcado
            if ($id != '') {
                // Cria os objetos
                $contato = new Contato();
                $service = new ContatoService();
                // Preenche os objetos
                $contato->id = $id;
                $contato->barbeiro = 1;
        
                // Envia os objetos
                $response = $service->atualizarContatoService($contato);
            }
        }
    } else {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $telefone = $_POST['telefone'];
        $sobrenome = $_POST['sobrenome'];

        // Cria os objetos
        $contato = new Contato();
        $service = new ContatoService();

        // Preenche os objetos
        $contato->nome = $nome;
        $contato->email = $email;
        $contato->senha = $senha;
        $contato->telefone = $telefone;
        $contato->sobrenome = $sobrenome;

        // Envia os objetos
        $response = $service->editarContatoService($contato);
    }

    // Verifica o tipo de retorno
    if ($response['sucesso']) {
        // Mostra mensagem de sucesso
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'codigo' => 1
        ));
        exit();
    } else {
        // Mostra mensagem de erro
        print json_encode(array(
            'mensagem' => $response['mensagem'],
            'campo' => $response['campo'],
            'codigo' => 0
        ));
        exit();
    }
}