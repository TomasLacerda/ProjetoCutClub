<?php
class BonusDAO
{
    public function recuperaTodos($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT * FROM bonus
                JOIN servico
                    ON servico.id = bonus.id_servico
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

    public function cadastrarDAO($agendar) 
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();

        $conexao = $db->abrirConexaoDB();

        // monta o query cadastro
        $sql = "INSERT INTO bonus (id_servico, data_inicio, data_fim, meta)
                VALUES (?, ?, ?, ?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("isss", $id_servico, $dtInicio, $dtFim, $meta);
        // Recebe os valores guardados no objeto
        $id_servico = $agendar->id_servico;
        $meta = $agendar->meta;
        $dtInicio = $agendar->data_inicio;
        $dtFim = $agendar->data_fim;

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conex�o
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
        $sql = " SELECT * FROM contato WHERE email = ?";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $email);

        $email = $contato->email;

        // Executa o Statement
        $stmt->execute();

        // Guarda o resultado encontrado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Fecha Statement e conex�o
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

        // Fecha Statement e conex�o
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;

    }

    public function excluirAgendamentoDAO($agenda)
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "DELETE FROM agenda WHERE agenda.id in (?)";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $id);

        // Recebe os valores guardados no objeto
        $id = $agenda->id;

        // Executa o Statement
        $deletou = $stmt->execute();

        // Fecha Statement e conex�o
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $deletou;
    }
}