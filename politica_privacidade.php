<!DOCTYPE html>
<html lang="en">

<head>
  <title>Política de Privacidade - Pedra do Couto</title>
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
            <h1 class="mb-4">Política de Privacidade</h1>
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
            <h2 class="mb-2">Política de Privacidade e Proteção de Dados</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">
            <p>É ambição da Pedra do Couto ser uma marca exemplar. Damos grande valor à honestidade e à transparência e estamos empenhados em construir uma relação sólida e duradoura com os nossos colaboradores, utilizadores e clientes, baseada na confiança e no benefício mútuo. Parte deste compromisso traduz‑se em proteger e respeitar a sua privacidade e as suas escolhas. É por essa razão que estabelecemos a nossa Política de Privacidade na íntegra abaixo.</p>


            <h3>O nosso compromisso de privacidade</h3>

            <ol>
              <li>Respeitamos a sua privacidade e as suas escolhas.</li>
              <li>Certificamo‑nos que a privacidade e a segurança estão integradas em tudo o que fazemos.</li>
              <li>Enviamos comunicações de marketing exceto se expressamente nos tiver informado que não as deseja receber. Poderá mudar de ideias a qualquer momento.</li>
              <li>Nunca oferecemos ou vendemos dados.</li>
              <li>Estamos empenhados em manter os seus dados de forma segura e protegida, o que inclui trabalharmos unicamente com parceiros de confiança.</li>
              <li>Estamos empenhados em ser abertos e transparentes no que respeita à forma como usamos os seus dados.</li>
              <li>Não utilizamos os seus dados de formas que não lhe tenham sido informadas.</li>
              <li>Respeitamos os seus direitos e tentamos sempre acomodar os seus pedidos na medida que seja possível, em linha com as nossas próprias responsabilidades legais.</li>
            </ol>

            <p>Para mais informações acerca das nossas práticas de privacidade, estabelecemos abaixo os tipos de dados pessoais que podemos receber diretamente de si ou da sua interação connosco; como os podemos utilizar; com quem os podemos partilhar; como os protegemos e mantemos seguros; bem como quais os seus direitos relativos aos seus dados pessoais.</p>
            <p>Nem todas as situações lhe serão aplicáveis. A presente política de privacidade fornece uma visão geral de todas as situações possíveis em que podemos interagir mutuamente.</p>
            <p>Quando partilha dados pessoais connosco ou quando recolhemos dados pessoais acerca de si, utilizamo‑los em conformidade com a presente Política. Por favor leia cuidadosamente a presente informação.</p>
            <p>Se tiver quaisquer questões ou preocupações relativas aos seus dados pessoais, por favor contacte‑nos através do seguinte endereço de correio eletrónico: <a href="mailto:info@pedradocouto.net" target="_blank">info@pedrodocouto.net</a>.</p>



          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">O que encontrará na presente política de privacidade</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">
            <p>Quem somos?<br>
              O que são dados pessoais?<br>
              Que dados recolhemos de si e como os utilizamos?<br>
              Como recolhemos ou recebemos os seus dados?<br>
              Processos de Decisão Automatizados Profiling<br>
              Quem pode aceder aos seus dados pessoais?<br>
              Onde armazenamos os seus dados pessoais?<br>
              Quanto tempo guardamos os seus dados pessoais?<br>
              Os meus dados pessoais estão seguros?<br>
              Ligações eletrónicas para sites de entidades externas e registo em redes sociais<br>
              Os seus direitos e escolhas</p>


          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Entidade responsável pelo tratamento</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <p>A Pedra do Couto é responsável pelo tratamento dos dados pessoais que partilha connosco, para efeitos de legislação de proteção de dados aplicável.</p>
            <p>Representante do responsável pelo tratamento de dados: {NOME}.</p>

          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">O que são dados pessoais?</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <p>"Dados pessoais" significa qualquer informação que possa identificá‑lo, quer diretamente (por exemplo, o seu nome), quer indiretamente (por exemplo, dados apresentados sob um pseudónimo, tal como um número de identificação único). Significa, portanto, que os dados pessoais incluem informação como o endereço eletrónico, moradas pessoais, número de telemóvel, nomes de utilizador, fotografias de perfis, preferências pessoais e hábitos de compra, conteúdos gerados pelos utilizadores, informação financeira e informação sobre bem‑estar. Pode também incluir identificadores numéricos únicos como o endereço IP do computador ou o endereço do aparelho móvel, bem como os cookies.</p>

          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Informação para Clientes Empresariais</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <p>A partir de 25 de Maio de 2018 passou a ser aplicável o Regulamento Geral sobre a Proteção de Dados Pessoais – Regulamento n.º 2016/679 do Parlamento Europeu e do Conselho, de 27 de Abril de 2016, que estabelece as regras relativas à proteção, tratamento e livre circulação dos dados pessoais das pessoas singulares e que se aplica diretamente a todas as entidades que procedam ao tratamento desses dados, em qualquer Estado‑Membro da União Europeia, nomeadamente Portugal.</p>
            <p>O objetivo desta comunicação é dar‑lhe a conhecer que, relativamente ao tratamento dos dados pessoais, a Pedra do Couto cumpre o disposto na legislação de proteção de dados em vigor a cada momento, nomeadamente as decorrentes do Regulamento acima referido.</p>
            <p>No que aos eventuais dados pessoais de utilizadores do serviço, administradores de conta e representantes respeita, bem como sempre que esteja em causa um empresário em nome individual, nomeadamente em termos de identidade e contactos do responsável pelo tratamento, encarregado da proteção de dados, finalidades, destinatários, prazo de conservação, decisões automatizadas e direitos referentes ao tratamento de dados pessoais, aplicar‑se‑á, com as devidas adaptações, o previsto na presente Política de Privacidade.</p>


          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Que dados recolhemos de si e como os utilizamos</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <p>Como recolhemos ou recebemos os seus dados?</p>
            <p>Podemos recolher ou receber os dados enviados por si através dos nossos websites, formulários, apps, equipamentos, produtos comercializados pela Pedra do Couto ou páginas nas redes sociais. Às vezes fornece‑nos diretamente (por exemplo, quando cria uma conta, quando nos contacta, quando faz compras a partir do(s) nosso(s) websites/apps ou lojas). Por vezes, recolhemos os dados (por exemplo, utilizando cookies para perceber como utiliza os nosso(s) websites/apps).</p>
            <p>Para além das situações em que tratamos dados para cumprimento de imposições legais (por exemplo, faturar ou celebrar certo tipo de contratos) tratamos os seus dados com as seguintes finalidades:</p>
            <p>Celebrar um contrato consigo (por exemplo para o fornecimento de produtos que adquiriu nos nossos websites/apps);</p>
            <p>Fornecer‑lhe o serviço que solicitou (por exemplo fornecendo‑lhe uma newsletter). Para além desta finalidade, os seus dados serão ainda tratados para prestação de serviços de terceiros, quando o tenha expressamente consentido; ou</p>
            <p>Efeitos de marketing – os seus dados pessoais, localização geográfica, perfil e/ou consumo são igualmente tratados para fins de marketing ou divulgação de ofertas de bens ou serviços da Pedra do Couto, caso o tenha autorizado. Estas comunicações pretendem dar‑lhe a conhecer novidades, promoções, campanhas e outras oportunidades de que poderá beneficiar.</p>
            <p>Os seus dados pessoais podem ser comunicados a prestadores de serviços da Pedra do Couto, para efeitos de prestação dos serviços, e a autoridades judiciais, fiscais e regulatórias, com a finalidade do cumprimento de imposições legais.</p>
            <p>Estabelecemos pormenores adicionais na tabela abaixo, explicando:</p>

            <ol>
              <li>Durante que interações podem ser fornecidos ou recolhidos os seus dados? Esta coluna explica em que atividade ou situação está envolvido quando utilizamos ou recolhemos os seus dados.</li>
              <li>Que dados pessoais podemos receber diretamente de si ou que resultam da sua interação connosco? Esta coluna explica o tipo de dados que podemos recolher acerca de si, dependendo da situação.</li>
              <li>Como e porquê podemos utilizar os seus dados? Esta coluna explica o que podemos fazer com os seus dados e as finalidades da sua recolha.</li>
              <li>
                <p>Qual é a base legal para a utilização dos seus dados pessoais? Esta coluna explica a razão pela qual podemos utilizar os seus dados. Dependendo da finalidade para a qual os dados são utilizados, a base legal para o tratamento dos seus dados pode ser:</p>
                <ul>
                  <li>O seu consentimento;</li>
                  <li>O nosso interesse legítimo, que pode consistir na:
                    <ul>
                      <li>Melhoria dos nossos produtos e serviços: mais especificamente, o nosso interesse comercial em nos ajudar a compreender melhor as suas necessidades e expectativas e, portanto, a melhorar os nossos serviços, websites / Apps / equipamentos, produtos e marcas para benefício dos nossos utilizadores.</li>
                      <li>Prevenção da fraude: para garantirmos que um pagamento foi concluído, sem fraude e sem apropriação indevida.</li>
                      <li>Proteger as nossas ferramentas: manter as ferramentas usadas por si (os nossos websites/Apps/equipamentos) protegidas e seguras e para garantir que estão a trabalhar de forma adequada e que estão continuamente a melhorar.</li>
                    </ul>
                  <li>A execução de um contrato: mais especificamente na prestação dos serviços que nos solicitou;</li>
                  <li>Fundamento legal quando o tratamento é obrigatório por lei.</li>
                </ul>
              </li>
            </ol>




          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Informação geral acerca das suas interações connosco e as respetivas consequências para os seus dados</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <table>
              <tr>
                <td>
                  <p>Durante que interações podem ser fornecidos os seus dados?</p>
                </td>
                <td>
                  <p>Que dados pessoais podemos receber diretamente por si ou que resultam da sua interação connosco?</p>
                </td>
                <td>
                  <p>Como e porquê podemos utilizar os seus dados pessoais?</p>
                </td>
                <td>
                  <p>Qual é o fundamento legal para a utilização dos seus dados pessoais?</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Criação e gestão da conta:<br>A informação recolhida durante a criação de uma conta na Pedra do Couto através do registo numa rede social, do nosso website ou na app Pedra do Couto.</p>
                </td>
                <td>
                  <p>Dependendo de quanto interage connosco, esses dados podem incluir:</p>
                  <ul>
                    <li>Nome e apelido;</li>
                    <li>Género;</li>
                    <li>Endereço eletrónico;</li>
                    <li>Morada;</li>
                    <li>Telefone;</li>
                    <li>Fotografia;</li>
                    <li>Data de nascimento;</li>
                    <li>Nome de utilizador e palavra‑passe;</li>
                    <li>Perfil nas redes sociais;</li>
                    <li>Preferências do Utilizador.</li>
                  </ul>
                </td>
                <td>
                  <p>Para:</p>
                  <ul>
                    <li>Gerir promoções, inquéritos ou concursos;</li>
                    <li>Responder às suas questões;</li>
                    <li>Permitir‑lhe gerir as suas preferências;</li>
                    <li>Enviar comunicações de marketing, que podem ser personalizadas quanto ao seu perfil (de acordo com os seus dados e as suas preferências);</li>
                    <li>Disponibilizar serviços personalizados baseados nas suas características,</li>
                    <li>Melhorar o nosso website e a nossa app;</li>
                    <li>Recolher dados estatísticos;</li>
                    <li>Proteger os nossos websites e a nossa app, e protegê‑lo contra fraude;</li>
                  </ul>
                </td>
                <td>
                  <p>Execução de um contrato para fornecer‑lhe o serviço que solicitou (por exemplo, a criação de uma conta ou adquirir um produto).</p>
                  <p>Consentimento para lhe enviar diretamente comunicações de marketing.</p>
                  <p>Interesse Legítimo para garantir que o nosso website permanece seguro, para o proteger contra a fraude e para nos ajudar a melhor compreender as suas necessidades e, dessa forma, melhorar os nossos serviços e produtos.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Subscrição de newsletters e de comunicações:</p>
                </td>
                <td>
                  <p>Dependendo de quanto interage connosco, esses dados podem incluir:</p>
                  <ul>
                    <li>Nome e apelido;</li>
                    <li>Género;</li>
                    <li>Endereço eletrónico;</li>
                    <li>Descrição pessoal e preferências;</li>
                    <li>Perfil nas redes sociais.</li>
                  </ul>
                </td>
                <td>
                  <p>Para:</p>
                  <ul>
                    <li>Enviar‑lhe comunicações de marketing, que podem ser personalizadas ao seu "perfil", baseadas nos dados pessoais que sabemos acerca de si e das suas preferências;</li>
                    <li>Manter uma lista de remoção atualizada se solicitou que não fosse contactado;</li>
                  </ul>
                </td>
                <td>
                  <p>Execução de um contrato para fornecer‑lhe o serviço que solicitou (por exemplo, adquirir um produto).</p>
                  <p>Consentimento para lhe enviar diretamente comunicações de marketing.</p>
                  <p>Fundamento legal para manter os seus dados numa lista de remoção, se nos solicitou para não lhe enviar mais marketing diretamente.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Atividades promocionais:</p>
                  <p>A informação recolhida durante um jogo, concursos, ofertas promocionais, pedido de amostra, inquéritos.</p>
                </td>
                <td>
                  <p>Dependendo de quanto interage connosco, esses dados podem incluir:</p>
                  <ul>
                    <li>Nome e apelido;</li>
                    <li>Endereço eletrónico;</li>
                    <li>Número de telefone;</li>
                    <li>Data de nascimento;</li>
                    <li>Género;</li>
                    <li>Morada;</li>
                    <li>Descrição pessoal e preferências;</li>
                    <li>Perfil nas redes sociais;</li>
                    <li>Outra informação que tenha partilhado connosco acerca de si (por exemplo através da página "A minha Conta", através do contacto connosco ou fornecendo os seus próprios conteúdos tais como fotografias ou uma avaliação, ou uma questão através da participação num concurso, jogo ou inquérito).</li>
                  </ul>
                </td>
                <td>
                  <ul>
                    <li>Para completar as tarefas que nos solicitou, como por exemplo gerir a sua participação em concursos, jogos e inquéritos, incluindo para tomar em consideração o seu feedback e sugestões;</li>
                    <li>Para efeitos estatísticos;</li>
                    <li>Enviar‑lhe comunicações de marketing</li>
                  </ul>
                </td>
                <td>
                  <p>Execução de um contrato para fornecer‑lhe o serviço que solicitou.</p>
                  <p>Interesse legítimo para nos ajudar a compreender melhor as suas necessidades e expectativas e, portanto, melhorar os nossos serviços, websites / Apps / equipamentos, produtos e marcas.</p>
                  <p>Consentimento para enviar‑lhe comunicações de marketing direto.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Pesquisa Online:</p>
                  <p>Informação recolhida por cookies ou por tecnologias similares ("Cookies" *) como parte do seu motor de busca em website/apps da Pedra do Couto e/ou em website/apps de entidades externas.</p>
                  <p>Para informação sobre Cookies específicos colocados através de um determinado website/app, por favor consulte a tabelas de cookies relevante.</p>
                  <p>* Cookies são pequenos ficheiros de texto armazenados no seu equipamento (computador, tablet ou telemóvel) quando está na Internet.</p>
                </td>
                <td>
                  <p>Dependendo de quanto interage connosco, esses dados podem incluir:</p>
                  <ul>
                    <li>Dados relacionados com a sua utilização dos nossos websites/apps:</li>
                    <li>Dados de registo;</li>
                    <li>Páginas que consultou;</li>
                    <li>Vídeos que viu;</li>
                    <li>Anúncios nos quais clica ou seleciona;</li>
                    <li>Produtos acerca dos quais faz pesquisas;</li>
                    <li>A sua localização;</li>
                    <li>Duração da sua visita.</li>
                  </ul>
                  <p>Informação Técnica:</p>
                  <ul>
                    <li>Endereço IP;</li>
                    <li>Informação do motor de busca;</li>
                    <li>Informação do equipamento.</li>
                  </ul>
                </td>
                <td>
                  <p>Nós usamos Cookies, quando relevante, com outros dados pessoais que já partilhou connosco (p. ex.: se já subscreveu ou não as nossas newsletters por correio eletrónico) ou para os seguintes efeitos:</p>
                  <ul>
                    <li>Para permitir o funcionamento adequado dos nossos websites/apps;</li>
                    <li>A visualização adequada do conteúdo;</li>
                    <li>Criação e para relembrar do seu registo;</li>
                    <li>Personalização de interface tal como a língua;</li>
                    <li>Parâmetros associados ao seu equipamento incluindo a resolução do seu ecrã, etc.;</li>
                    <li>Melhoria dos nossos websites/apps, por exemplo, testando ideias novas;</li>
                    <li>Para garantir que o website/app está protegido e seguro e para protegê‑lo a si contra fraude ou uso indevido dos nossos websites ou serviços, por exemplo através da realização de resolução de problemas;</li>
                    <li>Ter a funcionar sistemas estatísticos;</li>
                    <li>Para evitar que os visitantes sejam registados duas vezes;</li>
                    <li>Para conhecer as reações dos utilizadores às nossas campanhas publicitárias;</li>
                    <li>Para melhorar as nossas ofertas;</li>
                    <li>Para saber como descobriu os nossos websites/apps;</li>
                    <li>Para entregar publicidade comportamental online;</li>
                    <li>Para lhe mostrar anúncios online de produtos que possam ter interesse para si, baseado nos seus comportamentos anteriores;</li>
                    <li>Para lhe mostrar anúncios e conteúdos em plataformas de social media;</li>
                    <li>Para personalizar os nossos serviços a si;</li>
                    <li>Para enviar‑lhe recomendações, marketing, ou conteúdos baseados no seu perfil e interesses;</li>
                    <li>Para visualizar os websites/apps de forma personalizada, nomeadamente recordando o seu registo, a sua língua, os cookies de personalização do interface do utilizador (i.e., os parâmetros associados ao seu equipamento incluindo a resolução do seu ecrã, preferência quanto ao tipo de letra, etc.);</li>
                    <li>Para permitir a partilha dos nossos conteúdos na social media (os botões de partilha têm como intuito a visualização do site).</li>
                  </ul>
                </td>
                <td>
                  <p>Interesse legítimo para garantir que estamos a pôr à sua disposição websites/apps, anúncios e comunicações que estão a funcionar de forma adequada e que estão continuamente melhores para os cookies que são (i) essenciais para o funcionamento dos nossos websites/apps, (ii) usados para manter os nossos websites/apps protegidos e seguros.</p>
                  <p>Consentimento para todos os outros cookies.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Conteúdos gerados pelos utilizadores:</p>
                  <p>A informação recolhida quando submeteu conteúdos numa das nossas plataformas ou aceitou a reutilização por nós do conteúdo que publicou em plataformas de redes sociais.</p>
                </td>
                <td>
                  <p>Dependendo de quanto interage connosco, esses dados podem incluir:</p>
                  <ul>
                    <li>Nome e apelido ou pseudónimo;</li>
                    <li>Endereço eletrónico;</li>
                    <li>Fotografia;</li>
                    <li>Descrição pessoal e preferências;</li>
                    <li>Perfil nas redes sociais;</li>
                    <li>Outra informação que tenha partilhado connosco acerca de si (por exemplo através da página "A minha Conta", através do contacto connosco ou fornecendo os seus próprios conteúdos tais como fotografias ou uma avaliação, ou uma questão através da participação num concurso, jogo ou inquérito).</li>
                  </ul>
                </td>
                <td>
                  <p>Em conformidade com os termos e condições específicas aceite por si:</p>
                  <ul>
                    <li>Para publicar a sua avaliação ou conteúdo;</li>
                    <li>Para promover os nossos serviços;</li>
                    <li>Para efeitos estatísticos.</li>
                  </ul>
                </td>
                <td>
                  <p>Consentimento para reutilizar o conteúdo que publicou online.</p>
                  <p>Interesse legítimo para nos ajudar a melhor compreender as suas necessidades e expectativas e, dessa forma, a melhorar os nossos serviços, produtos e marcas.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Utilização de Apps e equipamentos:</p>
                  <p>A informação recolhida como parte da sua utilização das nossas Apps e/ou equipamentos.</p>
                </td>
                <td>
                  <p>Dependendo de quanto interage connosco, esses dados podem incluir:</p>
                  <ul>
                    <li>Nome e apelido;</li>
                    <li>Endereço eletrónico;</li>
                    <li>Localização;</li>
                    <li>Data de nascimento;</li>
                    <li>Descrição pessoal e preferências;</li>
                    <li>Fotografia;</li>
                    <li>Geolocalização.</li>
                  </ul>
                </td>
                <td>
                  <p>Para:</p>
                  <ul>
                    <li>Fornecer‑lhe o serviço solicitado (por exemplo, adquirir os nossos serviços através da App ou em websites de comércio eletrónico relacionados; conselhos e notificações relativamente à atividade noturna);</li>
                    <li>Analisar os seus gostos e recomendar os estabelecimentos adequados;</li>
                    <li>Para acompanhamento e melhoria das nossas Apps e equipamentos;</li>
                    <li>Para efeitos estatísticos.</li>
                  </ul>
                </td>
                <td>
                  <p>Execução de um contrato para fornecer‑lhe o serviço solicitado.</p>
                  <p>Interesse legítimo para melhorar continuamente os nossos produtos e serviços para corresponder às suas necessidades e expectativas e para efeitos de investigação e inovação.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Questões:</p>
                  <p>Informação recolhida quando coloca questões (por exemplo através do nosso serviço de apoio ao consumidor) relativo às nossas marcas, os nossos produtos e à sua utilização.</p>
                </td>
                <td>
                  <p>Dependendo de quanto interage connosco, esses dados podem incluir:</p>
                  <ul>
                    <li>Nome e apelido;</li>
                    <li>Número de telefone;</li>
                    <li>Endereço eletrónico;</li>
                    <li>Outra informação que tenha partilhado connosco relativamente à sua questão (que pode incluir informação sobre bem‑estar e saúde).</li>
                  </ul>
                </td>
                <td>
                  <ul>
                    <li>Para responder às suas questões;</li>
                    <li>Para acompanhar e impedir qualquer efeito indesejado relativo à utilização dos nossos produtos;</li>
                    <li>Para realizar e acompanhar as medidas corretivas adotadas, quando necessário.</li>
                  </ul>
                </td>
                <td>
                  <p>Consentimento para tratar da sua questão.</p>
                  <p>Fundamento legal para cumprir com a obrigação legal de acompanhar os efeitos indesejados dos respetivos produtos.</p>
                </td>
              </tr>
              <tr>
                <td>
                  <p>Contrato de Trabalho ou de Prestação de Serviços:</p>
                  <p>Informação recolhida quando celebra connosco um Contrato de Trabalho ou de Prestação de Serviços.</p>
                </td>
                <td>
                  <p>Estes dados incluem:</p>
                  <ul>
                    <li>Nomes e apelidos;</li>
                    <li>Nº de Cartão de Cidadão;</li>
                    <li>Nº de Identificação Fiscal;</li>
                    <li>Morada;</li>
                    <li>Nº de Segurança Social.</li>
                  </ul>
                </td>
                <td>
                  <ul>
                    <li>Para completar o contrato com os elementos legalmente exigidos;</li>
                    <li>Para faturar e emitir recibos.</li>
                  </ul>
                </td>
                <td>
                  <p>Fundamento legal para cumprir com a obrigação legal de inserir certos elementos no Contrato de Trabalho e de emitir faturas e recibos.</p>
                </td>
              </tr>
            </table>


            <h4>Processos de decisão automatizados</h4>

            <p>Tendo em vista proteger as transações efetuadas através dos nossos websites/apps/equipamentos contra a fraude e a apropriação indevida, utilizamos soluções de fornecimento por entidades externas. O método de deteção da fraude é baseado em, por exemplo, comparações simples, associações, aglomerações, previsões e deteção de entidades estranhas utilizando agentes inteligentes, técnicas de fusão de dados e várias técnicas de exploração de dados.</p>
            <p>Este processo de deteção da fraude pode ser totalmente automatizado ou pode envolver a intervenção humana, na qual uma pessoa toma a decisão final. Em qualquer dos casos, tomamos todas as precauções e garantias adequadas para limitar o acesso aos seus dados.</p>
            <p>Como resultado da deteção automática da fraude, pode</p>

            <ul>
              <li>sofrer atraso no tratamento da sua encomenda/pedido enquanto a sua transação está a ser por nós analisada; e</li>
              <li>ser limitado ou excluído dos benefícios de um serviço se for identificado um risco de fraude. Tem direito de acesso à informação que serve de base à nossa decisão. Por favor consulte a secção "Os seus direitos e escolhas" abaixo.</li>
            </ul>


          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Criação de perfis/Profiling</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">


            <p>Quando enviamos ou mostramos comunicações ou conteúdos personalizados, podemos usar algumas técnicas classificadas como "criação de perfis" (i.e. qualquer forma de tratamento automatizada de dados pessoais que consistam na utilização dos referidos dados para avaliar certos aspetos pessoais relativos a um indivíduo, em particular para analisar ou prever aspetos relativos às preferências pessoais, interesses, situação económica, comportamento, localização, saúde, fiabilidade, ou movimentos). Significa, portanto, que podemos recolher dados pessoais acerca de si nos diferentes cenários mencionados na tabela abaixo. Centralizamos estes dados e analisamo‑los para avaliar e prever as suas preferências pessoais e/ou interesses.</p>
            <p>Baseado na nossa análise, enviamos ou mostramos comunicações e/ou conteúdo personalizado aos seus interesses/necessidades.</p>
            <p>Tem direito a opor‑se à utilização dos seus dados para a "criação de perfis" em certas circunstâncias. Por favor consulte a secção "Os seus direitos e escolhas" abaixo.</p>


            <h4>Quem pode aceder aos seus dados pessoais?</h4>

            <p>Não partilhamos os seus dados pessoais com nenhumas empresas ou pessoas externas à Pedra do Couto. Apenas os utilizaremos para cumprir as nossas obrigações legais, podendo, nesse caso, comunicá‑los às entidades competentes, como Autoridade Tributária e Segurança Social e apenas se estivermos sujeitos ao dever de divulgar ou partilhar os seus dados pessoais de forma a cumprir com uma obrigação legal, ou de forma a fazer cumprir ou a aplicar os nossos termos de utilização ou outros termos e condições que tenha aceitado; ou para proteger os direitos, propriedade ou segurança da Pedra do Couto, dos nossos clientes ou funcionários.</p>
            <p>Apenas partilhamos os seus dados pessoais com entidades externas para efeitos de marketing direto com o seu consentimento. Neste contexto, os seus dados são tratados pela referida entidade, agindo como responsável pelo tratamento de dados e os seus próprios termos e condições e aviso de privacidade são aplicáveis. Deve verificar cuidadosamente a documentação dessa entidade externa antes de consentir a divulgação da sua informação a essa entidade.</p>
            <p>Podemos igualmente partilhar os seus dados pessoais apresentados sob um pseudónimo (não permitindo a identificação direta) com os nossos clientes e colaboradores.</p>
            <p>Quando autorizado, podemos também partilhar alguns dos seus dados pessoais, inclusive aqueles recolhidos através de Cookies entre a nossa plataforma, para harmonizar e atualizar a informação que partilha connosco e para personalizar as nossas comunicações.</p>
            <p>Podemos divulgar os seus dados pessoais aos nossos parceiros:</p>

            <ol>
              <li>No caso do serviço que subscreve tenha sido cocriado pela Pedra do Couto e por um parceiro (por exemplo, uma aplicação de marca conjunta). Nesse caso, a Pedra do Couto e o parceiro tratam os seus dados pessoais cada um para os seus próprios fins e, como tal, os seus dados são tratados:
                <ul>
                  <li>Pela Pedra do Couto de acordo com a presente Política de Privacidade;</li>
                  <li>Pelo parceiro agindo igualmente na qualidade de responsável pelo tratamento de dados, de acordo com os seus próprios termos e condições e em conformidade com a sua respetiva Política de Privacidade;</li>
                </ul>
              </li>
              <li>Caso tenha aceite receber marketing e comunicações comerciais de um parceiro da Pedra do Couto através de uma autorização exclusiva (por exemplo através de uma APP da marca e disponibilizada aos seus parceiros), os seus dados serão tratados pelo parceiro que age na qualidade de responsável pelo tratamento de dados, de acordo com os seus próprios termos e condições e em conformidade com a sua Política de Privacidade;</li>
              <li>Podemos publicar nos nossos suportes conteúdo das redes sociais. Caso consulte conteúdos de redes sociais no nosso website/APP, a Cookie da referida rede social pode ser armazenada no seu equipamento. Para mais informações consulte a nossa Política de Cookies.</li>
            </ol>


          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Não damos nem vendemos os seus dados pessoais</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <h4>Onde armazenamos os seus dados pessoais</h4>

            <p>Os dados que recolhemos de si serão armazenados na AWS, numa base de dados encriptada, acessível apenas pelo representante do responsável pelo tratamento, e não irão ser transferidos para qualquer outro local.</p>
            <p>Para mais informações, por favor contacte‑nos de acordo com a secção "Contactos" abaixo.</p>


            <h4>Quanto tempo guardamos os seus dados pessoais?</h4>

            <p>Só guardamos os seus dados pessoais pelo tempo necessário para atingir a finalidade para a qual detemos os seus dados pessoais, para responder às suas necessidades ou para cumprir com as nossas obrigações legais.</p>
            <p>Para determinar o período de retenção dos dados, usamos os seguintes critérios:</p>

            <ul>
              <li>Quando criar uma conta, nós conservamos os seus dados pessoais até que nos peça para os apagar ou após um período de inatividade definido nos termos dos regulamentos e orientações locais;</li>
              <li>Quando adquirir serviços, nós conservamos os seus dados pessoais durante a vigência da nossa relação comercial;</li>
              <li>Quando participar em ofertas promocionais, nós conservamos os seus dados pessoais durante a vigência da oferta promocional;</li>
              <li>Quando nos contactar para efetuar questões, nós conservamos os seus dados pessoais pelo período necessário para o tratamento da sua questão;</li>
              <li>Quando tenha consentido em marketing direto, nós conservamos os seus dados pessoais até que anule a subscrição ou nos peça para eliminá‑la ou, após um período de inatividade definido nos termos dos regulamentos e orientações locais;</li>
              <li>Quando são colocados cookies no seu computador, nós conservamo‑los enquanto for necessário para atingir os seus objetivos (por exemplo durante uma sessão para os cookies de sessão de identificação) definido nos termos dos regulamentos e orientações locais;</li>
            </ul>

            <p>Podemos reter alguns dos seus dados pessoais para cumprir as nossas obrigações legais ou regulamentares, bem como para permitirmos administrar os nossos direitos (por exemplo para fazer valer as nossas petições em Tribunais) ou para efeitos históricos.</p>
            <p>Quando já não necessitarmos de utilizar os seus dados pessoais, serão os mesmos removidos dos nossos sistemas e registos ou mantidos de forma anónima para que não possam ser identificados.</p>


            <h4>Os seus dados pessoais estão seguros?</h4>

            <p>Estamos empenhados em manter os seus dados pessoais protegidos e tomamos todas as precauções razoáveis para o fazer.</p>
            <p>Fazemos sempre o nosso melhor para proteger os seus dados pessoais e, após os termos recebido usamos procedimentos rigorosos e características de segurança para tentar impedir o acesso não autorizado.</p>


            <h4>Ligações eletrónicas a sites de entidades externas e para registo em redes sociais</h4>

            <p>Os nossos websites e Apps podem conter, ocasionalmente, ligações eletrónicas para e com origem nos websites da nossa rede de parceiros, publicitários e afiliados. Se seguir uma ligação para alguns destes websites, tenha por favor em consideração que estes websites têm as suas próprias políticas de privacidade e que não somos responsáveis por essas políticas.</p>
            <p>Verifique, por favor, estas políticas antes de submeter quaisquer dados pessoais a estes websites.</p>
            <p>Podemos também oferecer‑lhe a oportunidade de utilizar o seu registo nas redes socias. Se o fizer, por favor tenha em consideração que partilha a informação do seu perfil connosco, dependendo das suas configurações da plataforma de social media. Visite por favor a plataforma de social media relevante e analise as respetivas políticas de privacidade para perceber como são partilhados e usados os seus dados pessoais neste contexto.</p>


            <h4>Social Media e Conteúdos gerados pelos Utilizadores</h4>

            <p>Alguns dos nossos websites e Apps permitem aos utilizadores apresentar os seus próprios conteúdos. Por favor lembre‑se que qualquer conteúdo submetido numa das nossas plataformas de social media podem ser vistas pelo público, portanto, deve ser cauteloso quanto ao fornecimento de alguns dados pessoais, por exemplo, informação financeira ou pormenores de morada. Não somos responsáveis por quaisquer ações de outras pessoas se publicar dados pessoais numa das nossas plataformas de social media e recomendamos que não partilhe informação desse cariz.</p>


          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Os seus direitos e escolhas</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">


            <p>A Pedra do Couto respeita o seu direito à privacidade: é importante que seja capaz de controlar os seus dados pessoais.</p>
            <p>Em qualquer momento tem o direito de aceder aos seus dados pessoais, bem como, dentro dos limites do contratualizado e do Regulamento, de os alterar, opor‑se ao respetivo tratamento, decidir sobre o tratamento automatizado dos mesmos, retirar o consentimento e exercer os demais direitos previstos na lei (salvo quanto aos dados que são indispensáveis à prestação dos serviços pela Pedra do Couto, portanto, de fornecimento obrigatório, ou ao cumprimento de obrigações legais a que a Pedra do Couto esteja sujeita). Caso retire o seu consentimento, tal não compromete a licitude do tratamento efetuado até essa data. Tem o direito de ser notificado, nos termos previstos no Regulamento, caso ocorra uma violação dos seus dados pessoais, podendo apresentar reclamações perante as autoridades.</p>


            <h4>Tem, assim, os seguintes direitos:</h4>

            <ul>
              <li>
                <h5>O direito a ser informado</h5>
                <p>Tem direito a obter informação clara, transparente e facilmente compreensível acerca da forma como usamos os seus dados pessoais e acerca dos seus direitos. É por essa razão que lhe fornecemos informação na presente Política.</p>
              </li>
              <li>
                <h5>O direito de acesso</h5>
                <p>Tem direito de acesso aos dados pessoais que detemos sobre si (sujeito a determinadas limitações). Para o fazer, contacte‑nos por favor para os dados abaixo.</p>
              </li>
              <li>
                <h5>O direito de retificação</h5>
                <p>Tem direito à retificação dos seus dados pessoais se estiverem incorretos ou desatualizados e/ou a completá‑los se estes estiverem incompletos. Para o fazer, contacte‑nos por favor para os dados abaixo.</p>
              </li>
              <li>
                <h5>O direito de apagamento/direito a ser esquecido</h5>
                <p>Em alguns casos, tem direito a que os seus dados pessoais sejam apagados ou eliminados. Tenha em consideração que este não é um direito absoluto, uma vez que podemos ter fundamentos legais ou legítimos para a retenção dos seus dados pessoais. Se deseja que eliminemos os seus dados pessoais, contacte‑nos por favor para os dados abaixo.</p>
              </li>
              <li>
                <h5>O direito de oposição a marketing direto, inclusive à criação de perfis</h5>
                <p>Pode eliminar a subscrição ou optar por ser removido das nossas comunicações diretas de marketing a qualquer momento. A forma mais fácil de o fazer é contactar‑nos para os dados abaixo</p>
              </li>
              <li>
                <h5>O direito de, a qualquer momento, retirar o consentimento para o tratamento de dados baseado no consentimento</h5>
                <p>Pode retirar o seu consentimento ao nosso tratamento dos seus dados quando o referido tratamento for baseado no seu consentimento. A retirada do consentimento não afeta a legalidade do tratamento baseado no consentimento antes da respetiva retirada. Remetemos para a tabela inserida na secção "que dados recolhemos de si e como os utilizamos" especialmente a coluna "Qual é a nossa base legal para o tratamento dos seus dados?" para identificar quando o nosso tratamento é baseado no consentimento. Se deseja retirar o seu consentimento, contacte‑nos por favor para os dados abaixo.</p>
              </li>
              <li>
                <h5>O direito a opor‑se ao tratamento tendo como base interesses legítimos</h5>
                <p>Pode opor‑se em qualquer momento ao tratamento dos seus dados quando o referido tratamento tem por base um interesse legítimo. Remetemos para a tabela inserida na secção "que dados recolhemos e como os utilizamos", especialmente a coluna "Qual é a base legal para o tratamento dos seus dados?" para identificar quando o nosso tratamento é baseado em interesses legítimos. Para o fazer, contacte‑nos por favor para os dados abaixo.</p>
              </li>
              <li>
                <h5>O direito a apresentar uma queixa junto da autoridade supervisora</h5>
                <p>Tem direito de contactar a autoridade para a proteção de dados do seu país de forma a apresentar uma queixa contra as práticas de proteção de dados e privacidade da Pedra do Couto. Não hesite em contactar‑nos para os dados abaixo antes de apresentar qualquer queixa junto da autoridade de proteção de dados competente.</p>
              </li>
              <li>
                <h5>O direito à portabilidade dos dados</h5>
                <p>Tem direito de mover, copiar ou transferir os dados da nossa base de dados para outra. O presente aplica‑se apenas a dados que forneceu, quando o tratamento se basear no seu consentimento ou em contrato e o tratamento for realizado por meios automatizados. Remetemos para as tabelas inseridas na seção "que dados recolhemos e como os usamos", especialmente a coluna "Qual é a base legal para o tratamento dos seus dados?" para identificar quando o tratamento é baseado na execução de um contrato ou no consentimento. Para mais informações, contacte‑nos por favor para os dados abaixo.</p>
              </li>
              <li>
                <h5>O direito à restrição</h5>
                <p>Tem direito a solicitar a restrição do tratamento dos seus dados. Este direito significa que o nosso tratamento dos seus dados é restrito, portanto podemos armazená‑los, mas não os podemos utilizar, nem os submeter a tratamento adicional. Aplica‑se em circunstâncias limitadas enumeradas pelo Regulamento Geral de Proteção de Dados, que é como segue:</p>
                <ul>
                  <li>O rigor dos dados pessoais é posto em causa pelo titular dos dados (i.e., o/a senhor(a)), por um período, permitindo ao responsável verificar o rigor dos dados pessoais;</li>
                  <li>O tratamento é ilegal e o titular dos dados (i.e., o/a senhor(a)) opõe‑se à eliminação dos dados pessoais e, em vez disso, solicita a restrição do seu uso;</li>
                  <li>O responsável pelo tratamento de dados (i.e., a Pedra do Couto) não necessita mais dos seus dados pessoais para efeitos de tratamento, mas são‑lhes exigidos pelo titular dos dados para determinação, exercício ou defesa de pretensões legais;</li>
                  <li>O titular dos dados (i.e., o/a senhor(a)) opôs‑se ao tratamento com base em interesses legítimos do responsável pelo tratamento de dados, pendente de verificação da existência de fundamentos legítimos pelo responsável pelo tratamento de dados que prevaleça sobre os interesses legítimos do titular de dados.</li>
                </ul>
                <p>Se desejar solicitar a restrição, contacte‑nos por favor para os dados abaixo.</p>
              </li>
              <li>
                <h5>O direito a desativar os Cookies</h5>
                <p>As configurações dos motores de busca da Internet estão normalmente programadas para, por defeito, aceitar Cookies, mas pode facilmente ajustá‑las pela alteração das configurações do seu motor de busca. Muitos cookies são usados para melhorar a utilização e funcionalidade dos websites/apps; portanto a desativação dos cookies pode impedi‑lo de utilizar certas partes dos nossos websites/apps, conforme detalhado na Tabela de Cookies aplicável. Se deseja restringir ou bloquear todos os cookies que estão configurados pelos nossos websites/apps (que podem impedi‑lo de utilizar certas partes do site), ou quaisquer outros websites/apps, pode fazê‑lo através das configurações do seu motor de busca. A função de ajuda no seu motor de busca deve dizer‑lhe como fazê‑lo.</p>
              </li>
            </ul>


          </div>
          <div class="col-md-12 heading-section text-center ftco-animate mt-5">
            <span class="subheading"></span>
            <h2 class="mb-2">Contactos</h2>
          </div>
          <div class="col-md-12 text px-md-5 pt-4">

            <p>Se tiver quaisquer questões ou preocupações acerca do modo como tratamos e usamos os seus dados pessoais ou gostaria de exercer qualquer dos seus direitos acima, por favor contacte‑nos em <a href="mailto:support@Pedra do Couto.eu" target="_blank">support@Pedra do Couto.eu</a> ou por carta registada com aviso de receção para Pedra do Couto - Rua António Variações 5, 2740-315 Porto Salvo.</p>
            <p>Pode também contactar o responsável pela Proteção de Dados através do endereço <a href="mailto:privacy@Pedra do Couto.eu" target="_blank">privacy@Pedra do Couto.eu</a>.</p>
            <p>Estamos empenhados na proteção e confidencialidade dos seus dados pessoais. Tomámos as medidas técnicas e organizativas necessárias ao cumprimento do Regulamento, garantindo que o tratamento dos dados é lícito, leal, transparente e limitado às finalidades autorizadas. Adotámos as medidas que consideramos adequadas para assegurar a exatidão, integridade e confidencialidade dos dados pessoais, bem como os demais direitos.</p>
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