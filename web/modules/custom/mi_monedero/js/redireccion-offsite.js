/**
 * @file
 * Defines behaviors for the payment redirect form.
 */
(function ($, Drupal, drupalSettings) {
  "use strict";
  /**
   * Attaches the mimonederoRedireccion behavior.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   */

  Drupal.behaviors.mimonederoRedireccion = {
    attach: function (context) {
      $(".mi-monedero-tpvvirtual", context).submit();
    },
  };
})(jQuery, Drupal, drupalSettings);
