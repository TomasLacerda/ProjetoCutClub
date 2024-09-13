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
    padding: 10px; /* Espaçamento interno */
    margin-bottom: 20px; /* Margem inferior */
    border: 1px solid #ced4da; /* Borda com cor */
    border-radius: 10px; /* Bordas arredondadas */
    box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
    resize: none; /* Desabilitar redimensionamento automático */
    min-height: 100px; /* Altura mínima */
    max-height: 200px; /* Altura máxima */
    overflow-y: auto; /* Adicionar barras de rolagem vertical, se necessário */
}
    /* Personalize o estilo do tooltip */
    .tooltip-inner {
        background-color: #007bff; /* Cor de fundo azul */
        color: #fff; /* Cor do texto branco */
        font-size: 18px; /* Tamanho da fonte aumentado */
        border-radius: 8px; /* Borda arredondada */
        padding: 8px 12px; /* Preenchimento interno */
        max-width: 300px; /* Largura máxima do tooltip */
    }

    /* Personalize o estilo da seta do tooltip */
    .arrow::before {
        border-top-color: #007bff !important; /* Cor da borda superior azul */
    }

    /* Torna o tooltip responsivo */
    @media (max-width: 768px) {
        .tooltip-inner {
            font-size: 14px; /* Reduz o tamanho da fonte para telas menores */
        }
    }

        .legend-style {
            font-size: 24px; /* Tamanho da fonte do título */
            color: #333; /* Cor do texto */
            font-weight: bold; /* Negrito */
            margin-bottom: 20px; /* Margem inferior */
        }

        select, input[type="text"] {
            width: 100%; /* Largura total para os campos de seleção e texto */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 20px; /* Margem inferior */
            border: 1px solid #ced4da; /* Borda com cor */
            border-radius: 10px; /* Bordas arredondadas */
            box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
        }

        /* Adicionando bordas arredondadas aos botões */
        .btn {
            border-radius: 10px;
        }

        .rodape {
        text-align: center;
        }
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
                    <h1 id="subtitle">Ausência Programada</h1>
                    <div class="m-5"></div>
                    <div class="row">
                        <div class="col">
                            <label for="dt_inicio"><span data-toggle="tooltip" data-placement="top" title="Selecione a data inicial para os serviços">Data inicial*</span></label>
                            <p>
                                <input id="dt_inicio" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" class="form-control" required/>
                            </p>
                        </div>
                        <div class="col">
                            <label for="dt_fim"><span data-toggle="tooltip" data-placement="top" title="Caso tenha uma data final definida, preencha esse campo">Data final</span></label>
                            <p>
                                <input id="dt_final" type="date" min="<?php echo date('Y-m-d'); ?>" class="form-control"/>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="dia_semana"><span data-toggle="tooltip" data-placement="top" title="Caso tenha um dia da semana definido para não abrir, preencha esse campo">Dias de Não Funcionamento</span></label>
                            <p> 
                                <select id="dia_semana" multiple required>
                                    <option value="1">Segunda-Feira</option>
                                    <option value="2">Terça-Feira</option>
                                    <option value="3">Quarta-Feira</option>
                                    <option value="4">Quinta-Feira</option>
                                    <option value="5">Sexta-Feira</option>
                                    <option value="6">Sábado</option>
                                    <option value="7">Domingo</option>
                                </select>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="hrInicio">Hora início</label>
                            <input type="time" id="hrInicio" name="hrInicio" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="hrFinal">Hora fim</label>
                            <input type="time" id="hrFinal" name="hrFinal" class="form-control" required>
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col">
                            <label for="barbeiro"><span data-toggle="tooltip" data-placement="top" title="Selecione o barbeiro que fará esse horário">Selecione o Barbeiro*</span></label>
                            <div class="select-container">
                                <select id="barbeiro" multiple>
                                <option>Todos</option>
                                    <?php
                                    // Verificar se existem resultados da consulta
                                    if ($resultado->num_rows > 0) {
                                        // Loop através dos resultados e exibir as opções do select
                                        while ($coluna = $resultado->fetch_assoc()) {
                                            echo "<option value='" . $coluna['id'] . "'>" . $coluna['nome'] . ' ' . $coluna['sobrenome'] ."</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Nenhum barbeiro encontrado</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <p></p>
                    <div class="row">
                        <div class="col">
                            <input type="checkbox" name="repetir" id="repetir" checked/>
                            <label for="repetir" name="repetir" id="repetir">Repetir Semanalmente</label>
                        </div>
                    </div>
                    <div class="button-row">
                        <button type="button" class="btn btn-secondary" onclick="history.go(-1); return false;">Voltar</button>
                        <button id="cadastrar" onclick="cadastrarDatas()" type="button" class="btn btn-primary">Cadastrar</Button>
                    </div>
                    <p></p>
                    <div class="rodape">
                        <p style="font-weight: bold;">Campos marcados com (*) são obrigatórios.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- JavaScript customizado -->
    <script src="js/scripts.js"></script>
    <script src="js/bootstrap-multiselect.js"></script>

    <script>
        $(document).ready(function() {
            $('#dia_semana').multiselect({
                // includeSelectAllOption: true,
                buttonWidth: '15rem',
                buttonWidth: '100%'
            });

            // Cache do elemento select de barbeiro
            var $barbeiroSelect = $('#barbeiro');

            // Inicialização do multiselect
            $barbeiroSelect.multiselect({
                // includeSelectAllOption: true,
                buttonWidth: '15rem',
                buttonWidth: '100%'
            });

            // Evento de mudança para o select de barbeiro
            $barbeiroSelect.change(function() {
                // Verifica se a opção 'Todos' foi selecionada
                var todosSelected = $(this).val() && $(this).val().includes('Todos');
                console.log(todosSelected);

                // Se 'Todos' já estiver selecionado, desmarca todas as outras opções
                if (todosSelected) {
                    // Verifica se 'Todos' já está selecionado
                    var allOptionsSelected = $(this).val().length === $(this).find('option').length;
                    if (allOptionsSelected) {
                        // Se todas as opções estiverem selecionadas, desmarca todas
                        $(this).val([]);
                    } else {
                        // Marca todas as outras opções, exceto "Todos"
                        $(this).find('option').not('[value="Todos"]').prop('selected', true);
                    }
                } else {
                    // Verifica se todas as opções foram selecionadas manualmente
                    var allSelected = $(this).find('option').length === $(this).find('option:selected').length;
                    if (allSelected) {
                        // Marca "Todos" se todas as outras opções estiverem selecionadas manualmente
                        $(this).val(['Todos']);
                    }
                }
                
                // Atualiza o multiselect após alterar as opções selecionadas
                $barbeiroSelect.multiselect('refresh');
            });
        });

        // Inicialize os tooltips do Bootstrap
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>
