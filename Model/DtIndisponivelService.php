<?php
class DtIndisponivelService
{
    // Atributos da classe
    public function cadastrarDatasService($dataIndisponivel)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($dataIndisponivel->data_inicio, "Preencha o campo 'Data inicial'!");
        if (!$campo['sucesso']) return $campo;
        $campo = $this->verificarCampo($dataIndisponivel->hora_inicio, "Preencha o campo 'Hora inicial'!");
        if (!$campo['sucesso']) return $campo;
        $campo = $this->verificarCampo($dataIndisponivel->hora_fim, "Preencha o campo 'Hora final'!");
        if (!$campo['sucesso']) return $campo;
        $campo = $this->verificarCampo($campo = $dataIndisponivel->id_barbeiro == NULL ? '' : 'Barbeiro', "Preencha o campo 'Selecione o Barbeiro'!");
        if (!$campo['sucesso']) return $campo;

        $horaInicio = $dataIndisponivel->hora_inicio;
        $horaFim = $dataIndisponivel->hora_fim;

        // Verifica se ambos os campos de hora estão preenchidos
        if (!empty($horaInicio) && !empty($horaFim)) {
            // Separa as horas e minutos
            list($horaInicioHora, $horaInicioMinuto) = explode(':', $horaInicio);
            list($horaFimHora, $horaFimMinuto) = explode(':', $horaFim);
        
            // Converte as horas e minutos para minutos totais
            $totalMinutosInicio = intval($horaInicioHora) * 60 + intval($horaInicioMinuto);
            $totalMinutosFim = intval($horaFimHora) * 60 + intval($horaFimMinuto);

            if ($totalMinutosInicio > $totalMinutosFim) {
                return array (
                    "mensagem" => "A hora inicial deve ser menor que a hora final",
                    "campo" => "#hora_inicial",
                    "sucesso" => false
                );
            }
        }

        $resultado = $this->buscarIndisponivelService($dataIndisponivel);

        // Caso retorne algo do banco
        if ($resultado != null) {
            return array (
                "mensagem" => "Já existe essa data de início para o barbeiro: ".$resultado['nome_completo'],
                "campo" => "#dt_inicio",
                "sucesso" => false
            );
        }

        include_once "DtIndisponivelDAO.php";
        $dao = new DtIndisponivelDAO();
        $cadastrou = $dao->CadastrarDtIndisponivel($dataIndisponivel);

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

    private function buscarIndisponivelService($dataIndisponivel)
    {
        include_once "DtIndisponivelDAO.php";

        $dao = new DtIndisponivelDAO();

        return $dao->buscarIndisponivelDAO($dataIndisponivel);
    }

    private function recuperaDatas($stFiltro)
    {
        include_once "DtIndisponivelDAO.php";

        $dao = new DtIndisponivelDAO();

        return $dao->recuperaRelacionamento($stFiltro);
    }

    private function verificarCampo($valorCampo, $nomeCampo)
    {
        // Verifica se o campo foi preenchido
        if (strcmp($valorCampo, "") == 0 || strcmp($valorCampo, "_") == 0) {
            return array (
                'mensagem' => "$nomeCampo",
                'campo' => "#$nomeCampo",
                'sucesso' => false
            );
        }
        return array (
            'sucesso' => true
        );
    }
}