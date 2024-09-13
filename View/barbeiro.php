<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agenda PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
    $stFiltro = " WHERE barbeiro = 1";
    $resultado = $ContatoDAO->recuperaTodos($stFiltro);
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Barbeiros</h1>
                    <div class="m-5"></div>
                    <table class="table text-white table-bg">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dados = mysqli_fetch_assoc($resultado)) {
                                $formattedPhone = formatPhone($dados['telefone']); ?>
                                    <tr onclick="toggleInfo(<?= $dados['id'] ?>)">
                                        <td><?= $dados['id'] ?></td>
                                        <td><?= $dados['nome']?></td>
                                    </tr>
                                    <tr class="more-info" id="info_<?= $dados['id'] ?>">
                                        <td colspan="2">
                                            Email: <?= $dados['email'] ?><br>
                                            Telefone: <?= $formattedPhone ?><br>
                                            <a href="editarContato.php?source=Barbeiro&id=<?= $dados['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-pencil-alt"></i></a>
                                            <button onclick="confirmarExclusao(<?= $dados['id'] ?>)" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                                        </td>
                                    </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="button-row">
                        <button onclick="history.go(-1);" class="btn btn-secondary">Voltar</button>
                        <button id="cadastrar" onclick="window.location.href='cadastrarBarbeiro.php'" type="button" class="btn btn-primary">Cadastrar</Button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleInfo(id) {
            event.stopPropagation(); // Previne a propagação do evento no DOM
            $('#info_' + id).toggleClass('show');
        }

        function confirmarExclusao(id) {
            event.stopPropagation(); // Previne a propagação do evento no DOM
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja excluir este cadastro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Não, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'exclusaoContato.php?id=' + id;
                }
            });
        }
    </script>
</body>
</html>