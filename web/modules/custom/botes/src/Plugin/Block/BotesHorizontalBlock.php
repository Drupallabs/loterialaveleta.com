<?php
namespace Drupal\botes\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\botes\BotesBbdd;

/**
 * Provides a 'BotesHorizontalBlock' block.
 *
 * @Block(
 *  id = "botes_horizontal_block",
 *  admin_label = @Translation("Botes horizontal block"),
 * )
 */
class BotesHorizontalBlock extends BlockBase implements ContainerFactoryPluginInterface {

  private $botes = array();

  /**
  * @var \Drupal\botes\BotesBbdd
  */
  protected $botesService;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Botes $botesService
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, BotesBbdd $botesService) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->botesService = $botesService;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('botes.botesbbdd')
    );
  }

  public function build() {
    $build = [];
    $this->botes['emil'] = $this->botesService->getBote('EMIL');
    $this->botes['lapr'] = $this->botesService->getBote('LAPR');
    $this->botes['bono'] = $this->botesService->getBote('BONO');
    $this->botes['elgr'] = $this->botesService->getBote('ELGR');
    $this->botes['lnac'] = $this->botesService->getBote('LNAC');
    $this->botes['laqu'] = $this->botesService->getBote('LAQU');
    $this->botes['qgol'] = $this->botesService->getBote('QGOL');
    $this->botes['lotu'] = $this->botesService->getBote('LOTU');
    $this->botes['qupl'] = $this->botesService->getBote('QUPL');

    $build['#theme'] = 'botes_horizontal_block';
    $build['#botes'] = $this->botes;

    return $build;
  }

}
