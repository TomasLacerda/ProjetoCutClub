<?php
class BonusService
{
    // Atributos da classe
    public function bonusService($cadastrar)
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

        include_once "BonusDAO.php";
        $dao = new BonusDAO();
        $cadastrou = $dao->cadastrarDAO($cadastrar);

        if ($cadastrou) {
            return array (
                'mensagem' => "Cadastro efetuado com sucesso!",
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

    public function bonusUpdate($bonus)
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

        include_once "BonusDAO.php";
        $dao = new BonusDAO();
        $cadastrou = $dao->updateDAO($bonus);

        if ($cadastrou) {
            return array (
                'mensagem' => "Cadastro atualizado com sucesso!",
                'sucesso' => true
            );
        } else {
            return array (
                'mensagem' => "Erro ao efetuar a atualização.",
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