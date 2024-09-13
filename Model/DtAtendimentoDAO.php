<?php
class DtAtendimentoDAO
{
    public function CadastrarDtAtendimento($datasAtendimento) 
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
        $conexao = $db->abrirConexaoDB();

        for ($x = 0; $x < count($datasAtendimento->idSemana); $x++) {
            // monta o query cadastro
            $sql = "INSERT INTO dt_atendimento (dt_inicio_mes)
            VALUES (?)";
    
            // cria o prepared statement
            $stmt = $conexao->prepare($sql);
    
            //Vincula o parametro que sera inserido no DB
            $stmt->bind_param("s", $dtInicioMes);
    
            // Recebe os valores guardados no objeto
            $dtInicioMes = $datasAtendimento->dtInicioMes;
    
            // Executa o Statement
            $cadastrou = $stmt->execute();
        }
        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);
        return $cadastrou;
    }

    public function buscarAtendimentoDAO($datasAtendimento)
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();

        $conexao = $db->abrirConexaoDB();

        // Monta query Busca
        $sql = " SELECT * FROM dt_atendimento where dt_inicio_mes = ?";

        // cria o prepared statement
        $stmt = $conexao->prepare($sql);

        //Vincula o parametro que sera inserido no DB
        $stmt->bind_param("s", $dtInicioMes);

        // Recebe os valores guardados no objeto
        $dtInicioMes = $datasAtendimento->dtInicioMes;

        // Executa o Statement
        $stmt->execute();

        // Guarda o resultado encontrado
        $resultado = $stmt->get_result()->fetch_assoc();

        // Fecha Statement e conexão
        $stmt->close();
        $db->fecharConexaoDB($conexao);

        return $resultado;
    }

    public function recuperaTodos($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT dt_atendimento.id, dt_atendimento.dt_inicio_mes FROM dt_atendimento
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
}