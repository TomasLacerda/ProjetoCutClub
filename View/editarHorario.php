<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agenda PHP</title>
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap-multiselect.css">

</head>

<style>
    .select-container {
        display: flex;
        flex-direction: column;
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
</style>

<body>
    <?php
    include_once "include/menu.php";
    include_once "../Model/ContatoDAO.php";        
    
    $ContatoDAO = new ContatoDAO();
    $stFiltro = " WHERE barbeiro = 1";
    $resultado = $ContatoDAO->recuperaTodos($stFiltro);
    ?>

    <div class="container" style="padding:2.5rem 2.5rem 2.5rem 2.5rem; border:10px double white;">
        <div class="row">
            <div class="col"></div>
            <div class="col-lg-8">
                <h3>Cadastro de horários para atendimento</h3>
                <form id="formulario">
                <div class="row">
                    <div class="col">
                        <label for="dt_inicio">Data inicial* <span data-toggle="tooltip" data-placement="top" title="Selecione a data inicial para os serviços">Selecione a data de início*</span></label>
                        <p>
                            <input id="dt_inicio" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="2024-12-31" required/>
                        </p>
                    </div>
                    <div class="col">
                        <label for="dt_fim">Data final <span data-toggle="tooltip" data-placement="top" title="Caso tenha uma data final definida, preencha esse campo">Selecione a data final</span></label>
                        <p>
                            <input id="dt_final" type="date" max="2024-12-31"/>
                        </p>
                    </div>
                </div>
                    <div class="row">
                        <div class="col">
                        <label for="nome">Selecione o dia que a loja não abrirá</span></label>
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
                            <label for="appt-time">Hora inicial*</label>
                            <p>
                                <input id="hora_inicio" type="time" required data-toggle="tooltip" data-placement="top" title="Selecione o horário inicial dos trabalhos"/>
                            </p>
                        </div>
                        <div class="col">
                            <label for="appt-time">Hora final*</label>
                            <p>
                                <input id="hora_fim" type="time" required data-toggle="tooltip" data-placement="top" title="Selecione o horário final dos trabalhos"/>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="appt-time">Hora inicial do intervalo</label>
                            <p>
                                <input id="hora_inicio_intervalo" type="time" data-toggle="tooltip" data-placement="top" title="Selecione o horário inicial do intervalo"/>
                            </p>
                        </div>
                        <div class="col">
                            <label for="appt-time">Hora final do intervalo</label>
                            <p>
                                <input id="hora_fim_intervalo" type="time" data-toggle="tooltip" data-placement="top" title="Selecione o horário final do intervalo"/>
                            </p>
                        </div>
                    </div>
                    <div class="col">
                        <label for="barbeiro" class="form-label">Selecione o Barbeiro</label>
                        <p class="select-container">
                            <select id="barbeiro" multiple>
                            <option selected disabled>Todos</option>
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
                            <small class="form-text text-muted">Se todos os barbeiros farão o mesmo horário, não é necessário selecionar nenhum.</small>
                        </p>

                    </div>
                    <div class="row">
                        <div class="col">
                            <button id="cadastrar" onclick="cadastrarDatas()" type="button" class="btn btn-primary">Cadastrar</Button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary" onclick="history.go(-1); return false;">Voltar</button>
                        </div>
                    </div>
                </form>
                <div id="status"></div>
            </div>
            <div class="col"></div>
        </div>
    </div> <!-- fecha /container -->

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
                buttonWidth: '15rem'
            });
            $('#barbeiro').multiselect({
                // includeSelectAllOption: true,
                buttonWidth: '15rem'
            });
        });

        // Inicialize os tooltips do Bootstrap
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>