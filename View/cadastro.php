<?php
    if (isset($_GET['source']) && $_GET['source'] == 'cadastrarBarbeiro') {
        $cadastroBarbeiro = 1;
    } else {
        $cadastroBarbeiro = 0;
    }
?>

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
</head>

<body>
    <?php
        if ($cadastroBarbeiro == 1) {
            include_once "include/menu.php";
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Cadastrar Barbeiro</h1>
                    <p></p>
                    <form>
                        <div class="form-group mb-3">
                            <label for="nome">Nome*</label>
                            <input type="text" class="form-control" id="nome" placeholder="Informe seu Nome">
                        </div>

                        <div class="form-group mb-3">
                            <label for="sobrenome">Sobrenome*</label>
                            <input type="text" class="form-control" id="sobrenome" placeholder="Informe seu Sobrenome">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">E-mail*</label>
                            <input type="email" class="form-control" id="email" placeholder="Informe seu E-mail">
                        </div>

                        <div class="form-group mb-3">
                            <label for="telefone">Telefone*</label>
                            <input type="text" class="form-control" id="telefone" placeholder="(00)00000-0000" oninput="formatarTelefone(this)">
                        </div>

                        <div class="form-group mb-3">
                            <label for="senha">Senha*</label>
                            <input type="password" class="form-control" id="senha" placeholder="Informe uma Senha">
                        </div>
                        <div class="button-row">
                            <button id="cadastrar" type="button" class="btn btn-primary" onclick="cadastrarBarbeiro()">Cadastrar</button>
                            <button type="button" class="btn btn-secondary" onclick="voltarBarbeiro()">Voltar</button>
                        </div>
                    </form>
                    <p></p>
                    <div class="rodape">
                        <p style="font-weight: bold;">Campos marcados com (*) são obrigatórios.</p>
                    </div>
                    <div id="status"></div>
                </fieldset>
            </div>
        </div>
    </div>

    <?php
        }

        if ($cadastroBarbeiro == 0) {
            include_once "include/menuLogin.php";

    ?>
        <style>
        .container {
            margin-top: 10rem;
        }

        .box {
            text-align: left;
        }

        #link-cadastrar a {
            color: #F8DE7E; /* Define a cor do link */
            text-decoration: none; /* Remove o sublinhado do link */
        }

        #link-cadastrar a:hover, #link-cadastrar a:focus {
            text-decoration: underline; /* Adiciona um sublinhado ao passar o mouse ou focar */
        }
    </style>
<div class="container">
                <div>
                    <fieldset class="box">
                    <h1 id="subtitle">Cadastro</h1>
                    <form>
                        <div class="form-group mb-3">
                            <label for="nome">Nome Completo*</label>
                            <input type="text" class="form-control" id="nome" placeholder="Informe seu Nome Completo">
                        </div>

                        <div class="form-group mb-3">
                            <label for="sobrenome">Apelido</label>
                            <input type="text" class="form-control" id="sobrenome" placeholder="Informe seu Apelido">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">E-mail*</label>
                            <input type="email" class="form-control" id="email" placeholder="Informe seu E-mail">
                        </div>

                        <div class="form-group mb-3">
                            <label for="telefone">Telefone*</label>
                            <input type="text" class="form-control" id="telefone" placeholder="(00)00000-0000" oninput="formatarTelefone(this)">
                        </div>

                        <div class="form-group mb-3">
                            <label for="senha">Senha*</label>
                            <input type="password" class="form-control" id="senha" placeholder="Informe uma Senha">
                        </div>
                        <div class="button-row">
                            <button type="button" class="btn btn-primary" id="cadastrar" onclick="cadastrarContato()">Cadastrar</button>
                            <button type="button" class="btn btn-secondary" id="voltar" onclick="voltarLogin()">Voltar</button>
                        </div>
                    </form>
                        <p></p>
                        <div class="rodape">
                            <p style="font-weight: bold;">Campos marcados com (*) são obrigatórios.</p>
                        </div>
                        <div id="status"></div>
                    </fieldset>
                </div>
    </div>
    <?php
        }
    ?>



    <!-- jQuery (online) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script>
        function formatarTelefone(input) {
            // Remove todos os caracteres nÃ£o numÃ©ricos do valor do campo
            let cleaned = input.value.replace(/\D/g, '');

            // Adiciona parÃªnteses nos dois primeiros caracteres
            if (cleaned.length >= 2) {
                cleaned = "(" + cleaned.substring(0, 2) + ")" + cleaned.substring(2);
            }

            // Insere hÃ­fen apÃ³s o nono caractere, se houver mais de nove caracteres
            if (cleaned.length > 9) {
                cleaned = cleaned.substring(0, 9) + "-" + cleaned.substring(9);
            }

            // Limita o comprimento mÃ¡ximo do valor formatado
            input.value = cleaned.substring(0, 14);
        }
    </script>
</body>
</html>