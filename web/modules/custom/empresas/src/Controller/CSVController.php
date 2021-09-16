<?php

namespace Drupal\empresas\Controller;

use Drupal\Core\Controller\ControllerBase;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;

class CSVController extends ControllerBase
{

    public function descargarDatos()
    {

        $download_folder = 'public://PedidosEmpresaCSV';
        $file_name = 'PedidosEmpresa.csv';
        $file_url = $download_folder . '/' . $file_name;

        unlink($file_url);

        $fwp = fopen($file_url, 'a');

        $tempstore = \Drupal::service('user.private_tempstore')->get('empresas');
        $query = $tempstore->get('empresas_query');
        $result = $query->execute();

        if (!empty($result)) {
            foreach ($result as $record) {
                $fields = array(
                    $record->order_id,
                    $record->order_number,
                    $record->nombre_empresa,
                    $record->name,
                    $record->mail,
                    $record->field_nombre_value,
                    $record->field_dni_value,
                    number_format($record->quantity),
                    $record->field_numero_decimo_value,
                    number_format($record->total_linea, 2, ',', '.'),
                    number_format($record->total_price__number, 2, ',', '.'),
                    date('d/m/Y H:i:s', $record->completed)

                );
                fputcsv($fwp, $fields);
            }
        }
        fclose($file_url);

        // This should return the file to the browser as response
        $response = new BinaryFileResponse($file_url);
        // To generate a file download, you need the mimetype of the file
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();
        // Set the mimetype with the guesser or manually
        if ($mimeTypeGuesser->isSupported()) {
            // Guess the mimetype of the file according to the extension of the file
            $response->headers->set('Content-Type', $mimeTypeGuesser->guess($file_url));
        } else {
            // Set the mimetype of the file manually, in this case for a text file is text/plain
            $response->headers->set('Content-Type', 'text/plain');
        }
        // Set content disposition inline of the file
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file_name
        );

        return $response;
    }
}
