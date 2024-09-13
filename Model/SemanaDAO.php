<?php
class SemanaDAO
{
    public function recuperaDiaSemana($stFiltro="")
    {
        require_once "ConexaoDB.php";        
        $db = new ConexaoDB();
    
        $conexao = $db->abrirConexaoDB();
    
        // Monta query Busca
        $sql = "SELECT 
                    CASE 
                        WHEN id = 1 THEN 'segunda'
                        WHEN id = 2 THEN 'terca'
                        WHEN id = 3 THEN 'quarta'
                        WHEN id = 4 THEN 'quinta'
                        WHEN id = 5 THEN 'sexta'
                        WHEN id = 6 THEN 'sabado'
                        WHEN id = 7 THEN 'domingo'
                        ELSE 'sabado'
                    END AS dia_da_semana
                FROM `semana`
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