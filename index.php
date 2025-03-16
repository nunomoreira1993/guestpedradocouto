<?php
// Alterar o idioma para português
setlocale(LC_TIME, 'pt_PT.utf-8');
if (session_id() == '') {
  session_start();
}
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
date_default_timezone_set("Europe/Lisbon");

if (preg_match('/^[a-f0-9]{32}$/i', $_GET["hash"]) || (preg_match('/^[a-f0-9]{32}$/i', $_GET["evento"]) && preg_match('/^[a-f0-9]{32}$/i', $_GET["rp"]))) {

  require_once($_SERVER['DOCUMENT_ROOT'] . '/ajax/methods.obj.php');
  $dbmethods = new methods($_GET["hash"], $_GET["evento"], $_GET["rp"]);

  if ($_GET["hash"]) {
    $convite = $dbmethods->getConviteByHash();
	// pr($convite);
    if ((int) $convite["data"]["id_evento"] > 0) {

      $evento = $dbmethods->getEventoByID($convite["data"]["id_evento"]);
	// pr($evento);
      if (!empty($evento)) {

        $evento["data"]["nome_rp"] = $dbmethods->getNomeRP($convite["data"]["id_rp"])["data"];

        $fields["nome"] = $convite["data"]["convite_nome"];
        $fields["email"] = $convite["data"]["convite_email"];
        $fields["telemovel"] = $convite["data"]["convite_telemovel"];
      }
    }
  } else if ($_GET["rp"] && $_GET["evento"]) {
    $evento = $dbmethods->getEventoByMD5();
    $rp = $dbmethods->getRPByMD5();

    $evento["data"]["nome_rp"] = $dbmethods->getNomeRP($rp["data"]["id"])["data"];
  }

  if (!empty($evento["data"]) && $evento["status"] != "error") {

    if (date('H') < 14) {
      $data = date('Y-m-d', strtotime('-1 day'));
    } else {
      $data = date('Y-m-d');
    }

    $expired = 0;
    if ($evento["data"]["data"] < $data) {
      $expired = 1;
    }
    if ($evento["data"]["data"]) {
      $evento["data"]["data_extenso"] = $dbmethods->formatarDataPortugues($evento["data"]["data"]);
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  if ($evento["status"] == "success") {
  ?>
    <title><?php echo $evento["data"]["nome"]; ?> - Pedra do Couto</title>
  <?php
  } else {
  ?>
    <title>Page Not Found - Pedra do Couto</title>
  <?php
  }
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <link rel="apple-touch-icon" sizes="180x180" href="/temas/login/imagens/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="194x194" href="/temas/login/imagens/favicons/favicon-194x194.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/temas/login/imagens/favicons/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/temas/login/imagens/favicons/favicon-16x16.png">
    <link rel="manifest" href="/temas/login/imagens/favicons/site.webmanifest">
    <link rel="mask-icon" href="/temas/login/imagens/favicons/safari-pinned-tab.svg" color="#000000">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/temas/login/imagens/favicons/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">
	
  <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.3/build/css/intlTelInput.css">

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
  <link rel="stylesheet" href="/temas/public/css/style.css?v=1.1.1">
</head>

<body>
  <?php
  if (!empty($_POST) && (int) $convite["qrcode"] == 0) {
    $camposComErro = [];

    // Validar o campo Nome
    $nome = $fields["nome"] = $_POST['nome'];
    if (empty($nome)) {
      $camposComErro[] = "nome";
    }

    // Validar o campo E-mail
    $email = $fields["email"] = $_POST['email'];
    if (!empty($email)) {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $camposComErro[] = "email";
      }
    }

    // Validar o campo Telémovel
    $telemovel_validation = $fields["telemovel_validation"] = $_POST['telemovel_validation'];
    if (empty($telemovel_validation) || !preg_match("/^\+?\d{9,15}$/", $telemovel_validation)) {
      $camposComErro[] = "telemovel_validation";
    }

    // Validar o campo Telémovel
    $telemovel = $fields["telemovel"] = $_POST['telemovel'];
    if (empty($telemovel) || !preg_match("/^\+?\d{9,15}$/", $telemovel)) {
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

      $send = $dbmethods->sendForm($fields);

      if ($send["status"] == "error") {
        $camposComErro = $send["message"];
      } else {
        if ($send["data"]["hash"]) {
          header("Location: /index.php?hash=" . $send["data"]["hash"]);
          exit;
        }
      }
    }
  }
  ?>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand" href="https://www.pedradocouto.net/" target="_blank"><img src="https://club.pedradocouto.net/wp-content/uploads/2022/11/NOVO-BRASA%CC%83O-1.png" /></a>
    </div>
  </nav>
  <!-- END nav -->

  <div class="hero-wrap ftco-degree-bg" <?php if ($evento["data"]["imagem"]) { ?> style="background-image: url('https://www.staffpedradocouto.com/fotos/eventos/<?php echo $evento["data"]["imagem"]; ?>');" <?php } ?> data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
      <div class="row no-gutters slider-text justify-content-start align-items-center justify-content-center">
        <div class="col-lg-8 ftco-animate">
          <?php
          if (($evento["status"] == "success") && ($convite["status"] == "success" || ($rp["status"] == "success" && $_GET["evento"] && $_GET["rp"]))) {
          ?>
            <div class="text w-100 text-center mb-md-5 pb-md-5">
              <h1 class="mb-4"><?php echo $evento["data"]["nome"]; ?></h1>
              <p style="font-size: 18px;"><?php echo $evento["data"]["data_extenso"]; ?></p>
              <a href="#" class="icon-wrap  d-flex align-items-center mt-4 justify-content-center">
                <div class="heading-title ml-5">
                  <span>Guest List - <?php echo $evento["data"]["nome_rp"]; ?></span>
                </div>
              </a>
            </div>
          <?php
          } else {
          ?>
            <div class="text w-100 text-center mb-md-5 pb-md-5">
              <h1 class="mb-4">404 - Página não encontrada</h1>
              <p style="font-size: 18px;">Este endereço do seu convite não está correcto.</p>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <?php
  if (($convite["status"] == "success" || ($rp["status"] == "success" && $_GET["evento"] && $_GET["rp"])) && $evento["status"] == "success" && $expired == 0 && (int) $convite["data"]["qrcode"] == 0) {
  ?>
    <section class="ftco-section ftco-no-pt bg-light">
      <div class="container">
        <div class="row no-gutters">
          <div class="col-md-12	featured-top">
            <div class="row no-gutters column-search">
              <div class="col-md-4 d-flex align-items-center">
                <form action="" method="POST" class="request-form ftco-animate bg-primary">
                  <h2>Recebe o teu bilhete</h2>
                  <input type="hidden" name="hash" id="input-hash" value="<?php echo $_GET["hash"]; ?>"/>
                  <input type="hidden" name="hash_evento" id="input-evento" value="<?php echo $_GET["evento"]; ?>"/>
                  <input type="hidden" name="hash_rp"  id="input-rp" value="<?php echo $_GET["rp"]; ?>"/>
                  <input type="hidden" name="valid_phone"  value="<?php echo $fields["telemovel_validation"]; ?>"/>
                  <div class="step1 <?php if(!empty($fields["telemovel_validation"]) && !in_array("telemovel_validation", $camposComErro)) { ?> d-none <?php } ?>">
                    <div class="form-group <?php if (in_array("telemovel_validation", $camposComErro)) { ?>error<?php } ?>">
                      <label for="telemovel_validation" class="label">Telémovel <span class="required">*</span></label>
                      <input type="tel" class="form-control" name="telemovel_validation" id="telemovel_validation" value="<?php echo $fields["telemovel_validation"]; ?>" placeholder="Telémovel">
                      <span class="error-message" id="error-message-telemovel-validation">Deve preencher um número de telemovel válido.</span>
                    </div>
                  </div>
                  <div class="step2 <?php if(empty($fields["telemovel_validation"]) || in_array("telemovel_validation", $camposComErro)) { ?> d-none <?php } ?>">
                    <div class="form-group <?php if (in_array("nome", $camposComErro)) { ?>error<?php } ?>">
                      <label for="nome" class="label">Nome <span class="required">*</span></label>
                      <input type="text" name="nome" id="nome" value="<?php echo $fields["nome"]; ?>" <?php if ($fields["nome"]) { ?> readonly <?php } ?> class="form-control" placeholder="O teu nome">
                      <span class="error-message">O campo Nome é obrigatório.</span>
                    </div>
                    <div class="form-group <?php if (in_array("email", $camposComErro)) { ?>error<?php } ?>">
                      <label for="email" class="label">E-mail</label>
                      <input type="text" id="email" class="form-control" name="email" value="<?php echo $fields["email"]; ?>" placeholder="meueamail@domain.com">
                      <span class="error-message">Deve preencher um e-mail válido.</span>
                    </div>
                    <div class="d-flex">
                      <div class="form-group mr-2 <?php if (in_array("telemovel", $camposComErro)) { ?>error<?php } ?>">
                        <label for="telemovel" class="label">Telémovel <span class="required">*</span></label>
                        <input type="tel" class="form-control" name="telemovel" readonly value="<?php echo $fields["telemovel"]; ?>" id="telemovel" placeholder="Telémovel">
                        <span class="error-message">Deve preencher um número de telemovel válido.</span>
                      </div>
                      <div class="form-group ml-2 <?php if (in_array("data_nascimento", $camposComErro)) { ?>error<?php } ?>">
                        <label for="data_nascimento" class="label">Data de Nascimento <span class="required">*</span></label>
                        <input type="text" name="data_nascimento" class="form-control" maxlength="10" pattern="\d{2}-\d{2}-\d{4}" value="<?php echo $fields["data_nascimento"]; ?>" id="data_nascimento" placeholder="dd-mm-AAAA">
                        <span class="error-message">O campo Data de Nascimento é obrigatório no formato dd-mm-AAAA</span>
                      </div>
                    </div>
                    <div class="form-group custom-checkbox <?php if (in_array("termos_condicoes", $camposComErro)) { ?>error<?php } ?>">
                      <input class="custom-control-input" type="checkbox" value="1" <?php echo $fields["termos_condicoes"] == 1 ? "checked" : ""; ?> name="termos_condicoes" id="termos_condicoes">
                      <label class="custom-control-label" for="termos_condicoes">
                        Ao continuar, autorizo ​​o tratamento de dados pessoais nos termos da lei em vigor, conforme descrito nos nossos <a href="/termos_condicoes.php" target="_blank">termos e condições</a> e <a href="/termos_condicoes.php" target="_blank">política de privacidade </a> que tomei conhecimento. <span class="required">*</span>
                      </label>
                      <span class="error-message">Deve aceitar os termos e condições para prosseguir.</span>
                    </div>
                    <div class="form-group custom-checkbox">
                      <input class="custom-control-input" type="checkbox" value="1" name="marketing" id="marketing">
                      <label class="custom-control-label" for="marketing">
                        Concordo em receber ofertas e novidades da Pedra do Couto.
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="submit" value="Continuar" class="btn btn-secondary py-3 px-4">
                  </div>
                </form>
              </div>
              <div class="col-md-8 d-flex align-items-center">
                <div class="services-wrap rounded-right w-100">
                  <h3 class="heading-section mb-4">A melhor forma de entrares na tua noite!</h3>
                  <div class="row d-flex mb-4">
                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                      <div class="services w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span class="icon-form"></span></div>
                        <div class="text w-100">
                          <h3 class="heading mb-2">Preenche o formulário.</h3>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                      <div class="services w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span class="icon-ticket-new"></span></div>
                        <div class="text w-100">
                          <h3 class="heading mb-2">Faz download do teu bilhete.</h3>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                      <div class="services w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span class="icon-party"></span></div>
                        <div class="text w-100">
                          <h3 class="heading mb-2">Diverte-te!</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
  <?php
  } else if ($convite["status"] == "success" && $evento["status"] == "success" && $expired == 0 && (int) $convite["data"]["qrcode"] > 0) {
  ?>
    <section class="ftco-section ftco-no-pt bg-light">
      <div class="container">
        <div class="row no-gutters">
          <div class="col-md-12	featured-top">
            <div class="col-md-12 mb-4 d-flex justify-content-center align-items-center">
              <a href="/download_qrcode.php?hash=<?php echo $_GET["hash"]; ?>" class="btn btn-secondary py-3 px-4"> Download QR CODE </a>
            </div>
            <div class="col-md-12 d-flex align-items-center">
              <div class="qrcode-wrap rounded-right w-100">
                <?php
                include_once($_SERVER["DOCUMENT_ROOT"] . '/plugins/phpqrcode/lib/full/qrlib.php');
                $dataText   = $convite["data"]["qrcode"];
                $svgTagId   = 'line-' . $convite["data"]["id"];
                $saveToFile = false;
                $imageWidth = 600;
                echo QRcode::svg($dataText, $svgTagId, $saveToFile, QR_ECLEVEL_L, $imageWidth, false, 2);
                ?>
              </div>
            </div>
          </div>
        </div>
        <div class="row no-gutters">
          <div class="col-md-12	">
            <div class="row no-gutters column-search">
              <div class="col-md-12 d-flex align-items-center">
                <div class="services-wrap rounded-right w-100">
                  <h3 class="heading-section mb-4">Não te esqueças!</h3>
                  <div class="row d-flex mb-4">
                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                      <div class="services w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span class="icon-ticket-new"></span></div>
                        <div class="text w-100 mb-4">
                          <h3 class="heading mb-2">O bilhete é único e intransmissível.</h3>
                          <span class="text mb-2">Não partilhes o teu bilhete com mais ninguém.</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                      <div class="services w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span class="icon-ticket-new"></span></div>
                        <div class="text w-100 mb-4">
                          <h3 class="heading mb-2">Usa o bilhete na data correcta.</h3>
                          <span class="text mb-2">Este bilhete é de uso único para <?php echo $evento["data"]["data_extenso"]; ?> no evento <?php echo $evento["data"]["nome"]; ?>.</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                      <div class="services w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span class="icon-party"></span></div>
                        <div class="text w-100">
                          <h3 class="heading mb-2">Diverte-te!</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
  <?php
  } else if ($expired == 1) {
  ?>

    <section class="ftco-section ftco-no-pt bg-light">
      <div class="container">
        <div class="row no-gutters">
          <div class="col-md-12	">
            <div class="row no-gutters column-search">
              <div class="col-md-12 d-flex align-items-center">
                <div class="services-wrap rounded-right w-100">
                  <h3 class="heading-section mb-4 text-center">Convite expirado!</h3>
                  <div class="row d-flex mb-4 justify-content-center">
                    <div class="col-md-4 d-flex justify-content-center ftco-animate">
                      <div class="services w-100 text-center">
                        <div class="icon d-flex align-items-center justify-content-center"><span class="icon-sad"></span></div>
                        <div class="text w-100 mb-4">
                          <h3 class="heading mb-2">Este evento já decorreu.</h3>
                          <span class="text mb-2">Contacta um dos nossos RP's para saberes mais sobre os próximos eventos.</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
  <?php
  }
  if ($evento["status"] == "success") {
  ?>
    <section class="ftco-section ftco-no-pt bg-light">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-12 heading-section text-center ftco-animate mb-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Sobre o evento</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">
            <p>
              <?php echo nl2br($evento["data"]["descricao"]); ?>
            </p>
          </div>
        </div>
      </div>
    </section>
  <?php
  }
  ?>




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

  <script src="/temas/public/js/input-tel/intlTelInput.min.js"></script>

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
  <script src="/temas/public/js/main.js?v=5"></script>

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