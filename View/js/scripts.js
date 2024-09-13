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
                }, 2000);
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
    //            }, 2000);
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

function direcionaCadastrar() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "cadastro.php"
    }, 2000);
}

function voltarLogin() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "login.php"
    }, 2000);
}

function voltarBarbeiro() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "cadastrarBarbeiro.php"
    }, 2000);
}

function voltar() {
    carregarModalLoading();

    // Redirecionamento
    setTimeout(() => {
        window.location.href = "../"
    }, 2000);
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
            }, 2000);
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
                    }, 2000); // Aguarda 2 segundos antes de redirecionar
                }
            }, 1000); // Aguarda 1 segundo antes de executar o código
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