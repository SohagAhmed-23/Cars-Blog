<?php

namespace Drupal\teams\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Service to fetch country details.
 */
class CountryDetails {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Constructs a CountryDetails object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * Fetches country details and formats them for display.
   *
   * @return array
   *   An array of formatted country details.
   */
  public function getCountryDetails() {
    $endpoint = 'https://restcountries.com/v2/all?fields=name';
    
    try {
      // Make the GET request.
      $response = $this->httpClient->get($endpoint, [
        'headers' => [
          'Accept' => 'application/json',
        ],
      ]);

      // Get the response body and decode JSON.
      $data = $response->getBody()->getContents();
      $countries = json_decode($data, TRUE);

      // Prepare rows for output.
      $rows = [];
      foreach ($countries as $item) {
        $value = new TranslatableMarkup('<a href=":link">More</a>', [':link' => "/teams-details"]);
        $rows[] = [$item['name'], $value];
      }

      return $rows;

    } catch (RequestException $e) {
      // Handle the exception or log the error.
      \Drupal::logger('teams')->error('Failed to fetch country details: @message', ['@message' => $e->getMessage()]);
      return FALSE;
    }
  }

  /**
   * Example test function.
   */
  public function getTest() {
    return 'Testing...';
  }
}
