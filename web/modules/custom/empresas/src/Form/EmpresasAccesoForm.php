<?php

namespace Drupal\empresas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ComprobarResultadosForm.
 */
class EmpresasAccesoForm extends FormBase
{
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * Class constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager)
  {
    $this->entityTypeManager = $entity_type_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'empresas_acceso_formulario';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['codigo'] = [
      '#type' => 'textfield',
      '#title' => 'Codigo',
      '#maxlength' => 64,
      '#required' => true,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Acceder !!',
    ];
    $form['#theme'] = 'empresas-acceso-form';
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $codigo = $form_state->getValue('codigo');
    $empresa = reset($this->entityTypeManager->getStorage('empresa')->loadByProperties(['contrasena' =>  $codigo]));
    if (!$empresa) {
      $form_state->setErrorByName('codigo', 'Codigo de acceso incorrecto');
    }

    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $codigo = $form_state->getValue('codigo');

    $path = URL::fromRoute('view.comprar_loteria_empresa.page_comprar_loteria_empresa', ['arg_0' => $codigo])->toString();
    $response = new RedirectResponse($path);
    $response->send();
  }
}
