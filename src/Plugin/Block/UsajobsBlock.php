<?php

/**
 * @file
 * Contains \Drupal\usajobs\Plugin\Block\UsajobsBlock.
 */

namespace Drupal\usajobs\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'USAjobs' block.
 *
 * @Block(
 *   id = "usajobs_block",
 *   admin_label = @Translation("USAjobs Block"),
 * )
 */
class UsajobsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    //TODO: look at the Cache API to see if I can get/set the block from cache

    //get the block form
    //$block = \Drupal::formBuilder()->getForm('Drupal\usajobs\Form\UsajobsBlockForm');
    //add the block's config to drupalSettings
    $config = \Drupal::config('usajobs.settings');
//    $affiliate_name = $config->get('affiliate_name');
//    $use_type_ahead = $config->get('autocomplete');
//    $block['#attached']['drupalSettings']['usajobs']['type_ahead'] = $use_type_ahead ? TRUE : FALSE;
//    $block['#attached']['drupalSettings']['usajobs']['affiliate_name'] = $affiliate_name ? $affiliate_name : '';
//    if ($use_type_ahead) {
//      //add the type_ahead js library
//      $block['#attached']['library'][] = 'usajobs/type_ahead';
//    }

    return $block;
  }

}
