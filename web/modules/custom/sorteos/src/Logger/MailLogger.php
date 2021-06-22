<?php

namespace Drupal\sorteos\Logger;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Logger\RfcLoggerTrait;
use Drupal\Core\Logger\RfcLogLevel;
use Drupal\Core\Session\AccountProxyInterface;
use Psr\Log\LoggerInterface;

/**
 * A logger that sends an email when the log type is error.
 */
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
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Drupal\Core\Logger\LogMessageParserInterface $parser
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     */
    public function __construct(LoggerInterface $logger, LogMessageParserInterface $parser, ConfigFactoryInterface $config_factory)
    {
        $this->logger = $logger;
        $this->parser = $parser;
        $this->configFactory = $config_factory;
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        echo "5555555555555555555555555555555555555555";
        die;
        if ($level !== RfcLogLevel::ERROR) {
            return;
        }
        /*
        $severity = RfcLogLevel::EMERGENCY;
            $severity = RfcLogLevel::ALERT;
            $severity = RfcLogLevel::CRITICAL;
            $severity = RfcLogLevel::ERROR;
            $severity = RfcLogLevel::WARNING;
            $severity = RfcLogLevel::NOTICE;
            $severity = RfcLogLevel::INFO;
            $severity = RfcLogLevel::DEBUG;
            $levels = RfcLogLevel::getLevels();
            */

        /** @var AccountProxyInterface $account */
        $account = $context['user'];
        $config = $this->configFactory->get('sorteos.configuration');
        $to = $config->get('email');
        $langode = $this->configFactory->get('system.site')->get('langcode');

        $variables = $this->parser->parseMessagePlaceholders($message, $context);
        $markup = new FormattableMarkup($message, $variables);
        \Drupal::service('plugin.manager.mail')->mail('sorteos', 'sorteos_log', $to, $langode, ['message' => $markup, 'user' => $account]);
    }
}
