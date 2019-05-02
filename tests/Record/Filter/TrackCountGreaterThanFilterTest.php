<?php
namespace AppTest\Record\Filter;

use App\Record\DAO\RecordDaoInterface;
use App\Record\Filter\TrackCountGreaterThanFilter;
use PHPUnit\Framework\TestCase;

class TrackCountGreaterThanFilterTest extends TestCase {

  private $_mockupRecordDao;

  private $_trackCountGreaterThan;

  private $_trackCountGreaterThanFilter;

  public function setUp(): void {
    $this->_mockupRecordDao = $this->createMock(RecordDaoInterface::class);
    $this->_trackCountGreaterThan = 10;
    $this->_trackCountGreaterThanFilter = new TrackCountGreaterThanFilter($this->_trackCountGreaterThan);
  }

  public function tearDown(): void {
    $this->_mockupRecordDao = null;
    $this->_trackCountGreaterThan = null;
    $this->_trackCountGreaterThanFilter = null;
  }

  public function testMoreTrackCountThanRequiredMatches() :void {
    $this->_mockupRecordDao->method('getTrackCount')->willReturn($this->_trackCountGreaterThan + 1);
    $this->assertTrue($this->_trackCountGreaterThanFilter->applyFilter($this->_mockupRecordDao));
  }

  public function testEqualTrackCountThanRequiredNotMatch() :void {
    $this->_mockupRecordDao->method('getTrackCount')->willReturn($this->_trackCountGreaterThan);
    $this->assertFalse($this->_trackCountGreaterThanFilter->applyFilter($this->_mockupRecordDao));
  }

  public function testTrackCountLessThanRequiredNotMatch() :void {
    $this->_mockupRecordDao->method('getTrackCount')->willReturn($this->_trackCountGreaterThan - 1);
    $this->assertFalse($this->_trackCountGreaterThanFilter->applyFilter($this->_mockupRecordDao));
  }
}