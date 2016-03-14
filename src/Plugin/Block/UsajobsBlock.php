<?php

/**
 * @file
 * Contains \Drupal\usajobs\Plugin\Block\UsajobsBlock.
 */

namespace Drupal\usajobs\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\usajobs\JobListing;
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
    //get jobs listings
    $jobs = array();
    $url = Url::fromRoute('usajobs.listings', $route_parameters = array(), $options = array('absolute' => true))->toString();
    dpm($url);
    $response = \Drupal::httpClient()->get($url)->getBody();
    dpm($response);
//    if( $response && isset($response['success']) && $response['success'] == true){
//      if( isset($response['data']['SearchResult']) && $response['data']['SearchResult']['SearchResultCount'] > 0 ){
//        $results = $response['data']['SearchResult']['SearchResultItems'];
//        foreach($results as $result){
//          array_push($jobs, new JobListing($result));
//        }
//      }
//    }

    return array(
      '#theme' => 'usajobs_block',
//      '#jobs' => array('title' => 'the job title'),
      '#jobs' => $jobs,
    );

  }

}
