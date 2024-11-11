<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agenda PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .arrow {
            display: inline-block;
            transition: transform 0.6s ease; /* AnimaçÃ£o suave para rotaçÃ£o da seta */
            transform: rotate(0deg); /* Seta inicialmente apontando para baixo */
            font-size: 16px; /* Tamanho do Ã­cone */
            margin-left: 5px; /* Espaçamento entre o texto e o Ã­cone */
        }

        .expanded .arrow {
            transform: rotate(180deg); /* Rota a seta para cima */
        }

        .round-button {
            width: 40px; /* Largura do botÃ£o */
            height: 40px; /* Altura do botÃ£o */
            border-radius: 50%; /* Torna o botÃ£o completamente redondo */
            background-color: rgba(143, 188, 143, 0.9); /* Cor de fundo do botÃ£o com transparÃªncia */
            color: white; /* Cor do texto/icon do botÃ£o */
            border: none; /* Sem bordas */
            cursor: pointer; /* Cursor do tipo ponteiro */
            display: flex; /* Uso de Flexbox para centralizar o conteÃºdo */
            justify-content: center; /* Centraliza horizontalmente o conteÃºdo */
            align-items: center; /* Centraliza verticalmente o conteÃºdo */
        }

        .round-button-red {
            width: 40px; /* Largura do botÃ£o */
            height: 40px; /* Altura do botÃ£o */
            border-radius: 50%; /* Torna o botÃ£o completamente redondo */
            background-color: rgba(205, 92, 92, 0.9); /* Cor de fundo do botÃ£o com transparÃªncia */
            color: white; /* Cor do texto/icon do botÃ£o */
            border: none; /* Sem bordas */
            cursor: pointer; /* Cursor do tipo ponteiro */
            display: flex; /* Uso de Flexbox para centralizar o conteÃºdo */
            justify-content: center; /* Centraliza horizontalmente o conteÃºdo */
            align-items: center; /* Centraliza verticalmente o conteÃºdo */
        }

        .round-button, .round-button-red {
            margin: 5px; /* Espaço entre os botÃµes */
        }

        .btn-toggle {
            width: 100%;
            text-align: left;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
        }

        .btn-toggle .arrow {
            float: right;
            transition: transform 1s ease;
            color: white;
        }

        .btn-toggle:hover {
            background-color: #0056b3;
        }

        .table-bg {
            background-color: rgba(192,192,192,0.3);
            border-radius: 5px;
            color: white;
        }

        .more-info {
            display: none;
            background-color: rgba(192,192,192,0.3);
        }

        .more-info.show {
            display: table-row;
        }
    </style>
</head>

<body data-page-id="historico">
    <?php
        include_once "include/menu.php";
        include_once "../Model/AgendaDAO.php";
        include_once "../Model/ContatoDAO.php";
        include_once "../Model/ServicoDAO.php";

        $id = $_SESSION['id'];

        $servicoDAO = new ServicoDAO();
        $servico = $servicoDAO->recuperaTodos();

        $ContatoDAO = new ContatoDAO();
        $stFiltro = " WHERE admin = 1 AND id =".$id;
        $rsAdmin = $ContatoDAO->recuperaTodos($stFiltro);

        $stFiltro = " WHERE barbeiro = 1 AND id =".$id;
        $rsBarbeiro = $ContatoDAO->recuperaTodos($stFiltro);

        $stFiltroHistorico = " WHERE cliente.id = ".$id." AND dthora_execucao <= NOW() ORDER BY dthora_execucao ASC";
        $stFiltroAgendado = " WHERE cliente.id = ".$id." AND ((dthora_execucao > NOW()) OR (dthora_execucao >= CURDATE() AND TIME(dthora_execucao) >= CURTIME())) ORDER BY dthora_execucao ASC";

        if ($rsAdmin->num_rows > 0) {
            $stFiltroHistorico = " WHERE dthora_execucao <= NOW() ORDER BY confirmado, dthora_execucao ASC";
            $stFiltroAgendado = " WHERE (dthora_execucao > NOW()) OR (dthora_execucao >= CURDATE() AND TIME(dthora_execucao) >= CURTIME()) ORDER BY dthora_execucao ASC";
        }

        if ($rsAdmin->num_rows <= 0 && $rsBarbeiro->num_rows > 0) {
            $stFiltroHistorico = " WHERE dthora_execucao <= NOW() AND cliente.id = ".$id." ORDER BY confirmado, dthora_execucao ASC";
            $stFiltroAgendado = " WHERE (dthora_execucao > NOW()) OR (dthora_execucao >= CURDATE() AND TIME(dthora_execucao) >= CURTIME()) AND cliente.id = ".$id." ORDER BY confirmado, dthora_execucao ASC";
        }

        $AgendaDAO = new AgendaDAO();
        $historico = $AgendaDAO->recuperaHistorico($stFiltroHistorico);

        $AgendaDAO = new AgendaDAO();
        $agendado = $AgendaDAO->recuperaHistorico($stFiltroAgendado);

        if ($rsAdmin->num_rows <= 0 && $rsBarbeiro->num_rows <= 0) {
            $servico = $agendado;
        }

        $tipoFiltro = isset($_GET['tipoFiltro']) ? $_GET['tipoFiltro'] : '';
        $valorFiltro = isset($_GET['valorFiltro']) ? $_GET['valorFiltro'] : '';
        
        $stFiltroHistorico = "";

        $labels = [];
        $valores = [];
        
        if ($tipoFiltro) {
            // Constrói o filtro de acordo com o tipo de filtro escolhido
            if ($tipoFiltro === 'data') {
                if ($valorFiltro != '') {
                    $valorFiltro = " WHERE dthora_execucao <= NOW() AND DATE_FORMAT(dthora_execucao, '%Y-%m-%d') = '" . $valorFiltro . "' AND confirmado = 1 ORDER BY dthora_execucao asc";
                    // Recupera o histórico filtrado
                    $AgendaDAO = new AgendaDAO();
                    $HistservicosRealizados = $AgendaDAO->recuperaQntdServicosRealizados($valorFiltro);
                    
                    while ($row = mysqli_fetch_assoc($HistservicosRealizados)) {
                        $labels[] = $row['data'];
                        $valores[] = $row['quantidade'];
                    }
                } else {
                    $AgendaDAO = new AgendaDAO();
                    $HistservicosRealizados = $AgendaDAO->recuperaQntdServicosTodasDatas();
                    
                    while ($row = mysqli_fetch_assoc($HistservicosRealizados)) {
                        $labels[] = $row['data'];
                        $valores[] = $row['total_cortes'];
                    }
                }

            } elseif ($tipoFiltro === 'servico') {
                $stFiltroHistorico .= " AND servico = '" . $valorFiltro . "'";
            } elseif ($tipoFiltro === 'valor') {
                $stFiltroHistorico .= " AND valor = " . (int)$valorFiltro;
            } elseif ($tipoFiltro === 'cliente') {
                $stFiltroHistorico .= " AND cliente LIKE '%" . mysqli_real_escape_string($conn, $valorFiltro) . "%'";
            }
        }

        // Capture as datas de entrada e saída se enviadas pelo formulário
        $dataInicio = isset($_GET['dataInicio']) ? $_GET['dataInicio'] : '';
        $dataFim = isset($_GET['dataFim']) ? $_GET['dataFim'] : '';

        $stFiltroHistorico = ""; // Inicialize o filtro como vazio
        $periodo = "";

        if ($dataInicio && $dataFim) {
            // Converta as datas para o formato SQL
            $dataInicioObj = DateTime::createFromFormat('d/m/Y', $dataInicio);
            $dataFimObj = DateTime::createFromFormat('d/m/Y', $dataFim);

            if ($dataInicioObj && $dataFimObj) {
                $dataInicioSQL = $dataInicioObj->format('Y-m-d');
                $dataFimSQL = $dataFimObj->format('Y-m-d');
                $periodo = " AND dthora_execucao BETWEEN '$dataInicioSQL' AND '$dataFimSQL 23:59:59' ";
            }
        }
    ?>

    <div class="container">
        <div class="box-wrapper">
            <!-- Serviços Agendados -->
            <fieldset class="box">
                <h1 id="subtitle">Serviços Agendados</h1>
                <div class="m-5"></div>
                <div class="form-group">
                    <table class="table table-bg" id="agendados">
                        <thead>
                            <tr>
                                <th scope="col">Data - Hora</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Serviço</th>
                                <th scope="col">Barbeiro</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                mysqli_data_seek($agendado, 0);
                                $dadosAgendado = [];
                                while ($row = mysqli_fetch_assoc($agendado)) {
                                    $dadosAgendado[] = $row;
                                }
                                foreach ($dadosAgendado as $dados) { ?>
                                    <tr onclick="toggleInfo(<?= $dados['id'] ?>)">
                                        <td><?= $dados['data'] . ' - ' . $dados['hora_minuto'] ?></td>
                                        <td><?= $dados['cliente'] ?></td>
                                        <td><?= $dados['servico'] ?></td>
                                        <td><?= $dados['barbeiro'] ?></td>
                                        <td>
                                            <a href="#" class="delete-link" data-id="<?= $dados['id'] ?>" title="Cancelar">
                                                <i class="fas fa-trash-alt" style="color: red; margin-right: 5px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
            <fieldset class="box">
                <h1 id="subtitle">Histórico</h1>
                <div class="m-5"></div>
                <div class="form-group">
                <form method="GET" id="filtroForm">
                    <div class="row">
                        <div class="col">
                            <label for="dataInicio">Data Início:</label>
                            <input type="date" id="dataInicio" name="dataInicio" placeholder="dd/mm/yyyy" class="form-control" value="<?= htmlspecialchars($dataInicio) ?>">
                        </div>
                        <div class="col">
                            <label for="dataFim">Data Fim:</label>
                            <input type="date" id="dataFim" name="dataFim" placeholder="dd/mm/yyyy" class="form-control" value="<?= htmlspecialchars($dataFim) ?>">
                        </div>
                        <div class="col">
                            <label for="servico">Serviço:</label>
                            <select id="servico" name="servico" class="form-control">
                                <option value="">Todos</option>
                                <?php 
                                // Loop para gerar as opções dinamicamente e definir a opção selecionada com base no filtro atual
                                while ($row = mysqli_fetch_assoc($servico)) {
                                    $selected = ($row['nome'] == $servico) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($row['nome'], ENT_QUOTES) . '" ' . $selected . '>' . htmlspecialchars($row['nome']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                        </div>
                    </div>
                </form>

                    <!-- Histórico -->
                    <table class="table table-bg mt-3" id="historico">
                        <thead>
                            <tr>
                                <th scope="col">Data - Hora</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Serviço</th>
                                <th scope="col">Barbeiro</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            // Obtém os valores dos filtros
                            $dataInicio = isset($_GET['dataInicio']) ? $_GET['dataInicio'] : '';
                            $dataFim = isset($_GET['dataFim']) ? $_GET['dataFim'] : '';
                            $servico = isset($_GET['servico']) ? $_GET['servico'] : '';

                            // Constrói a condição SQL para o filtro de período
                            $periodo = "";
                            if ($dataInicio || $dataFim) {
                                $dataInicioSQL = $dataInicio ? DateTime::createFromFormat('Y-m-d', $dataInicio)->format('Y-m-d') : '';
                                $dataFimSQL = $dataFim ? DateTime::createFromFormat('Y-m-d', $dataFim)->format('Y-m-d') : '';
                                if ($dataInicioSQL && $dataFimSQL) {
                                    $periodo = "AND dthora_execucao BETWEEN '$dataInicioSQL' AND '$dataFimSQL 23:59:59'";
                                } elseif ($dataInicioSQL) {
                                    $periodo = "AND dthora_execucao >= '$dataInicioSQL'";
                                } elseif ($dataFimSQL) {
                                    $periodo = "AND dthora_execucao <= '$dataFimSQL 23:59:59'";
                                }
                            }

                            // Constrói a condição SQL para o filtro de serviço
                            $filtroServico = $servico ? " AND servico.nome = '$servico'" : "";
                            $cliente = '';
                            if ($rsAdmin->num_rows <= 0 && $rsBarbeiro->num_rows <= 0) {
                                $cliente = " AND cliente.id = ".$id."";
                            }

                            // Junta as condições no filtro final
                            $stFiltroHistorico = " WHERE dthora_execucao <= NOW() $periodo $filtroServico $cliente ORDER BY dthora_execucao ASC";

                            // Recupera o histórico com os filtros aplicados
                            $AgendaDAO = new AgendaDAO();
                            $historico = $AgendaDAO->recuperaHistorico($stFiltroHistorico);

                            // Exibe os dados filtrados na tabela
                            if (mysqli_num_rows($historico) > 0) {
                                while ($row = mysqli_fetch_assoc($historico)) { ?>
                                    <tr onclick="toggleInfo(<?= $row['id'] ?>)">
                                        <td><?= $row['data'] . ' - ' . $row['hora_minuto'] ?></td>
                                        <td><?= $row['cliente'] ?></td>
                                        <td><?= $row['servico'] ?></td>
                                        <td><?= $row['barbeiro'] ?></td>
                                    </tr>
                                    <tr class="more-info" id="info_<?= $row['id'] ?>" style="display: none;">
                                        <td colspan="4">Realizado: <?= $row['confirmado'] ? 'Sim' : 'Não' ?></td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='4'>Nenhum registro encontrado para os filtros aplicados.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>

    <!-- jQuery (online) -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        createSearchBar("agendados");
        createSearchBar("historico");
        createPagination("historico", 10); 

        function toggleVisibility(contentId, iconId) {
            var element = document.getElementById(contentId);
            var icon = document.getElementById(iconId);
            if (element.style.display === 'none') {
                element.style.display = 'block';
                icon.style.transform = 'rotate(180deg)'; // Rota a seta para cima
            } else {
                element.style.display = 'none';
                icon.style.transform = 'rotate(0deg)'; // Retorna a seta para a posiçÃ£o original
            }
        }
        function toggleInfo(id) {
            const infoRow = document.getElementById(`info_${id}`);
            if (infoRow.style.display === "none") {
                infoRow.style.display = "table-row";
            } else {
                infoRow.style.display = "none";
            }
        }

        function confirmarExclusao(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja cancelar este agendamento?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, cancelar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuÃ¡rio confirmar, redirecione para a pÃ¡gina de exclusÃ£o
                    window.location.href = 'cancelarAgendamento.php?id=' + id;
                }
            });
        }

        function confirmarCorte(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja confirmar este corte?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, confirmar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuÃ¡rio confirmar, redirecione para a pÃ¡gina de exclusÃ£o
                    window.location.href = 'manterCorte.php?id=' + id;
                }
            });
        }

        function confirmarTodos() {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja confirmar este corte?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, confirmar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuÃ¡rio confirmar, redirecione para a pÃ¡gina de exclusÃ£o
                    window.location.href = 'manterCorte.php?id=Todos';
                }
            });
        }

        document.querySelectorAll('.delete-link').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Impede a propagação para o toggleInfo

                const idAgendamento = this.getAttribute('data-id'); // Pegue o ID do serviço

                // Exibe a mensagem de confirmação
                Swal.fire({
                    title: `Tem certeza que deseja cancelar o agendamento: ${idAgendamento}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, cancelar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Se o usuário confirmar, enviar o AJAX para excluir
                        fetch('../Controller/atendimentoController.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id_agendamento=${idAgendamento}&excluir=excluir`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.sucesso) {
                                Swal.fire('Sucesso', 'Agendamento cancelado com sucesso!', 'success')
                                .then(() => {
                                    // Recarregar a página após a exclusão
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Erro', 'Erro ao cancelar o agendamento.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Erro', `Erro: ${error}`, 'error');
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
