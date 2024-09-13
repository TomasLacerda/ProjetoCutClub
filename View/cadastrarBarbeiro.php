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
    include_once "../Model/ContatoDAO.php";

    function formatPhone($phone) {
        $phone = preg_replace('/\D/', '', $phone);
        return strlen($phone) === 11 ? preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $phone) : 
               (strlen($phone) === 10 ? preg_replace("/(\d{2})(\d{4})(\d{4})/", "($1) $2-$3", $phone) : $phone);
    }
    
    $ContatoDAO = new ContatoDAO();
    $stFiltro = " WHERE barbeiro = 0";
    $resultado = $ContatoDAO->recuperaTodos($stFiltro);
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Cadastrar Barbeiro</h1>
                    <div class="m-5"></div>
                    <p>Para incluir um novo funcionário, selecione o cadastro dele na tabela abaixo e salve. Se o funcionário não estiver cadastrado, <a href="cadastro.php?source=cadastrarBarbeiro">clique aqui</a> para criar um novo cadastro.</p>
                    <table class="table text-white table-bg">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Selecionar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dados = mysqli_fetch_assoc($resultado)) {
                                $formattedPhone = formatPhone($dados['telefone']); ?>
                                <tr onclick="toggleInfo(<?= $dados['id'] ?>)">
                                    <td><?= $dados['id'] ?></td>
                                    <td><?= $dados['nome'] . ' ' . $dados['sobrenome'] ?></td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input position-static" type="checkbox" name="boBarbeiro[]" id="boBarbeiro_<?= $dados['id'] ?>" value="<?= $dados['id'] ?>" aria-label="...">
                                        </div>
                                    </td>

                                </tr>
                                <tr class="more-info" id="info_<?= $dados['id'] ?>">
                                    <td colspan="3">
                                        Telefone: <?= $formattedPhone ?><br>
                                        Email: <?= $dados['email'] ?><br>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="button-row">
                        <button id="cadastrar" onclick="SalvarEditarContato()" type="button" class="btn btn-primary">Cadastrar</Button>
                        <button onclick="history.go(-1);" class="btn btn-secondary">Voltar</button>
                    </div>
                    <div id="status"></div>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- jQuery (online) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>

    <script>
        function toggleInfo(id) {
            event.stopPropagation(); // Previne a propagaÃ§Ã£o do evento no DOM
            $('#info_' + id).toggleClass('show');
        }
    </script>
</body>
</html>
