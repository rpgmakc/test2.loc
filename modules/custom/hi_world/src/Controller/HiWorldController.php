<?php

namespace Drupal\hi_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HiWorldController.
 */
class HiWorldController extends ControllerBase {

  /**
   * Hiworld.
   *
   * @return string
   *   Return Hello string.
   */
  public function hiworld() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hiworld')
    ];
  }

}
