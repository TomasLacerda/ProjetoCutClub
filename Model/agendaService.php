<?php
class agendaService
{
    // Atributos da classe
    public function agendarService($agendar)
    {
        // Verificar se os campos foram preenchidos
        //$campo = $this->verificarCampo($dataIndisponivel->dtRecesso, "Data que a loja não abrirá");
        //if (!$campo['sucesso']) return $campo;

        //$resultado = $this->buscarIndisponivelService($dataIndisponivel);

        // Caso retorne algo do banco
        //if ($resultado != null) {
        //    return array (
        //        "mensagem" => "Esta data de recesso já está sendo utilizada.",
        //        "campo" => "#dt_recesso",
        //        "sucesso" => false
        //    );
        //}

        include_once "AgendaDAO.php";
        $dao = new AgendaDAO();
        $cadastrou = $dao->agendarDAO($agendar);

        if ($cadastrou) {
            return array (
                'mensagem' => "Agendamento efetuado com sucesso!",
                'sucesso' => true
            );
        } else {
            return array (
                'mensagem' => "Erro ao efetuar o cadastro.",
                'campo' => "",
                'sucesso' => false
            );
        }
    }

    private function verificarCampo($valorCampo, $nomeCampo)
    {
        // Verifica se o campo foi preenchido
        if (strcmp($valorCampo, "") == 0) {
            return array (
                'mensagem' => "Preencha a $nomeCampo",
                'campo' => "#$nomeCampo",
                'sucesso' => false
            );
        }
        return array (
            'sucesso' => true
        );
    }
}