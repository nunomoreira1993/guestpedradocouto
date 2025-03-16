<!DOCTYPE html>
<html lang="en">

<head>
  <title>Termos e Condições - Pedra do Couto</title>
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
            <h1 class="mb-4">Termos e Condições</h1>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section class="ftco-section ftco-no-pt bg-light  featured-top" style="padding-top:20px">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 text px-md-5 pt-4">
          <!-- INIT -->

          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Termos e Condições para os Utilizadores</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <p>Os presentes Termos e Condições regulam as condições gerais de uso e informação legal (doravante T&C) e aplicam-se ao website www.pedradocouto.net (doravante Pedra do Couto), cujo domínio é www.pedradocouto.net e a correspondente aplicação de telemóvel, bem como todos os sites relacionados ou vinculados com www.pedradocouto.net e com a Pedra do Couto, bem como suas afiliadas e associados (a seguir e coletivamente, a "APP"). A APP é propriedade da Pedra do Couto. Ao usar a APP, o utilizador concorda com os presentes T&C. Se o Utilizador não concordar, pedimos-lhe que se abstenha de usar a APP.</p>
            <p>Através destes T&C, a Pedra do Couto põe à disposição dos Utilizadores o website, backoffice e a aplicação de telemóvel Pedra do Couto (a seguir denominadas conjuntamente, a Plataforma).</p>

            <ol class="tc">
              <li>
                <h3>Definições</h3>

                <h5>Utilizador</h5>
                <p>O "utilizador" refere-se a si enquanto utilizador do serviço. Um utilizador é alguém que usa o serviço com o intuito de partilhar, guardar, publicar, transacionar ou carregar informações denominado por "gerir conteúdo"). Inclui outras pessoas que participem em conjunto na utilização do Serviço.</p>

                <h5>Conteúdo</h5>
                <p>Os "Conteúdos" incluem de forma não exaustiva, imagens, fotografias, áudio, vídeos, dados de localização, locais próximos, etc.</p>
                <p>"Conteúdos dos utilizadores" referem-se a conteúdos que o Utilizador tenha carregado, partilhado ou publicado, através da APP da Pedra do Couto, tais como gostos, avaliações, comentários, informações de perfil e outros materiais exiba na sua conta pessoal.</p>
                <p>"Conteúdos da Pedra do Couto" refere-se aos conteúdos que a Pedra do Couto cria e disponibiliza em associação ao Serviço, incluindo, de forma não exaustiva, funcionalidades e interfaces, gráficos, design, códigos informáticos, produtos, software, avaliações agregadas, relatórios e outros dados de utilização ligados com a sua conta.</p>
                <p>"Conteúdos de Terceiros" refere-se a conteúdos disponíveis através do Serviço com origem que não a Pedra do Couto ou os seus utilizadores.</p>

                <h5>Estabelecimentos Noturnos</h5>
                <p>Todos os espaços, bares e discotecas ou outro tipo de estabelecimentos noturnos, listados na Pedra do Couto e em qualquer aplicação relacionada com a Pedra do Couto.</p>

                <h5>Serviço</h5>
                <p>Todas as funcionalidades disponíveis para o Utilizador, através da APP ou do website da Pedra do Couto.</p>

                <h5>Plataforma</h5>
                <p>O conjunto da APP e do Website e do Backoffice da Pedra do Couto.</p>

              </li>


              <li>
                <h3>Objeto</h3>

                <p>A ACCESSPIXEL, LDA. (doravante Pedra do Couto) é uma empresa de tecnologia, cuja principal atividade é o desenvolvimento de uma plataforma tecnológica através de uma aplicação móvel ou web (doravante, a APP) permite determinados bares, discotecas ou estabelecimentos promovam os seus serviços e ofertas podendo, a pedido dos Utilizadores da APP, criar uma ligação direta entre o estabelecimento e o Utilizador, a qual poderá originar pontos, descontos ou outra ofertas para o Utilizador desfrutar nos locais publicitados.</p>
                <p>A Pedra do Couto tem uma plataforma através da qual diferentes estabelecimentos noturnos – com os quais a Pedra do Couto pode manter um acordo comercial, para a utilização da Plataforma – oferecem uma série de produtos e Plataformas. O Utilizador tem a possibilidade de solicitar a aquisição dos produtos ou serviços por meio de um pedido através da Plataforma e, nestes casos, a Pedra do Couto atua meramente como intermediária e, portanto, não assume qualquer tipo de responsabilidade sobre a qualidade dos produtos, ou a correta prestação dos Serviços oferecidos diretamente pelos mesmos nem por terceiros.</p>

              </li>


              <li>
                <h3>Termos de Utilização</h3>

                <p>O acesso à plataforma, e a criação voluntária de um perfil pelo Utilizador implica o conhecimento e a aceitação expressa e inequívoca das presentes Condições Gerais, da <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade</a> e da <a href="https://Pedra do Couto.eu/cookie-policy" target="_blank">Política de Cookies</a> por todos os Utilizadores.</p>

              </li>


              <li>
                <h3>Acesso e Registo para Utilizadores</h3>

                <p>Para poder ser Utilizador da Plataforma é indispensável que se cumpram os seguintes requisitos:</p>

                <ul>
                  <li>Ser maior de 18 anos de idade;</li>
                  <li>Preencher de maneira verdadeira os campos obrigatórios do formulário de registo, no qual se solicitam dados de carácter pessoal como nome de Utilizador, e-mail, número de telefone;</li>
                  <li>Aceitar os presentes Termos e Condições;</li>
                  <li>Aceitar a <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade e Proteção de Dados</a>;</li>
                  <li>Aceitar a <a href="https://Pedra do Couto.eu/cookie-policy" target="_blank">Política de Cookies</a>.</li>
                </ul>

                <p>O Utilizador garante que todos os dados fornecidos sobre a sua identidade e legitimidade são verdadeiros, corretos e completos. Também se compromete a manter os seus dados atualizados.</p>
                <p>Caso o Utilizador comunique qualquer dado falso, incorreto ou incompleto, ou caso a Pedra do Couto considere existirem motivos fundados para duvidar da veracidade, exatidão e integridade dos mesmos, a Pedra do Couto poderá negar o acesso e uso, presente ou futuro, da Plataforma ou de quaisquer dos seus conteúdos e/ou Plataformas.</p>
                <p>Ao inscrever-se na Plataforma, o Utilizador selecionará um nome de Utilizador (username) e uma senha de acesso (password). Tanto o username como a password são estritamente confidenciais, pessoais e intransmissíveis.</p>
                <p>O Utilizador compromete-se a não divulgar os dados relativos à sua conta nem fazê-los acessíveis a terceiros. O Utilizador será o único responsável em caso de uso destes dados por terceiros, através do uso do username e/ou password.</p>
                <p>A Pedra do Couto não pode garantir a identidade dos Utilizadores registados, portanto, não será responsável pelo uso da identidade de um Utilizador registado, por terceiros não registados. Os Utilizadores obrigam-se a comunicar imediatamente à Pedra do Couto a subtração, divulgação ou perda do seu username ou password, através do e-mail <a href="mailto:geral@Pedra do Couto.eu" target="_blank">geral@Pedra do Couto.eu</a>.</p>

                <ol>
                  <li>
                    <h4>Perfil</h4>

                    <p>Para poder completar o registo na Plataforma, o Utilizador deverá proporcionar alguns dados como: nome de Utilizador, endereço de e-mail, telefone, etc.</p>
                    <p>Uma vez completado o registo, todos os Utilizadores poderão entrar no seu perfil e completá-lo e/ou editá-lo conforme entenda conveniente.</p>
                    <p>A forma como os dados pessoais do Utilizador são tratados encontra-se na nossa <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade e Proteção de Dados</a>.</p>

                  </li>
                </ol>

              </li>


              <li>
                <h3>Prestação do Serviço oferecido pela Pedra do Couto</h3>

                <p>O Utilizador reconhece e aceita que o Serviço disponibilizado pela Pedra do Couto pode ter alterações, pelo que a Pedra do Couto se reserva o direito de suspender, cancelar ou extinguir produtos e Serviços a qualquer momento e sem aviso prévio, bem como fazer alterações a todos e quaisquer conteúdos próprios, produtos e serviços contidos no BackOffice ou na APP da Pedra do Couto.</p>
                <p>A Pedra do Couto reserva-se o direito de alterar o tarifário ou estabelecer novos preços para os serviços prestados, sem qualquer comunicação prévia ao utilizador, podendo ainda estabelecer que um serviço que anteriormente fosse gratuito, passe a ser pago. O utilizador terá sempre possibilidade de consultar os preços estabelecidos através da consulta da APP ou do website.</p>
                <p>A Pedra do Couto, o seu software e a plataforma que disponibiliza poderão incluir a opção de automaticamente verificar se há atualizações ao software, podendo o Utilizador ser notificado da existência de tais atualizações ou melhorias bem como serem transferidas automaticamente tais atualizações ou melhorias ao software para o seu dispositivo. Poderão também ser necessárias atualizações ou melhorias ao software para que seja permitido utilizar o Serviço ou parte dele (incluindo atualizações para corrigir problemas com a Plataforma). Quaisquer atualizações disponibilizadas ao abrigo dos T&C deverão ser consideradas parte do Plataforma.</p>
                <p>O Utilizador aceita que, se a Pedra do Couto desativar o seu acesso à conta, ficará impedido de aceder ao Serviço, e dessa forma aos seus dados de conta e a ficheiros e conteúdos.</p>
                <p>Ao utilizar os Serviços da Pedra do Couto, o Utilizador aceita que os conteúdos do software da Pedra do Couto existem para fins exclusivamente informativos. A Pedra do Couto não assume a responsabilidade de quaisquer informações que não estejam atualizadas. A Pedra do Couto reserva-se também o direito de proceder a alterações ao conteúdo do website sem aviso prévio.</p>
                <p>A Pedra do Couto não garante os preços listados nos menus ou a disponibilidade de todos os itens de um menu em qualquer dos estabelecimentos listados.</p>
                <p>Salvo disposição em contrário, todas as fotografias e informações incluídas no software da Pedra do Couto são consideradas de domínio público, como materiais promocionais ou arquivos de imprensa.</p>
                <p>O Utilizador poderá enviar um pedido para retirar certos materiais através do e-mail <a href="mailto:geral@Pedra do Couto.eu" target="_blank">geral@Pedra do Couto.eu</a>, no caso de deter direitos sobre conteúdos presentes na Plataforma e se considerar que o uso de tais materiais viola os seus Direitos de Autor. Indique por favor o URL exato a que se refere no seu pedido. Nenhuma outra identidade estará autorizada a reproduzir ou republicar estas versões digitais em qualquer formato sem a permissão escrita da Pedra do Couto.</p>
                <p>A Pedra do Couto reserva-se o direito de cobrar aos Utilizadores taxas de subscrição e/ou de adesão, com notificação prévia, no que respeita a qualquer produto, Plataforma ou qualquer outro aspeto do software da Pedra do Couto.</p>

              </li>


              <li>
                <h3>Utilização do Serviço</h3>

                <p>O uso de quaisquer informações pessoais que o Utilizador nos forneça durante o processo de criação de conta é regulado pela <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade e Proteção de Dados Pessoais</a>.</p>
                <p>O Utilizador deve manter a sua palavra-passe confidencial, sendo o único responsável por manter a confidencialidade e segurança da sua conta e de todas as mudanças e atualizações submetidas através da sua conta, bem como de todas as atividades que ocorram em ligação com a sua conta.</p>
                <p>O utilizador tem também a opção de se registar para usufruir da Plataforma ao associar os seus dados de conta às suas credenciais de algumas redes sociais não relacionadas com a Pedra do Couto (por exemplo, com o Facebook). Nesse ato confirma que é o detentor de qualquer uma dessas contas de redes sociais. Autoriza também a Pedra do Couto a recolher seus dados de autenticação e quaisquer outras informações que possam estar disponíveis na, ou a partir da sua conta da rede social em conformidade com as suas definições e instruções.</p>
                <p>O utilizador é responsável por todas as atividades que ocorram na sua conta e aceita em notificar-nos imediatamente acerca de quaisquer usos não autorizados de forma a permitir-nos tomar as necessárias ações corretivas. Compromete-se igualmente a não permitir a terceiros a utilização da sua conta. O utilizador é o único responsável pela segurança e inviolabilidade dos seus dados de acesso.</p>
                <p>Ao criar uma conta, o Utilizador concorda em receber certas comunicações associadas ao Serviço, tais como preferências relativas às comunicações não essenciais através das definições de conta.</p>
                <p>Em geral, os Utilizadores comprometem-se, a título enunciativo e não taxativo, a:</p>

                <ul>
                  <li>Não alterar ou modificar, total ou parcialmente a Plataforma, eludindo, desativando ou manipulando de qualquer outra das funções ou serviços da mesma;</li>
                  <li>Não violar os direitos de propriedade industrial e intelectual ou as normas reguladoras da proteção de dados de carácter pessoal;</li>
                  <li>Não usar a Plataforma para injuriar, difamar, intimidar, violar a própria imagem ou assediar outros Utilizadores;</li>
                  <li>Não introduzir vírus informáticos, arquivos defeituosos, ou qualquer outro programa informático que possa provocar danos ou alterações nos conteúdos dos sistemas da Pedra do Couto ou terceiras pessoas;</li>
                  <li>Não enviar e-mails com carácter massivo e/ou repetitivo a uma pluralidade de pessoas, nem enviar endereços de e-mail de terceiros sem o seu consentimento;</li>
                  <li>Não realizar ações publicitárias de bens ou serviços sem o prévio consentimento da Pedra do Couto.</li>
                </ul>

                <p>Qualquer Utilizador poderá informar a Pedra do Couto de qualquer abuso ou vulnerabilidade das presentes condições, através do envio de e-mail para <a href="mailto:geral@Pedra do Couto.eu" target="_blank">geral@Pedra do Couto.eu</a>.</p>
                <p>A Pedra do Couto reserva-se o direito a retirar e/ou suspender qualquer mensagem com conteúdo ilegal ou ofensivo, sem necessidade de prévio aviso ou posterior notificação.</p>

              </li>


              <li>
                <h3>Conteúdos da Plataforma Pedra do Couto</h3>

                <p>A Pedra do Couto é a detentora única e exclusiva dos direitos de autor da Plataforma e detém em exclusivo os direitos de autor, marca registada, logótipos, direitos de imagem e outros direitos de propriedade industrial ou intelectual em Portugal.</p>
                <p>O Utilizador reconhece que a Plataforma pode conter informação que é considerada confidencial pela Pedra do Couto e que não deverá revelar tais informações sem o consentimento prévio por escrito da Pedra do Couto, bem como concorda em proteger os direitos de propriedade intelectual de todos os que tenham direitos na Plataforma durante e após o término deste acordo.</p>
                <p>O Utilizador reconhece e aceita que a Pedra do Couto detém todos os direitos legais e interesses na Plataforma, incluindo quaisquer direitos de propriedade intelectual (quer tais direitos estejam ou não registados, e em qualquer parte do mundo).</p>
                <p>O Utilizador compromete-se a não utilizar qualquer marca registada, logótipo ou outra informação propriedade da Pedra do Couto; ou remover, esconder ou apagar quaisquer avisos de direitos de autor ou de propriedade ou identificadores de fonte, incluindo sem limite, o tamanho, cor, localização ou estilo de quaisquer marcas de propriedade, salvo autorização prévia, por escrito. Quaisquer infrações conduzirão a procedimentos legais apropriados.</p>
                <p>O Utilizador não poderá utilizar a Plataforma para fins ilegais. O utilizador poderá utilizar a informação disponibilizada pela Platafoma apenas para fins pessoais e não-comerciais. O Utilizador aceita que não pode utilizar, copiar ou modificar, exibir ou distribuir, reproduzir, reformatar, ou utilizar em materiais ou produtos publicitários, bem como comercializar de qualquer forma toda ou qualquer parte do Serviço, Conteúdos ou Direitos de Propriedade Intelectual da Pedra do Couto, exceto se previamente autorizado por escrito pela Pedra do Couto.</p>
                <p>Qualquer violação pelo utilizador poderá resultar na suspensão imediata do direito a utilizar a Plataforma, bem como em potenciais responsabilidades civis e criminais por infração dos direitos de autor ou de outros direitos.</p>

                <ol>
                  <li>
                    <h4>Responsabilidade por Conteúdos</h4>

                    <p>O utilizador é responsável pelos Seus Conteúdos, garantindo que é o único autor, e que detém controlo sobre todos os seus direitos ou de que lhe foi concedida autorização expressa pelo detentor dos direitos para submeter tais conteúdos; que não foram copiados ou baseados em qualquer outros conteúdos, trabalhos ou websites; que são verdadeiros e rigorosos e que não violam os Termos e Condições ou quaisquer leis aplicáveis.</p>
                    <p>Se o Conteúdo for uma opinião, o Utilizador garante que é o único autor da mesma; que a opinião reflete uma experiência noturna que teve; que não foi recompensado em relação à escrita e publicação da opinião e que não recebeu incentivos financeiros, competitivos ou pessoais para escrever ou publicar uma opinião que não seja uma expressão justa e honesta da sua vontade.</p>
                    <p>O utilizador assume todos os riscos associados ao seu Conteúdo, incluindo a opinião de terceiros relativamente à sua qualidade, rigor e credibilidade, ou ao revelar informações que o tornem identificável.</p>
                    <p>Apesar de nos reservarmos o direito de remover conteúdos não garantimos o rigor ou qualidade de qualquer conteúdo publicado por terceiros ou por utilizadores.</p>
                    <p>A Pedra do Couto não tem obrigação de controlar a utilização que os Utilizadores fazem da Plataforma e, por conseguinte, não garante que os Utilizadores utilizem a Plataforma em conformidade com o estabelecido nos presentes Termos e Condições, nem que façam um uso diligente e/ou prudente da mesma.</p>
                    <p>A Pedra do Couto não tem a obrigação de verificar a identidade dos Utilizadores, nem a veracidade, vigência, exaustividade e/ou autenticidade dos dados que os mesmos proporcionam.</p>
                    <p>Sem prejuízo do anterior, a Pedra do Couto reserva-se a faculdade de limitar, total ou parcialmente, o acesso à Plataforma a determinados Utilizadores, assim como de cancelar, suspender, bloquear ou eliminar determinado tipo de conteúdos, mediante a utilização de instrumentos tecnológicos aptos ao efeito, se tiver conhecimento efetivo de que a atividade ou a informação armazenada é ilícita ou de que lesiona bens ou direitos de um terceiro. Neste sentido, a Pedra do Couto poderá estabelecer os filtros necessários a fim de evitar que através do Serviço possam difundir-se na rede conteúdos ilícitos ou nocivos.</p>
                    <p>A disposição por parte dos Utilizadores de conteúdos através da Plataforma implicará a cessão a favor da Pedra do Couto de todos os direitos de exploração derivados dos conteúdos fornecidos na Plataforma.</p>
                    <p>Quando partilha e comunica através da Plataforma Pedra do Couto, aceita que sejam públicos os conteúdos que partilha. Por exemplo, a rede de "Seguidores" pode ver as ações que realizou através dos nossos Serviços.</p>
                    <p>As informações públicas podem ser vistas por qualquer pessoa, dentro da Plataforma. Isto inclui o nome de utilizador, o número de seguidores, a fotografia de perfil, quaisquer informações que partilhe publicamente e os conteúdos que partilha em qualquer fórum público.</p>
                    <p>O Utilizador, outras pessoas que utilizam a Plataforma e a Pedra do Couto podem disponibilizar o acesso ou enviar informações públicas para qualquer pessoa dentro ou fora da Plataforma.</p>
                    <p>As informações públicas também podem ser vistas, acedidas, partilhadas novamente ou descarregadas através de serviços de terceiros, como os motores de busca, APIs e meios de comunicação offline, como a TV, e em apps, sites e outros serviços que integram os nossos Serviços.</p>
                    <p>Poderá sempre alterar as suas definições de privacidade, através do acesso à sua conta.</p>

                  </li>

                  <li>
                    <h4>Remoção de Conteúdos</h4>

                    <p>A Pedra do Couto reserva-se no direito de, a qualquer momento e sem notificação prévia, remover, bloquear ou impedir o acesso a quaisquer conteúdos que considere violar os Termos e Condições ou que sejam prejudiciais para o Serviço ou para os nossos Utilizadores, bem como não se considera obrigada a devolver nenhum dos Seus Conteúdos.</p>

                  </li>

                  <li>
                    <h4>Conteúdos de terceiros e ligações</h4>

                    <p>Alguns dos conteúdos disponíveis através da Plataforma Pedra do Couto poderão pertencer parcialmente a terceiros, e/ou estar ligados a empresas com serviços de reservas ou de encomendas online. O uso dos serviços de terceiros será regulado pelos seus próprios termos e condições, podendo ser obtidos de informações de contacto, não existindo por parte da Pedra do Couto qualquer ação sobre os mesmos, nem assegurando esta a legalidade, grau de atualização ou qualidade. A Pedra do Couto não verifica nem investiga materiais de terceiros antes ou depois de os incluir no Serviço.</p>
                    <p>A Pedra do Couto reserva-se o direito de fazer alterações ou correções a erros ou omissões a qualquer parte do conteúdo acessível pelo Serviço, mas não se responsabiliza por qualquer atraso ou imprecisão relacionado com tais alterações.</p>
                    <p>O utilizador reconhece e aceita que a Pedra do Couto não é responsável pela disponibilidade de quaisquer sites ou recursos externos, nem apoia anúncios, produtos ou outros materiais disponíveis a partir de tais sites ou fontes.</p>
                    <p>O Utilizador reconhece e aceita ainda que a Pedra do Couto não é responsável por quaisquer perdas ou danos que possam ocorrer como resultado da disponibilidade de tais sites e fontes externas e dos conteúdos neles incluídos.</p>
                    <p>Sem prejuízo do anteriormente descrito, não assumimos quaisquer responsabilidades por conteúdos ofensivos, difamatórios ou ilegais que tenham sido fornecidos por terceiros.</p>

                  </li>

                  <li>
                    <h4>Opiniões dos Utilizadores</h4>

                    <p>As opiniões dos Utilizadores não refletem a opinião da Pedra do Couto, sendo cada uma das opiniões publicadas na Pedra do Couto da responsabilidade de quem as publicou.</p>
                    <p>A Pedra do Couto é uma plataforma neutra que apenas serve de canal de comunicação entre os Utilizadores e os Estabelecimentos Noturnos.</p>
                    <p>Os anúncios na Pedra do Couto são publicados de forma independente e não têm qualquer relação com as opiniões dos anunciantes na Plataforma Pedra do Couto.</p>
                    <p>Somos uma plataforma neutra e não arbitramos disputas.</p>
                    <p>Se o Estabelecimento estiver convicto de que uma opinião em particular está a violar as Políticas da Pedra do Couto, poderá sinalizá-la para que a consideremos.</p>
                    <p>A Pedra do Couto poderá remover a opinião se esta violar os Termos e Condições ou for prejudicial ao Serviço.</p>
                    <p>Nenhum comportamento ativo ou omissivo da Pedra do Couto poderá ser interpretado como aprovando ou rejeitando qualquer associação a qualquer mensagem colocada em público ou em privado através da plataforma.</p>

                  </li>

                </ol>
              </li>


              <li>
                <h3>Orientações</h3>

                <p>O utilizador aceita que leu, compreende e concorda com a <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade</a> da Pedra do Couto, e que esta pode partilhar informação sua a terceiros ou com as autoridades caso seja essencial ou relevante para identificar</p>

                <ol type="i">
                  <li>suspeita de atividades ilegais;</li>
                  <li>cumprir procedimentos judiciais ou outros processos/avisos legais que nos sejam transmitidos;</li>
                  <li>aplicar os nossos Termos e <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade</a>; ou</li>
                  <li>proteger os nossos direitos, reputação e propriedade e dos nossos utilizadores.</li>
                </ol>

              </li>


              <li>
                <h3>Restrição ao uso</h3>

                <p>Aceitando os presentes Termos e Condições, o Utilizador concorda em não publicar ou transmitir conteúdos (incluindo opiniões) que, no entendimento da Pedra do Couto:</p>

                <ol type="a">
                  <li>Violem as nossas Políticas;</li>
                  <li>Sejam prejudiciais à sociedade, sejam ameaçadoras, ou que encorajem atividades ilegais;</li>
                  <li>Sejam uma opinião falsa ou deliberadamente errónea, ou que não se refira exclusivamente aos atributos do Estabelecimento Noturno, produtos ou serviços que esteja a avaliar;</li>
                  <li>Incluam conteúdos que violem o bom gosto dos serviços;</li>
                  <li>Violem direitos de terceiros, nomeadamente direito à privacidade, bem como detentores de direitos da publicidade, de autor ou de propriedade intelectual, marcas registadas e patentes;</li>
                  <li>Acusem injustificadamente outros utilizadores de atividades ilegais;</li>
                  <li>Tentem fazer-se passar por outra pessoa ou entidade;</li>
                  <li>Dissimulem ou tentem dissimular a origem do conteúdo que submeteram, sob um nome errado; ou tentativa de disfarçar o endereço IP a partir do qual o teu conteúdo foi submetido;</li>
                  <li>Sejam uma forma de publicidade enganosa, ou que tenham origem ou sejam resultado de um conflito de interesses;</li>
                  <li>Tenham natureza comercial, tais como spam, concursos, esquemas de pirâmide, inserções ou opiniões publicadas ou removidas a troco de pagamento e/ou a pedido do Estabelecimento Noturno a ser avaliado;</li>
                  <li>Sugiram que o Seu Conteúdo é patrocinado ou apoiado por nós direta ou indiretamente;</li>
                  <li>Que introduza vírus ou outros códigos informáticos, ficheiros ou programas que prejudicam, destroem ou limitam a operacionalidade de programas, equipamentos ou comunicações eletrónicas, e que interfiram no recurso ao Serviço, servidores e redes ligadas ao Serviço;</li>
                  <li>Pirateiam ou acedem sem permissão aos registos confidenciais da Pedra do Couto ou de outros utilizadores ou Clientes tentando aceder a informações pessoais dos utilizadores do Serviço;</li>
                  <li>Tentem determinar o código fonte a partir do Serviço, bem como tentem desativar, danificar ou interferir de qualquer outra forma nas funcionalidades de segurança ou funcionalidades que impõem limites no uso dos Serviços;</li>
                  <li>Violem as restrições de quaisquer cabeçalhos de exclusão automática no Serviço, caso existam, ou ignorem ou contornem quaisquer medidas empregues para prevenir ou limitar o acesso ao Serviço;</li>
                  <li>Sejam publicados por um "bot" automático (sistema de envio de mensagens robot);</li>
                  <li>Copiem, modifiquem, vendam, arrendem, ou comercializem de qualquer forma quaisquer direitos ao Serviço da Pedra do Couto.</li>
                </ol>

                <p>O Utilizador reconhece que a Pedra do Couto não tem a obrigação de monitorizar o acesso e o uso do Serviço da sua parte ou de outros para violações dos Termos ou de rever ou editar quaisquer conteúdos. No entanto, temos o direito a fazê-lo para o propósito de operar e melhorar o Serviço (incluindo, a prevenção de fraudes, avaliação de riscos, investigação e serviço ao cliente), para garantir o seu cumprimento dos Termos e Condições e para cumprir a lei aplicável ou uma notificação judicial.</p>
                <p>O Utilizador compromete-se a não utilizar o serviço para fins ilícitos e a não violar quaisquer leis nacionais ou internacionais. Não deverá publicar, enviar por e-mail, transmitir ou disponibilizar por qualquer outro meio quaisquer anúncios, materiais promocionais, spam, ou qualquer outra forma de solicitação não autorizada ou não solicitada, nem deverá fazer quaisquer representações ou garantias em nome da Pedra do Couto sob qualquer forma.</p>
                <p>Quaisquer Conteúdos publicados pelo Utilizador estão sujeitos às leis do país onde a utilização é feita, e poderão ser suspensos ou sujeitos a investigação sob as leis aplicáveis. Mais, no caso de incumprimento das leis e regulamentos, destes Termos e Condições ou da <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade</a>, a Pedra do Couto tem o direito de bloquear imediatamente o seu acesso e utilização do Serviço e de remover quaisquer conteúdos que não se encontrem em conformidade, bem como de tomar as medidas necessárias.</p>

              </li>


              <li>
                <h3>Feedback dos Utilizadores</h3>

                <p>Quando partilha ou envia ideias, sugestões, alterações ou documentos ("Feedback"), está a concordar que</p>

                <ol type="i">
                  <li>o seu feedback não inclui informação confidencial, secreta ou privada de terceiros,</li>
                  <li>a Pedra do Couto não tem obrigações de confidencialidade face a tal Feedback,</li>
                  <li>a Pedra do Couto pode ter recebido já Feedback semelhante de outro utilizador ou pode tê-lo já em consideração, e</li>
                  <li>ao fornecer-nos Feedback, garante-nos autorização vinculativa, não-exclusiva, livre de direitos, vitalícia e global para usar, modificar, desenvolver, publicar, distribuir e sublicenciar o Feedback, e renuncia irrevogavelmente a quaisquer asserções ou reivindicações de qualquer natureza contra a Pedra do Couto e os seus utilizadores em relação a tal Feedback.</p>
                </ol>

                <p>Poderá enviar a sua Reclamação, Opinião ou Sugestão através do email <a href="mailto:geral@Pedra do Couto.eu" target="_blank">geral@Pedra do Couto.eu</a>.</p>

              </li>


              <li>
                <h3>Submissão de ideias</h3>

                <p>O Utilizador concorda que:</p>

                <ol type="a">
                  <li>As submissões de ideias do utilizador e respetivo conteúdo serão automaticamente propriedade da Pedra do Couto, sem compensação para aquele;</li>
                  <li>a Pedra do Couto poderá usar ou redistribuir os conteúdos de qualquer forma e para qualquer propósito;</li>
                  <li>a Pedra do Couto não tem a obrigação de rever a submissão e não há qualquer obrigação de manter quaisquer submissões confidenciais.</li>
                </ol>

              </li>


              <li>
                <h3>Publicidade</h3>

                <p>Ao criar uma conta, está a optar por receber comunicações via e-mail ou SMS da Pedra do Couto ou parceiros/clientes Pedra do Couto. Pode fazer o login para gerir as suas preferências de comunicação mas tome em atenção que não é possível deixar de receber comunicações da Pedra do Couto com informações legais, políticas de serviço ou administrativos.</p>
                <p>A Plataforma poderá apresentar anúncios e promoções. Estes anúncios podem estar direcionados ao tipo de conteúdo presente no Serviço, a pedidos ou questões feitas através do Serviço ou a outra informação. A forma, modo e extensão dos anúncios na Pedra do Couto estão sujeitos a alterações. Como contrapartida do acesso ao Serviço, o Utilizador concorda com a existência de publicidade no Serviço.</p>
                <p>Parte da APP da Pedra do Couto pode conter informação publicitária, material promocional ou outro material submetido por terceiros. A responsabilidade por garantir que o material submetido para inclusão no BackOffice e na APP da Pedra do Couto está em conformidade com a lei nacional e internacional é exclusiva das partes que submetem os materiais/informações.</p>
                <p>A correspondência, transações comerciais ou participação em promoções de anunciantes que não a Pedra do Couto encontrados em, ou através do BackOffice ou da APP da Pedra do Couto, incluindo o pagamento e a entrega dos bens e serviços a que respeitam, bem como quaisquer outros termos, condições, garantias ou representações associadas com tais operações, é feita apenas entre o Utilizador e o anunciante.</p>
                <p>A Pedra do Couto não se responsabiliza por qualquer erro, omissão ou imprecisão no material promocional ou por quaisquer perdas ou danos de qualquer tipo incorridos em resultado de tais transações ou da presença de tais anunciantes no BackOffice e na APP da Pedra do Couto.</p>

              </li>


              <li>
                <h3>Exclusão de garantias, limitação da responsabilidade e indemnizações</h3>

                <h5>Garantias</h5>

                <p>O Utilizador reconhece e concorda que o Serviço é disponibilizado "conforme a disponibilidade" e que a sua utilização do Serviço é realizada por sua conta e risco.</p>
                <p>A Pedra do Couto não se responsabiliza por quaisquer</p>

                <ol type="i">
                  <li>erros, enganos ou imprecisões nos conteúdos</li>
                  <li>quaisquer acessos não autorizados ao uso do Serviço</li>
                  <li>toda e qualquer informação armazenada na Plataforma</li>
                  <li>qualquer interrupção ou cessação da transmissão de e para o Serviço</li>
                  <li>quaisquer bugs, vírus, trojans ou afins que possam ser transmitidos para ou através do Serviço através das ações de terceiros</li>
                  <li>quaisquer erros ou omissões em qualquer conteúdo ou por quaisquer perdas e danos de qualquer tipo incorridos como resultado do uso de qualquer conteúdo publicado, enviado, transmitido ou disponibilizado de qualquer forma através do Serviço</li>
                  <li>qualquer material descarregado ou obtido através do Serviço é efetuado por conta e risco do Utilizador, que irá ser o único responsável por qualquer dano ao seu sistema informático ou a qualquer outro equipamento ou perda de dados que resulte do download de tais materiais.</li>
                </ol>

                <p>A Pedra do Couto não será responsável por monitorizar quaisquer transações entre o Utilizador e prestadores terceiros de produtos ou serviços. O Utilizador é o único responsável pelas interações com outros utilizadores do Serviço e com outras pessoas com quem comunica ou interage como resultado do seu uso do Serviço. Nenhum aviso ou informação, oral ou escrita, obtida pelo Utilizador da Pedra do Couto ou através do Serviço constitui uma garantia que não expressamente descrita nos Termos e Condições.</p>
                <p>Salvo autorização escrita em contrário da Pedra do Couto, o Utilizador não poderá usar quaisquer marcas registadas, marcas de serviço, nomes registados, logótipos de qualquer companhia ou organização de forma que possa criar confusões sobre o detentor ou utilizador autorizado de tais marcas, nomes e logótipos.</p>
                <p>A Pedra do Couto não responderá em caso de interrupções do Serviço, erros de conexão, falta de disponibilidade ou deficiências no Serviço de acesso à Internet, nem por interrupções da rede de Internet ou por qualquer outra razão a si alheia.</p>
                <p>A Pedra do Couto não se responsabiliza pelos erros de segurança que se possam produzir nem pelos danos que possam causar ao sistema informático do Utilizador (hardware e software), aos arquivos ou documentos armazenados no mesmo, como consequência de:</p>

                <ul>
                  <li>A presença de um vírus no sistema informático ou terminal móvel do Utilizador que seja utilizado para a conexão aos serviços e conteúdos da Plataforma;</li>
                  <li>Um mau funcionamento do navegador;</li>
                  <li>Uso de versões não atualizadas do mesmo.</li>
                </ul>

                <h5>Indemnizações</h5>

                <p>O utilizador concorda em isentar de responsabilidade, defender e proteger a Pedra do Couto de e contra quaisquer reivindicações de terceiros, danos, ações, procedimentos, exigências, perdas, responsabilizações, custos e despesas (incluindo custas judiciais) incorridos em resultado ou em ligação com:</p>

                <ol type="i">
                  <li>o Conteúdo do Utilizador;</li>
                  <li>o uso não autorizado dos Serviços ou dos produtos incluídos ou publicitados nos Serviços;</li>
                  <li>o acesso e uso dos Serviços pelo Utilizador;</li>
                  <li>violação, pelo Utilizador, dos direitos de terceiros; ou</li>
                  <li>incumprimento, pelo Utilizador, dos presentes Termos e Condições, incluindo a infração dos direitos de propriedade intelectual de terceiros.</li>
                </ol>

                <p>A Pedra do Couto mantém o direito exclusivo de negociar, acordar e pagar, sem o consentimento prévio do utilizador, todas as reivindicações e ações judiciais contra si intentadas. Reserva-se o direito de, à custa do utilizador, assumir a defesa e controlo exclusivo de quaisquer questões em que este deva compensar a Pedra do Couto.</p>
                <p>O utilizador concorda em não chegar a acordo em quaisquer assuntos em que a Pedra do Couto seja parte sem o seu consentimento prévio por escrito.</p>
                <p>A Pedra do Couto esforçar-se-á, dentro do razoável, para notificar o utilizador de qualquer uma dessas reivindicações, ações ou procedimentos.</p>

              </li>


              <li>
                <h3>Cancelamento do acesso aos serviços</h3>

                <p>O utilizador pode encerrar a sua conta a qualquer momento ao contactar-nos através de e-mail para <a href="mailto:geral@Pedra do Couto.eu" target="_blank">geral@Pedra do Couto.eu</a>, cessando assim a posterior utilização do Serviço.</p>

                <p>A Pedra do Couto pode cancelar o uso do Serviço e negar ao utilizador acesso ao Serviço de acordo com o seu critério exclusivo, por:</p>

                <ol type="i">
                  <li>violação destes Termos e Condições; ou</li>
                  <li>falta de uso do Serviço.</li>
                </ol>

                <p>O Utilizador concorda que qualquer cancelamento do seu acesso à Plataforma pode ser efetuado sem notificação prévia e reconhece e concorda que pode a Pedra do Couto desativar ou apagar imediatamente a sua conta e toda a informação relacionada e/ou impedir os acessos à sua conta ou Serviço. Mais, concorda que não lhe deve a Pedra do Couto responsabilidades a si ou a terceiros pela suspensão ou cancelamento do seu acesso ao Serviço.</p>

              </li>


              <li>
                <h3>Termos gerais</h3>

                <h5>Contrato integral</h5>

                <p>Os Termos e Condições, juntamente com a <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade e Proteção de Dados</a>, constituem o contrato integral entre o utilizador e a Pedra do Couto no que respeita aos Serviços.</p>
                <p>A falha ou atraso da nossa parte em exercer quaisquer direitos, poderes ou privilégios ao abrigo dos T&C não constitui uma renúncia de tais direitos ou a aceitação de qualquer variação nos Termos e Condições, nem o exercício de ambas as partes de quaisquer direitos, poderes ou privilégios exclui o futuro exercício futuro de qualquer outro direito, poder ou privilégio.</p>

                <h5>Aproveitamento do Contrato</h5>

                <p>Se alguma previsão destes Termos e Condições for por qualquer motivo considerada nula, inválida ou anulável por um órgão judicial, tal provisão deverá ser retirada destes Termos e Condições, e o remanescente dos Termos e Condições deverá manter plena validade e efeito legal.</p>

                <h5>Lei aplicável, caducidade e prescrição</h5>

                <p>O Utilizador, deverá iniciar qualquer ação judicial no prazo máximo de um (1) ano após o início do alegado dano.</p>
                <p>O não exercício de direitos dentro deste período exclui quaisquer reivindicações ou causas de ação face aos mesmos factos ou ocorrências, não obstante quaisquer prazos de caducidade ou outras leis em contrário.</p>
                <p>Dentro deste período, a não imposição ou exercício de quaisquer provisões destes termos ou de quaisquer direitos relacionados não deverá constituir renúncia ao exercício do direito.</p>
                <p>Poderão existir custos de operador ao aceder ao Serviço através de um dispositivo móvel ou de qualquer outro tipo, poderá estar sujeito a custos do seu operador de telecomunicações ou de Internet.</p>
                <p>Pedimos, portanto, que verifique antecipadamente, já que é o responsável por custos deste tipo.</p>

                <h5>Ligações e enquadramentos</h5>

                <p>O Utilizador pode criar ligações para o Serviço, assumindo que reconhece e concorda que não irá ligar o Serviço a qualquer site que contenha tópicos, nomes, materiais ou informações impróprias, profanas, difamatórias, infratoras, obscenas, indecentes ou ilegais ou que violem propriedade intelectual, direitos de propriedade, privacidade ou direitos de publicidade. Quaisquer violações desta disposição poderão resultar na cessação do seu uso e acesso ao Serviço com efeito imediato, podendo a Pedra do Couto usar dos meios legais à sua disposição para responsabilizar o infrator da presente norma.</p>

              </li>


              <li>
                <h3>Aviso de infração dos direitos de autor e mecanismo de resolução de queixas</h3>

                <p>A Pedra do Couto não deverá ser responsabilizada por qualquer Infração dos direitos de propriedade intelectual relativos a materiais publicados ou transmitidos através do BackOffice ou da APP da Pedra do Couto, ou de itens publicitados na APP da Pedra do Couto, por Utilizadores finais ou terceiros.</p>
                <p>Respeitamos os direitos de propriedade intelectual de outros e esperamos de todos os que utilizam o Serviço que façam o mesmo.</p>
                <p>Poderemos, em circunstâncias apropriadas e ao nosso critério, remover ou impedir o acesso a materiais no Serviço que infrinjam os direitos de autor de terceiros.</p>
                <p>Poderemos também remover ou desativar ligações ou referências a localizações online que contenham materiais ou atividades transgressoras.</p>
                <p>No caso de utilizadores do Serviço que desrespeitem repetidamente os direitos de autor de terceiros, podemos cessar unilateralmente o direito desse indivíduo ao Serviço.</p>
                <p>Caso julgue que os seus direitos de autor estejam a ser infringidos por conteúdos encontrados no Serviço, deverá seguir os passos abaixo para apresentar uma notificação:</p>

                <ol type="a">
                  <li>Identifique por escrito o material protegido que acredita estar a ser desrespeitado;</li>
                  <li>Identifique por escrito o material nos Serviço que alega estar a violar os direitos de cópia, e apresente informação suficiente para identificar a localização do alegado material transgressor (por exemplo, o nome de utilizador do alegado transgressor e o nome do estabelecimento em que está publicado);</li>
                  <li>Inclua a seguinte declaração; "Acredito de boa-fé que o uso do conteúdo no serviço conforme explicitado acima não está autorizado pelo detentor dos direitos de autor, o seu agente ou a lei.";</li>
                  <li>Inclua a seguinte declaração "Declaro sob pena de perjúrio que a informação no meu aviso está correta e que eu sou o detentor dos direitos de autor ou estou autorizado a agir em seu nome";</li>
                  <li>Disponibilize a sua informação de contacto, incluindo a sua morada, número de telefone e endereço de e-mail (se disponível);</li>
                  <li>Inclua a sua assinatura física ou eletrónica;</li>
                  <li>Envie a comunicação escrita para <a href="mailto:geral@Pedra do Couto.eu" target="_blank">geral@Pedra do Couto.eu</a>.</li>
                </ol>

              </li>


              <li>
                <h3>Geolocalização</h3>

                <p>A Pedra do Couto poderá recolher e utilizar dados precisos sobre localizações, incluindo a localização geográfica em tempo real do computador ou dispositivo móvel do Utilizador, sempre que o Utilizador autorize. Estes dados de localização podem ser recolhidos e utilizados pela Pedra do Couto para mostrar aos Utilizadores a localização de origem de um local ou serviço. Os Utilizadores poderão optar por desativar a Geolocalização nos seus dispositivos conforme se detalha na <a href="https://Pedra do Couto.eu/privacy-policy" target="_blank">Política de Privacidade e Proteção de Dados</a>.</p>

              </li>


              <li>
                <h3>Compras de bilhetes no site Pedra do Couto</h3>

                <ol type="a">
                  <li>É da responsabilidade do utilizador confirmar sempre os dados que insere na compra de artigos/bilhetes através da Pedra do Couto.</li>
                  <li>Ao valor total da transacção acresce ainda os custos de transação online com acréscimo do IVA à taxa legal em vigor, consoante o evento escolhido. Estes custos aplicam-se a todas as compras efectuadas no domínio da Pedra do Couto.</li>
                  <li>Apenas se efectuam trocas ou devoluções no caso de o evento ser cancelado e toda a resolução da devolução ou troca é da total responsabilidade da entidade promotora.</li>
                  <li>A Pedra do Couto não assume, em qualquer caso, as obrigações e responsabilidades do promotor.</li>
                  <li>O cancelamento ou alteração de um evento que se realize ao ar livre ou que esteja dependente das condições climáticas, não obriga o promotor a devolver o valor do bilhete.</li>
                  <li>No caso de o evento ser cancelado, adiado ou seja solicitada a devolução da compra de um bilhete, a Pedra do Couto não procede ao reembolso dos custos de transacção e dos custos de envio da compra.</li>
                </ol>

              </li>


              <li>
                <h3>Legislação Aplicável</h3>

                <p>A relação entre Pedra do Couto e o Utilizador, reger-se-á e interpretar-se-á em conformidade com os Termos e Condições que em matéria de interpretação, validade e execução, funcionarão pela legislação portuguesa; e qualquer litígio será submetido aos Tribunais de Lisboa, exceto se o Utilizador solicitar os tribunais de seu domicílio de residência.</p>

              </li>


              <li>
                <h3>Resolução extrajudicial de conflitos</h3>

                <p>Em conformidade com o estabelecido no artigo 14 do Regulamento (UE) nº 524/2013, a Pedra do Couto informa que a Comissão Europeia estabeleceu uma Plataforma de resolução extrajudicial de litígios online para resolver os litígios relativos a obrigações contratuais derivadas de contratos de compra/venda ou de prestação de Serviços celebrados online entre um consumidor residente na União e um comerciante estabelecido na União. Pelo que o Cliente pode entrar a esta Plataforma através do link <a href="http://ec.europa.eu/consumers/odr" target="_blank">http://ec.europa.eu/consumers/odr</a>.</p>
              </li>
            </ol>

          </div>
          <!-- FIM -->
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