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

<style>
    .textarea {
        width: 100%; /* Largura total */
        padding: 10px; /* EspaÃ§amento interno */
        margin-bottom: 20px; /* Margem inferior */
        border: 1px solid #ced4da; /* Borda com cor */
        border-radius: 10px; /* Bordas arredondadas */
        box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
        resize: none; /* Desabilitar redimensionamento automÃ¡tico */
        min-height: 100px; /* Altura mÃ­nima */
        max-height: 200px; /* Altura mÃ¡xima */
        overflow-y: auto; /* Adicionar barras de rolagem vertical, se necessÃ¡rio */
    }
    /* Personalize o estilo do tooltip */
    .tooltip-inner {
        background-color: #007bff; /* Cor de fundo azul */
        color: #fff; /* Cor do texto branco */
        font-size: 18px; /* Tamanho da fonte aumentado */
        border-radius: 8px; /* Borda arredondada */
        padding: 8px 12px; /* Preenchimento interno */
        max-width: 300px; /* Largura mÃ¡xima do tooltip */
    }

    /* Personalize o estilo da seta do tooltip */
    .arrow::before {
        border-top-color: #007bff !important; /* Cor da borda superior azul */
    }

    /* Torna o tooltip responsivo 
    @media (max-width: 768px) {
        .tooltip-inner {
            font-size: 14px;
        }
    }
        body {
            background-color: #f8f9fa;
        }
        .box {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin-top: 200px;
        }
        .legend-style {
            font-size: 24px;
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .btn {
            border-radius: 10px;
        }

        .rodape {
        text-align: center;
        }*/
</style>

<body>
    <?php
    include_once "include/menu.php";
    include_once "../Model/ContatoDAO.php";        
    
    $ContatoDAO = new ContatoDAO();
    $stFiltro = " WHERE barbeiro = 1";
    $resultado = $ContatoDAO->recuperaTodos($stFiltro);
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Cadastrar Serviço</h1>
                    <div class="m-5"></div>
                    <div class="form-group">
                        <div class="form-group mb-3">
                            <label for="nome">Nome do serviço:*</label>
                            <input type="text" class="form-control" id="nome" placeholder="Informe o nome do serviço" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="valor">Valor do serviço:*</label>
                            <input type="text" class="form-control" id="valor" oninput="formatarMoeda()" placeholder="Informe o valor do serviço" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="duracao">Duração do serviço (horas:minutos):*</label>
                            <input type="time" class="form-control" id="duracao" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="descricao"><span data-toggle="tooltip" data-placement="top">Descrição</span></label>
                            <textarea class="textarea" id="descricao" data-toggle="tooltip" maxlength="200" placeholder="Caso tenha descrição, preencha esse campo"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="imagem">Imagem do serviço:</label>
                            <input type="file" class="form-control-file" id="imagem" accept="image/*" class="form-control">
                        </div>
                    </div>
                    <p></p>
                    <div class="button-row">
                            <button onclick="history.go(-1);" class="btn btn-secondary">Voltar</button>
                            <button id="cadastrar" type="button" class="btn btn-primary" onclick="cadastrarServico()">Cadastrar</button>
                        </div>
                    <p></p>
                    <div class="rodape">
                        <p style="font-weight: bold;">Campos marcados com (*) são obrigatórios.</p>
                    </div>
                </fieldset>
                <div id="status"></div>
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
        function number_format(number, decimals, dec_point, thousands_sep) {
            number = parseFloat(number);

            if (!isFinite(number) || !number && number !== 0) {
                return '';
            }

            decimals = !isFinite(decimals) ? 0 : Math.abs(decimals);
            dec_point = typeof dec_point === 'undefined' ? ',' : dec_point;
            thousands_sep = typeof thousands_sep === 'undefined' ? '.' : thousands_sep;

            var negative = number < 0 ? '-' : '';
            var i = parseInt(number = Math.abs(+number || 0).toFixed(decimals), 10) + '';
            var j = (j = i.length) > 3 ? j % 3 : 0;

            return negative + (j ? i.substr(0, j) + thousands_sep : '') +
                i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousands_sep) +
                (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : '');
        }

        function formatarMoeda() {
            var elemento = document.getElementById('valor');
            var valor = elemento.value.replace(/[^\d]/g, ''); // Remove todos os caracteres nÃ£o numÃ©ricos

            valor = number_format(valor / 100, 2, ',', '.'); // Divida por 100 para corrigir o valor
            elemento.value = 'R$ ' + valor; // Adiciona 'R$ ' no inÃ­cio do valor formatado

        }
    </script>
</body>
</html>
