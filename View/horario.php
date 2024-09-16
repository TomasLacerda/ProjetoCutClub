<!DOCTYPE html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agenda PHP</title>
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .table-bg {
            margin: auto;
            border-radius: 5px;
            background-color: rgba(192,192,192,0.3);
        }

        .table-bg th, .table-bg td {
            color: white;
        }

        .more-info {
            display: none;
            background-color: rgba(192,192,192,0.3);
        }

        .more-info.show {
            display: table-row;
        }

        .button-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .active-row, .table-hover tbody tr:hover {
            color: white !important;
        }
    </style>
</head>

<body>
    <?php
    include_once "include/menu.php";
    include_once "../Model/DtIndisponivelDAO.php";
    include_once "../Model/ExpedienteDAO.php";        
    
    $DtIndisponivelDAO = new DtIndisponivelDAO();
    $stFiltro = "";
    $resultado = $DtIndisponivelDAO->recuperaRelacionamento($stFiltro);

    $ExpedienteDAO = new ExpedienteDAO();
    $stFiltro = "";
    $expediente = $ExpedienteDAO->recuperaRelacionamento($stFiltro);
    ?>


    <div class="container">
        <div class="box-wrapper">
            <div>
                <!--<fieldset class="box">
                    <h1 id="subtitle">Horários</h1>
                    <form>
                        <div class="m-5"></div>
                        <div class="table-responsive">
                            <table class="table text-white table-bg">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Barbeiro</th>
                                        <th scope="col">hora inicial</th>
                                        <th scope="col">hora final</th>
                                    </tr>
                                </thead>
                                <tbody> -->
                                    <?php
                                //        while($dados = mysqli_fetch_assoc($resultado))
                                //        {
                                //            echo "<tr class='clickable-row' data-id='" . $dados['id'] . "'>";
                                //            echo "<td>".$dados['id']."</td>";
                                //            echo "<td>".$dados['barbeiro']."</td>";
                                //            echo "<td>".$dados['hora_inicio']."</td>";
                                //            echo "<td>".$dados['hora_fim']."</td>";
                                //            echo "</tr>";
                                //            // Detalhes adicionados após cada linha
                                //            echo "<tr class='more-info' id='info_".$dados['id']."' style='background-color: rgba(192,192,192,0.3);'>
                                //                <td colspan='5'>Data início horário: ".$dados['data_inicio']."<br>Data fim horário: ".$dados['data_fim_regra']."<br>Dia: ".$dados['dias_semana'].
                                //                "<br><div class='d-flex'>
                                //                <a class='btn btn-sm btn-primary ml-2' data-tooltip='Editar' href='editarHorario.php?id=$dados[id]'>
                                //                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                                //                        <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325'/>
                                //                    </svg>
                                //                </a>
                                //                <a class='btn btn-sm btn-danger ml-2' data-tooltip='Excluir' onclick='confirmarExclusao(".$dados['id'].")'>
                                //                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16'>
                                //                        <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06l.5-8.5a.5.5 0 0 1 .528-.47Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5'/>
                                //                    </svg>
                                //                </a>
                                //            </div>".
                                //                "</td></tr>
                                //            ";
                                //        }
                                //    ?>
                                </tbody>
                        <!--    </table>
                        </div>
                        <div class="button-row">
                            <button type="button" class="btn btn-secondary" onclick="history.go(-1); return false;">Voltar</button>
                            <button id="cadastrar" onclick="window.location.href='cadastroHorario.php'" type="button" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </form>
                    <div id="status"></div>
                </fieldset> -->
                <fieldset class="box">
                    <h1 id="subtitle">Expediente</h1>
                    <form>
                        <div class="m-5"></div>
                        <div class="table-responsive">
                            <table class="table text-white table-bg">
                                <thead>
                                    <tr>
                                        <th scope="col">Dia Semana</th>
                                        <th scope="col">hora inicial</th>
                                        <th scope="col">hora final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        while($dados = mysqli_fetch_assoc($expediente))
                                        {
                                            echo "<tr class='clickable-row' data-id='" . $dados['id'] . "'>";
                                            echo "<td>".$dados['id_semana']."</td>";
                                            echo "<td>".$dados['hr_inicio']."</td>";
                                            echo "<td>".$dados['hr_fim']."</td>";
                                            echo "</tr>";
                                            // Detalhes adicionados após cada linha
                                             echo "<tr class='more-info' id='info_".$dados['id']."' style='background-color: rgba(192,192,192,0.3);'>
                                                   <td colspan='5'>Início Intervalo: ".$dados['hora_inicio_intervalo']."<br>Fim Intervalo: ".$dados['hora_fim_intervalo'].
                                                  "<br><div class='d-flex'>";
                                                    echo ' <a href="#" class="delete-link" data-id="' . $dados['id'] . '" title="Excluir">';
                                                    echo '<i class="fas fa-trash-alt" style="color: red;"></i>';
                                                    echo '</a>';
                                             "</div>".
                                            "</td></tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="button-row">
                            <button type="button" class="btn btn-secondary" onclick="history.go(-1); return false;">Voltar</button>
                            <button id="cadastrar" onclick="window.location.href='cadastroHorarioBarbearia.php'" type="button" class="btn btn-primary">Cadastrar</button>
                        </div>
                    </form>
                    <div id="status"></div>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- jQuery (online) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script>
        document.querySelectorAll('.delete-link').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();
                const idServico = this.getAttribute('data-id'); // Pegue o ID do serviço

                // Exibe a mensagem de confirmação
                Swal.fire({
                    title: `Tem certeza que deseja excluir o expediente: ${idServico}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Se o usuário confirmar, enviar o AJAX para excluir
                        fetch('../Controller/horarioController.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id_horario=${idServico}&excluir=excluir`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.sucesso) {
                                Swal.fire('Sucesso', 'Expediente excluído com sucesso!', 'success')
                                .then(() => {
                                    // Recarregar a página após a exclusão
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Erro', 'Erro ao excluir o expediente.', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Erro', `Erro: ${error}`, 'error');
                        });
                    }
                });
            });
        });

        $(document).ready(function() {
            $(".clickable-row").click(function() {
                var id = $(this).data('id'); // Obtem o ID da linha clicada
                $('#info_' + id).slideToggle(); // Exibe ou oculta os detalhes correspondentes
            });
        });

        function toggleInfo(event, id) {
            event.preventDefault(); // Impede o comportamento padrão de redirecionamento do link
            $('#info_' + id).toggle();
        }
    </script>
</body>
</html>
