<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        label {
            font-weight: bold;
        }
        select, input[type="text"], button:not([name='menu_select']) {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .carousel-item img {
            width: 100%;
            height: 12rem;
            object-fit: cover;
        }
        .card {
            height: 22rem;
            text-align: center;
        }
        .card-title,
        .card-text,
        .card-text small.text-muted {
            color: black;
        }
        .card-body {
            padding: 1px;
        }
        .card-title {
            margin-bottom: 1px;
            font-size: 1.1rem;
            font-weight: bold;
        }
        .card-text {
            margin-bottom: 1px;
            font-size: 1rem;
        }
        .card-text small.text-muted {
            margin-bottom: 0;
            display: block;
        }
    </style>
</head>
<body data-page-id="agendamento">
    <?php
        include_once "include/menu.php";
        include_once "../Model/ContatoDAO.php";
        include_once "../Model/dtIndisponivelDAO.php";
        include_once "../Model/ServicoDAO.php";
        include_once "../Model/ExpedienteDAO.php";
        include_once "../Model/AgendaDAO.php"; // Supondo que você tenha uma classe para manipular a tabela de agenda

        $ContatoDAO = new ContatoDAO();
        $stFiltro = " WHERE barbeiro = 1";
        $rsBarbeiros = $ContatoDAO->recuperaTodos($stFiltro);

        $expedienteDAO = new ExpedienteDAO();
        $servicoDAO = new ServicoDAO();
        $agendaDAO = new AgendaDAO(); // Instância do DAO da tabela de agenda

        // Verifica se o AJAX enviou o ID do barbeiro e o ID do serviço
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['diaSemana']) && isset($_POST['idService']) && isset($_POST['dataSelecionada'])) {
            $diaSemana = $_POST['diaSemana'];
            $idService = $_POST['idService'];
            $barbeiroId = $_POST['barbeiroId'];
            $dataSelecionada = $_POST['dataSelecionada'];

            // Recupera os dados do serviço selecionado
            $stFiltro = " WHERE id = " . $idService;
            $servico = $servicoDAO->recuperaTodos($stFiltro);
            $servico = $servico->fetch_assoc();
            $duracaoServico = converterDuracaoParaMinutos($servico['duracao']); // duração do serviço em minutos

            // Recupera os dados do expediente
            $stFiltro = " WHERE id_semana = " . $diaSemana;
            $rsexpediente = $expedienteDAO->recuperaTodos($stFiltro);

            // Recupera os horários já agendados para o dia selecionado
            $dataSelecionada = DateTime::createFromFormat('d/m/Y', $dataSelecionada)->format('Y-m-d');
            $stFiltro = " WHERE DATE(dthora_execucao) = '".$dataSelecionada."'";
            $stFiltro .= " AND id_barbeiro = ".$barbeiroId;
            $resultadoHorariosAgendados = $agendaDAO->recuperarHorariosAgendados($stFiltro);

            $horariosAgendados = [];
            while ($row = $resultadoHorariosAgendados->fetch_assoc()) {
                $horariosAgendados[] = [
                    'dthora_execucao' => $row['dthora_execucao'],
                    'duracao' => $row['duracao']
                ];
            }

            // Gera os horários disponíveis com base na duração do serviço
            $horariosDisponiveis = [];
            $expediente = $rsexpediente->fetch_assoc();

            if ($expediente) {
                $horariosDisponiveis = gerarHorarios(
                    $expediente['hr_inicio'], 
                    $expediente['hr_fim'], 
                    $duracaoServico,
                    $expediente['hr_inicio_itv'],
                    $expediente['hr_fim_itv'],
                    $horariosAgendados // Passa os horários já agendados para excluí-los
                );
            }

            // Retorna as opções de horários
            if (count($horariosDisponiveis) > 0) {
                foreach ($horariosDisponiveis as $horario) {
                    echo "<option value='" . $horario . "'>" . $horario . "</option>";
                }
            } else {
                echo "<option value=''>Nenhum horário disponível</option>";
            }
            exit;
        }

        // Função para gerar horários
        function gerarHorarios($inicio, $fim, $intervaloMinutos, $inicioItv = null, $fimItv = null, $horariosAgendados = []) {
            $horarios = [];
            $horaAtual = new DateTime($inicio);
            $horaFim = new DateTime($fim);
            $intervalo = new DateInterval("PT{$intervaloMinutos}M");

            while ($horaAtual < $horaFim) {
                // Verifica se o horário atual cai no intervalo de descanso
                if ($inicioItv && $fimItv) {
                    $inicioIntervalo = new DateTime($inicioItv);
                    $fimIntervalo = new DateTime($fimItv);

                    if ($horaAtual >= $inicioIntervalo && $horaAtual < $fimIntervalo) {
                        $horaAtual->add($intervalo);
                        continue;
                    }
                }

                // Verifica se o horário está ocupado
                if (!verificarHorarioOcupado($horaAtual, $intervaloMinutos, $horariosAgendados)) {
                    $horarios[] = $horaAtual->format('H:i');
                }

                $horaAtual->add($intervalo);
            }

            return $horarios;
        }

        // Função para verificar se um horário está ocupado
        function verificarHorarioOcupado($horaAtual, $intervaloMinutos, $horariosAgendados) {
            foreach ($horariosAgendados as $horarioAgendado) {
                $horaInicioAgendada = new DateTime($horarioAgendado['dthora_execucao']);
                $duracaoAgendada = converterDuracaoParaMinutos($horarioAgendado['duracao']);
                $horaFimAgendada = clone $horaInicioAgendada;
                $horaFimAgendada->add(new DateInterval("PT{$duracaoAgendada}M"));

                // Verifica se o horário atual ou qualquer parte do serviço a ser agendado cai dentro do período agendado
                $horaFimAtual = clone $horaAtual;
                $horaFimAtual->add(new DateInterval("PT{$intervaloMinutos}M"));

                if (($horaAtual >= $horaInicioAgendada && $horaAtual < $horaFimAgendada) ||
                    ($horaFimAtual > $horaInicioAgendada && $horaFimAtual <= $horaFimAgendada) ||
                    ($horaAtual < $horaInicioAgendada && $horaFimAtual > $horaFimAgendada)) {
                    return true;
                }
            }

            return false;
        }

        // Função para converter duração para minutos
        function converterDuracaoParaMinutos($duracao) {
            list($horas, $minutos) = explode(':', $duracao);
            return ($horas * 60) + $minutos;
        }


        $servicoDAO = new ServicoDAO();
        $rsServicos = $servicoDAO->recuperaTodos();
        
        $servicos = [];
        if ($rsServicos->num_rows > 0) {
            // Armazenar todos os resultados em um array
            while ($coluna = $rsServicos->fetch_assoc()) {
                // Capturar a duração no formato hh:mm
                $duracao = $coluna['duracao'];
                $partesDuracao = explode(':', $duracao);
                $horas = (int)$partesDuracao[0]; // Converter para inteiro para remover zeros à esquerda
                $minutos = (int)$partesDuracao[1];
        
                // Formatar a duração no formato desejado
                $duracaoFormatada = '';
                if ($horas > 0) {
                    $duracaoFormatada .= $horas . 'h ';
                }
                $duracaoFormatada .= $minutos . 'min';
        
                // Substituir a duração no array
                $coluna['duracao'] = $duracaoFormatada;
        
                // Adicionar o serviço ao array
                $servicos[] = $coluna;
            }
        }

        // Geração de dias ativos
        $dias_semana_ativos = [];
        $rsexpediente = $expedienteDAO->recuperaTodos();
        while ($row = $rsexpediente->fetch_assoc()) {
            $dias_semana_ativos[] = $row['id_semana'];
        }

        // Converter array para JSON para passar ao JavaScript
        $dias_semana_ativos_json = json_encode($dias_semana_ativos);

        $idServicoSelecionado = isset($_GET['id_servico']) ? intval($_GET['id_servico']) : null;
        ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
            <fieldset class="box" id="servicoSection">
                <h1 id="subtitle">Agendar Horário</h1>
                <div class="m-5"></div>
                <label for="servico">Selecione o serviço*:</label>
                
                <!-- Botão para alternar entre os modos -->
                <div style="text-align: right;"><i id="toggleViewIcon" class="fas fa-bars" style="cursor: pointer; font-size: 24px;"></i></div>                

                <!-- Carrossel de Serviços -->
                <div id="servicoCarrossel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        if (count($servicos) > 0) {
                            $first = true; // Variável para marcar o primeiro serviço como ativo
                            foreach ($servicos as $coluna) {
                                // Se houver um id_servico, mostre apenas o serviço correspondente
                                if (!$idServicoSelecionado || $coluna['id'] == $idServicoSelecionado) {
                                    echo "<div class='carousel-item " . ($first ? 'active' : '') . "' data-id-service='" . $coluna['id'] . "'>";
                                    echo "<div class='card' style='width: 15rem; margin: auto;'>";
                                    echo "<img class='card-img-top' src='" . $coluna['imagem'] . "' alt='" . $coluna['nome'] . "'>";
                                    echo "<div class='card-body'>";
                                    echo "<p class='card-title'>" . $coluna['nome'] . "</p>";
                                    echo "<p class='card-text'>Valor: R$" . $coluna['valor'] . "</p>";
                                    echo "<p class='card-text'>Duração: " . $coluna['duracao'] . "</p>";
                                    echo "<p class='card-text'>Descrição: " . $coluna['descricao'] . "</p>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    $first = false; // Após o primeiro item, desativa o status 'active' para os demais
                                }
                            }
                        } else {
                            echo "<div class='carousel-item active'><div class='card' style='width: 10rem; margin: auto;'>";
                            echo "<div class='card-body'><h5 class='card-title'>Nenhum serviço encontrado</h5></div></div></div>";
                        }
                        ?>
                    </div>
                    <a class="carousel-control-prev" href="#servicoCarrossel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#servicoCarrossel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>

                <!-- Lista de Serviços (Inicialmente Oculta) -->
                <div id="servicoLista" class="d-none">
                    <ul class="list-group">
                        <?php
                        if (count($servicos) > 0) {
                            foreach ($servicos as $coluna) {
                                // Se houver um id_servico, mostre apenas o serviço correspondente
                                if (!$idServicoSelecionado || $coluna['id'] == $idServicoSelecionado) {
                                    echo "<li class='list-group-item servico-list-item' data-id-service='" . $coluna['id'] . "' style='background-color: #f0f0f0; color: #000; margin-bottom: 15px; cursor: pointer;'>"; 
                                    echo "<p class='card-title'>" . $coluna['nome'] . "</strong></p>";
                                    echo "<p class='card-text'>Valor: R$" . $coluna['valor'] . "</p>";
                                    echo "<p class='card-text'>Duração: " . $coluna['duracao'] . "</p>";
                                    echo "<p class='card-text'>Descrição: " . $coluna['descricao'] . "</p>";
                                    echo "</li>";
                                }
                            }
                        } else {
                            echo "<li class='list-group-item'>Nenhum serviço encontrado</li>";
                        }
                        ?>
                    </ul>
                </div>

                <p></p>
                <button id="okButton" class="btn btn-primary">OK</button>
            </fieldset>

                <fieldset class="box" id="dataSection" style="display: none;">
                    <label for="data">Selecione a data*:</label>
                    <input type="text" id="data" name="data" class="form-control">
                    <button id="dataOkButton" class="btn btn-primary">OK</button>
                    <button id="btnVoltarServico" class="btn btn-secondary">Voltar</button>
                </fieldset>

                <fieldset class="box" id="barbeiroSection" style="display: none;">
                    <input type="hidden" id="barbeiroId" name="barbeiroId">
                    <label for="barbeiro" id="barbeiroLabel">Selecione o barbeiro*:</label>
                    <select id="barbeiro" name="barbeiro">
                        <option selected disabled>Selecione</option>
                            <?php
                            if ($rsBarbeiros->num_rows > 0) {
                                while ($coluna = $rsBarbeiros->fetch_assoc()) {
                                    echo "<option value='" . $coluna['id'] . "'>" . $coluna['nome'] ."</option>";
                                }
                            } else {
                                echo "<option value=''>Nenhum barbeiro encontrado</option>";
                            }
                            ?>
                    </select>
                    <button id="barbeiroOkButton" class="btn btn-primary">OK</button>
                    <button id="backToDataButton" class="btn btn-secondary">Voltar</button>
                </fieldset>

                <fieldset class="box" id="horarioSection" style="display: none;">
                    <label for="horario">Selecione o horário*:</label>
                    <select id="horario" name="horario">
                        <option selected disabled>Selecione</option>
                        <!-- Horários serão adicionados dinamicamente aqui -->
                    </select>
                    <button id="horarioOkButton" class="btn btn-primary">OK</button>
                    <button id="backToBarbeiroButton" class="btn btn-secondary">Voltar</button>
                </fieldset>

                <fieldset class="box" id="resumoSection" style="display: none;">
                    <h2>Resumo do Agendamento</h2>
                    <p id="resumoTexto"></p>
                    <button id="agendar" type="button" class="btn btn-primary" onclick="agendarServico()">Agendar</button>
                    <button id="voltarButton" class="btn btn-secondary">Voltar</button>
                    <div id="status"></div>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- JavaScript customizado -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
    // Obter os dias ativos do PHP
    var diasAtivos = <?php echo $dias_semana_ativos_json; ?>;

    // Mapear os dias para o formato usado pelo Datepicker (0 = Domingo, 6 = Sábado)
    var diasAtivosMap = diasAtivos.map(function(dia) {
        return dia == 7 ? 0 : dia;
    });

    $(document).ready(function() {
        // Configurações do datepicker (dias da semana, meses, etc.)
        $.datepicker.setDefaults({
            closeText: 'Fechar',
            prevText: 'Anterior',
            nextText: 'Próximo',
            currentText: 'Hoje',
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
                'Jul','Ago','Set','Out','Nov','Dez'],
            dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        });

        // Inicializa o Datepicker diretamente
        $('#data').datepicker({
            beforeShowDay: function(date) {
                var day = date.getDay();
                if (diasAtivosMap.includes(day)) {
                    return [true, ""];  // Dia ativo
                } else {
                    return [false, ""]; // Dia inativo
                }
            },
            minDate: 0,
            maxDate: '+3M'
        });

        // Função para alternar entre o carrossel e a lista de serviços
        $('#toggleViewIcon').on('click', function() {
            var carrossel = $('#servicoCarrossel');
            var lista = $('#servicoLista');
            var icon = $(this);

            if (carrossel.hasClass('d-none')) {
                // Alternar para visualização de carrossel
                carrossel.removeClass('d-none');
                lista.addClass('d-none');
                icon.removeClass('fa-th-large').addClass('fa-bars'); // Ícone de barrinhas horizontais
            } else {
                // Alternar para visualização de lista
                carrossel.addClass('d-none');
                lista.removeClass('d-none');
                icon.removeClass('fa-bars').addClass('fa-th-large'); // Ícone de grade (blocos)
            }
        });

        // Tornar os itens da lista clicáveis
        $('.servico-list-item').on('click', function() {
            var idService = $(this).data('id-service');
            // Avançar para a próxima seção (igual ao carrossel)
            $('#servicoSection').slideUp();
            $('#dataSection').slideDown();
        });

        // Transições entre seções
        $('#okButton').on('click', function() {
            $('#servicoSection').slideUp();
            $('#dataSection').slideDown();
        });

        $('#dataOkButton').on('click', function() {
            var dataSelecionada = $('#data').val();
            if (!dataSelecionada) {
                Swal.fire('Erro', 'Por favor, selecione uma data!', 'error');
                return;
            }
            $('#dataSection').slideUp();
            $('#barbeiroSection').slideDown();
        });

        $('#barbeiroOkButton').on('click', function() {
            var barbeiroId = $('#barbeiro').val();
            if (!barbeiroId) {
                Swal.fire('Erro', 'Por favor, selecione um barbeiro!', 'error');
                return;
            }
            var idService = $('#servicoCarrossel .carousel-item.active').data('id-service');
            var dataSelecionada = $('#data').val();
            var barbeiroId = $('#barbeiro').val();

            // Quebra a data em partes (dia, mês, ano)
            var partesData = dataSelecionada.split('/');
            var dia = partesData[0];
            var mes = partesData[1] - 1;
            var ano = partesData[2];

            // Cria um novo objeto Date no formato que o JavaScript entende
            var dataObj = new Date(ano, mes, dia);

            // Obtém o dia da semana (1 = segunda, 7 = domingo)
            var diaSemana = dataObj.getDay();
            diaSemana = diaSemana === 0 ? 7 : diaSemana;

            // Faz uma requisição AJAX para enviar o ID do barbeiro ao PHP
            $.ajax({
                url: '', // Como está no mesmo arquivo, você pode deixar a URL vazia
                type: 'POST',
                data: {
                    diaSemana: diaSemana,
                    idService: idService,
                    barbeiroId: barbeiroId,
                    dataSelecionada: dataSelecionada
                },
                success: function(response) {
                    // Preenche o campo de horários com a resposta do PHP
                    $('#horario').html(response);
                    $('#barbeiroSection').slideUp();
                    $('#horarioSection').slideDown();
                }
            });
        });

        $('#horarioOkButton').on('click', function() {
            var horario = $('#horario').val();
            if (!horario) {
                Swal.fire('Erro', 'Por favor, selecione um horário!', 'error');
                return;
            }
            $('#horarioSection').slideUp();
            $('#resumoSection').slideDown();
            mostrarResumo();
        });

        $('#btnVoltarServico').on('click', function() {
            $('#dataSection').slideUp();
            $('#servicoSection').slideDown();
        });

        $('#backToDataButton').on('click', function() {
            $('#barbeiroSection').slideUp();
            $('#dataSection').slideDown();
        });

        $('#backToBarbeiroButton').on('click', function() {
            $('#horarioSection').slideUp();
            $('#barbeiroSection').slideDown();
        });

        $('#voltarButton').on('click', function() {
            $('#resumoSection').slideUp();
            $('#horarioSection').slideDown();
        });

        function mostrarResumo() {
            var servico = $('#servicoCarrossel .carousel-item.active .card-title').text();
            var valor = $('#servicoCarrossel .carousel-item.active .card-text:contains("Valor:")').text();
            var duracao = $('#servicoCarrossel .carousel-item.active .card-text:contains("Duração:")').text(); // Captura a duração
            var data = $('#data').val();
            var barbeiro = $('#barbeiro option:selected').text();
            var horario = $('#horario option:selected').text();

            // Verifica se o agendamento foi feito via bônus (por exemplo, verificando se existe um parâmetro bônus na URL)
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('id_servico') && urlParams.has('meta')) {
                valor = "<b>Agendamento realizado pelo Plano Fidelidade!<b>";
            }

            // Atualiza o resumo, incluindo a duração formatada
            $('#resumoTexto').html(`Serviço: ${servico}<br>Barbeiro: ${barbeiro}<br>Data: ${data}<br>Horário: ${horario}<br>${duracao}<br>${valor}`);
        }
    });
</script>
</body>
</html>
