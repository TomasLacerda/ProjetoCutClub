<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>
        .box {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
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

        .help-text {
            font-size: 0.8rem; /* Tamanho da fonte do texto de ajuda */
            color: black; /* Cor do texto de ajuda */
            margin-top: 5px; /* Espaçamento superior */
        }

        label {
            font-weight: bold; /* Negrito para os rótulos */
            width: 100%; /* Largura total para os campos de seleção e texto */
        }
        select, input[type="text"], input[type="number"] {
            width: 50%; /* Largura total para os campos de seleção e texto */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 20px; /* Margem inferior */
            border: 1px solid #ced4da; /* Borda com cor */
            border-radius: 5px; /* Bordas arredondadas */
            box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
        }

        input[type="date"] {
            width: 50%; /* Largura total para os campos de seleção e texto */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 20px; /* Margem inferior */
            border: 1px solid #ced4da; /* Borda com cor */
            border-radius: 5px; /* Bordas arredondadas */
            box-sizing: border-box; /* Caixa de modelagem inclui borda e preenchimento */
        }

        .custom-ul {
            padding-left: 0; /* Remove qualquer padding padrão à esquerda */
            list-style-type: none; /* Remove marcadores de lista */
        }

        .custom-ul li {
            text-align: left; /* Alinha o texto à esquerda */
        }

        .points {
            color: #F8DE7E;
            font-size: 1.6rem;
            font-family: 'Playfair Display', serif;
        }

        .decimal {
            color: #F8DE7E;
            font-size: 1.3rem;
            font-family: 'Playfair Display', serif;
        }

        .disabled-link {
            color: gray;
            cursor: not-allowed;
            text-decoration: none;
        }

        .bonus-link:hover {
            text-decoration: underline;
        }

        .disabled-link:hover {
            text-decoration: none; /* Remove o sublinhado quando o link está desabilitado */
        }
    </style>
</head>

<body data-page-id="planoFidelidade">
    <?php
        include_once "include/menu.php";
        include_once "../Model/AgendaDAO.php";
        include_once "../Model/BonusDAO.php";
        include_once "../Model/ContatoDAO.php";
        include_once "../Model/ServicoDAO.php";

        $id = $_SESSION['id'];

        $ContatoDAO = new ContatoDAO();
        $stFiltro = " WHERE admin = 1 AND id =".$id;
        $rsAdmin = $ContatoDAO->recuperaTodos($stFiltro);

        $stFiltro = " WHERE barbeiro = 1 AND id =".$id;
        $rsBarbeiro = $ContatoDAO->recuperaTodos($stFiltro);

        $stFiltro = " WHERE id =".$id;
        $rsCliente = $ContatoDAO->recuperaTodos($stFiltro);

        $AgendaDAO = new AgendaDAO();
        $stFiltro = " WHERE confirmado = 1 AND cliente.id = ".$id;
        $cortesRealizados = $AgendaDAO->recuperaHistorico($stFiltro);

        $ServicoDAO = new ServicoDAO();
        $stFiltro = "";
        $rsServicos = $ServicoDAO->recuperaTodos($stFiltro);

        // Inicializa as variáveis
        $pontosUtilizados = [];
        $somaTotal = 0.0;

        // Verifica se há resultados para o cliente
        if ($rsCliente && $rsCliente->num_rows > 0) {
            while ($row = $rsCliente->fetch_assoc()) {
                $pontosUtilizados[] = (int)$row['pontos_utilizados']; // Assegura que seja inteiro
            }
        }

        if ($cortesRealizados && $cortesRealizados->num_rows > 0) {
            while ($linha = $cortesRealizados->fetch_assoc()) {
                $valorStr = $linha['valor']; // Valor no formato "xx,xx"
                $valorStr = str_replace(",", ".", $valorStr); // Substitui vírgula por ponto
                $valorFloat = (float)$valorStr; // Converte a string para float

                $somaTotal += $valorFloat; // Soma ao total
            }

            // Verifica se há pontos utilizados antes de subtrair
            if (!empty($pontosUtilizados)) {
                $somaTotal -= $pontosUtilizados[0];
            }

            // Formata o número, garantindo que seja sempre exibido com duas casas decimais
            $pontos = number_format($somaTotal, 2, ',', '.');
        } else {
            // Caso não haja cortes realizados, os pontos permanecem inalterados
            $pontos = number_format($somaTotal, 2, ',', '.');
        }

        $BonusDAO = new BonusDAO();
        $rsBonus = $BonusDAO->recuperaTodos();
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Plano de Fidelidade</h1>
                    <div class="m-5"></div>
                    <div class="row">
                        <div class="col">
                            <h4 class="bonus-title">Regras do Plano:</h4>
                            <ul class="custom-ul" style="list-style: none; padding-left: 0; font-size: 16px; font-family: 'Arial', sans-serif;">
                                <li><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> A cada <span class="decimal">1 real</span> gasto, você recebe <span class="decimal">1 ponto</span>.</li>
                                <li><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> <span class="decimal">Acumule</span> pontos e <span class="decimal">troque</span> por bônus.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <hr>
                            <h4>Seus Pontos:</h4>
                            <p>Atualmente você possui <strong><?php echo $pontos; ?> pontos</strong>.</p>
                        </div>
                    </div>
                    <?php
                        $pontos = str_replace(',', '.', $pontos);
                        $pontos = floatval($pontos);

                        if ($rsBonus->num_rows > 0) {
                            echo '<div class="row">';
                            echo '<div class="col">';
                            echo '<hr>';
                            echo '<h4 class="bonus-title">Bônus Disponíveis:</h4>';
                            echo '<ul class="custom-ul">';
                            
                            while ($bonus = $rsBonus->fetch_assoc()) {
                                $idServico = $bonus['id_servico']; // ID do serviço
                                $meta = number_format($bonus['meta'], 2, ',', '.'); // Pontos necessários formatados para exibição
                                $metaValue = floatval($bonus['meta']); // Pontos necessários (meta) para a comparação
                        
                                echo '<li>';

                                // Verifica se o usuário tem pontos suficientes
                                if ($pontos >= $metaValue) {
                                    // Link ativo se o usuário tiver pontos suficientes
                                    echo '<a href="agendamento.php?id_servico=' . $idServico . '&meta=' . $meta . '" class="bonus-link">';
                                    echo '<u><i class="fas fa-angle-right" style="color: #F8DE7E;"></i> ' . $bonus['nome'] . ':';
                                    echo '<span class="points"> ' . $meta . '</span> pontos</u>';
                                    echo '</a>';
                                } else {
                                    // Link desativado se o usuário não tiver pontos suficientes
                                    echo '<span class="bonus-link disabled-link" title="Você não tem pontos suficientes para resgatar este bônus">';
                                    echo '<u><i class="fas fa-angle-right" style="color: gray;"></i> ' . $bonus['nome'] . ':';
                                    echo '<span class="points"> ' . $meta . '</span> pontos</u>';
                                    echo '</span>';
                                }

                                // Exibe os ícones de edição e lixeira se o $rsAdmin->num_rows for maior que 0
                                if ($rsAdmin->num_rows > 0) {
                                    echo ' <a href="#" class="edit-link" data-id="' . $idServico . '" title="Editar">';
                                    echo '<i class="fas fa-edit" style="color: #A9A9A9;"></i>';
                                    echo '</a>';
                                    echo ' <a href="#" class="delete-link" data-id="' . $idServico . '" title="Excluir">';
                                    echo '<i class="fas fa-trash-alt" style="color: rgba(205, 92, 92, 0.9);"></i>';
                                    echo '</a>';
                                }
                        
                                echo '</li>';
                            }
                        
                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                        } else {
                            echo '<p>Nenhum bônus disponível no momento.</p>';
                        }
                    ?>

                    <?php if ($rsBarbeiro->num_rows > 0) { ?>
                        <div class="row">
                            <div class="col">
                                <button id="cadastrar" type="button" name="cadastrarBonus" class="btn btn-primary" style="text-align: center;">Cadastrar Bônus</button>
                            </div>
                        </div>
                    <?php } ?>
                    <p></p>
                    <div id="status"></div>
                </fieldset>
            </div>
        </div>
    </div>

    <!-- JavaScript customizado -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.edit-link').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    const idServico = this.getAttribute('data-id'); // Pegue o ID do serviço

                    // Fazer a requisição AJAX para buscar os dados do bônus com base no ID do serviço
                    fetch('../Controller/bonusController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id_servico=${idServico}&buscar=buscar`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            // Abrir o popup para editar o bônus com os dados recebidos
                            Swal.fire({
                                title: 'Editar Bônus',
                                html: `
                                    <div class="row text-center">
                                        <div class="col">
                                            <label for="servico">Serviço:</label>
                                            <input type="text" id="servico" value="${data.nome_servico}" readonly class="form-control mx-auto" style="max-width: 400px;">
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col">
                                            <label for="meta">Meta:*</label>
                                            <input type="number" id="meta" maxlength="5" step="0.01" value="${data.meta}" class="form-control mx-auto" style="max-width: 400px;" required>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col">
                                            <label for="dt_inicio">Data inicial*</label>
                                            <input id="dt_inicio" type="date" value="${data.dt_inicio}" min="2024-01-01" max="2099-12-31" class="form-control mx-auto" style="max-width: 400px;" required>

                                            <label for="dt_final">Data final*</label>
                                            <input id="dt_final" type="date" value="${data.dt_final}" min="2024-01-01" max="2099-12-31" class="form-control mx-auto" style="max-width: 400px;" required>
                                        </div>
                                    </div>
                                `,
                                showCloseButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Salvar',
                                preConfirm: () => {
                                    const meta = document.getElementById('meta').value;
                                    const dt_inicio = document.getElementById('dt_inicio').value;
                                    const dt_final = document.getElementById('dt_final').value;

                                    if (!meta || !dt_inicio || !dt_final) {
                                        Swal.showValidationMessage(`Por favor, preencha todos os campos`);
                                        return false;
                                    }

                                    // Enviar os dados via AJAX para salvar as alterações
                                    return fetch('../Controller/bonusController.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded',
                                        },
                                        body: `id_servico=${idServico}&meta=${meta}&dt_inicio=${dt_inicio}&dt_final=${dt_final}&editar=editar`,
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.sucesso) {
                                            // Exibe a mensagem de sucesso
                                            Swal.fire('Sucesso', 'Bônus atualizado com sucesso!', 'success')
                                            .then(() => {
                                                // Recarregar a página após a mensagem de sucesso
                                                window.location.reload();
                                            });
                                        } else {
                                            Swal.showValidationMessage(`Erro ao salvar: ${data.mensagem}`);
                                        }
                                        return data;
                                    })
                                    .catch(error => {
                                        Swal.showValidationMessage(`Erro: ${error}`);
                                    });
                                }
                            });
                        } else {
                            Swal.fire('Erro', 'Erro ao buscar os dados do bônus.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Erro', `Erro: ${error}`, 'error');
                    });
                });
            });

            // Exclusão
            document.querySelectorAll('.delete-link').forEach(function (element) {
                element.addEventListener('click', function (e) {
                    e.preventDefault();
                    const idServico = this.getAttribute('data-id'); // Pegue o ID do serviço

                    // Exibe a mensagem de confirmação
                    Swal.fire({
                        title: `Tem certeza que deseja excluir o bônus do serviço: ${idServico}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, excluir!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Se o usuário confirmar, enviar o AJAX para excluir
                            fetch('../Controller/bonusController.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `id_servico=${idServico}&excluir=excluir`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.sucesso) {
                                    Swal.fire('Sucesso', 'Bônus excluído com sucesso!', 'success')
                                    .then(() => {
                                        // Recarregar a página após a exclusão
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire('Erro', 'Erro ao excluir o bônus.', 'error');
                                }
                            })
                            .catch(error => {
                                Swal.fire('Erro', `Erro: ${error}`, 'error');
                            });
                        }
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.getElementsByName('cadastrarRegra').forEach(function(element) {
                element.addEventListener("click", function() {
                    Swal.fire({
                        title: 'Cadastrar Regras',
                        html: `
                        <?php if ($rsAdmin->num_rows > 0) { ?>
                                        <textarea class="textarea" id="descricao" data-toggle="tooltip" maxlength="255" placeholder="Digite as regras do plano aqui. Use o dígito separador para novos itens."></textarea>
                                        <label for="separador">Dígito Separador:*</label>
                                        <input type="text" id="separador" maxlength="1" placeholder="Informe o dígito separador (ex: | ou ;)">
                                        <p class="help-text">Exemplo: Digite 'Regra1| Regra2| Regra3' e pressione OK. Cada termo separado pelo dígito será uma nova linha.</p>
                                <?php } ?>
                        `,
                        showCloseButton: true,
                        showCancelButton: false,
                        allowOutsideClick: true,
                        preConfirm: () => {
                            Swal.showLoading()
                            return new Promise((resolve) => {
                                setTimeout(() => {
                                    resolve(true)
                                }, 3000)
                            })
                        },
                    })
                })
            })

            document.getElementsByName('cadastrarBonus').forEach(function(element) {
                element.addEventListener("click", function() {
                    Swal.fire({
                        title: 'Cadastrar Bônus',
                        html: `
                            <div class="row">
                                <div class="col">
                                    <label for="servico">Selecione o serviço*:</label>
                                    <select id="servico" name="servico">
                                        <option selected disabled value="">Selecione</option>
                                        <?php
                                        if ($rsServicos->num_rows > 0) {
                                            while ($coluna = $rsServicos->fetch_assoc()) {
                                                echo "<option value='" . $coluna['id'] . "'>" . $coluna['nome'] ."</option>";
                                            }
                                        } else {
                                            echo "<option value=''>Nenhum serviço encontrado</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="meta">Meta:*</label>
                                    <input type="number" id="meta" maxlength="5" step="0.01" placeholder="Informe a meta para o resgate do bônus.">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="dt_inicio">Data inicial*</label>
                                    <input id="dt_inicio" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?> max="2099-12-31"" required/>
                                    <label for="dt_final">Data final*</label>
                                    <input id="dt_final" type="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?> max="2099-12-31"" required/>
                                </div>
                            </div>
                        `,
                        showCloseButton: true,
                        showCancelButton: true,
                        confirmButtonText: 'Cadastrar',
                        didOpen: () => {
                            const metaInput = document.getElementById('meta');
                            
                            // Evento para limitar a digitação
                            metaInput.addEventListener('input', function() {
                                // Remove caracteres não numéricos
                                let value = this.value.replace(/[^\d.]/g, '');

                                // Limita a 3 dígitos antes da vírgula e 2 após
                                let regex = /^(\d{0,3})(\.\d{0,2})?$/;

                                if (regex.test(value)) {
                                    this.value = value;
                                } else {
                                    // Se não estiver no formato correto, apaga o último caractere
                                    this.value = this.value.slice(0, -1);
                                }
                            });

                            // Formatar para duas casas decimais ao sair do campo (blur)
                            metaInput.addEventListener('blur', function() {
                                if (this.value) {
                                    this.value = parseFloat(this.value).toFixed(2);
                                }
                            });
                        },
                        preConfirm: () => {
                            // Obter os dados do formulário
                            const servico = document.getElementById('servico').value;
                            const meta = parseFloat(document.getElementById('meta').value).toFixed(2);
                            const dt_inicio = document.getElementById('dt_inicio').value;
                            const dt_final = document.getElementById('dt_final').value;

                            // Enviar os dados via AJAX para bonusController.php
                            return fetch('../Controller/bonusController.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: `servico=${servico}&meta=${meta}&dt_inicio=${dt_inicio}&dt_final=${dt_final}&cadastrar=cadastrar`,
                            })
                            .then(response => response.json()) // Converta a resposta para JSON
                            .then(data => {
                                if (data.sucesso) {
                                    Swal.fire('Sucesso', data.mensagem, 'success')
                                    .then(() => {
                                        // Recarregar a página após a exclusão
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire('Erro', data.mensagem, 'error'); // Exibe mensagem de erro
                                }
                            })
                            .catch(error => {
                                Swal.fire('Erro', 'Ocorreu um erro ao salvar o bônus.', 'error');
                            });
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>