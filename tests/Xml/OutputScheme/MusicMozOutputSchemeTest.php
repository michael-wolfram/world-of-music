<?php
namespace AppTest\Xml\OutputScheme;

use App\Record\DAO\RecordDao;
use App\Record\DAO\RecordDaoInterface;
use App\Xml\OutputScheme\MusicMozOutputScheme;
use PHPUnit\Framework\TestCase;

class MusicMozOutputSchemeTest extends TestCase {

  private $_mockupRecordDao;

  private $_musicMozOutputScheme;

  private $_xmlWriter;

  public function setUp(): void {
    $this->_mockupRecordDao = $this->createMock(RecordDaoInterface::class);
    $this->_musicMozOutputScheme = new MusicMozOutputScheme();
    $this->_xmlWriter = new \XMLWriter();
    $this->_xmlWriter->openMemory();
  }

  public function tearDown(): void {
    $this->_mockupRecordDao = null;
    $this->_musicMozOutputScheme = null;
    $this->_xmlWriter = null;
  }

  public function testReturnTrueOnCheckingValidRecordDao() : void {
    $this->_mockupRecordDao->method('__toString')->willReturn(RecordDao::class);
    $this->assertTrue($this->_musicMozOutputScheme->outputSchemeCanByAppliedOnRecordDao($this->_mockupRecordDao));
  }

  public function testReturnFalseOnCheckingInvalidRecordDao() : void {
    $this->_mockupRecordDao->method('__toString')->willReturn('OtherRecordDao');
    $this->assertFalse($this->_musicMozOutputScheme->outputSchemeCanByAppliedOnRecordDao($this->_mockupRecordDao));
  }

  public function testWriteXmlByEmptyRecordDao() : void {
    $this->_musicMozOutputScheme->addRecordElementsToXmlWriterByRecordDao($this->_xmlWriter, $this->_mockupRecordDao);
    $this->assertEquals('<name/><trackCount>0</trackCount>', $this->_xmlWriter->outputMemory(true));
  }

  public function testWriteXmlByRecordDao() : void {
    $this->_mockupRecordDao->method('getTrackCount')->willReturn(12);
    $this->_mockupRecordDao->method('getName')->willReturn('testRecord');
    $this->_musicMozOutputScheme->addRecordElementsToXmlWriterByRecordDao($this->_xmlWriter, $this->_mockupRecordDao);
    $this->assertEquals('<name>testRecord</name><trackCount>12</trackCount>', $this->_xmlWriter->outputMemory(true));
  }
}