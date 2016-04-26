<?php
/**
 * @file
 * Contains \Drupal\usajobs_integration\JobListingCollection.
 */

namespace Drupal\usajobs_integration;

use Drupal\Core\Url;
use Drupal\usajobs_integration\JobListing;

/**
 * Defines a Collection of Job Listings.
 */
class JobListingCollection {

  static public $items = array();

  /**
   * Get jobs from usajobs endpoint.
   */
  static public function getJobListings() {
    $url = Url::fromRoute('usajobs_integration.listings', array(), array('absolute' => TRUE))->toString();
    $contents = \Drupal::httpClient()->get($url)->getBody()->getContents();
    $response = json_decode($contents);
    if (is_object($response)  && $response->success == TRUE) {
      $data = $response->data;
      if ($data->SearchResult->SearchResultCount > 0) {
        $results = $response->data->SearchResult->SearchResultItems;
        foreach ($results as $result) {
          array_push(self::$items, new JobListing($result));
        }
      }
    }

    return self::$items;
  }

  /**
   * Get the number of JobListing object in collection.
   */
  public function length() {
    return count(self::$items);
  }

}
