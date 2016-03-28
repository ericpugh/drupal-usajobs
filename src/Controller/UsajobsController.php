<?php

/**
 * @file
 * Contains \Drupal\usajobs\Controller\UsajobsController.
 */

namespace Drupal\usajobs\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Cache\CacheableJsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller routines for usajobs routes.
 */
class UsajobsController extends ControllerBase {

  /**
   * List jobs from the USAjobs Search API.
   *
   * @return object
   *   Return a response object.
   */
  public function getJobs() {
    // @todo: move this to a custom REST resource??
    try {

      // Get module settings.
      $config = \Drupal::config('usajobs.settings');
      $headers = array(
        'Host' => $config->get('host'),
        'User-Agent' => $config->get('user_agent'),
        'Authorization-Key' => $config->get('api_key'),
        'Accept' => 'application/json',
      );

      // Build request url with querystring parameters provided by module
      // configuration.
      $params = \Drupal::config('usajobs.parameters')->getRawData();
      // Remove any settings which aren't used as Search API parameters.
      unset($params['_core']);
      foreach ($params as $key => $value) {
        // Get params with multiple values and convert to a csv string.
        if (is_array($params[$key])) {
          $combined = '';
          foreach ($params[$key] as $k => $v) {
            $combined .= $v . ';';
          }
          // Set the new value.
          $params[$key] = rtrim($combined, ';');
        }
        // Remove empty params.
        if (empty($value)) {
          unset($params[$key]);
        }
      }

      // Request USAjobs Search API.
      $client = \Drupal::httpClient();
      $query = http_build_query($params);
      $request_url = $config->get('endpoint_url') . '?' . $query;
      $response = $client->get($request_url, array('headers' => $headers));
      return new CacheableJsonResponse([
        'success' => TRUE,
        'data' => json_decode($response->getBody()),
      ]);

    } catch (RequestException $exception) {
      watchdog_exception('usajobs', $exception);
      return new JsonResponse([
        'success' => FALSE,
        'code'    => $exception->getCode(),
        'message' => $exception->getMessage(),
      ]);
    }

  }

}
