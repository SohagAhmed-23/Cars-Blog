<?php

namespace Drupal\teams\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\teams\Service\CountryDetails; // Ensure this matches your service class

/**
 * Controller for the Teams module.
 */
class TeamsController extends ControllerBase {

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  
  protected $httpClient;

  /**
   * The Teams service.
   *
   * @var \Drupal\teams\Service\CountryDetails
   */
  protected $countryDetailsService;

  /**
   * Constructs a TeamsController object.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client service.
   * @param \Drupal\teams\Service\CountryDetails $country_details_service
   *   The Teams service.
   */
  public function __construct(ClientInterface $http_client, CountryDetails $country_details_service) {
    $this->httpClient = $http_client;
    $this->countryDetailsService = $country_details_service;
  }

  /**
   * Creates an instance of the controller.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container.
   *
   * @return static
   *   The controller instance.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('teams.default') // This should match the service name in teams.services.yml
    );
  }

  /**
   * Displays the Teams details page.
   *
   * @return array
   *   A render array.
   */
  public function content() {
    $rows = $this->countryDetailsService->getCountryDetails();
    
    if ($rows === FALSE) {
      return [
        '#markup' => $this->t('Failed to fetch country details.'),
      ];
    }

    $header = [$this->t('Country Name'), $this->t('More Info')];
    $build = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $build;
  }
}
