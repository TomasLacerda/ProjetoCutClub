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
        .img-thumbnail {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid black;
        }
    </style>
</head>

<body data-page-id="servico">
<?php
    include_once "include/menu.php";
    include_once "../Model/ServicoDAO.php";

    function formatarDuracao($duracao) {
        $partes = explode(':', $duracao); // Separa a string por ':'
        return $partes[0] . 'h' . $partes[1] ; // ReconstrÃ³i a string no novo formato
    }

    $ServicoDAO = new ServicoDAO();
    $stFiltro = " ORDER BY nome ASC";
    $resultado = $ServicoDAO->recuperaTodos($stFiltro);
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Serviços</h1>
                    <div class="m-5"></div>
                    <table class="table text-white table-bg" id="servico">
                        <thead>
                            <tr>
                                <th scope="col">Imagem</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dados = mysqli_fetch_assoc($resultado)) { ?>
                                    <tr onclick="toggleInfo(<?= $dados['id'] ?>)">
                                        <td><img src="<?= $dados['imagem'] ?>" alt="<?= $dados['nome'] ?>" class="img-thumbnail" /></td>
                                        <td><?= $dados['nome'] ?></td>
                                        <td>R$<?= $dados['valor'] ?></td>
                                    </tr>
                                    <tr class="more-info" id="info_<?= $dados['id'] ?>">
                                        <td colspan="3">
                                            Duração: <?= formatarDuracao($dados['duracao']) ?><br> <!-- Aqui a funÃ§Ã£o Ã© chamada -->
                                            Descricao: <?= $dados['descricao'] ?><br>
                                            <!--<a href="editarContato.php?source=Barbeiro&id=<?= $dados['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>-->
                                            <?php 
                                            echo ' <a href="#" class="delete-link" data-id="' . $dados['id'] . '" title="Excluir">';
                                            echo '<i class="fas fa-trash-alt" style="color: red;"></i>';
                                            echo '</a>';
                                            ?>
                                        </td>
                                    </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="button-row">
                        <button type="button" class="btn btn-secondary" onclick="history.go(-1); return false;">Voltar</button>
                        <button id="cadastrar" type="button" class="btn btn-primary" onclick="window.location.href='cadastrarServico.php'">Cadastrar</button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- jQuery e outros scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/scripts.js"></script>
    <script>
        createSearchBar("servico");
        createPagination("servico", 10);

        function toggleInfo(id) {
            event.stopPropagation(); // Previne a propagaÃ§Ã£o do evento no DOM
            $('#info_' + id).toggleClass('show');
        }

        document.querySelectorAll('.delete-link').forEach(function (element) {
            element.addEventListener('click', function (e) {
                e.preventDefault();
                const idServico = this.getAttribute('data-id'); // Pegue o ID do serviço

                // Exibe a mensagem de confirmação
                Swal.fire({
                    title: `Tem certeza que deseja excluir o serviço: ${idServico}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Se o usuário confirmar, enviar o AJAX para excluir
                        fetch('../Controller/servicoController.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `id_servico=${idServico}&excluir=excluir`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.sucesso) {
                                Swal.fire('Sucesso', 'Serviço excluído com sucesso!', 'success')
                                .then(() => {
                                    // Recarregar a página após a exclusão
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Erro', 'Erro ao excluir o serviço.', 'error');
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
