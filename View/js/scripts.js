function cadastrarContato() {
    dados = {
        'nome': $('#nome').val(),
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'telefone': $('#telefone').val(),
        'sobrenome': $('#sobrenome').val(),
        'cadastrar': $('#cadastrar').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'login.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function cadastrarBarbeiro() {
    dados = {
        'nome': $('#nome').val(),
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'telefone': $('#telefone').val(),
        'sobrenome': $('#sobrenome').val(),
        'cadastrarBarbeiro': $('#cadastrar').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'barbeiro.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function SalvarEditarContato() {
    // ObtÃÂ©m os valores dos checkboxes selecionados
    var boBarbeiro = [];
    $('input[name="boBarbeiro[]"]:checked').each(function() {
        boBarbeiro.push($(this).val());
    });

    // Define os dados a serem enviados
    dados = {
        'nome': $('#nome').val(),
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'telefone': $('#telefone').val(),
        'sobrenome': $('#sobrenome').val(),
        'boBarbeiro': boBarbeiro, // Usa o array com os valores dos checkboxes selecionados
        'editar': $('#editar').val()
    }

    // ParÃÂ¢metros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'home.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function cadastrarDatas() {
    // Obtenha o valor do campo de rádio 'dia_trabalho'
    var repetir = $('input[name="repetir"]:checked').val();

    // Crie o objeto 'dados' e inclua todas as outras propriedades
    var dados = {
        'dtFinal': $('#dt_final').val(),
        'hrFinal': $('#hrFinal').val(),
        'semana': $('#dia_semana').val(),
        'dtInicio': $('#dt_inicio').val(),
        'idBarbeiro': $('#barbeiro').val(),
        'descricao': $('#descricao').val(),
        'hrInicio': $('#hrInicio').val(),
        'repetir': repetir,
        'cadastrar': $('#cadastrar').val()
    };

    // Parâmetros Ajax
    var parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'horario.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    };

    // Chame a função para carregar o Ajax com os dados e parâmetros especificados
    carregarAjax(dados, parametros);
}

function cadastrarExpediente() {
    // Crie o objeto 'dados' e inclua todas as outras propriedades
    var dados = {
        'semana': $('#dia_semana').val(),
        'horaAbertura': $('#horaAbertura').val(),
        'horaFechamento': $('#horaFechamento').val(),
        'hrInicioItv': $('#hrInicioItv').val(),
        'hrFimItv': $('#hrFimItv').val(),
        'expediente': $('#cadastrar').val()
    };

    // Parâmetros Ajax
    var parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'cadastroHorarioBarbearia.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    };

    // Chame a função para carregar o Ajax com os dados e parâmetros especificados
    carregarAjax(dados, parametros);
}

function agendarServico() {
    // Captura os valores necessários
    var idBarbeiroSelecionado = $('#barbeiro').val();
    var dataSelecionada = $('#data').val();
    var horarioSelecionado = $('#horario').val();
    var idServicoSelecionado = $('#servicoCarrossel .carousel-item.active').data('id-service');
    var valorServicoSelecionado = $('#servicoCarrossel .carousel-item.active .card-text:contains("Valor:")').text().replace('Valor: R$', '').trim();
    
    // Verifica se o agendamento foi feito via bônus (fidelidade)
    var urlParams = new URLSearchParams(window.location.search);
    var agendamentoPorFidelidade = false;
    var pontosFidelidade = 0;

    if (urlParams.has('id_servico') && urlParams.has('meta')) {
        agendamentoPorFidelidade = true;
        pontosFidelidade = urlParams.get('meta');
        valorServicoSelecionado = 'Pago com seus pontos';  // Sobrescreve o valor com uma mensagem
    }

    // Monta o objeto de dados para enviar
    var dados = {
        'idBarbeiro': idBarbeiroSelecionado,
        'dtServico': dataSelecionada,
        'horario': horarioSelecionado,
        'idServico': idServicoSelecionado,
        'valor': valorServicoSelecionado,
        'agendar': $('#agendar').val(),
        'agendamentoPorFidelidade': agendamentoPorFidelidade,  // Indica se é via fidelidade
        'pontosFidelidade': pontosFidelidade  // Passa a quantidade de pontos
    };

    // Parâmetros de AJAX
    var parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'home.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    };

    // Chama a função carregarAjax para enviar os dados via AJAX
    carregarAjax(dados, parametros);
}

function cadastrarServico() {
    // Remova o 'R$ ' do valor antes de enviar
    var valor = $('#valor').val().replace('R$ ', '');

    // Construa um objeto FormData
    var formData = new FormData();
    formData.append('nome', $('#nome').val());
    formData.append('duracao', $('#duracao').val());
    formData.append('valor', valor);
    formData.append('imagem', $('#imagem')[0].files[0]);
    formData.append('descricao', $('#descricao').val());
    formData.append('cadastrarServico', $('#cadastrar').val());

    var parametros = {
        'urlBackEnd': '../Controller/atendimentoController.php',
        'location': 'servico.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    };

    // Realize a solicitação AJAX
    $.ajax({
        url: parametros.urlBackEnd,
        type: 'POST',
        data: formData,
        contentType: false, // Necessário para enviar arquivos
        processData: false, // Necessário para enviar arquivos
        success: function(response) {
            // Verifica se o retorno indica sucesso
            if (response.codigo === 1) {
                // Exibe mensagem de sucesso
                $('#status').text(response.mensagem).css('color', parametros.corSucesso);

                // Redireciona para a página de serviços após um breve intervalo
                setTimeout(function() {
                    window.location.href = parametros.location;
                }, 0);
            } else {
                // Caso contrário, exibe mensagem de erro
                $('#status').text(response.mensagem).css('color', parametros.corErro);
            }
        },
        error: function(xhr, status, error) {
            // Manipule o erro aqui
            console.error(xhr.responseText);
        }
    });
}

function cadastrarBonus() {
    // Remova o 'R$ ' do valor antes de enviar
    //var valor = $('#valor').val().replace('R$ ', '');
//
    console.log('cheguei aqui');
    //// Construa um objeto FormData
    //var formData = new FormData();
    //formData.append('nome', $('#nome').val());
    //formData.append('duracao', $('#duracao').val());
    //formData.append('valor', valor);
    //formData.append('imagem', $('#imagem')[0].files[0]);
    //formData.append('descricao', $('#descricao').val());
    //formData.append('cadastrarServico', $('#cadastrar').val());
//
    //var parametros = {
    //    'urlBackEnd': '../Controller/atendimentoController.php',
    //    'location': 'servico.php',
    //    'corSucesso': '#f1c40f',
    //    'corErro': '#ff6f65'
    //};
//
    //// Realize a solicitação AJAX
    //$.ajax({
    //    url: parametros.urlBackEnd,
    //    type: 'POST',
    //    data: formData,
    //    contentType: false, // Necessário para enviar arquivos
    //    processData: false, // Necessário para enviar arquivos
    //    success: function(response) {
    //        // Verifica se o retorno indica sucesso
    //        if (response.codigo === 1) {
    //            // Exibe mensagem de sucesso
    //            $('#status').text(response.mensagem).css('color', parametros.corSucesso);
//
    //            // Redireciona para a página de serviços após um breve intervalo
    //            setTimeout(function() {
    //                window.location.href = parametros.location;
    //            }, 1000);
    //        } else {
    //            // Caso contrário, exibe mensagem de erro
    //            $('#status').text(response.mensagem).css('color', parametros.corErro);
    //        }
    //    },
    //    error: function(xhr, status, error) {
    //        // Manipule o erro aqui
    //        console.error(xhr.responseText);
    //    }
    //});
}

function recuperarSenha() {
    var dados = {
        'email': $('#email').val(),
        'nome': $('#nome').val(),
        'apelido': $('#apelido').val(),
        'recuperar': true
    };

    $.ajax({
        url: '../Controller/contatoController.php', // Sua URL de backend
        method: 'POST',
        data: dados,
        dataType: 'json',
        success: function(response) {
            if (response.sucesso) {
                // Preenche o campo de senha com a senha recebida do servidor
                $('#nova-senha').val(response.senha);
                $('#nova-senha-group').show(); // Exibe o campo de senha
                $('#status').text(response.mensagem).css('color', parametros.corSucesso);
            } else {
                $('#status').text(response.mensagem).css('color', parametros.corErro);
            }
        },
        error: function() {
            $('#status').text(response.mensagem).css('color', parametros.corErro);
        }
    });
}

function direcionaCadastrar() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "cadastro.php"
    }, 0);
}

function voltarLogin() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "login.php"
    }, 0);
}

function voltarBarbeiro() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "cadastrarBarbeiro.php"
    }, 0);
}

function voltar() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "../"
    }, 0);
}

function efetuarLogin() {
    dados = {
        'email': $('#email').val(),
        'senha': $('#senha').val(),
        'login': $('#login').val()
    }

    // Parametros Ajax
    parametros = {
        'urlBackEnd': '../Controller/contatoController.php',
        'location': 'home.php',
        'corSucesso': '#f1c40f',
        'corErro': '#ff6f65'
    }
    carregarAjax(dados, parametros);
}

function efetuarLogout() {
    dados = {
        'logout': $('#logout').val()
    }

    carregarModalLoading();
    
    $.ajax({
        url: "../Controller/contatoController.php",
        type: 'POST',
        data: dados,
        success: function() {
            // Redirecionamento
            setTimeout(() => {
                window.location.href = "login.php"
            }, 0);
        }
    });
}

function carregarAjax(dados, parametros) {
    carregarModalLoading(); // Exibe o modal de loading

    $.ajax({
        url: parametros['urlBackEnd'],
        type: 'POST',
        data: dados,
        success: function(response) {
            // Converte a resposta em JSON, caso necessário
            var data = typeof response === 'object' ? response : JSON.parse(response);

            setTimeout(() => {
                esconderModalLoading(); // Esconde o modal de loading

                // Atualiza o status com a mensagem recebida
                $('#status').text(data.mensagem);

                // Aplica a cor de acordo com o código de retorno
                if (data.codigo == 1) { // Sucesso
                    $('#status').css("color", parametros['corSucesso']);
                } else { // Erro
                    $('#status').css("color", parametros['corErro']);
                }

                // Redirecionamento em caso de sucesso
                if (data.codigo == 1 && parametros['location']) {
                    console.log("Redirecionando para:", parametros['location']);
                    setTimeout(() => {
                        window.location.href = parametros['location'];
                    }, 0); // Aguarda 2 segundos antes de redirecionar
                }
            }, 0); // Aguarda 1 segundo antes de executar o código
        },
        error: function(xhr, status, error) {
            esconderModalLoading(); // Esconde o modal de loading em caso de erro
            $('#status').text('Erro na comunicação com o servidor.').css("color", parametros['corErro']);
            console.error("Erro na requisição AJAX:", error);
        }
    });
}

function carregarModalLoading() {
    $('#modalLoading').css({
        "display": "block"
    });
}

function esconderModalLoading() {
    $('#modalLoading').css({
        "display": "none"
    });
}

function createFooter() {
    var footer = document.createElement("footer");
    footer.classList.add("footer");

    // Exibe o nome do usuário à esquerda
    var userNameElement = document.createElement("span");
    userNameElement.classList.add("user-name");
    userNameElement.innerText = userName; // Certifique-se de definir `userName` anteriormente
    footer.appendChild(userNameElement);

    // Cria um contêiner para agrupar o botão de ajuda e o botão home à direita
    var buttonContainer = document.createElement("div");
    buttonContainer.classList.add("button-container");

    // Cria o botão de ajuda como um link com o ícone
    var helpButton = document.createElement("a");
    helpButton.href = "#"; // Mantém a navegação sem recarregar a página
    helpButton.classList.add("help-button");
    helpButton.innerHTML = '<i class="fa-solid fa-circle-question"></i>';
    helpButton.title = "Clique para obter ajuda";
    buttonContainer.appendChild(helpButton);

    // Cria o botão "Home" com o ícone e redirecionamento
    var homeButton = document.createElement("a");
    homeButton.href = "home.php";
    homeButton.classList.add("home-button");
    homeButton.innerHTML = '<i class="fa-solid fa-house"></i>';
    homeButton.title = "Ir para a página inicial";
    buttonContainer.appendChild(homeButton);

    // Adiciona o contêiner de botões ao rodapé, alinhado à direita
    footer.appendChild(buttonContainer);
    document.body.appendChild(footer);

    // Configura o evento de clique do botão de ajuda para exibir o painel
    helpButton.addEventListener("click", (event) => {
        event.preventDefault(); // Evita o comportamento padrão do link
        const helpPanel = document.querySelector(".help-panel");
        helpPanel.classList.toggle("active"); // Exibe ou oculta o painel
    });
}

// Função para criar o painel de ajuda fixo
function createHelpPanel() {
    const helpContent = {
        barbeiros: [
            "",
            "Esta é a lista de barbeiros.",
            "Use o botão 'Cadastrar' para adicionar um novo barbeiro.",
            "Clique em um barbeiro para ver mais detalhes e opções.",
            "O botão 'Voltar' permite retornar à tela anterior."
        ],
        home: [
            "",
            "Bem-vindo à tela inicial! Aqui, você encontrará as principais funcionalidades do sistema para gerenciar seus agendamentos, histórico de serviços, pontos de fidelidade e o plano de fidelidade.",
            "Agendamento de Horários: Clique nesta opção para agendar novos horários de atendimento.",
            "Serviços: Acesse o histórico dos seus serviços e agendamentos.",
            "Pontos de Fidelidade: Visualize seus pontos acumulados no programa de fidelidade.",
            "Plano Fidelidade: Conheça todos os detalhes do plano de fidelidade, como regras e benefícios. Consulte seu saldo atual de pontos e saiba como aproveitá-los."
        ],
        agendamento: [
            "",
            "A tela está dividida em etapas para guiar você no processo de agendamento de forma organizada e prática.",
            "Selecione o Serviço: Navegue pelos serviços disponíveis no carrossel ou na lista e escolha o serviço desejado. Clique em OK para confirmar.",
            "Selecione a Data: Escolha a data desejada para o agendamento usando o calendário e confirme clicando em OK.",
            "Selecione o Barbeiro: Escolha o barbeiro desejado a partir do menu suspenso e clique em OK para avançar.",
            "Selecione o Horário: Escolha o horário disponível e confirme a seleção clicando em OK.",
            "Resumo do Agendamento: Revise as informações do agendamento, incluindo serviço, data, barbeiro e horário. Clique em Agendar para confirmar ou em Voltar para ajustar.",
            "Confirmação e Status: Após clicar em Agendar, uma mensagem de status confirmará o sucesso do agendamento ou alertará sobre qualquer problema."
        ],
        historico: [
            "",
            "Esta tela permite que você visualize e filtre os serviços agendados e o histórico de atendimento.",
            "Tabela de Serviços Agendados: Na primeira tabela, você encontrará uma lista dos serviços agendados com informações sobre data e hora, cliente, serviço e barbeiro. Clique em qualquer linha para ver mais detalhes sobre o agendamento.",
            "Seção Histórico: Esta seção exibe o histórico de serviços e permite que você aplique filtros para refinar os resultados.",
            "Filtros de Data: Selecione a Data Início e a Data Fim para restringir o período dos serviços exibidos no histórico. Insira as datas e clique em 'Aplicar Filtros'.",
            "Filtro de Serviço: Use o menu suspenso para escolher um serviço específico. Apenas os serviços correspondentes ao filtro selecionado serão exibidos no histórico.",
            "Histórico Filtrado: A tabela exibirá os serviços realizados dentro do período e com o serviço selecionado.",
            "Mensagem de Status: Caso nenhum serviço seja encontrado para os filtros aplicados, uma mensagem indicará que não há registros disponíveis."
        ],
        pontosFidelidade: [
            "Esta tela permite que você gerencia os pontos associados aos seus serviços.",
            "Tabela de Pontos Fidelidade: A tabela exibe uma lista de agendamentos, mostrando a data e hora, junto com opções de confirmação ou negação.",
            "Coluna Confirmar: Para confirmar um serviço, clique no ícone de check para confirmar o serviço realizado.",
            "Coluna Negar: Se deseja negar um serviço, clique no ícone de X para negar que o serviço foi realizado.",
            "Informações Detalhadas: Clique em qualquer linha da tabela para exibir detalhes adicionais do agendamento.",
            "Ação de Confirmar: A confirmação de um serviço atualizará os pontos de fidelidade do cliente.",
            "Ação de Negar: A negação de um serviço deixará o cliente sem os pontos de fidelidade, significa que o cliente não compareceu no serviço."
        ],
        planoFidelidade: [
            "Esta tela permite que você visualize as regras de pontos, saldo atual e os bônus disponíveis para resgate.",
            "Seus Pontos: Nesta seção, você verá seu saldo atual de pontos acumulados.",
            "Bônus Disponíveis: Esta lista exibe os bônus que você pode resgatar com seus pontos. Se você tiver pontos suficientes, clique no bônus desejado para iniciar o resgate.",
            "Bônus Indisponíveis: Se você não tiver pontos suficientes para resgatar um bônus, ele será exibido como inativo. Você precisará acumular mais pontos para resgatá-lo.",
            "Ações Administrativas (para Administradores): Como administrador, você verá ícones de edição e exclusão ao lado de cada bônus. Clique no ícone de lápis para editar o bônus ou no ícone de lixeira para removê-lo.",
            "Cadastrar Bônus (para Administradores): Como administrador, verá o botão 'Cadastrar Bônus'. Clique neste botão para adicionar novos bônus ao sistema."
        ],
        cadastrarBarbeiro: [
            "Esta tela permite que você selecione um funcionário com cadastro existente ou crie um novo cadastro.",
            "Instruções de Cadastro: Para incluir um novo funcionário, selecione o cadastro dele na tabela abaixo e clique em 'Cadastrar'.",
            "Novo Cadastro: Caso o funcionário ainda não esteja cadastrado, clique no link para 'criar um novo cadastro'. Isso o redirecionará para a tela de criação de um novo funcionário.",
            "Tabela de Funcionários: Abaixo, você encontrará uma lista dos cadastros ativos, com suas informações básicas. Clique em um funcionário para visualizar detalhes adicionais.",
            "Voltar: Se desejar retornar à tela anterior sem fazer alterações, clique no botão 'Voltar'."
        ],
        cadastro: [
            "Esta tela permite que você registre um novo barbeiro no sistema.",
            "Nome*: Informe o nome do barbeiro no campo indicado. Este campo é obrigatório.",
            "Sobrenome*: Insira o sobrenome do barbeiro. Este campo é obrigatório.",
            "E-mail*: Digite o e-mail do barbeiro para que ele possa receber notificações e atualizações. Este campo é obrigatório e deve seguir o formato de e-mail válido.",
            "Telefone*: Informe o número de telefone do barbeiro. Este campo é obrigatório.",
            "Senha*: Defina uma senha para o barbeiro. Este campo é obrigatório e será utilizado para o acesso ao sistema.",
            "Cadastrar: Após preencher todos os campos obrigatórios, clique no botão 'Cadastrar' para concluir o registro.",
            "Voltar: Caso deseje retornar à tela anterior sem salvar, clique no botão 'Voltar'."
        ],
        horario: [
            "Esta tela permite você visualizar o expediente, incluindo intervalos de cada dia da semana.",
            "Tabela de Horários: A tabela exibe as informações do expediente.",
            "Dia Semana: Mostra o dia da semana inicial e final correspondente ao expediente listado.",
            "Início: Indica a hora de início do expediente.",
            "Fim: Informa o horário de término do expediente.",
            "Início Intervalo: Mostra o horário de início do intervalo.",
            "Fim Intervalo: Indica o horário de término do intervalo.",
            "Botão 'Cadastrar': Clique para adicionar novos horários de expediente. Isso levará você à tela de cadastro de expediente.",
            "Botão 'Voltar': Utilize este botão para retornar à tela anterior sem fazer alterações."
        ],
        cadastroHorarioBarbearia: [
            "Esta tela permite você definir os dias e horários de funcionamento, incluindo intervalos.",
            "Seleção do Dia da Semana: Escolha um ou mais dias da semana para aplicar o expediente.",
            "Campo 'Abertura': Defina o horário de abertura, ou seja, o início do expediente para o(s) dia(s) selecionado(s).",
            "Campo 'Fechamento': Insira o horário de fechamento do expediente para o(s) dia(s) selecionado(s).",
            "Campo 'Início Intervalo': Se houver uma pausa durante o expediente, indique o horário de início do intervalo. Este campo é opcional.",
            "Campo 'Fim Intervalo': Informe o horário de término do intervalo, se houver. Também é opcional.",
            "Botão 'Cadastrar': Após preencher todos os campos obrigatórios, clique em 'Cadastrar' para salvar o expediente configurado.",
            "Botão 'Voltar': Use este botão para retornar à tela anterior sem salvar as alterações."
        ],
        servico: [
            "Esta tela permite você visualizar e gerenciar os serviços oferecidos.",
            "Tabela de Serviços: Esta tabela exibe todos os serviços cadastrados.",
            "Visualizar Detalhes do Serviço: Clique em qualquer linha da tabela para visualizar mais detalhes do serviço.",
            "Excluir Serviço: Nos detalhes expandidos de cada serviço, há um ícone de lixeira em vermelho para exclusão. Clique no ícone para excluir o serviço selecionado.",
            "Botão 'Cadastrar': Para adicionar um novo serviço, clique no botão 'Cadastrar' na parte inferior. Você será redirecionado para a tela de cadastro de serviços.",
            "Botão 'Voltar': Utilize o botão 'Voltar' para retornar à página anterior sem realizar alterações."
        ],
        cadastrarServico: [
            "Esta tela permite você registrar um novo serviço no sistema.",
            "Nome do Serviço*: Preencha o nome do serviço a ser cadastrado neste campo. Este é um campo obrigatório.",
            "Valor do Serviço*: Informe o valor do serviço em reais. Este campo também é obrigatório.",
            "Duração do Serviço*: Insira a duração do serviço no formato horas:minutos. Por exemplo, 01:30 para uma hora e meia. Este é um campo obrigatório.",
            "Descrição: Utilize este campo para fornecer detalhes adicionais sobre o serviço. Este campo é opcional, mas pode ser útil para informações complementares.",
            "Imagem do Serviço: Selecione uma imagem que represente o serviço. Aceita formatos de imagem como JPEG, PNG, etc. Este campo é opcional.",
            "Botão 'Cadastrar': Após preencher todos os campos obrigatórios, clique em 'Cadastrar' para salvar o novo serviço no sistema.",
            "Botão 'Voltar': Use o botão 'Voltar' para retornar à página anterior sem salvar as informações."
        ],
        editarContato: [
            "Esta tela permite você atualizar suas informações de perfil.",
            "Nome Completo: Edite seu nome completo no campo fornecido.",
            "Apelido: Adicione ou altere o apelido desejado.",
            "E-mail: Verifique ou altere o e-mail cadastrado, que será usado para comunicações.",
            "Telefone: Atualize seu número de telefone para contato. O formato será ajustado automaticamente.",
            "Senha: Por motivos de segurança, sua senha atual não pode ser visualizada. Para sua proteção, oferecemos apenas a opção de alteração da senha.",
            "Para redefinir sua senha, insira uma nova e clique em 'Salvar'. Assim, sua senha será atualizada de forma segura.",
            "Botões: Após atualizar os campos desejados, clique em 'Salvar' para confirmar as alterações. Para retornar à página anterior, clique em 'Voltar'."
        ],
    };

    const pageId = document.body.getAttribute("data-page-id");
    console.log("Page ID:", pageId);
    const steps = helpContent[pageId] || ["Ajuda indisponível para esta tela."];

    // Cria o painel de ajuda fixo
    const helpPanel = document.createElement("div");
    helpPanel.classList.add("help-panel");
    console.log("Painel de ajuda criado");

    // Adiciona os passos da ajuda como lista
    const helpList = document.createElement("ul");
    steps.forEach(step => {
        const listItem = document.createElement("li");
        listItem.innerText = step;
        helpList.appendChild(listItem);
    });
    helpPanel.appendChild(helpList);



    document.body.appendChild(helpPanel);

    // Fecha o painel se clicar fora dele
    document.addEventListener("click", function(event) {
        if (!helpPanel.contains(event.target) && !event.target.classList.contains("help-button")) {
            helpPanel.classList.remove("active");
        }
    });
}

// CSS básico para o rodapé e contêiner de botões
var style = document.createElement("style");
style.innerHTML = `
    .footer {
        background-color: #f8f9fa;
        color: #333;
        padding: 5px 10px;
        border-top: 1px solid #ccc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 1000;
    }
    .user-name { margin-left: 10px; }
    .button-container { display: flex; gap: 10px; }
    .help-button, .home-button {
        background: none;
        color: #333;
        border: none;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px; /* Tamanho mínimo para área clicável */
        height: 40px; /* Tamanho mínimo para área clicável */
        padding: 0;
        margin: 0; /* Remove margens que possam causar deslocamento */
        transition: color 0.3s ease;
    }

    .help-button:hover, .home-button:hover {
        color: #007bff;
    }

    .help-panel {
        position: fixed;
        top: 50%; /* Centraliza verticalmente */
        right: 20px; /* Alinha ao lado direito */
        transform: translateY(-50%); /* Ajusta para o centro exato vertical */
        width: 350px; /* Aumenta a largura */
        height: 500px; /* Aumenta a altura */
        padding: 20px; /* Espaço interno maior */
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
        display: none; /* Inicialmente oculto */
        opacity: 0;
        z-index: 1000;
        transition: opacity 0.3s ease;
        font-size: 16px; /* Aumenta o tamanho da fonte */
    }

    .help-panel.active {
        display: block; /* Exibe o painel */
        opacity: 1;
    }

    .help-panel ul {
        list-style-type: none;
        padding: 0;
    }

    .help-panel li {
        margin-bottom: 10px;
        font-size: 16px; /* Aumenta o tamanho da fonte nos itens de lista */
        color: #333;
    }
    .close-help {
        position: absolute;
        top: 10px; /* Define a posição superior */
        right: 10px; /* Define a posição à direita */
        transform: none; /* Garante que não haverá centralização */
        background: transparent;
        border: none;
        color: #888;
        font-size: 16px;
        cursor: pointer;
    }
    function toggleHelpPanel() {
        const helpPanel = document.querySelector(".help-panel");
        helpPanel.classList.add("active");
    }

    document.querySelector(".help-button").addEventListener("click", (event) => {
        event.preventDefault();
        toggleHelpPanel();
    });
`;
document.head.appendChild(style);

// Chamadas para iniciar o rodapé e o painel de ajuda
window.addEventListener("load", function () {
    createFooter();
    createHelpPanel();
});