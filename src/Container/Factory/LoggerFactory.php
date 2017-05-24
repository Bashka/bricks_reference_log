<?php
namespace Bricks\ReferenceLog\Container\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
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
    $config = $container->get('Configuration');
    if(isset($options['logfile'])){
      $logfile = $options['logfile'];
    }
    else{
      $logfile = $config['log']['file'];
    }

    $logger = new Logger([
      'writers' => [
        [
          'name' => StreamWriter::class,
          'options' => [
            'stream' => $logfile,
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

    $referenceId = 'undefined';
    if(isset($options['request'])){
      $referenceId = $options['request']->getHeaderLine('Request-Id');
    }
    else if($container->has(RequestInterface::class)){
      $referenceId = $container->get(RequestInterface::class)->getHeaderLine('Request-Id');
    }
    $processor = new ReferenceIdProcessor;
    $processor->setReferenceId($referenceId);
    $logger->addProcessor($processor);

    return $logger;
  }
}
