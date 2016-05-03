<?php

namespace Drupal\usajobs_integration;

use GuzzleHttp\Exception\RequestException;
use Drupal\Core\Cache\CacheableJsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * USAJobs Integration RequestData service.
 */
class RequestData {

  public $listings = array();

  /**
   * The response retrieved from USAJobs API.
   *
   * @var \Drupal\Core\Cache\CacheableJsonResponse
   */
  protected $response;

  /**
   * Config Factory Service Object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new RequestData instance.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    $this->response = $this->getData();
  }

  /**
   * Build request url with querystring parameters provided by module settings.
   *
   * @return string
   *    The request URL with querystring params.
   */
  public function getRequestUrl() {
    $params = $this->configFactory->get('usajobs_integration.parameters')->getRawData();
    $config = $this->configFactory->get('usajobs_integration.settings');

    // Remove any settings which aren't used as Search API parameters.
    unset($params['_core']);
    foreach ($params as $key => $value) {
      // Get params with multiple values and convert to a csv string.
      if (is_array($params[$key])) {
        $combined = '';
        foreach ($params[$key] as $v) {
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
    $query = http_build_query($params);

    return $request_url = $config->get('endpoint_url') . '?' . $query;

  }

  /**
   * Get data from the USAJobs Search API.
   *
   * @return object
   *   Return a response object.
   */
  private function getData() {
    try {

      // Get module settings.
      $config = $this->configFactory->get('usajobs_integration.settings');
      $headers = array(
        'Host' => $config->get('host'),
        'User-Agent' => $config->get('user_agent'),
        'Authorization-Key' => $config->get('api_key'),
        'Accept' => 'application/json',
      );

      // Request USAJobs Search API.
      $client = \Drupal::httpClient();
      $request_url = $this->getRequestUrl();
      $response = $client->get($request_url, array('headers' => $headers));
      $result = new CacheableJsonResponse([
        'success' => TRUE,
        'data' => json_decode($response->getBody()),
      ]);
    }

    catch (RequestException $exception) {
      watchdog_exception('usajobs', $exception);
      $result = new JsonResponse([
        'success' => FALSE,
        'code'    => $exception->getCode(),
        'message' => $exception->getMessage(),
      ]);
    }

    return $result;

  }

  /**
   * Create an array of JobListing objects from response.
   */
  public function getJobListings() {
    if (is_object($this->response)  && $this->response->isOk()) {
      $data = json_decode($this->response->getContent());
      if ($data->data->SearchResult->SearchResultCount > 0) {
        $results = $data->data->SearchResult->SearchResultItems;
        foreach ($results as $result) {
          array_push($this->listings, new JobListing($result));
        }
      }
    }

    return $this->listings;
  }

  /**
   * Get the response object.
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * Get the number of JobListing objects.
   */
  public function length() {
    return count($this->listings);
  }

}
