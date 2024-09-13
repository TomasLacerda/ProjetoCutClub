<?php
class DtAtendimentoService
{
    // Atributos da classe
    public function cadastrarAtendimentoService($dtAtendimento)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($dtAtendimento->idSemana[0], "Data que a loja não abrirá");
        if (!$campo['sucesso']) return $campo;

        $campo = $this->verificarCampo($dtAtendimento->dtInicioMes, "Data de início");
        if (!$campo['sucesso']) return $campo;

        $resultado = $this->buscarAtendimentoService($dtAtendimento);
        if ($resultado != null) {
            return array (
                "mensagem" => "Essa data de início já está sendo utilizada.",
                "campo" => "#dt_inicio_mes",
                "sucesso" => false
            );
        }

        $resultado = $this->buscarIndisponivelService($dtAtendimento);
        if ($resultado != null) {
            return array (
                "mensagem" => "Essa data de recesso já está sendo utilizada.",
                "campo" => "#dt_recesso",
                "sucesso" => false
            );
        }

        include_once "DtAtendimentoDAO.php";
        $dao = new DtAtendimentoDAO();
        $cadastrou = $dao->CadastrarDtAtendimento($dtAtendimento);

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

    private function buscarAtendimentoService($dtAtendimento)
    {
        // incluir o arquivo contatoDAO
        include_once "DtAtendimentoDAO.php";

        $dao = new DtAtendimentoDAO();

        return $dao->buscarAtendimentoDAO($dtAtendimento);
    }

    private function buscarIndisponivelService($dtAtendimento)
    {
        // incluir o arquivo contatoDAO
        include_once "DtIndisponivelDAO.php";

        $dao = new DtIndisponivelDAO();

        return $dao->buscarIndisponivelDAO($dtAtendimento);
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