<?php

namespace Drupal\empresas\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\empresas\EmpresasBbdd;
use \TCPDF;


class PDFController extends ControllerBase
{
    /**
     * @var \Drupal\empresas\EmpresasBbddd
     */
    protected $empresasService;

    /**
     * @param \Drupal\empresas\EmpresasBbdd $empresas
     */
    public function __construct(EmpresasBbdd $empresasService)
    {
        $this->empresasService = $empresasService;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('empresas.empresasbbdd')
        );
    }

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
        $pedidos = $this->empresasService->damePedido($pedidoid);
        $ped = new \stdClass;
        // create some HTML content
        $html = '<h1 style="margin-bottom: 10px;">Pedido Confirmado en loterialaveleta.com</h1><hr>';
        foreach ($pedidos as $record) {
            $ped = $record;
        }
        $html .= '<p></p>';
        $html .= '<p> <b>Numero de Pedido:</b>&nbsp; ' . $pedidoid . '</p>';
        $html .= '<p> Realizado con el email:&nbsp;' . $ped->mail . '</p>';
        $html .= '<p> Fecha de Pedido:&nbsp;' . date('d/m/Y', $ped->completed) . '</p>';
        $html .= '<table style="border:1px solid black; border-collapse:collapse;">';
        $html .= '<tr><th width="100" style="border: 1px solid black;">Cantidad</th>
                      <th width="200" style="border: 1px solid black;">Articulo</th>
                      <th width="100" style="border: 1px solid black;">Precio Unitario</th>
                      <th width="100" style="border: 1px solid black;">Precio</th>
                      </tr>';
        $pedidos = $this->empresasService->damePedido($pedidoid);
        foreach ($pedidos as $record) {
            $html .= '<tr>';
            $html .= '<td style="border: 1px solid black;"><p>' . number_format($record->quantity) . '</p></td>
                      <td style="border: 1px solid black;"><p>' . $record->field_numero_decimo_value . '</p>
                                                        <small>Décimo del sordeo extraordinario de Navidad de Loteria Nacional, del 22 de diciembre de 2020</small></td>
                      <td style="border: 1px solid black;">20€</td>
                      <td style="border: 1px solid black;">' . number_format($record->total_linea) . '€</td>';
            // dump($record->field_nombre_value);
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '<p>Administracion loterias numero 320 de Madrid, LA VELETA hace entrega de los decimos del pedido al cliente.';
        $html .= '<p>Yo ' . $ped->field_nombre_value . ' con DNI o NIE: </p>
                  <p></p><p></p><p></p><p></p><p></p>
                  <p>Confirmo que he recibido los decimos este de pedido.</p>';
        $pdf->writeHTML($html, true, false, true, false, '');

        // ---------------------------------------------------------


        $pdf->Output('EntregaPedido.pdf', 'I');

        return array('#markup' => $pedidoid);
    }
}
