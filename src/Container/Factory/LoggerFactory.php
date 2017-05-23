<?php
namespace Bricks\ReferenceLog\Container\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream as StreamWriter;
use Zend\Log\Formatter\ErrorHandler as ErrorHandlerFormatter;
use Zend\Log\Processor\ReferenceId as ReferenceIdProcessor;

/**
 * Фабрика логгера.
 *
 * @author Artur Sh. Mamedbekov
 */
class LoggerFactory implements FactoryInterface{
  /**
   * {@inheritdoc}
   */
  public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
    $logger = new Logger([
      'writers' => [
        [
          'name' => StreamWriter::class,
          'options' => [
            'stream' => $options['logfile'],
            'formatter' => [
              'name' => ErrorHandlerFormatter::class,
              'options' => [
                'format' => '%timestamp% %priorityName% %extra[referenceId]%: %message%',
              ],
            ],
          ],
        ],
      ],
    ]);

    $processor = new ReferenceIdProcessor;
    $processor->setReferenceId($options['request']->getHeaderLine('Request-Id'));
    $logger->addProcessor($processor);

    return $logger;
  }
}
