<?php

namespace Drupal\jugar\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Jugar Selae Vertical block' block.
 *
 * @Block(
 *   id = "jugar_selae",
 *   admin_label = @Translation("Jugar Selae Block")
 * )
 */
class JugarSelaeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   *
   * The return value of the build() method is a renderable array. Returning an
   * empty array will result in empty block contents. The front end will not
   * display empty blocks.
   */
  public function build() {
    // We return an empty array on purpose. The block will thus not be rendered
    // on the site. See BlockExampleTest::testBlockExampleBasic().

    return [
      '#markup' => '
                  <div id="jugar-vertical">
                   <a title="Jugar al Euromillones en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/EMIL" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Euromillones.png" alt="Juega al Euromillones" /></a>
                   <a title="Jugar al La Primitiva en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/LAPR" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Primitiva.png" alt="Juega a la Primitiva" /></a>
                   <a title="Jugar al Bonoloto en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/BONO" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Bonoloto.png" alt="Juega a la Bonoloto" /></a>
                   <a title="Jugar al Gordo de la Primitiva en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/ELGR" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Gordo_primitiva.png" alt="Juega al Gordo de la Primitiva" /></a>
                   <a title="Jugar al Loteria Nacional en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/LNAC" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Loteria_nacional.png" alt="Juega a la Loteria Nacional" /></a>
                   <a title="Jugar al La Quiniela en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/LAQU" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Quiniela.png" alt="Juega a la Quiniela" /></a>
                   <a title="Jugar al Quinigol en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/QGOL" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Quinigol.png" alt="Juega al Quinigol" /></a>
                   <a title="Jugar al Lototurf en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/LOTU" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Lototurf.png" alt="Juega al Lototurf" /></a>
                   <a title="Jugar al Quintuple Plus en la pagina de Loterias" href="https://juegos.loteriasyapuestas.es/jugar/cas/configuracion/club_conmigo_online/95005/QUPL" target="_blank">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Quintuple_plus.png" alt="Juega al Quintuple Plus" /></a>
                   <a title="Registrarse en la web de SELAE" target="_blank" href="https://juegos.loteriasyapuestas.es/jugar/registro/verificacion/95005">
                     <img src="'.base_path().'themes/custom/laveleta/images/btn-venta-juegos/Registro.png" alt="Registrate para jugar en Loterias y apuestas del estado" /></a>
                  </div>
      ',
    ];
  }

}
