<?php
namespace AppTest\Record\Filter;

use App\Exception\Filter\NumericFilterException;
use App\Record\Filter\AbstractNumericFilter;
use PHPUnit\Framework\TestCase;

class AbstractNumericFilterTest extends TestCase {

  private $_mockupNumericFilter;

  public function setUp(): void {
    $this->_mockupNumericFilter = $this->getMockForAbstractClass(AbstractNumericFilter::class);
  }

  public function testEqualsComparisonOperator() : void {
    $this->assertTrue($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::EQ, 1));
    $this->assertFalse($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::EQ, 2));
  }

  public function testGreaterThanComparisonOperator() : void {
    $this->assertTrue($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::GT, 0));
    $this->assertFalse($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::GT, 1));
    $this->assertFalse($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::GT, 2));
  }

  public function testGreaterThanEqualsComparisonOperator() : void {
    $this->assertTrue($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::GTE, 0));
    $this->assertTrue($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::GTE, 1));
    $this->assertFalse($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::GTE, 2));
  }

  public function testLowerThanComparisonOperator() : void {
    $this->assertTrue($this->_mockupNumericFilter->compare(0, $this->_mockupNumericFilter::LT, 1));
    $this->assertFalse($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::LT, 1));
    $this->assertFalse($this->_mockupNumericFilter->compare(2, $this->_mockupNumericFilter::LT, 1));
  }

  public function testLowerThanEqualsComparisonOperator() : void {
    $this->assertTrue($this->_mockupNumericFilter->compare(0, $this->_mockupNumericFilter::LTE, 1));
    $this->assertTrue($this->_mockupNumericFilter->compare(1, $this->_mockupNumericFilter::LTE, 1));
    $this->assertFalse($this->_mockupNumericFilter->compare(2, $this->_mockupNumericFilter::LTE, 1));
  }

  public function testThrowExceptionOnInvalidComparisonOperator() : void {
    $this->expectException(NumericFilterException::class);
    $this->expectExceptionMessage(NumericFilterException::INVALID_COMPARISON_OPERATOR);
    $this->_mockupNumericFilter->compare(10, 'NOT_EXISTING_OPERATOR', 10);
  }
}