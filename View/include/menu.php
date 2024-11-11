<?php
    session_start();

    // Verifica se o usuário está logado
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit();
    }

    include_once "../Model/ContatoDAO.php";

    $id = $_SESSION['id'];
    $ContatoDAO = new ContatoDAO();

    // Verifica se o usuário é barbeiro
    $stFiltro = " WHERE barbeiro = 1 AND id =".$id;
    $rsBarbeiro = $ContatoDAO->recuperaTodos($stFiltro);

    $_SESSION['tipo_usuario'] = 'cliente';

    // Define o tipo de usuário e armazena na sessão
    if ($rsBarbeiro->num_rows > 0) {
        $_SESSION['tipo_usuario'] = 'barbeiro';
    }

    // Obtém a ação atual com base no nome do arquivo PHP
    $acaoAtual = basename($_SERVER['PHP_SELF'], '.php');

    // Verifica permissões apenas para o cliente
    if ($_SESSION['tipo_usuario'] === 'cliente' && !verificarPermissaoCliente($acaoAtual)) {
        header("Location: login.php");
        exit();
    }

    // Função para verificar as permissões do cliente
    function verificarPermissaoCliente($acao) {
        $permissoesCliente = ['agendamento', 'historico', 'fidelidade', 'home', 'editarContato'];

        // Verifica se a ação atual está nas permissões do cliente
        return in_array($acao, $permissoesCliente);
    }
?>

<style>
    html, body {
        height: 100%; /* Garante que ambos html e body tenham altura mÃ¯Â¿Â½nima de 100% */
        margin: 0; /* Remove a margem padrÃ¯Â¿Â½o */
        padding: 0; /* Remove o padding padrÃ¯Â¿Â½o */
    }

    body {
        font-family: 'Optima', serif;
        background: linear-gradient(to right, #2f2e37, #2f2e37);
        background-size: cover; /* Ajusta o gradiente para cobrir completamente o espaÃ¯Â¿Â½o, mesmo que seja maior que 100% 100% */
        background-repeat: repeat; /* O gradiente se repite */
    }

    .box {
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5); /* Sombra mais preta com opacidade maior */
        border-radius: 1em; /* Raio de borda de 2em */
        border: 0.1em solid #F8DE7E;
        position: relative; /* Define um contexto de posicionamento */
        margin: 2% auto; /* Margem de 5% em relaÃ¯Â¿Â½Ã¯Â¿Â½o ao contÃ¯Â¿Â½iner pai */
        padding: 5%; /* Preenchimento de 5% em relaÃ¯Â¿Â½Ã¯Â¿Â½o ao tamanho do contÃ¯Â¿Â½iner pai */
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

    /* Adicione um novo estilo para esconder a navbar quando a classe 'hidden' ÃÂÃÂÃÂÃÂ© aplicada */
    .navbar.hidden {
        transform: translateY(-100%);
    }

    .form-group {
        margin-bottom: 2rem; /* Aumenta o espaÃ§amento entre os campos de formulÃ¡rio */
    }

    .btn-primary {
        color: #212529;
        background-color: #B8860B;
        border-color: #F3B95F;
        transition: background-color 1s ease, border-color 1s ease, color 1s ease, box-shadow 1s ease; /* TransiÃÂÃÂ§ÃÂÃÂµes suavizadas */
    }

    .btn-primary:hover,
    .btn-primary:focus:hover {
        background-color: #F3B95F; /* Cor de fundo ao passar o mouse */
        border-color: #B8860B; /* Cor da borda ao passar o mouse */
    }

    /* Estado quando o botÃÂÃÂ£o estÃÂÃÂ¡ focado, mas nÃÂÃÂ£o ativo */
    .btn-primary:focus {
        background-color: #B8860B; /* Voltar ÃÂÃÂ  cor original apÃÂÃÂ³s soltar */
        border-color: #F3B95F; /* Voltar ÃÂÃÂ  cor original da borda apÃÂÃÂ³s soltar */
        box-shadow: none; /* Remover sombra ao focar, se desejado */
    }

    /* Estado quando o botÃÂÃÂ£o estÃÂÃÂ¡ sendo clicado e focado */
    .btn-primary:not(:disabled):not(.disabled):active,
    .btn-primary:not(:disabled):not(.disabled).active,
    .show>.btn-primary.dropdown-toggle {
        background-color: #F8DE7E; /* Cor durante o clique */
        border-color: #B8860B; /* Cor da borda durante o clique */
    }

    /* Estado quando o botÃÂÃÂ£o ÃÂÃÂ© solto apÃÂÃÂ³s o clique, mas ainda focado */
    .btn-primary:not(:disabled):not(.disabled):active:focus,
    .btn-primary:not(:disabled):not(.disabled).active:focus,
    .show>.btn-primary.dropdown-toggle:focus {
        background-color: #F8DE7E; /* Esta configuraÃÂÃÂ§ÃÂÃÂ£o determina a cor imediata apÃÂÃÂ³s soltar o botÃÂÃÂ£o, mas ainda focado */
        border-color: #B8860B;
        box-shadow: 0 0 0 .2rem rgba(91, 194, 194, 0.5);
    }

    /* Estilos adicionais para o btn-outline-primary, se necessÃÂÃÂ¡rio */
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
        background-color: #000; /* BotÃµes com fundo preto */
        color: #fff; /* Texto branco */
        border-color: #fff;
    }

    .high-contrast .box {
        background-color: #fff;
        color: #000;
        border-color: #000;
    }
</style>

<?php 
    include_once "../Model/AgendaDAO.php";
    include_once "../Model/ContatoDAO.php";

    $id = $_SESSION['id'];
    $ContatoDAO = new ContatoDAO();

    $stFiltro = " WHERE barbeiro = 1 AND id =".$id;
    $rsBarbeiro = $ContatoDAO->recuperaTodos($stFiltro);
?>

<nav class="navbar navbar-expand-lg navbar-dark">
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
                <?php if ($rsBarbeiro->num_rows > 0) { ?>
                    <a href="barbeiro.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'barbeiro.php' ? 'active' : ''); ?>">Barbeiro</a>
                    <a href="horario.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'horario.php' ? 'active' : ''); ?>">Horários</a>
                    <a href="servico.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'servico.php' ? 'active' : ''); ?>">Serviços</a>
                <?php } ?>

                <!-- Dropdown para o usuário -->
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_SESSION['nome']; ?> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a href="#" id="logout" class="dropdown-item" onclick="efetuarLogout()" style="color: #000 !important;">Logout</a>
                        <a href='editarContato.php?id=<?= $_SESSION['id']; ?>&location=menu' id="editar" class="dropdown-item" style="color: #000 !important;">Editar</a>
                        <a href="#" id="excluir" class="dropdown-item" onclick="confirmarExclusao('<?= $_SESSION['id']; ?>')" style="color: #000 !important;">Excluir</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- modal -->
<div id="modalLoading" class="modal-loading animated bounceIn"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<script src="js/scripts.js"></script>
<script>
    var userName = "<?= $_SESSION['nome']; ?>";
    const toggleContrastButton = document.getElementById('toggleContrastButton');
    const body = document.body;

    // FunÃ§Ã£o para alternar o modo de contraste
    toggleContrastButton.addEventListener('click', function() {
        body.classList.toggle('high-contrast'); // Alterna a classe high-contrast no body

        // Salvar a preferÃªncia de contraste no localStorage
        if (body.classList.contains('high-contrast')) {
            localStorage.setItem('contrastMode', 'high');
        } else {
            localStorage.setItem('contrastMode', 'normal');
        }
    });

    // Verifica o estado do contraste salvo no localStorage e aplica se necessÃ¡rio
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
            text: 'VocÃª realmente deseja excluir este cadastro?',
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

    // Função para criar a barra de pesquisa e anexá-la acima da tabela
    function createSearchBar(tableId) {
        // Cria o elemento de entrada de pesquisa
        var searchInput = document.createElement("input");
        searchInput.setAttribute("type", "text");
        searchInput.setAttribute("placeholder", "Pesquisar...");
        searchInput.classList.add("search-bar");  // Classe CSS para estilização
        searchInput.onkeyup = function() { filterTable(tableId, searchInput.value); };

        // Insere o campo de busca antes da tabela
        var table = document.getElementById(tableId);
        table.parentNode.insertBefore(searchInput, table);

        // Adiciona estilo básico
        var style = document.createElement("style");
        style.innerHTML = `
            .search-bar {
                width: 50%;
                padding: 8px;
                margin-bottom: 10px;
                box-sizing: border-box;
                font-size: 16px;
                float: right;
            }
        `;
        document.head.appendChild(style);
    }

    // Função para filtrar a tabela com base no valor de entrada
    function filterTable(tableId, query) {
        var filter = query.toUpperCase();
        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName("tr");

        // Loop para verificar se cada linha da tabela deve ser exibida
        for (var i = 1; i < rows.length; i++) { // Ignora o cabeçalho
            var cells = rows[i].getElementsByTagName("td");
            if (cells.length > 0) {
                var rowText = Array.from(cells).map(cell => cell.textContent || cell.innerText).join(" ");
                rows[i].style.display = rowText.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }

    // Função para criar a paginação e anexá-la abaixo da tabela
    function createPagination(tableId) {
        var table = document.getElementById(tableId);
        var rows = Array.from(table.getElementsByTagName("tbody")[0].getElementsByTagName("tr")) // Seleciona apenas as linhas do tbody
                        .filter(row => !row.classList.contains("more-info")); // Ignora as linhas ocultas

        var currentPage = 1;
        var rowsPerPage = 10;
        var totalPages = Math.ceil(rows.length / rowsPerPage);

        // Cria o elemento de paginação
        var paginationDiv = document.createElement("div");
        paginationDiv.classList.add("pagination-controls");

        // Cria o seletor de linhas por página
        var rowsPerPageSelect = document.createElement("select");
        rowsPerPageSelect.classList.add("rows-per-page-select");
        [5, 10, 20, 50].forEach(value => {
            var option = document.createElement("option");
            option.value = value;
            option.innerText = `${value}`;
            if (value === rowsPerPage) option.selected = true;
            rowsPerPageSelect.appendChild(option);
        });
        rowsPerPageSelect.onchange = function() {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            totalPages = Math.ceil(rows.length / rowsPerPage);
            renderTableWithPagination(rows, currentPage, rowsPerPage, pageDisplay, totalPages);
        };
        paginationDiv.appendChild(rowsPerPageSelect);

        // Container para os botões de navegação
        var navContainer = document.createElement("div");
        navContainer.classList.add("nav-buttons");

        // Cria o botão de página anterior
        var prevButton = document.createElement("button");
        prevButton.innerText = "Anterior";
        prevButton.onclick = function() { changePage(--currentPage); };
        navContainer.appendChild(prevButton);

        // Exibição do número da página atual
        var pageDisplay = document.createElement("span");
        pageDisplay.innerText = `Página ${currentPage} de ${totalPages}`;
        navContainer.appendChild(pageDisplay);

        // Cria o botão de próxima página
        var nextButton = document.createElement("button");
        nextButton.innerText = "Próximo";
        nextButton.onclick = function() { changePage(++currentPage); };
        navContainer.appendChild(nextButton);

        // Adiciona o container de navegação à área de paginação
        paginationDiv.appendChild(navContainer);

        // Insere o controle de paginação abaixo da tabela
        table.parentNode.insertBefore(paginationDiv, table.nextSibling);

        // Exibe a página inicial
        renderTableWithPagination(rows, currentPage, rowsPerPage, pageDisplay, totalPages);

        // Função para alternar as páginas e atualizar o número da página
        function changePage(page) {
            if (page < 1) currentPage = 1;
            if (page > totalPages) currentPage = totalPages;

            renderTableWithPagination(rows, currentPage, rowsPerPage, pageDisplay, totalPages);
        }
    }

    // Função para exibir apenas as linhas da página atual (excluindo as "more-info" inicialmente ocultas)
    function renderTableWithPagination(rows, page, rowsPerPage, pageDisplay, totalPages) {
        rows.forEach((row, index) => {
            row.style.display = (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) ? "" : "none";
        });
        pageDisplay.innerText = `Página ${page} de ${totalPages}`;
    }

    // CSS básico para a área de paginação com espaçamento ajustado
    var style = document.createElement("style");
    style.innerHTML = `
        .pagination-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .rows-per-page-select {
            margin-right: auto;
            padding: 5px;
        }
        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-buttons button {
            padding: 5px 10px;
        }
    `;
    document.head.appendChild(style);
</script>