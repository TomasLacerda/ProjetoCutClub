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

        .button-container {
            display: flex;
            justify-content: center; /* Alinha horizontalmente ao centro */
            align-items: center; /* Alinha verticalmente ao centro */
            flex-direction: row; /* Alinha os itens lado a lado */
            width: 100%; /* Ocupa a largura total do container pai */
            margin-top: 0px; /* Espaço superior para separar dos outros conteÃºdos */
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

<body>
    <?php
        include_once "include/menu.php";
        include_once "../Model/AgendaDAO.php";
        include_once "../Model/ContatoDAO.php";

        $id = $_SESSION['id'];
        $ContatoDAO = new ContatoDAO();
        $stFiltro = " WHERE admin = 1 AND id =".$id;
        $rsAdmin = $ContatoDAO->recuperaTodos($stFiltro);

        $stFiltro = " WHERE barbeiro = 1 AND id =".$id;
        $rsBarbeiro = $ContatoDAO->recuperaTodos($stFiltro);

        $stFiltroHistorico = " WHERE cliente.id = ".$id." AND dthora_execucao <= NOW() ORDER BY dthora_execucao ASC";
        $stFiltroAgendado = " WHERE cliente.id = ".$id." AND (dthora_execucao > NOW()) OR (dthora_execucao >= CURDATE() AND TIME(dthora_execucao) >= CURTIME()) ORDER BY dthora_execucao ASC";

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
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Agendados</h1>
                    <div class="m-5"></div>
                    <div class="form-group">
                        <button onclick="toggleVisibility('cortesAgendados', 'arrow-icon-agendado')" class="btn btn-primary btn-toggle" style="color:white;">
                            Serviços Agendados
                            <span id="arrow-icon-agendado" class="arrow">&#x25BC;</span>
                        </button>
                        <div id="cortesAgendados" style="display: none;">
                            <table class="table table-bg">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Data-Hora</th>
                                        <th scope="col">Barbeiro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    mysqli_data_seek($agendado, 0);
                                    $dadosAgendado = [];
                                    while($row = mysqli_fetch_assoc($agendado)) {
                                        $dadosAgendado[] = $row;
                                    }
                                    foreach ($dadosAgendado as $dados) { ?>
                                        <tr onclick="toggleInfo(<?= $dados['id'] ?>)">
                                            <td><?= $dados['id'] ?></td>
                                            <td><?= $dados['data'] . '/' . $dados['hora_minuto'] ?></td>
                                            <td><?= 'Exemplo Barbeiro' ?></td>
                                        </tr>
                                        <tr class="more-info" id="info_<?= $dados['id'] ?>">
                                            <td colspan="3">
                                                Valor: R$<?= $dados['valor'] ?><br>
                                                Descrição: <?= $dados['confirmado'] ?><br>
                                                <a href="editarContato.php?source=Barbeiro&id=<?= $dados['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                                <button onclick="confirmarExclusao(<?= $dados['id'] ?>)" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                            </td>
                                        </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="box">
                    <h1 id="subtitle">Pontos Fidelidade</h1>
                    <div class="m-5"></div>
                    <div class="form-group">
                        <button onclick="toggleVisibility('cortesRealizados', 'arrow-icon-realizado')" class="btn btn-primary btn-toggle" style="color:white;">
                            Serviços Realizados
                            <span id="arrow-icon-realizado" class="arrow">&#x25BC;</span>
                        </button>
                        <div id="cortesRealizados" style="display: none;">
                            <table class="table text-white table-bg">
                                <thead>
                                    <tr>
                                        <th scope="col">Data - Hora</th>
                                        <th scope="col">Confirmar</th>
                                        <th scope="col">Negar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        mysqli_data_seek($historico, 0);
                                        $dadosHistorico = [];
                                        while($row = mysqli_fetch_assoc($historico)) {
                                            $dadosHistorico[] = $row;
                                        }
                                    foreach ($dadosHistorico as $dados) { ?>
                                        <tr onclick="toggleInfo(<?= $dados['id'] ?>)">
                                            <td><?= $dados['data'] . ' - ' . $dados['hora_minuto'] ?></td>
                                            <?php if ($dados['confirmado'] == 1) { ?>
                                                <td colspan="2">
                                                    Serviço Confirmado!<br>
                                                </td>
                                            <?php } else { ?>
                                                <td>
                                                    <button onclick="confirmarCorte(<?= $dados['id'] ?>)" class="round-button"><i class="fas fa-check"></i></button></td>
                                                    <td>   <button onclick="confirmarExclusao(<?= $dados['id'] ?>)" class="round-button-red"><i class="fas fa-times"></i></button>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr class="more-info" id="info_<?= $dados['id'] ?>">
                                            <td colspan="3">
                                                Cliente: <?= $dados['cliente'] ?><br>
                                                Valor: R$<?= $dados['valor'] ?><br>
                                                Serviço: <?= $dados['servico'] ?><br>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group" style="text-align: center;">
                        <button type="button" class="btn btn-secondary" onclick="history.go(-1); return false;">Voltar</button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- jQuery (online) -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
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
            var infoRow = document.getElementById('info_' + id);
            infoRow.classList.toggle('show');
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
    </script>
</body>
</html>
