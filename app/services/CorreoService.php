<?php

class CorreoService
{
    public static function enviar($destinatario, $asunto, $mensajeHtml)
    {
        $headers = [];
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-type: text/html; charset=UTF-8";
        $headers[] = "From: Sistema de Reservas <reservas@midominio.com>";
        $headers[] = "Reply-To: reservas@midominio.com";

        return mail(
            $destinatario,
            $asunto,
            $mensajeHtml,
            implode("\r\n", $headers)
        );
    }

    public static function enviarConfirmacionReserva(
        $email,
        $nombre,
        $fecha,
        $horario,
        $tipoUso
    ) {

        $asunto = "Confirmación de reserva";

        $mensaje = "
        <html>
        <body>
            <h2>Reserva confirmada</h2>

            <p>Hola <strong>{$nombre}</strong>,</p>

            <p>Tu reserva fue registrada correctamente.</p>

            <table border='1' cellpadding='8'>
                <tr>
                    <td><strong>Fecha</strong></td>
                    <td>{$fecha}</td>
                </tr>
                <tr>
                    <td><strong>Horario</strong></td>
                    <td>{$horario}</td>
                </tr>
                <tr>
                    <td><strong>Tipo de uso</strong></td>
                    <td>{$tipoUso}</td>
                </tr>
            </table>

            <br>

            <p>Gracias por utilizar el sistema.</p>

        </body>
        </html>
        ";

        return self::enviar(
            $email,
            $asunto,
            $mensaje
        );
    }
}
