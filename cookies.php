<!DOCTYPE html>
<html lang="en">

<head>
  <title>Política de Cookies - Pedra do Couto</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/temas/public/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="/temas/public/css/animate.css">

  <link rel="stylesheet" href="/temas/public/css/owl.carousel.min.css">
  <link rel="stylesheet" href="/temas/public/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="/temas/public/css/magnific-popup.css">

  <link rel="stylesheet" href="/temas/public/css/aos.css">

  <link rel="stylesheet" href="/temas/public/css/ionicons.min.css">
  <link rel="stylesheet" href="/temas/public/css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="/temas/public/css/jquery.timepicker.css">


  <link rel="stylesheet" href="/temas/public/css/flaticon.css">
  <link rel="stylesheet" href="/temas/public/css/icomoon.css">
  <link rel="stylesheet" href="/temas/public/css/style.css">
</head>

<body>
  <?php
  $fields["nome"] = $convite["convite_nome"];
  $fields["email"] = $convite["convite_email"];
  $fields["telemovel"] = $convite["convite_telemovel"];

  if (!empty($_POST) && (int) $convite["qrcode"] == 0) {
    $camposComErro = [];

    // Validar o campo Nome
    $nome = $fields["nome"] = $_POST['nome'];
    if (empty($nome)) {
      $camposComErro[] = "nome";
    }

    // Validar o campo E-mail
    $email = $fields["email"] = $_POST['email'];
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $camposComErro[] = "email";
    }

    // Validar o campo Telémovel
    $telemovel = $fields["telemovel"] = $_POST['telemovel'];
    if (empty($telemovel) || !preg_match("/^\d{9}$/", $telemovel)) {
      $camposComErro[] = "telemovel";
    }

    // Validar o campo Data de Nascimento
    $data_nascimento = $fields["data_nascimento"] = $_POST['data_nascimento'];
    if (empty($data_nascimento)) {
      $camposComErro[] = "data_nascimento";
    }
    // Validar o campo Data de Nascimento
    $termos_condicoes = $fields["termos_condicoes"] = $_POST['termos_condicoes'];
    if (empty($termos_condicoes)) {
      $camposComErro[] = "termos_condicoes";
    }
    $marketing = $fields["marketing"] = $_POST['marketing'];

    if (empty($camposComErro)) {
      $arrUpdate["nome"] = $fields["nome"];
      $arrUpdate["data_nascimento"] = $fields["data_nascimento"];
      $arrUpdate["telemovel"] = $fields["telemovel"];
      $arrUpdate["email"] = $fields["email"];
      $arrUpdate["qrcode"] = strtotime(date("now")) . $convite["id_rp"] . $convite["id_evento"] . $convite["id"];
      $arrUpdate["qrcode_data"] = 1;
      $arrUpdate["qrcode_ip"] = real_getip();
      $arrUpdate["qrcode_user_agent"] = $_SERVER["HTTP_USER_AGENT"];
      $arrUpdate["estado"] = 1;
      $arrUpdate["marketing"] = $marketing;
      $arrUpdate["termos_condicoes"] = $termos_condicoes;


      include_once($_SERVER["DOCUMENT_ROOT"] . '/administrador/entradas/ticket_generator.obj.php');
      $bilheteGenerator = new BilheteGenerator($db, $arrUpdate, $evento, $convite);
      $bilheteGenerator->generateAndSendTicket();
      header("Location: /ticket.php?hash=" . $_GET["hash"]);
      exit;
    }
  }
  ?>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="https://www.pedradocouto.net/" target="_blank"><img src="https://club.pedradocouto.net/wp-content/uploads/2022/11/NOVO-BRASA%CC%83O-1.png" /></a>
    </div>
  </nav>
  <!-- END nav -->

  <div class="hero-wrap ftco-degree-bg" style="background-image: url('/fotos/eventos/<?php echo $evento["imagem"]; ?>');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
        <div class="col-lg-8 ftco-animate">
          <div class="text w-100 text-center mb-md-5 pb-md-5">
            <h1 class="mb-4">Política de Cookies</h1>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="ftco-section ftco-no-pt bg-light  featured-top" style="padding-top:20px">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 text px-md-5 pt-4">

          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Política de Cookies</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">
            <p>Todas as suas informações pessoais recolhidas serão usadas para o ajudar a tornar a sua visita no nosso site ou app o mais produtiva e agradável possível.</p>
            <p>
              A garantia da confidencialidade dos dados pessoais dos utilizadores do nosso site ou app é importante para nós.
            </p>
            <p>
              Todas as informações pessoais relativas a utilizadores, clientes e visitantes da Pedra do Couto serão tratadas de acordo com a Política de Proteção de Dados.
            </p>
            <p>
              As informações pessoais recolhidas podem incluir o nome, e-mail, número de telefone, data de nascimento e/ou outros.
            </p>
            <p>
              O uso da plataforma Pedra do Couto pressupõe a aceitação deste acordo de privacidade. A nossa equipa reserva-se ao direito de alterar este acordo sem aviso prévio. Deste modo, recomendamos que consulte a nossa política de privacidade com regularidade para estar sempre atualizado.
            </p>

            <div class=" heading-section text-center ftco-animate mt-5">
              <span class="subheading"></span>
              <h2 class="mb-2">Os cookies e web beacons</h2>
            </div>
            <div class=" text px-md-5 pt-4">
              <p>
                Utilizamos cookies para armazenar informação, tais como as suas preferências. Em adição, também usamos publicidade de terceiros no nosso website para suportar os custos de manutenção da plataforma. Alguns destes publicitários poderão utilizar tecnologias como os cookies e/ou web beacons quando publicitam no nosso website ou app, o que fará como os mesmos também recebam a sua informação pessoal, como o endereço IP, ISP, browser, entre outros. Esta função é geralmente utilizada para geotargeting (mostrar publicidade de uma cidade específica apenas aos leitores pertencentes a essa cidade) ou apresentar publicidade direcionada a um tipo de utilizador (mostrar publicidade sobre um assunto que possa ser do interesse do utilizador).
              <p>
                O utilizador/cliente detém o poder de desligar os cookies nas opções do seu browser, ou efetuando alterações nas ferramentas de programas anti-vírus. No entanto, isso poderá alterar a forma como interage com o nosso website ou app, ou outros websites/apps. Também poderá afetar ou não permitir que faça login em programas, websites, apps ou fóruns da nossa e de outras redes.
              </p>
            </div>
          </div>
        </div>
      </div>
  </section>


  <footer class="ftco-footer ftco-bg-dark ftco-section">
    <div class="container">
      <div class="row mb-5">
        <div class="col-md">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2"><a href="https://www.pedradocouto.net" target="_blank" class="logo"><img src="https://club.pedradocouto.net/wp-content/uploads/2022/11/NOVO-BRASA%CC%83O-1.png" /></a></h2>
            <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
              <li class="ftco-animate"><a href="https://www.facebook.com/pedradocouto/"><span class="icon-facebook"></span></a></li>
              <li class="ftco-animate"><a href="https://www.instagram.com/pedradocouto/"><span class="icon-instagram"></span></a></li>
            </ul>
          </div>
        </div>
        <div class="col-md">
          <div class="ftco-footer-widget mb-4 ml-md-5">
            <h2 class="ftco-heading-2">Informações Gerais</h2>
            <ul class="list-unstyled">
              <li><a href="/termos_condicoes.php" target="_blank" class="py-2 d-block">Termos e condições</a></li>
              <li><a href="/politica_privacidade.php" target="_blank" class="py-2 d-block">Politica de privacidade</a></li>
              <li><a href="/cookies.php" target="_blank" class="py-2 d-block">Politica de cookies</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md">
          <div class="ftco-footer-widget mb-4">
            <h2 class="ftco-heading-2">Contactos</h2>
            <div class="block-23 mb-3">

              <ul>
                <li><span class="icon icon-map-marker"></span><a href="https://www.google.com/maps/dir//R.+Santo+Andr%C3%A9,+Portugal/@41.3280221,-8.4995652,17.54z/data=!4m8!4m7!1m0!1m5!1m1!1s0xd245f9dbf93a5bd:0x149f0798644e3937!2m2!1d-8.4984029!2d41.3281733" target="_blank" class="text">Rua Santo André, 229<br>4780-222<br>
                    Couto Santa Cristina<br>
                    Santo Tirso, Portugal</a></li>
                <li><a href="tel:+351913577141"><span class="icon icon-phone"></span><span class="text">+351 913 577 141</span></a></li>
                <li><a href="mailto:info@pedradocouto.net"><span class="icon icon-envelope"></span><span class="text">info@pedradocouto.net</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>



  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
      <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
      <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00" />
    </svg></div>


  <script src="/temas/public/js/jquery.min.js"></script>
  <script src="/temas/public/js/jquery-migrate-3.0.1.min.js"></script>
  <script src="/temas/public/js/popper.min.js"></script>
  <script src="/temas/public/js/bootstrap.min.js"></script>
  <script src="/temas/public/js/jquery.easing.1.3.js"></script>
  <script src="/temas/public/js/jquery.waypoints.min.js"></script>
  <script src="/temas/public/js/jquery.stellar.min.js"></script>
  <script src="/temas/public/js/owl.carousel.min.js"></script>
  <script src="/temas/public/js/jquery.magnific-popup.min.js"></script>
  <script src="/temas/public/js/aos.js"></script>
  <script src="/temas/public/js/jquery.animateNumber.min.js"></script>
  <script src="/temas/public/js/bootstrap-datepicker.js"></script>
  <script src="/temas/public/js/jquery.timepicker.min.js"></script>
  <script src="/temas/public/js/scrollax.min.js"></script>
  <script src="/temas/public/js/main.js"></script>

</body>

</html>
<?php
if ((int) $convite["qrcode"] > 0) {
  $conteudo_cache = ob_get_contents();

  ob_end_clean();

  file_put_contents($arquivo_cache, $conteudo_cache);

  echo $conteudo_cache;
}
?>