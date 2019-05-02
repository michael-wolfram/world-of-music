<?php
namespace AppTest\Record\Filter;

use App\Record\DAO\RecordDaoInterface;
use App\Record\Filter\ReleaseDateBeforeFilter;
use PHPUnit\Framework\TestCase;

class ReleaseDateBeforeFilterTest extends TestCase {

  private $_compareDateTime;

  private $_mockupRecordDao;

  private $_releaseDateBeforeFilter;

  public function setUp(): void {
    $this->_compareDateTime = \DateTime::createFromFormat('d.m.Y', '08.04.2019');
    $this->_mockupRecordDao = $this->createMock(RecordDaoInterface::class);
    $this->_releaseDateBeforeFilter = new ReleaseDateBeforeFilter($this->_compareDateTime);
  }

  public function tearDown(): void {
    $this->_compareDateTime = null;
    $this->_mockupRecordDao = null;
    $this->_releaseDateBeforeFilter = null;
  }

  public function testMatchDateTimeBeforeCompareDateTime() : void {
    $matchingDateTime = clone $this->_compareDateTime;
    $matchingDateTime->sub(new \DateInterval('P1D'));

    $this->_mockupRecordDao->method('getReleaseDate')->willReturn($matchingDateTime);
    $this->assertTrue($this->_releaseDateBeforeFilter->applyFilter($this->_mockupRecordDao));
  }

  public function testDateTimeEqualsCompareDateTimeNotMatch() : void {
    $this->_mockupRecordDao->method('getReleaseDate')->willReturn($this->_compareDateTime);
    $this->assertFalse($this->_releaseDateBeforeFilter->applyFilter($this->_mockupRecordDao));
  }

  public function testDateTimeGreaterThanCompareDateTimeNotMatch() : void {
    $notMatchingDateTime = clone $this->_compareDateTime;
    $notMatchingDateTime->add(new \DateInterval('P1D'));

    $this->_mockupRecordDao->method('getReleaseDate')->willReturn($notMatchingDateTime);
    $this->assertFalse($this->_releaseDateBeforeFilter->applyFilter($this->_mockupRecordDao));
  }
}