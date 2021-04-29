<?php

namespace Drupal\premios\Mail;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Simple email message class.
 */
final class MailPremios
{
    /**
     * The mail handler.
     *
     * @var \Drupal\example\Mail\MailHandler
     */
    protected $mailHandler;

    protected $htmlmail;
    /**
     * Constructs a new UserLoginEmail object.
     *
     * @param \Drupal\example\Mail\MailHandler $mail_handler
     *   The mail handler.
     */
    public function __construct(MailHandler $mail_handler)
    {
        $this->mailHandler = $mail_handler;
    }
    /**
     * Sends email.
     * 
     * @param Drupal\commerce\Entity\Commerce
     * 
     * @return bool 
     *   The message status.
     */
    public function send($commerce_order, $prize): bool
    {
        $subject = new TranslatableMarkup('Notificacion Premio Pedido ' . $commerce_order->id());
        $body = [
            //'#markup' => $this->getHtmlMail($commerce_order),
            '#markup' => '',
        ];
        $params['commerce_order'] = $commerce_order;
        $params['prize'] = $prize;
        return $this->mailHandler->sendMail('david@hipertintorero.com', $subject, $body, $params);
    }

    private function getHtmlMail($commerce_order)
    {
        $htmlmail = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" bgcolor="#faeede">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" valign="top" style="padding: 26px 24px;">
                        <a href="https://loterialaveleta.com" target="_blank" style="display: inline-block;">
                           <img src="https://loterialaveleta.com/themes/custom/laveletav2/images/logo-loteria-la-veleta-blanco.svg" alt="Logo" border="0" style="display: block; width: 248px;">
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#faeede">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                         <h1 style="margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px; color:#222;">' . $titulo . '</h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#faeede">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="left" bgcolor="#ffffff" style="padding: 16px; font-family: Helvetica, Arial, sans-serif; color:#222; font-size: 13px; line-height: 24px; word-break: break-all">
                    </td>
                </tr>
                <tr>
                    <td align="left" bgcolor="#ffffff" style="color:#222; padding: 24px; font-family:Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                         <p style="margin: 0;">Saludos,<br> El Equipo de La Veleta</p>
                    </td>
                </tr>
            </table>
        </td>
        </tr>
        <tr>
            <td align="center" bgcolor="#faeede" style="padding: 24px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                       <td align="center" bgcolor="#faeede" style="padding: 12px 24px; font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                         <p style="margin: 0;">Has recibido este email porque has requirido alguna accion de usuario en loterialaveleta.com, puedes borrarlo siempre de forma segura.</p>
                       </td>
                    </tr>
                    <tr>
                        <td align="center" bgcolor="#faeede" style="padding: 12px 24px; font-family:Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                            <p style="margin: 0;"><a style="font-weight:bold; color:#666; " href="https://loterialaveleta.com">https://loterialaveleta.com</a> Avda. de la Institucion Libre de Enseñanza 1 - 28037 - Madrid</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>';
        return $htmlmail;
    }
}
