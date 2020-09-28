<?php

namespace Drupal\resultados\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Resultados' block.
 *
 * @Block(
 *   id = "resultados_block",
 *   admin_label = @Translation("Resultados: resultados block")
 * )
 */
class ResultadosBlock extends BlockBase {
  /**
   * {@inheritdoc}
   *
   * The return value of the build() method is a renderable array. Returning an
   * empty array will result in empty block contents. The front end will not
   * display empty blocks.
   */
  public function build() {
    $resultados = array();

    return [
      '#theme' => 'block-resultados',
      '#titulo' => 'Resultados Loterias',
      '#resultados' => $resultados
        ];
  }

  protected function getResultados(){

  }
}
