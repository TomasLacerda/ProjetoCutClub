<!DOCTYPE html>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Agenda PHP</title>
    <!-- CSS do Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap-multiselect.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php
    include_once "include/menu.php";
    ?>

    <div class="container">
        <div class="box-wrapper">
            <div>
                <fieldset class="box">
                    <h1 id="subtitle">Seja Bem-Vindo</h1>
                    <div class="m-5"></div>
                    <a href="agendamento.php" class="quadro bg-light rounded p-4 mb-4 text-dark">
                        <h5><i class="fas fa-scissors mr-2"></i> Agendamento de Horários:</h5>
                        <span class="subtitle"> Agende seu horário por aqui.</span>
                    </a>
                    <a href="historico.php" class="quadro bg-light rounded p-4 mb-4 text-dark">
                        <h5><i class="fas fa-calendar-alt mr-2"></i> Histórico:</h5>
                        <span class="subtitle"> Consulte seu histórico de agendamentos por aqui.</span>
                    </a>
                    <a href="fidelidade.php" class="quadro bg-light rounded p-4 mb-4 text-dark">
                        <h5><i class="fas fa-star mr-2"></i> Plano Fidelidade:</h5>
                        <span class="subtitle"> Saiba tudo sobre o Plano Fidelidade e consulte seus pontos por aqui.</span>
                    </a>
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
    <script>
        //  function openPopup(url) {
        //      Swal.fire({
        //          html: '<iframe src="' + url + '" width="100%" height="100%" frameborder="0"></iframe>',
        //          backdrop: true,
        //          allowOutsideClick: true,
        //          customClass: {
        //              container: 'custom-swal-container',
        //              popup: 'custom-swal-popup',
        //              title: 'custom-swal-title'
        //          },
        //          showCloseButton: true, // Mostra o botão de fechar
        //          showConfirmButton: false // Esconde o botão padrão de confirmação
        //      });
        //      
        //      // Ajustar a largura e a altura do pop-up com base no tamanho da tela
        //      var width = window.innerWidth * 0.8; // 80% da largura da tela
        //      var height = window.innerHeight * 0.8; // 80% da altura da tela
        //      
        //      // Definir a largura e a altura do pop-up
        //      Swal.getPopup().style.width = width + 'px';
        //      Swal.getPopup().style.height = height + 'px';
        //  }
</script>
</body>
</html>
