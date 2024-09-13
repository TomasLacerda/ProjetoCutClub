<?php
class AgendaDAO
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
    
        // Fecha Statement e conex�o
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        return $resultado;
    }

    public function recuperaRelacionamento($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT DATE_FORMAT(dthora_execucao, '%H:%i') AS hora_minuto, duracao
                FROM agenda
                JOIN servico
                    ON servico.id = agenda.id_servico
                ".$stFiltro;
    
        // cria o prepared statement
        $stmt = $conexao->prepare($sql);
    
        // Executa o Statement
        $stmt->execute();
    
        // Guarda todos os resultados encontrados em um array
        $resultado = $stmt->get_result();
    
        // Fecha Statement e conex�o
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        return $resultado;
    }

    public function recuperaHistorico($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT DATE_FORMAT(dthora_execucao, '%d/%m/%Y') AS data, agenda.id, servico.imagem, DATE_FORMAT(dthora_execucao, '%H:%i') AS hora_minuto, contato.nome AS barbeiro, servico.nome as servico, valor, dthora_consumo,confirmado, CONCAT(cliente.nome) AS cliente,
                        CASE WHEN dthora_consumo IS NULL THEN 'FALSE'
                        ELSE 'TRUE' END AS comparaceu 
                FROM agenda
                JOIN servico
                    ON servico.id = agenda.id_servico
                JOIN contato
                    ON contato.id = agenda.id_barbeiro
                JOIN contato as cliente
                    ON cliente.id = agenda.id_cliente
                ".$stFiltro;
    
        // cria o prepared statement
        $stmt = $conexao->prepare($sql);
    
        // Executa o Statement
        $stmt->execute();
    
        // Guarda todos os resultados encontrados em um array
        $resultado = $stmt->get_result();
    
        // Fecha Statement e conex�o
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        return $resultado;
    }

    function recuperarHorariosAgendados($stFiltro) {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Consulta SQL que separa a data e a hora
        $query = "SELECT TIME(dthora_execucao) AS dthora_execucao, duracao 
                  FROM agenda 
                  JOIN servico ON servico.id = agenda.id_servico 
                  ".$stFiltro;
                  
        $stmt = $conexao->prepare($query);
        $stmt->execute();
        $resultado = $stmt->get_result();    

        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $resultado;
    }

    public function agendarDAO($agendar) 
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();
    
        // Inicia uma transação para garantir a consistência entre INSERT e UPDATE
        $conexao->begin_transaction();
    
        try {
            // Query de cadastro na tabela agenda
            $sqlInsert = "INSERT INTO agenda (id_servico, dthora_agendamento, dthora_execucao, dthora_consumo, descricao, preco_atendimento, id_barbeiro, id_cliente)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
            // Prepara o statement para o INSERT
            $stmtInsert = $conexao->prepare($sqlInsert);
            $stmtInsert->bind_param("isssssii", $id_servico, $dtagendamento, $dtexecucao, $dtconsumo, $descricao, $preco, $idBarbeiro, $idCliente);
    
            // Recebe os valores guardados no objeto
            $id_servico = $agendar->id_servico;
            $dtagendamento = $agendar->dthora_agendamento;
            $dtexecucao = $agendar->dthora_execucao;
            $dtconsumo = NULL;
            $descricao = $agendar->descricao; // Aqui pode ser a quantidade de pontos utilizados
            $preco = $agendar->preco_atendimento;
            $idBarbeiro = $agendar->id_barbeiro;
            $idCliente = $agendar->id_cliente;
    
            // Executa o INSERT
            $stmtInsert->execute();
    
            // Se o campo descricao tiver valor (bônus utilizado), realiza o UPDATE na tabela contato
            if (!empty($descricao)) {
                $sqlUpdate = "UPDATE contato SET pontos_utilizados = pontos_utilizados + ? WHERE id = ?";
                $stmtUpdate = $conexao->prepare($sqlUpdate);
                $stmtUpdate->bind_param("ii", $descricao, $idCliente); // `descricao` aqui deve conter os pontos a serem descontados
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
    
            // Se tudo deu certo, faz o commit da transação
            $conexao->commit();
    
            // Fecha o Statement e a conexão
            $stmtInsert->close();
            $db->fecharConexaoDB($conexao);
    
            return true;
        } catch (Exception $e) {
            // Se ocorreu algum erro, faz o rollback da transação
            $conexao->rollback();
            return false;
        }
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
    
        // Inicia a transação para garantir que as duas operações sejam realizadas corretamente
        $conexao->begin_transaction();
    
        try {
            // Primeira parte: Recuperar os pontos utilizados e o id_cliente do agendamento
            $sql = "SELECT descricao, id_cliente FROM agenda WHERE id = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $agenda->id);
            $stmt->execute();
            $stmt->bind_result($pontosUtilizados, $idCliente);
            $stmt->fetch();
            $stmt->close();
    
            // Segunda parte: Devolver os pontos ao cliente
            if ($pontosUtilizados && $idCliente) {
                $sqlUpdatePontos = "UPDATE contato SET pontos_utilizados = pontos_utilizados - ? WHERE id = ?";
                $stmtUpdate = $conexao->prepare($sqlUpdatePontos);
                $stmtUpdate->bind_param("ii", $pontosUtilizados, $idCliente);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
    
            // Terceira parte: Excluir o agendamento
            $sqlDelete = "DELETE FROM agenda WHERE id = ?";
            $stmtDelete = $conexao->prepare($sqlDelete);
            $stmtDelete->bind_param("i", $agenda->id);
            $deletou = $stmtDelete->execute();
            $stmtDelete->close();
    
            // Se tudo ocorreu bem, comite a transação
            $conexao->commit();
    
        } catch (Exception $e) {
            // Se ocorrer algum erro, faça o rollback da transação
            $conexao->rollback();
            $deletou = false;
        }
    
        // Fecha a conexão
        $db->fecharConexaoDB($conexao);
    
        return $deletou;
    }

    public function confirmarCorteDAO($agenda)
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "UPDATE agenda SET confirmado = ? WHERE id in (".$agenda->id.")";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $confirmado);

        // Recebe os valores guardados no objeto
        $confirmado = 1;

        // Executa o Statement
        $deletou = $stmt->execute();

        // Fecha Statement e conex�o
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $deletou;
    }
}