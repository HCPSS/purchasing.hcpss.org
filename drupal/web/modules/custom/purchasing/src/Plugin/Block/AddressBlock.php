<?php
/**
 * @file
 * Contains \Drupal\purchasing\Plugin\Block\AddressBlock
 */

namespace Drupal\purchasing\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "address_block",
 *   admin_label = @Translation("Address Block")
 * )
 */
class AddressBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'markup',

      '#markup' => '
        <div class="footer-directions">
          <a href="https://www.google.com/maps/preview?hl=en&amp;q=10910+Clarksville+Pike,+Ellicott+City,+MD+21042">
            <img
              id="hcpss-gmaps-footer-icon"
              src="https://s3.amazonaws.com/hcpss.web.site/images/directions-maps.png"
              alt="Get directions to HCPSS Central Office on Google Maps">
          </a>

          <address>
            <strong><a href="http://www.hcpss.org">Howard County Public School System</a></strong><br>
            10910 Clarksville Pike<br>
            Ellicott City, MD 21042<br>
            Main Phone: (410) 313-6600<br>
            <a href="http://www.hcpss.org/employees/staff-directory/">Staff Directory</a> |
            <a href="http://www.hcpss.org/inclusivity/">Inclusivity &amp; Accessibility</a>
          </address>
        </div>',
    ];
  }
}
