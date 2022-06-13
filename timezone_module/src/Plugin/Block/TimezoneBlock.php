<?php

namespace Drupal\timezone_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\timezone_module\Services\TimezoneServiceClass;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Component\Datetime\TimeInterface;

/**
 * Provides a block to display time of selected timezone.
 *
 * @Block(
 *   id = "custom_timzone_block",
 *   admin_label = @Translation("Timezone Block"),
 * )
 */
class TimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\timezone_module\Services\TimezoneServiceClass definition.
   *
   * @var Drupal\timezone_module\Services\TimezoneServiceClass
   */
  protected $TimezoneServiceClass;

  /**
   * The cache.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Construct function.
   */
  public function __construct(array $configuration,
    $plugin_id,
    $plugin_definition,
    TimezoneServiceClass $TimezoneServiceClass,
    CacheBackendInterface $cache,
    TimeInterface $time
    ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->TimezoneServiceClass = $TimezoneServiceClass;
    $this->cache = $cache;
    $this->time = $time;
  }

  /**
   * Create function.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('timezone_module.timezone'),
      $container->get('cache.default'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $bid = 'custom_block_specbee';

    if ($cache = $this->cache->get($bid)) {
      $data = $cache->data;
    }
    else {
      $data = $this->TimezoneServiceClass->getCurrentTime();
      $this->cache->set('custom_block_specbee', $data, $this->time->getRequestTime() + (10));
    }

    return [
      '#theme' => 'custom_timezone_block',
      '#country' => $data['timezone_country'],
      '#city' => $data['timezone_city'],
      '#datetime' => $data['current_datetime'],
      '#cache' => [
        'cache-max-age' => 0,
      ],
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
