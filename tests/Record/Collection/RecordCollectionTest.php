<?php
namespace AppTest\Record\Collection;

use App\Record\Collection\RecordCollection;
use App\Record\DAO\RecordDaoInterface;
use App\Record\Filter\Chain\RecordFilterChain;
use PHPUnit\Framework\TestCase;

class RecordCollectionTest extends TestCase {

  private $_mockRecordDao;

  private $_recordCollection;

  public function setUp(): void {
    $this->_mockRecordDao = $this->createMock(RecordDaoInterface::class);
    $this->_mockRecordDao->method('__toString')->willReturn(get_class($this->_mockRecordDao));

    $this->_recordCollection = new RecordCollection($this->_mockRecordDao, []);
  }

  public function tearDown(): void {
    $this->_mockRecordDao = null;
    $this->_recordCollection = null;
  }

  public function testRecordsParameterOfRecordCollectionConstructor() : void {
    $this->_recordCollection = new RecordCollection($this->_mockRecordDao, [$this->_mockRecordDao, $this->_mockRecordDao, $this->_mockRecordDao]);
    $this->assertEquals(3, $this->_recordCollection->count());
  }

  public function testThrowExceptionOnAddingDifferentRecordDaoTypeBySecondConstructorParameter() : void {
    $otherMockupRecordDao = $this->createMock(RecordDaoInterface::class);
    $otherMockupRecordDao->method('__toString')->willReturn('OtherRecordDao');

    $this->expectException(\InvalidArgumentException::class);
    $this->_recordCollection = new RecordCollection($this->_mockRecordDao, [$otherMockupRecordDao]);
  }

  public function testGetCorrectRecordDaoTypeClass() : void {
    $this->assertEquals(get_class($this->_mockRecordDao), get_class($this->_recordCollection->getRecordDaoType()));
  }

  public function testGetCorrectRecordCount() : void {
    $this->assertEquals(0, $this->_recordCollection->count());
    $this->_recordCollection->add($this->_mockRecordDao);
    $this->assertEquals(1, $this->_recordCollection->count());
  }

  public function testThrowExceptionOnAddingOtherRecordDaoClassThanDefinedInConstructor() : void {
    $otherMockupRecordDao = $this->createMock(RecordDaoInterface::class);
    $otherMockupRecordDao->method('__toString')->willReturn('OtherRecordDao');
    $this->expectException(\InvalidArgumentException::class);
    $this->_recordCollection->add($otherMockupRecordDao);
  }

  public function testMatchingRecordFilterChainByRecordCollectionElementCount() : void {
    $mockupRecordFilterChain = $this->createMock(RecordFilterChain::class);
    $mockupRecordFilterChain->method('recordMatchesChainedFilters')->willReturn(true);

    $this->_recordCollection->add($this->_mockRecordDao);
    $filteredRecordCollection = $this->_recordCollection->getFilteredRecordCollectionByRecordFilterChain($mockupRecordFilterChain);

    $this->assertInstanceOf(RecordCollection::class, $filteredRecordCollection);
    $this->assertCount(1, $filteredRecordCollection);
  }

  public function testNotMatchingRecordFilterChainByRecordCollectionElementCount() : void {
    $mockupRecordFilterChain = $this->createMock(RecordFilterChain::class);
    $mockupRecordFilterChain->method('recordMatchesChainedFilters')->willReturn(false);

    $this->_recordCollection->add($this->_mockRecordDao);
    $filteredRecordCollection = $this->_recordCollection->getFilteredRecordCollectionByRecordFilterChain($mockupRecordFilterChain);

    $this->assertInstanceOf(RecordCollection::class, $filteredRecordCollection);
    $this->assertCount(0, $filteredRecordCollection);
  }
}