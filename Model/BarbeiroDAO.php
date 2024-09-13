<?php
class BarbeiroDAO
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
}