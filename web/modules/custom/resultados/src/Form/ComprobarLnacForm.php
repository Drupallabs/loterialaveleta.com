<?php
namespace Drupal\resultados\Form;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\entity\Node;
use Drupal\resultados\ResultadosConnection;
use Drupal\resultados\ComprobarDecimo;

class ComprobarLnacForm extends FormBase {


  public function buildForm(array $form, FormStateInterface $form_state) {

    $options = [];
    $sorteos_ids = \Drupal::entityQuery('node')->condition('type','sorteo')->condition('field_sorteo',16)->sort('nid','DESC')->execute();
    foreach ($sorteos_ids as $sorteo_id) {
        $node = Node::load($sorteo_id);
        $options[$sorteo_id] = $node->getTitle();
    }

    $form['sorteo'] = [
      '#type' => 'select',
      '#title' => $this->t('Sorteo'),
      '#options' => $options,
      '#multiple' => false,
      '#required' => true
    ];

	$form['decimo'] = [
  	'#type' => 'textfield',
  	'#title' => 'Decimo',
  	'#description' => 'Introduce el numero de tu Décimo',
    '#maxlength' => 5,
    '#size' => 10,
    '#required' => true,
  ];

  $form['submit'] = [
    '#type' => 'submit',
    '#value' => 'Comprobar !!',
    '#attributes'=>array('class'=> array('btn', 'btn--primary', 'btn--block')),
    '#ajax' => array(
      // Function to call when event on form element triggered.
      'callback' => 'Drupal\resultados\Form\ComprobarLnacForm::comprobarLnacCallback',
      // Effect when replacing content. Options: 'none' (default), 'slide', 'fade'.
      'effect' => 'fade',
      // Javascript event to trigger Ajax. Currently for: 'onchange'.
      //'event' => 'onclick',
      'progress' => array(
        // Graphic shown to indicate ajax. Options: 'throbber' (default), 'bar'.
        'type' => 'throbber',
        // Message to show along progress graphic. Default: 'Please wait...'.
        'message' => NULL,
      ),),
  ];

  $form['comprobacion'] = [
     '#type' => 'markup',
     '#markup' => '<div class="check__messages"></div>'
   ];
   $form['#theme'] = 'page-comprobar-resultados';
	return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
	//drupal_set_message('Nothing Submitted. Just an Example.');
  }

  public function comprobarLnacCallback(array &$form, FormStateInterface $form_state) {
  	$ajax_response = new AjaxResponse();
    $decimo = $form_state->getValue('decimo');
    $sorteo = $form_state->getValue('sorteo');

    $comprobacion = (object)[]; // guarda el resultado de la comprobacion
    $reso = new ResultadosConnection();

    $nodesorteo = node_load($sorteo); // el sorteo es el id de node (nid)
    $cdc = $nodesorteo->field_id_sorteo[0]->getValue();
    if(!$nodesorteo) {
       drupal_set_message('error buscando el node sorteo'.$sorteo);
       $comprobacion = (object) [
         'tipo' => 'error',
         'mensaje' => 'No encuentra el sorteo nid',
         'datos' => [],
       ];
    } else {
        $resultado = $reso->getPremioDecimoWeb($cdc['value']);
        $compo = new ComprobarDecimo($decimo,$nodesorteo,$resultado);
        $comprobacion = $compo->dameResultadosComprobacion();
        //print_r($comprobacion);
        if($comprobacion->tipo == 'ok') {
            $htmlok = '<div class="check-result">
      						<img src="'.$comprobacion->datos->decimo_imagen_url.'" alt="Décimo buscado" class="check-result__image">
      						<div class="check-result__data">
      							<h3 class="check-result__draw">'.$comprobacion->datos->sorteo.'</h3>
      							<p class="check-result__number">Décimo con número: <span class="check-result__number">'.$comprobacion->datos->decimo.'</span></p>
      						</div>
      						<div class="check__message check__message--success">
      							<p><strong>¡Enhorabuena! El décimo ha sido premiado con <span class="check-result__prize">'.number_format($comprobacion->datos->premio).'</span> euros</strong></p>
      						</div>
      					</div>';
            $ajax_response->addCommand(new HtmlCommand('.check__messages',$htmlok));
        } else {
            $htmlnook = '	<div class="check-result">
    						<img src="'.$comprobacion->datos->decimo_imagen_url.'" alt="Décimo buscado" class="check-result__image">
    						<div class="check-result__data">
    							<h3 class="check-result__draw">'.$comprobacion->datos->sorteo.'</h3>
    							<p class="check-result__number">Décimo con número: <span class="check-result__number">'.$comprobacion->datos->decimo.'</span></p>
    						</div>
    						<div class="check__message check__message--fail">
    							<p><strong>¡Lo sentimos! El décimo no ha sido premiado</strong></p>
    						</div>
    					</div>';
            $ajax_response->addCommand(new HtmlCommand('.check__messages',$htmlnook));
        }

    }


  	return $ajax_response;
  }

  public function getFormId() {
  return 'comprobar_lnac_form';
  }

}
