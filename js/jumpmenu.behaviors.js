/**
 * @file
 * Default JavaScript file for Jumpmenu Block.
 */

(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.jumpmenuBlock = {
    attach: function (context) {
      $('.jumpmenu', context).once('jumpmenuBlock').toc(
        {
          content: drupalSettings.jumpmenu.selector,
          headers: drupalSettings.jumpmenu.headings
        }
      );
    }
  };

})(jQuery, Drupal, drupalSettings);
