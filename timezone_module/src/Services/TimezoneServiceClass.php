<?php

namespace Drupal\timezone_module\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * SpecBee TimeZone Class.
 */
class TimezoneServiceClass {

  /**
   * Config Object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new SpecBeeTimeZone object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Function to get current date time from selected timezone.
   */
  public function getCurrentTime() {
    $timezone_data = $this->getCurrentConfigs();

    $current_time = new DrupalDateTime('now', $timezone_data['timezone_value']);
    $timezone_data['current_datetime'] = $current_time->format('jS M Y - H:i A');

    return $timezone_data;
  }

  /**
   * Function to return configuration data.
   */
  public function getCurrentConfigs() {
    $timezone_data = [];

    /** @var Drupal\Core\Config\ConfigFactoryInterface $config_factory */
    $config_data = $this->configFactory->get('timezone_config.settings');

    $timezone_data['timezone_country'] = $config_data->get('timezone_country');
    $timezone_data['timezone_city'] = $config_data->get('timezone_city');
    $timezone_data['timezone_value'] = $config_data->get('timezone_value');

    return $timezone_data;
  }
}
