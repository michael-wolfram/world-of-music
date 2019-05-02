<?php
namespace App\Exception\Filter;

/**
 * Class NumericFilterException
 *
 * @package App\Exception\Filter
 */
class NumericFilterException extends \Exception {

  public const INVALID_COMPARISON_OPERATOR = 'invalid comparison operator';
}