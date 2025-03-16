<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/phpmailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/phpmailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/plugins/phpmailer/src/SMTP.php';
class BilheteGenerator {
    private $evento;
    private $convite;
    private $data;
    private $db;

    public function __construct($db, $data, $evento, $convite) {
        $this->db = $db;
        $this->data = $data;
        $this->evento = $evento;
        $this->convite = $convite;
    }

    public function generateAndSendTicket() {
        if($this->data["email"]) {
            $this->prepareAndSendEmail();
        }
        $this->insertIntoDatabase();
    }

    private function prepareAndSendEmail() {

        $this->generateQRCode();

        $this->sendEmail();
    }

    private function prepareDataForDatabase() {
        $data_nascimento = DateTime::createFromFormat('d-m-Y', $this->data['data_nascimento']);
        $arrUpdate = [
            'nome' => $this->data['nome'],
            'data_nascimento' => $data_nascimento->format('Y-m-d'),
            'telemovel' => $this->data['telemovel'],
            'email' => $this->data['email'],
            'qrcode' => $this->data['qrcode'],
            'qrcode_data' => date('Y-m-d H:i:s'),
            'qrcode_ip' => real_getip(),
            'qrcode_user_agent' => $_SERVER["HTTP_USER_AGENT"],
            'estado' => 1,
            'marketing' => $this->data['marketing'],
            'termos_condicoes' => $this->data['termos_condicoes']
        ];

        return $arrUpdate;
    }

    private function generateQRCode() {
        include_once($_SERVER["DOCUMENT_ROOT"] . '/plugins/phpqrcode/lib/full/qrlib.php');

        ob_start();
        QRcode::png($this->data["qrcode"], null, QR_ECLEVEL_L, 35, 2);
        $this->data['qrcode_image'] = ob_get_clean();
    }
    private function getMessage() {
        $host = "https://" . $_SERVER["HTTP_HOST"] . "";
        $message = "<html>
        <head>
            <style>html,body { padding:0; margin:0; font-family: Inter, Helvetica, 'sans-serif'; } a:hover { color: #009ef7; }</style>

        </head>
        <body>
    <!--begin::Email template-->
                <div id='#kt_app_body_content' style='background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%;'>
                    <div style='background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;'>
                        <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' height='auto' style='border-collapse:collapse'>
                            <tbody>
                                <tr>
                                    <td align='center' valign='center' style='text-align:center; padding-bottom: 10px'>
                                        <!--begin:Email content-->
                                        <div style='text-align:center; margin:0 60px 34px 60px'>
                                            <!--begin:Logo-->
                                            <div style='margin-bottom: 10px'>
                                                <a href='https://www.pedradocouto.net/' rel='noopener' target='_blank'>
                                                    <img alt='Logo' src='".$host . '/temas/public/images/pedra.png' ."' width='64px' height='80px' style='height: 80px; width:64px;' />
                                                </a>
                                            </div>
                                            <!--end:Logo-->
                                            <!--begin:Media-->
                                            <div style='margin-bottom: 15px'>
                                                <img alt='Logo' src='" . $host . "/temas/public/assets/media/email/icon-positive-vote-2.svg' />
                                            </div>
                                            <div style='font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;'>
                                                <p style='margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700'>Obrigado pela tua confiança!</p>
                                                <p style='margin-bottom:2px; color:#7E8299'>Descarrega o bilhete em anexo</p>
                                                <p style='margin-bottom:2px; color:#7E8299'>e apresenta na entrada do recinto</p>
                                            </div>
                                            <!--end:Text-->
                                        </div>
                                        <!--end:Email content-->
                                    </td>
                                </tr>
                                <tr style='display: flex; justify-content: center; margin:0 60px 35px 60px'>
                                    <td align='start' valign='start' style='padding-bottom: 10px;'>
                                        <p style='color:#181C32; font-size: 18px; font-weight: 600; margin-bottom:13px'>Não te esqueças:</p>
<!--begin::Wrapper-->
<div style='background: #F9F9F9; border-radius: 12px; padding:35px 30px'>
    <!--begin::Item-->
    <table cellpadding='0' cellspacing='0' border='0' width='100%' style='border-collapse: collapse;'>
        <tbody>
            <tr>
                <td style='width: 40px; padding-right: 13px; vertical-align: top;'>
                    <img alt='Logo' src='" . $host . "/temas/public/assets/media/email/icon-polygon.svg' style='display: block; width: 40px; height: auto;' />
                </td>
                <td style='vertical-align: top;'>
                    <a href='#' style='color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif; text-decoration: none;'>O bilhete é único e intransmissível.</a>
                    <p style='color:#5E6278; font-size: 13px; font-weight: 500; margin: 3px 0 0; font-family:Arial,Helvetica,sans-serif;'>Não partilhes o teu bilhete com mais ninguém.</p>
                    <div style='border-top: 1px dashed #5E6278; margin-top: 15px;'></div>
                </td>
            </tr>
        </tbody>
    </table>
    <!--end::Item-->
    <!--begin::Item-->
    <table cellpadding='0' cellspacing='0' border='0' width='100%' style='border-collapse: collapse;'>
        <tbody>
            <tr>
                <td style='width: 40px; padding-right: 13px; vertical-align: top;'>
                    <img alt='Logo' src='" . $host . "/temas/public/assets/media/email/icon-polygon.svg' style='display: block; width: 40px; height: auto;' />
                </td>
                <td style='vertical-align: top;'>
                    <a href='#' style='color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif; text-decoration: none;'>Usa o bilhete na data correcta.</a>
                    <p style='color:#5E6278; font-size: 13px; font-weight: 500; margin: 3px 0 0; font-family:Arial,Helvetica,sans-serif;'>Este bilhete é de uso único para " . $this->evento["data_extenso"] . " no evento " . $this->evento["nome"] . ".</p>
                    <div style='border-top: 1px dashed #5E6278; margin-top: 15px;'></div>
                </td>
            </tr>
        </tbody>
    </table>
    <!--end::Item-->
</div>
<!--end::Wrapper-->

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Email template-->
            </body>
        </html>";

        return $message;
    }

    private function sendEmail() {
        $assunto = "Bilhete " . $this->evento["nome"] . " | Guest List " . $this->convite["nome_rp"] .  "";

        $mensagem = $this->getMessage();
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.staffpedradocouto.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'noreply@staffpedradocouto.com';                     //SMTP username
            $mail->Password   = '12qwaszx.-,';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // Recipients
            $mail->setFrom('noreply@staffpedradocouto.com', 'Pedra do Couto');
            $mail->addAddress($this->data['email'], $this->data['nome']);

            // Conteúdo do e-mail
            $mail->CharSet = "UTF-8";
            $mail->Encoding = 'base64';
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $mensagem;
            $mail->AltBody = $mensagem;
            // Anexo do bilhete (QR code)
            $mail->addStringAttachment($this->data['qrcode_image'], 'ticket.png', 'base64', 'image/png');

            $mail->send();
            $envioEmail = true;
        } catch (Exception $e) {
            // Tratamento de erros no envio de e-mail
        }
    }

    private function insertIntoDatabase() {
        $arrUpdate = $this->prepareDataForDatabase();

        $this->db->update("eventos_convites", $arrUpdate, "id=" . $this->convite['id']);
    }
}