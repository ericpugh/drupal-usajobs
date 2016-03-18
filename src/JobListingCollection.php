<?php
/**
 * @file
 * Contains \Drupal\usajobs\JobListingCollection.
 */

namespace Drupal\usajobs;

use Drupal\Core\Url;
use Drupal\usajobs\JobListing;


class JobListingCollection {

  static public $items = array();

  static function getJobListings(){
    //populate jobs from usajobs module
    $url = Url::fromRoute('usajobs.listings', $route_parameters = array(), $options = array('absolute' => true))->toString();
    $contents = \Drupal::httpClient()->get($url)->getBody()->getContents();
    $response = json_decode($contents);
    if( is_object($response)  && $response->success == true){
      $data = $response->data;
      if( $data->SearchResult->SearchResultCount > 0 ){
        $results = $response->data->SearchResult->SearchResultItems;
        foreach($results as $result){
          array_push(self::$items, new JobListing($result));
        }
      }
    }

    return self::$items;
  }

  public function length() {
    return count(self::$items);
  }

}