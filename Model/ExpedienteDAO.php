<?php
class ExpedienteDAO
{
    public function excluirHorarioDAO($expediente)
    {
        require_once "ConexaoDB.php";
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        // monta o update
        $sql = "DELETE FROM expediente WHERE id = ?";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("i", $expediente);

        // Executa o Statement
        $cadastrou = $stmt->execute();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $cadastrou;
    }

    public function recuperaRelacionamento($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT 
                    expediente.id,
                    CONCAT(
                        CASE
                            WHEN MIN(semana.id) = 1 THEN 'Seg'
                            WHEN MIN(semana.id) = 2 THEN 'Ter'
                            WHEN MIN(semana.id) = 3 THEN 'Qua'
                            WHEN MIN(semana.id) = 4 THEN 'Qui'
                            WHEN MIN(semana.id) = 5 THEN 'Sex'
                            WHEN MIN(semana.id) = 6 THEN 'Sáb'
                            WHEN MIN(semana.id) = 7 THEN 'Dom'
                        END, 
                        ' - ', 
                        CASE
                            WHEN MAX(semana.id) = 1 THEN 'Seg'
                            WHEN MAX(semana.id) = 2 THEN 'Ter'
                            WHEN MAX(semana.id) = 3 THEN 'Qua'
                            WHEN MAX(semana.id) = 4 THEN 'Qui'
                            WHEN MAX(semana.id) = 5 THEN 'Sex'
                            WHEN MAX(semana.id) = 6 THEN 'Sáb'
                            WHEN MAX(semana.id) = 7 THEN 'Dom'
                        END
                    ) AS id_semana,
                    MIN(hr_inicio) AS hr_inicio,
                    MAX(hr_fim) AS hr_fim,
                    MIN(IFNULL(hr_inicio_itv, 'N/A')) AS hora_inicio_intervalo,
                    MAX(IFNULL(hr_fim_itv, 'N/A')) AS hora_fim_intervalo
                FROM expediente
                JOIN semana
                    ON semana.id = expediente.id_semana
                GROUP BY hr_inicio, hr_fim, hr_inicio_itv, hr_fim_itv
                
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

    public function CadastrarExpediente($expediente) 
    {
        require_once "ConexaoDB.php";
    
        $response = ['sucesso' => false, 'mensagem' => 'Erro ao efetuar o cadastro.', 'campo' => ''];
    
        // Obtém os dias da semana ou define como array vazio se nenhum for selecionado
        $diasSemana = $expediente->id_semana != NULL ? $expediente->id_semana : array(0); // 0 para indicar todos os dias
    
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();
    
        // Iniciar uma transação para garantir que todas as inserções sejam atômicas
        $conexao->begin_transaction();
    
        $sql = "INSERT INTO expediente (id_semana, hr_inicio, hr_fim, hr_inicio_itv, hr_fim_itv)
                VALUES (?, ?, ?, ?, ?)";
    
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("issss", $dia_Semana, $horaInicio, $horaFim, $horaInicioItv, $horaFimItv);
    
        // Se o usuário não selecionou nenhum dia específico, inserimos um registro com id_semana = 0
        if (empty($diasSemana) || (count($diasSemana) == 1 && $diasSemana[0] == 0)) {
            $dia_Semana = 0; // Indica todos os dias
            $horaInicio = $expediente->hr_inicio;
            $horaFim = $expediente->hr_fim;
            $horaInicioItv = $expediente->hr_inicio_itv;
            $horaFimItv = $expediente->hr_fim_itv;
    
            if (!$stmt->execute()) {
                $conexao->rollback();
                $stmt->close();
                $db->fecharConexaoDB($conexao);
                return $response;
            }
        } else {
            // Itera sobre os dias da semana selecionados
            foreach ($diasSemana as $diaSemana) {
                $dia_Semana = $diaSemana;
                $horaInicio = $expediente->hr_inicio;
                $horaFim = $expediente->hr_fim;
                $horaInicioItv = $expediente->hr_inicio_itv;
                $horaFimItv = $expediente->hr_fim_itv;
    
                if (!$stmt->execute()) {
                    $conexao->rollback();
                    $stmt->close();
                    $db->fecharConexaoDB($conexao);
                    return $response;
                }
            }
        }
    
        $conexao->commit();
        $stmt->close();
        $db->fecharConexaoDB($conexao);
    
        $response['sucesso'] = true;
        $response['mensagem'] = 'Cadastro efetuado com sucesso.';
    
        return $response;
    }

    public function recuperaExpediente()
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = " SELECT * FROM expediente ";

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

    public function recuperaTodos()
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = " SELECT * FROM expediente ";

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
    
}