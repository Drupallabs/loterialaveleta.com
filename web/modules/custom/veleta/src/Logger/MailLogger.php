<?php

namespace Drupal\veleta\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;
use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLogLevel;

class MailLogger implements LoggerInterface
{

  use RfcLoggerTrait;
  /**
   * @var \Drupal\Core\Logger\LogMessageParserInterface
   */
  protected $parser;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * MailLogger constructor.
   *
   * @param \Drupal\Core\Logger\LogMessageParserInterface $parser
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(LogMessageParserInterface $parser, ConfigFactoryInterface $config_factory)
  {
    $this->parser = $parser;
    $this->configFactory = $config_factory;
  }
  /**
   * {@inheritdoc}
   */
  public function log($level, $message, array $context = array())
  {
    if ($level !== RfcLogLevel::ERROR) {
      return;
    }
    $to = $this->configFactory->get('veleta.configuration')->get('email_notify_errors');
    $langcode = $this->configFactory->get('system.site')->get('langcode');
    $variables = $this->parser->parseMessagePlaceholders($message, $context);
    $markup = new FormattableMarkup($message, $variables);
    \Drupal::service('plugin.manager.mail')->mail('veleta', 'veleta_log', $to, $langcode, ['message' => $markup]);
  }
}
