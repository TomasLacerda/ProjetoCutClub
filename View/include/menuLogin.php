<?php
session_start();
?>

<style>
    html, body {
        height: 100%; /* Garante que ambos html e body tenham altura mï¿½nima de 100% */
        margin: 0; /* Remove a margem padrï¿½o */
        padding: 0; /* Remove o padding padrï¿½o */
    }

    body {
        font-family: 'Georgia', serif;
        background: linear-gradient(to right, #2f2e37, #2f2e37);
        background-size: cover; /* Ajusta o gradiente para cobrir completamente o espaï¿½o, mesmo que seja maior que 100% 100% */
        background-repeat: repeat; /* O gradiente se repite */
    }

    #tituloBarbearia {
        font-family: 'Dancing Script', cursive;
        color: #F8DE7E;
        font-size: 3.2rem;
        text-align: center;
    }

    #subtitle {
        font-family: 'Dancing Script', cursive;
        color: #F8DE7E;
        font-size: 2.5rem;
        text-align: center;
    }

    .white-hr {
        border-color: white;
    }

    .box {
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5); /* Sombra mais preta com opacidade maior */
        border-radius: 1em; /* Raio de borda de 2em */
        border: 0.1em solid #F8DE7E;
        position: relative; /* Define um contexto de posicionamento */
        margin: 2% auto; /* Margem de 5% em relaï¿½ï¿½o ao contï¿½iner pai */
        padding: 5%; /* Preenchimento de 5% em relaï¿½ï¿½o ao tamanho do contï¿½iner pai */
        overflow: hidden; /* Garante que nada ultrapasse as bordas arredondadas */
        text-align: center;
    }

    .form-group {
        margin-bottom: 2rem;
    }

    @media (min-width: 600px) {
        .box {
            min-width: 400px; /* Tamanho mï¿½nimo para telas maiores */
            max-width: 50%; /* Reduz a largura mï¿½xima em telas grandes */
        }
    }

    #link a {
        color: #ffffff; /* Define a cor do link */
        text-decoration: none; /* Remove o sublinhado do link */
    }

    #link a:hover, #link a:focus {
        text-decoration: underline; /* Adiciona um sublinhado ao passar o mouse ou focar */
    }

    .legend-style {
        border-radius: 0.5em; /* Raio de borda de 0.5em */
        margin-top: -0.1em; /* Margem superior negativa para sobrepor a borda superior do .box */
        color: #000000;
        font-size: 1.4em;
        font-weight: bold;
        font-family: 'Georgia', serif;
        padding: 0.5em 1em; /* Preenchimento de 0.5em vertical e 1em horizontal */
        background-color: #F8DE7E;
    }

    .legend-middle {
        border-radius: 0.9em; /* Raio de borda de 0.5em */
        margin-top: -0.1em; /* Margem superior negativa para sobrepor a borda superior do .box */
        color: #000000;
        font-size: 1.2em;
        font-weight: bold;
        font-family: 'Georgia', serif;
        padding: 0.1em;
        background-color: #F8DE7E;
    }

    .quadro {
        display: block;
        text-decoration: none;
        border: 0.05em solid #ccc; /* Largura da borda de 0.05em */
        border-radius: 2em;
        box-shadow: 0 0.2em 0.5em rgba(0, 0, 0, 0.2); /* Sombra do box */
    }

    /* CSS para ajustar o posicionamento do pop-up */
    .custom-swal-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100vw;
        height: 100vh;
    }

    /* Estilos para a tabela */
    .table-custom {
        width: 100%; /* 100% da largura do contÃªiner pai */
        margin-top: 20px; /* EspaÃ§o entre a tabela e o resto do conteÃºdo */
    }

    .navbar {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        background-color: #0D2845;
        transform: translateY(0); /* Certifique-se de que a navbar nÃ£o esteja escondida por padrÃ£o */
        transition: transform 0.5s ease; /* Adicione a transiÃ§Ã£o */
    }

    .nav-link.active {
        color: #ebe5ed; /* Cor branca para o texto */
    }

    /* Adicione um novo estilo para esconder a navbar quando a classe 'hidden' Ã© aplicada */
    .navbar.hidden {
        transform: translateY(-100%);
    }

    .btn-primary {
        color: #212529;
        background-color: #B8860B;
        border-color: #F3B95F;
        transition: background-color 1s ease, border-color 1s ease, color 1s ease, box-shadow 1s ease; /* Transiï¿½ï¿½es suavizadas */
    }

    .btn-primary:hover,
    .btn-primary:focus:hover {
        background-color: #F3B95F; /* Cor de fundo ao passar o mouse */
        border-color: #B8860B; /* Cor da borda ao passar o mouse */
    }

    /* Estado quando o botï¿½o estï¿½ focado, mas nï¿½o ativo */
    .btn-primary:focus {
        background-color: #B8860B; /* Voltar ï¿½ cor original apï¿½s soltar */
        border-color: #F3B95F; /* Voltar ï¿½ cor original da borda apï¿½s soltar */
        box-shadow: none; /* Remover sombra ao focar, se desejado */
    }

    /* Estado quando o botï¿½o estï¿½ sendo clicado e focado */
    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active,
    .show>.btn-primary.dropdown-toggle {
        background-color: #F8DE7E; /* Cor durante o clique */
        border-color: #B8860B; /* Cor da borda durante o clique */
    }

    /* Estado quando o botï¿½o ï¿½ solto apï¿½s o clique, mas ainda focado */
    .btn-primary:not(:disabled):not(.disabled):active:focus,
    .btn-primary:not(:disabled):not(.disabled).active:focus,
    .show>.btn-primary.dropdown-toggle:focus {
        background-color: #F8DE7E; /* Esta configuraï¿½ï¿½o determina a cor imediata apï¿½s soltar o botï¿½o, mas ainda focado */
        border-color: #B8860B;
        box-shadow: 0 0 0 .2rem rgba(91, 194, 194, 0.5);
    }

    /* Estilos adicionais para o btn-outline-primary, se necessï¿½rio */
    .btn-outline-primary {
        color: #7cc;
        background-color: transparent;
        border-color: #7cc;
    }

    .btn-outline-primary:hover,
    .btn-outline-primary:focus {
        color: #222;
        background-color: #F8DE7E;
        border-color: #B8860B;
    }

    .btn-outline-primary:focus,
    .btn-outline-primary.focus {
        box-shadow: 0 0 0 .2rem rgba(119, 204, 204, 0.5);
    }

    .btn-outline-primary:not(:disabled):not(.disabled):active,
    .btn-outline-primary:not(:disabled):not(.disabled).active,
    .show>.btn-outline-primary.dropdown-toggle {
        background-color: #F8DE7E;
        border-color: #B8860B;
    }

    .btn-outline-primary:not(:disabled):not(.disabled):active:focus,
    .btn-outline-primary:not(:disabled):not(.disabled).active:focus,
    .show>.btn-outline-primary.dropdown-toggle:focus {
        box-shadow: 0 0 0 .2rem rgba(119, 204, 204, 0.5);
    }

    .or-divider {
        display: flex;
        align-items: center;
        text-align: center;
    }

    .or-divider::before,
    .or-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #ccc;
    }

    .or-text {
        padding: 0 10px;
    }
</style>

<!-- modal -->
<div id="modalLoading" class="modal-loading animated bounceIn"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="js/scripts.js"></script>
<script>
    // Evento de rolagem Ã  pÃ¡gina
    window.addEventListener('scroll', function() {
        // PosiÃ§Ã£o atual da janela de visualizaÃ§Ã£o
        var scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

        // Se a posiÃ§Ã£o de rolagem for maior que 100 pixels, adicione a classe 'hidden' Ã  navbar
        // Caso contrÃ¡rio, remova a classe 'hidden'
        if (scrollPosition > 100) {
            document.querySelector('.navbar').classList.add('hidden');
        } else {
            document.querySelector('.navbar').classList.remove('hidden');
        }
    });

    function confirmarExclusao(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'VocÃª realmente deseja excluir este cadastro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Se o usuÃ¡rio confirmar, redirecione para a pÃ¡gina de exclusÃ£o
                window.location.href = 'exclusaoContato.php?id=' + id + '&location=menu';

                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            }
        });
    }
</script>