<?php
namespace AppTest\Record\Filter\Chain;

use App\Exception\Filter\Chain\FilterAlreadyChainedException;
use App\Exception\Filter\Chain\FilterNotApplicableOnGivenRecordDaoException;
use App\Exception\Filter\Chain\FilterNotChainedException;
use App\Record\DAO\RecordDaoInterface;
use App\Record\Filter\Chain\RecordFilterChain;
use App\Record\Filter\RecordFilterInterface;
use PHPUnit\Framework\TestCase;

class RecordFilterChainTest extends TestCase {

  private $_recordFilterChain;

  private $_mockupRecordFilter;

  public function setUp(): void {
    $this->_recordFilterChain = new RecordFilterChain();
    $this->_mockupRecordFilter = $this->createMock(RecordFilterInterface::class);
  }

  public function tearDown(): void {
    $this->_recordFilterChain = null;
    $this->_mockupRecordFilter = null;
  }

  public function testRecordFilterChainContainsRecordFilter() : void {
    $this->assertFalse($this->_recordFilterChain->contains($this->_mockupRecordFilter));
    $this->_recordFilterChain->add($this->_mockupRecordFilter);
    $this->assertTrue($this->_recordFilterChain->contains($this->_mockupRecordFilter));
  }

  public function testThrowExceptionOnAddingAlreadyChainedRecordFilter() :void {
    $this->_recordFilterChain->add($this->_mockupRecordFilter);
    $this->expectException(FilterAlreadyChainedException::class);
    $this->_recordFilterChain->add($this->_mockupRecordFilter);
  }

  public function testRemoveRecordFilterFromRecordFilterChain() : void {
    $this->_recordFilterChain->add($this->_mockupRecordFilter);
    $this->_recordFilterChain->remove($this->_mockupRecordFilter);
    $this->assertFalse($this->_recordFilterChain->contains($this->_mockupRecordFilter));
  }

  public function testThrowExceptionOnRemoveNotChainedRecordFilter() : void {
    $this->expectException(FilterNotChainedException::class);
    $this->_recordFilterChain->remove($this->_mockupRecordFilter);
  }

  public function testRecordFilterChainIsApplicableOnRecordDaoType() : void {
    $this->_mockupRecordFilter->method('filterCanByAppliedOnRecordDao')->willReturn(true);
    $this->_recordFilterChain->add($this->_mockupRecordFilter);
    $this->_recordFilterChain->isRecordFilterChainApplicableToRecordDaoType($this->createMock(RecordDaoInterface::class));
    $this->assertTrue(true);
  }

  public function testThrowExceptionIfRecordDaoTypeIsNotApplicable() : void {
    $this->_mockupRecordFilter->method('filterCanByAppliedOnRecordDao')->willReturn(false);
    $this->_recordFilterChain->add($this->_mockupRecordFilter);
    $this->expectException(FilterNotApplicableOnGivenRecordDaoException::class);
    $this->_recordFilterChain->isRecordFilterChainApplicableToRecordDaoType($this->createMock(RecordDaoInterface::class));
  }
}