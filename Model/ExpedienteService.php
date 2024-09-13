<?php
class ExpedienteService
{
    // Atributos da classe
    public function cadastrarDatasService($expediente)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($expediente->hr_inicio, "Preencha o campo 'Abertura'!");
        if (!$campo['sucesso']) return $campo;
        $campo = $this->verificarCampo($expediente->hr_fim, "Preencha o campo 'Fechamento'!");
        if (!$campo['sucesso']) return $campo;

        $horaInicio = $expediente->hr_inicio;
        $horaFim = $expediente->hr_fim;

        // Verifica se ambos os campos de hora estão preenchidos
        if (!empty($horaInicio) && !empty($horaFim)) {
            // Separa as horas e minutos
            list($horaInicioHora, $horaInicioMinuto) = explode(':', $horaInicio);
            list($horaFimHora, $horaFimMinuto) = explode(':', $horaFim);

            // Converte as horas e minutos para minutos totais
            $totalMinutosInicio = intval($horaInicioHora) * 60 + intval($horaInicioMinuto);
            $totalMinutosFim = intval($horaFimHora) * 60 + intval($horaFimMinuto);

            // Calcula a diferença de minutos (garantindo que seja sempre positiva)
            $diferencaMinutos = $totalMinutosFim - $totalMinutosInicio;

            // Verifica se a hora inicial é maior que a hora final ou a diferença é menor que 3 horas
            if ($totalMinutosInicio >= $totalMinutosFim || $diferencaMinutos < 180) {
                return array (
                    "mensagem" => "O campo 'Abertura' deve ser 3 horas menor que o campo 'Fechamento'",
                    "campo" => "#horaAbertura",
                    "sucesso" => false
                );
            }
        }

        // Verifica se ambos os campos de hora estão preenchidos PARA O INTERVALO
        if ($expediente->hr_inicio_itv || $expediente->hr_fim_itv) {

            $horaInicioItv = $expediente->hr_inicio_itv;
            $horaFimItv = $expediente->hr_fim_itv;

            // Separa as horas e minutos
            list($horaInicioHoraItv, $horaInicioMinutoItv) = explode(':', $horaInicioItv);
            list($horaFimHoraItv, $horaFimMinutoItv) = explode(':', $horaFimItv);

            // Converte as horas e minutos para minutos totais
            $totalMinutosInicioItv = intval($horaInicioHoraItv) * 60 + intval($horaInicioMinutoItv);
            $totalMinutosFimItv = intval($horaFimHoraItv) * 60 + intval($horaFimMinutoItv);

            // Verifica se a hora inicial do intervalo é maior que a hora final do intervalo
            if ($totalMinutosInicioItv >= $totalMinutosFimItv) {
                return array (
                    "mensagem" => "A hora inicial do intervalo deve ser menor que a hora final",
                    "campo" => "#hora_inicio_itv",
                    "sucesso" => false
                );
            }

            // Verifica se o intervalo está dentro do período de abertura e fechamento
            if (!($totalMinutosInicioItv > $totalMinutosInicio && $totalMinutosFimItv < $totalMinutosFim)) {
                return array (
                    "mensagem" => "O intervalo deve estar entre a 'Abertura' e o 'Fechamento'",
                    "campo" => "#hora_inicio_itv",
                    "sucesso" => false
                );
            }
        }

        $resultado = $this->buscarExpedienteService();
        $diasSemana = $expediente->id_semana != NULL ? $expediente->id_semana : array(0);
        
        while ($row = $resultado->fetch_assoc()) {
            // Verifica se o id_semana já existe no array
            if (in_array($row['id_semana'], $diasSemana)) {
                return array(
                    "mensagem" => "Cada dia da semana pode ser inserido apenas 1 vez.",
                    "campo" => "",
                    "sucesso" => false
                );
            }
        
            // Se não existir, adiciona o id_semana no array
            $id_semana_array[] = $row['id_semana'];
        }

        include_once "ExpedienteDAO.php";
        $dao = new ExpedienteDAO();
        $cadastrou = $dao->CadastrarExpediente($expediente);

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

    private function buscarExpedienteService()
    {
        include_once "ExpedienteDAO.php";

        $dao = new ExpedienteDAO();

        return $dao->recuperaTodos();
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