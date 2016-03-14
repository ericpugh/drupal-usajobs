<?php

/**
 * @file
 * Contains \Drupal\usajobs\Controller\UsajobsController.
 */

namespace Drupal\usajobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Cache\CacheableJsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class UsajobsController extends ControllerBase {

  /**
   * List jobs from the USAjobs Search API.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */

  public function getJobs()
  {
    //TODO: move this to a custom REST resource??
    try {

      //get module settings
      $config = \Drupal::config('usajobs.settings');
      $headers = array(
        'Host' => $config->get('host'),
        'User-Agent' => $config->get('user_agent'),
        'Authorization-Key' => $config->get('api_key'),
        'Accept' => 'application/json',
      );

      //build request url with querystring parameters provided by module configuration
      $params = \Drupal::config('usajobs.parameters')->getRawData();
      //remove any settings which aren't used as Search API parameters
      unset($params['_core']);
      foreach($params as $key => $value){
        //get params with multiple values and convert to a (semi-colon delimited) string
        if( is_array($params[$key]) ){
          $combined = '';
          foreach($params[$key] as $k => $v){
            $combined .= $v . ';';
          }
          //set the new value
          $params[$key] = rtrim($combined, ';');
        }
        //remove empty params
        if(empty($value)){
          unset($params[$key]);
        }
      }

      //request USAjobs Search API
      $client = \Drupal::httpClient();
      $query = http_build_query($params);
      $request_url = $config->get('endpoint_url') . '?' . $query;
      $response = $client->get( $request_url, array('headers' => $headers) );
      return new CacheableJsonResponse([
        'success' => true,
        'data' => json_decode($response->getBody()),
      ]);

    } catch (RequestException $exception) {
      watchdog_exception('usajobs', $exception->getMessage());
      return new JsonResponse([
        'success' => false,
        'code'    => $exception->getCode(),
        'message' => $exception->getMessage(),
      ]);

    }

  }

}
