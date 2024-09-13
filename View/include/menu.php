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
        font-family: 'Optima', serif;
        background: linear-gradient(to right, #2f2e37, #2f2e37);
        background-size: cover; /* Ajusta o gradiente para cobrir completamente o espaï¿½o, mesmo que seja maior que 100% 100% */
        background-repeat: repeat; /* O gradiente se repite */
    }

    .box {
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5); /* Sombra mais preta com opacidade maior */
        border-radius: 1em; /* Raio de borda de 2em */
        border: 0.1em solid #F8DE7E;
        position: relative; /* Define um contexto de posicionamento */
        margin: 2% auto; /* Margem de 5% em relaï¿½ï¿½o ao contï¿½iner pai */
        padding: 5%; /* Preenchimento de 5% em relaï¿½ï¿½o ao tamanho do contï¿½iner pai */
        overflow: hidden; /* Garante que nada ultrapasse as bordas arredondadas */
        text-align: left;
    }

    #cadastrar {
        text-align: center;
    }

    @media (min-width: 600px) {
        .box {
            min-width: 400px;
            max-width: 50%;
        }
    }

    #subtitle {
        font-family: 'Dancing Script', cursive;
        color: #F8DE7E;
        font-size: 2.5rem;
        text-align: center;
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

    .table-custom {
        width: 100%;
        margin-top: 20px;
    }

    .navbar {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        background-color: #2f2e37;
        transform: translateY(0);
        transition: transform 0.5s ease;
    }

    .nav-link.active {
        color: #ebe5ed; /* Cor branca para o texto */
    }

    /* Adicione um novo estilo para esconder a navbar quando a classe 'hidden' ÃÂÃÂ© aplicada */
    .navbar.hidden {
        transform: translateY(-100%);
    }

    .form-group {
        margin-bottom: 2rem; /* Aumenta o espaçamento entre os campos de formulário */
    }

    .btn-primary {
        color: #212529;
        background-color: #B8860B;
        border-color: #F3B95F;
        transition: background-color 1s ease, border-color 1s ease, color 1s ease, box-shadow 1s ease; /* TransiÃÂ§ÃÂµes suavizadas */
    }

    .btn-primary:hover,
    .btn-primary:focus:hover {
        background-color: #F3B95F; /* Cor de fundo ao passar o mouse */
        border-color: #B8860B; /* Cor da borda ao passar o mouse */
    }

    /* Estado quando o botÃÂ£o estÃÂ¡ focado, mas nÃÂ£o ativo */
    .btn-primary:focus {
        background-color: #B8860B; /* Voltar ÃÂ  cor original apÃÂ³s soltar */
        border-color: #F3B95F; /* Voltar ÃÂ  cor original da borda apÃÂ³s soltar */
        box-shadow: none; /* Remover sombra ao focar, se desejado */
    }

    /* Estado quando o botÃÂ£o estÃÂ¡ sendo clicado e focado */
    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active,
    .show>.btn-primary.dropdown-toggle {
        background-color: #F8DE7E; /* Cor durante o clique */
        border-color: #B8860B; /* Cor da borda durante o clique */
    }

    /* Estado quando o botÃÂ£o ÃÂ© solto apÃÂ³s o clique, mas ainda focado */
    .btn-primary:not(:disabled):not(.disabled):active:focus,
    .btn-primary:not(:disabled):not(.disabled).active:focus,
    .show>.btn-primary.dropdown-toggle:focus {
        background-color: #F8DE7E; /* Esta configuraÃÂ§ÃÂ£o determina a cor imediata apÃÂ³s soltar o botÃÂ£o, mas ainda focado */
        border-color: #B8860B;
        box-shadow: 0 0 0 .2rem rgba(91, 194, 194, 0.5);
    }

    /* Estilos adicionais para o btn-outline-primary, se necessÃÂ¡rio */
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

    /* Estilos para o modo de alto contraste */
    .high-contrast body {
        background-color: #fff; /* Fundo branco */
        color: #000; /* Texto preto */
    }

    .high-contrast .navbar {
        background-color: #000; /* Fundo da navbar preto */
    }

    .high-contrast a.nav-link {
        color: #fff; /* Links brancos */
    }

    .high-contrast .btn-primary {
        background-color: #000; /* Botões com fundo preto */
        color: #fff; /* Texto branco */
        border-color: #fff;
    }

    .high-contrast .box {
        background-color: #fff;
        color: #000;
        border-color: #000;
    }
</style>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark">
    <button name='menu_select' class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu">
        <span class="navbar-toggler-icon"></span>
    </button>

    <a href="home.php" class="navbar-brand">
        <span>Barbearia313</span>
    </a>

<div class="collapse navbar-collapse justify-content-center" id="menu">
        <div class="navbar-header">
            <ul class="navbar-nav">
                <a href="home.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''); ?>">Home</a>
                <a href="barbeiro.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'barbeiro.php' ? 'active' : ''); ?>">Barbeiro</a>
                <a href="horario.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'horario.php' ? 'active' : ''); ?>">Horários</a>
                <a href="servico.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'servico.php' ? 'active' : ''); ?>">Serviços</a>
                <li>
                    <?php
                    if (isset($_SESSION['id'])) {
                        ?>
                        <li class="dropdown">
                            <a href="#" class="nav-link" data-toggle="dropdown">
                                <?= $_SESSION['nome']; ?> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" id="logout" onclick="efetuarLogout()">Logout</a>
                                </li>
                                <li>
                                    <a href='editarContato.php?id=<?= $_SESSION['id']; ?>&location=menu' id="editar">Editar</a>
                                </li>
                                <li>
                                    <a href="#" id="excluir" onclick="confirmarExclusao('<?= $_SESSION['id']; ?>')">Excluir</a>
                                </li>
                            </ul>
                        </li>
                        <?php
                    } else {
                        ?>
                        <a href="login.php">Login</a>
                        <?php
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- modal -->
<div id="modalLoading" class="modal-loading animated bounceIn"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="js/scripts.js"></script>
<script>
    const toggleContrastButton = document.getElementById('toggleContrastButton');
    const body = document.body;

    // Função para alternar o modo de contraste
    toggleContrastButton.addEventListener('click', function() {
        body.classList.toggle('high-contrast'); // Alterna a classe high-contrast no body

        // Salvar a preferência de contraste no localStorage
        if (body.classList.contains('high-contrast')) {
            localStorage.setItem('contrastMode', 'high');
        } else {
            localStorage.setItem('contrastMode', 'normal');
        }
    });

    // Verifica o estado do contraste salvo no localStorage e aplica se necessário
    window.addEventListener('load', function() {
        const contrastMode = localStorage.getItem('contrastMode');
        if (contrastMode === 'high') {
            body.classList.add('high-contrast');
        }
    });

    window.addEventListener('scroll', function() {
        var scrollPosition = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollPosition > 100) {
            document.querySelector('.navbar').classList.add('hidden');
        } else {
            document.querySelector('.navbar').classList.remove('hidden');
        }
    });

    function confirmarExclusao(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Você realmente deseja excluir este cadastro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'exclusaoContato.php?id=' + id + '&location=menu';

                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 3000);
            }
        });
    }
</script>