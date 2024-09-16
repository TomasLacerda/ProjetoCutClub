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
            <h1 id="subtitle">Recuperar Senha</h1>
            <p></p>
            <form id="form-recuperar-senha">
                <div class="form-group mb-3">
                    <input type="email" class="form-control" id="email" placeholder="Email*" required>
                </div>
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="nome" placeholder="Nome Completo*" required>
                </div>
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="apelido" placeholder="Apelido*" required>
                </div>

                <!-- Novo campo para a senha, inicialmente escondido -->
                <div class="form-group mb-3" id="nova-senha-group" style="display:none;">
                    <div class="input-group">
                        <input type="password" id="senha" name="senha" class="form-control" placeholder="Nova Senha*" required>
                        <span class="input-group-text">
                            <img id="olho" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABDUlEQVQ4jd2SvW3DMBBGbwQVKlyo4BGC4FKFS4+TATKCNxAggkeoSpHSRQbwAB7AA7hQoUKFLH6E2qQQHfgHdpo0yQHX8T3exyPR/ytlQ8kOhgV7FvSx9+xglA3lM3DBgh0LPn/onbJhcQ0bv2SHlgVgQa/suFHVkCg7bm5gzB2OyvjlDFdDcoa19etZMN8Qp7oUDPEM2KFV1ZAQO2zPMBERO7Ra4JQNpRa4K4FDS0R0IdneCbQLb4/zh/c7QdH4NL40tPXrovFpjHQr6PJ6yr5hQV80PiUiIm1OKxZ0LICS8TWvpyyOf2DBQQtcXk8Zi3+JcKfNafVsjZ0WfGgJlZZQxZjdwzX+ykf6u/UF0Fwo5Apfcq8AAAAASUVORK5CYII=" alt="Mostrar Senha">
                        </span>
                    </div>
                </div>

                <div class="button-row">
                    <button id="recuperar" onclick="recuperarSenha()" type="button" class="btn btn-primary">Avançar</button>
                    <button type="button" class="btn btn-secondary" onclick="voltarLogin()">Voltar</button>
                </div>
            </form>
            <p></p>
            <div id="status"></div>
            <div>
                <span>Campos marcados com (*) são obrigatórios.</span>
            </div>
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

    <script>
    const senha = document.getElementById('senha');
        const olho = document.getElementById('olho');

        olho.addEventListener('click', function (e) {
            // Assume que o ID do campo de senha é 'senha'
            var senha = document.getElementById('senha');
            if (senha.type === 'password') {
                senha.type = 'text';
            } else {
                senha.type = 'password';
            }
        });
    </script>
</body>
</html>