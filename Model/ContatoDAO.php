<?php
class ContatoDAO
{
    public function recuperaTodos($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT * FROM contato
                ".$stFiltro;
    
        // cria o prepared statement
        $stmt = $conexao->prepare($sql);
    
        // Executa o Statement
        $stmt->execute();
    
        // Guarda todos os resultados encontrados em um array
        $resultado = $stmt->get_result();
    
        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        return $resultado;
    }

    public function cadastrarContatoDAO($contato) 
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();

        $conexao = $db->abrirConexaoDB();

        // monta o query cadastro

        $sql = "INSERT INTO contato (nome, sobrenome, email, senha, telefone, barbeiro)
                VALUES 
                (?, ?, ?, ?, ?, ?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("ssssss", $nome, $sobrenome, $email, $senha, $telefone, $barbeiro);

        // Recebe os valores guardados no objeto
        $nome = $contato->nome;
        $sobrenome = $contato->sobrenome;
        $email = $contato->email;
        $senha = $contato->senha;
        $barbeiro = $contato->barbeiro;

        $removeCaracteres = array("(", ")", "-", " ");
        $telefone = str_replace($removeCaracteres, "", $contato->telefone);

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;

    }

    public function buscarContatoDAO($contato)
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();

        $conexao = $db->abrirConexaoDB();

        // Monta query Busca
        $sql = "SELECT * FROM contato WHERE email = ? OR telefone = ? OR nome = ? OR sobrenome = ?";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("ssss", $email,$email,$email,$email);

        $email = $contato->email;

        // Executa o Statement
        $stmt->execute();

        // Guarda o resultado encontrado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $resultado;
    }

    public function buscarSenhaDAO($contato)
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca usando LOWER() para ignorar maiúsculas/minúsculas
        $sql = "SELECT * FROM contato WHERE LOWER(email) = LOWER(?) AND LOWER(nome) = LOWER(?) AND LOWER(sobrenome) = LOWER(?)";
    
        // cria o prepared statement
        $stmt = $conexao->prepare($sql);
    
        // Vincula o parametro que sera inserido no DB
        $stmt->bind_param("sss", $email, $nome, $sobrenome);
    
        $email = $contato->email;
        $nome = $contato->nome;
        $sobrenome = $contato->sobrenome;

        // Executa o Statement
        $stmt->execute();

        // Guarda o resultado encontrado
        $resultado = $stmt->get_result()->fetch_assoc();
    
        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $resultado;
    }

    public function editarContatoDAO($contato) 
    {
        include_once "ContatoDAO.php";

        $dao = new ContatoDAO();

        $infoContato = $dao->buscarContatoDAO($contato);

        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "UPDATE contato set nome = ?, sobrenome = ?, email = ?, senha = ?, telefone = ?
                WHERE contato.id in (".$infoContato['id'].")";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("sssss", $nome, $sobrenome, $email, $senha, $telefone);

        // Recebe os valores guardados no objeto
        $nome = $contato->nome;
        $email = $contato->email;
        $senha = $contato->senha == "" ? $infoContato['senha'] : $contato->senha;
        $sobrenome = $contato->sobrenome;

        $removeCaracteres = array("(", ")", "-", " ");
        $telefone = str_replace($removeCaracteres, "", $contato->telefone);

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;

    }

    public function salvarFuncionarioDAO($contato) 
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "UPDATE contato set barbeiro = ?
                WHERE contato.id in (?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("ss", $barbeiro, $id);

        // Recebe os valores guardados no objeto
        $id = $contato->id;
        $barbeiro = $contato->barbeiro;
        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;
    }

    public function excluirCadastroDAO($contato)
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "DELETE FROM contato WHERE contato.id in (?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $id);

        // Recebe os valores guardados no objeto
        $id = $contato->id;

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;
    }
}