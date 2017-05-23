<?php
namespace Bricks\ReferenceLog;

/**
 * @author Artur Sh. Mamedbekov
 */
class Message{
  /**
   * @var string Логгируемый метод.
   */
  private $method;

  /**
   * @var string[] Параметры, переданные методу.
   */
  private $args;

  /**
   * @var string Результат вызова метода.
   */
  private $result;

  /**
   * @param string $methos Логгируемый метод.
   * @param string[] $args [optional] Параметры, переданные методу.
   * @param string $result [optional] Результат вызова метода.
   */
  public function __construct($method, array $args = [], $result = null){
    $this->method = $method;
    $this->args = $args;
    $this->result = $result;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString(){
    return sprintf(
      '%s(%s):%s',
      $this->method,
      implode(',', $this->args),
      $this->result
    );
  }
}
