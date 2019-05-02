<?php
namespace App\Record\Filter;

use App\Exception\Filter\NumericFilterException;

/**
 * Class AbstractNumericFilter
 *
 * @package App\Record\Filter
 */
abstract class AbstractNumericFilter implements RecordFilterInterface {

  public const EQ = 'eq';

  public const LT = 'lt';

  public const LTE = 'lte';

  public const GT = 'gt';

  public const GTE = 'gte';

  /**
   * performs simple comparison of two integers. Throws
   * App\Exception\Filter\NumericFilterException  if
   * comparisonOperator is unknown.
   *
   * @param int $value
   * @param string $comparisonOperator
   * @param int $compareValue
   * @return bool
   * @throws NumericFilterException
   */
  public function compare(int $value, string $comparisonOperator, int $compareValue) : bool {
    switch($comparisonOperator) {
      case $comparisonOperator === self::EQ:
        return $value === $compareValue;
      case $comparisonOperator === self::LT:
        return $value < $compareValue;
      case $comparisonOperator === self::LTE:
        return $value <= $compareValue;
      case $comparisonOperator === self::GT:
        return $value > $compareValue;
      case $comparisonOperator === self::GTE:
        return $value >= $compareValue;
      default:
        throw new NumericFilterException(NumericFilterException::INVALID_COMPARISON_OPERATOR);
    }
  }
}