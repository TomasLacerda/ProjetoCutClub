<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Agenda PHP</title>
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <style>
        .container {
            margin-top: 10rem;
        }

        #link-cadastrar a {
            color: #F8DE7E; /* Define a cor do link */
            text-decoration: none; /* Remove o sublinhado do link */
        }

        #link-cadastrar a:hover, #link-cadastrar a:focus {
            text-decoration: underline; /* Adiciona um sublinhado ao passar o mouse ou focar */
        }
    </style>
</head>

<body>
    <?php
        include_once "include/menuLogin.php";
    ?>

    <div class="container">
        <div>
            <fieldset class="box">
                <h1 id="tituloBarbearia">Barbearia 313</h1>
                <p></p>
                <form>
                    <div class="form-group mb-3">
                        <input type="email" class="form-control" id="email" placeholder="Telefone, nome de usuário ou email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="senha" placeholder="Senha" required>
                    </div>
                    <button id="login" onclick="efetuarLogin()" type="button" class="btn btn-primary">Entrar</button>
                    <p></p>
                    <hr class="white-hr">
                    <p id="link"><a href="agendamento.php">Esqueceu a senha?</a></p>
                </form>
                <div id="status"></div>
            </fieldset>
            <fieldset class="box" style="text-align: center;">
                <span>Não tem uma conta?</span>
                <a id="link-cadastrar" href="javascript:void(0)" onclick="direcionaCadastrar()" style="color: #F8DE7E; text-decoration: none;">Cadastre-se</a>
            </fieldset>
        </div>
    </div>

    <!-- jQuery (online) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
</body>
</html>