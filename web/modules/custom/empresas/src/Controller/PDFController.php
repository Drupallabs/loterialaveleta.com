<?php

namespace Drupal\empresas\Controller;

use Drupal\Core\Controller\ControllerBase;
//use Symfony\Component\HttpFoundation\Response;
use \TCPDF;


class PDFController extends ControllerBase
{

    public function pedidoPdf($pedidoid)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Loteria La Veleta');
        $pdf->SetTitle('Pedido de Loteria La Veleta');
        $pdf->SetSubject('Pedido de Loteria La Veleta');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // create some HTML content
        $html = '<h1>Pedido Confirmado en loterialaveleta.com</h1><hr><br>
                 <p> Yo David Álvarez Calvo con Dni 06266170G</p>
                 <p> Numero de pedido: 17852</p>
                    ';

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // ---------------------------------------------------------


        $pdf->Output('EntregaPedido.pdf', 'I');

        return array('#markup' => $pedidoid);
    }
}
