<?php
namespace AppTest\Xml;

use App\Exception\Xml\OutputScheme\OutputSchemeNotApplicableOnGivenRecordDaoException;
use App\Record\Collection\RecordCollection;
use App\Record\DAO\RecordDaoInterface;
use App\Xml\OutputScheme\OutputSchemeInterface;
use App\Xml\XmlDocumentWriter;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class XmlDocumentWriterTest extends TestCase {

  private $_mockupOutputScheme;

  private $_virtualFileSystem;

  private $_xmlDocumentWriter;

  public function setUp(): void {
    $this->_mockupOutputScheme = $this->createMock(OutputSchemeInterface::class);
    $this->_mockupOutputScheme->method('outputSchemeCanByAppliedOnRecordDao')->willReturn(true);
    $this->_mockupOutputScheme->method('getXmlRootElementName')->willReturn('matchingReleases');
    $this->_mockupOutputScheme->method('getXmlRecordElementName')->willReturn('record');
    $this->_xmlDocumentWriter = new XmlDocumentWriter();
    // setup virtual file system
    $fileDirectory = [
      'output' => [
      ]
    ];
    $this->_virtualFileSystem = vfsStream::setup('root', 444, $fileDirectory);
  }

  public function tearDown(): void {
    $this->_mockupOutputScheme = null;
    $this->_virtualFileSystem = null;
    $this->_xmlDocumentWriter = null;
  }

  public function testCreateOutputFileByEmptyRecordCollection() : void {
    $mockupRecordCollection = $this->createMock(RecordCollection::class);
    $outputFilepath = $this->_virtualFileSystem->url().'/output/output.xml';

    $this->_xmlDocumentWriter->writeXmlOutputFileByRecordCollectionAndOutputScheme($mockupRecordCollection, $this->_mockupOutputScheme, $outputFilepath);
    $outputFileContent = file_get_contents($outputFilepath);

    $this->assertEquals('<?xml version="1.0" encoding="UTF-8"?><matchingReleases/>', str_replace(["\r", "\n"], '', $outputFileContent));
  }

  public function testCreateOutputFileByRecordCollectionWithOneRecordDao() : void {
    $outputFilepath = $this->_virtualFileSystem->url().'/output/output.xml';

   $this->_mockupOutputScheme->method('addRecordElementsToXmlWriterByRecordDao')->willReturnCallback(function($xmlWriter, $recordDao) {
     $xmlWriter->writeElement('name', $recordDao->getName());
     $xmlWriter->writeElement('trackCount', $recordDao->getTrackCount());
    });

    $mockupRecordDao = $this->createMock(RecordDaoInterface::class);
    $mockupRecordDao->method('getName')->willReturn('title');
    $mockupRecordDao->method('getTrackCount')->willReturn(21);

    $mockupRecordCollection = $this->_getMockupRecordCollection([$mockupRecordDao]);
    $this->_xmlDocumentWriter->writeXmlOutputFileByRecordCollectionAndOutputScheme($mockupRecordCollection, $this->_mockupOutputScheme, $outputFilepath, false);

    $this->assertEquals(
      '<?xml version="1.0" encoding="UTF-8"?><matchingReleases><record><name>title</name><trackCount>21</trackCount></record></matchingReleases>',
      str_replace(["\r", "\n"], '', file_get_contents($outputFilepath))
    );
  }

  public function testThrowExceptionIfOutputSchemeIsNotApplicableOnRecordDao() : void {
    $mockupOutputScheme = $this->createMock(OutputSchemeInterface::class);
    $mockupOutputScheme ->method('outputSchemeCanByAppliedOnRecordDao')->willReturn(false);
    $this->expectException(OutputSchemeNotApplicableOnGivenRecordDaoException::class);
    $this->_xmlDocumentWriter->writeXmlOutputFileByRecordCollectionAndOutputScheme(
      $this->createMock(RecordCollection::class),
      $mockupOutputScheme,
      ''
    );
  }

  private function _getMockupRecordCollection(array $recordDaos = []) : MockObject {
    $mockupRecordCollection = $this->createMock(RecordCollection::class);

    $iterator = new \ArrayIterator($recordDaos);

    $mockupRecordCollection->method('rewind')->willReturnCallback(function () use ($iterator) {
      $iterator->rewind();
    });
    $mockupRecordCollection->method('current')->willReturnCallback(function () use ($iterator) {
      return $iterator->current();
    });
    $mockupRecordCollection->method('key')->willReturnCallback(function () use ($iterator) {
      return $iterator->key();
    });
    $mockupRecordCollection->method('next')->willReturnCallback(function () use ($iterator) {
      $iterator->next();
    });
    $mockupRecordCollection->method('valid')->willReturnCallback(function () use ($iterator) {
      return $iterator->valid();
    });
    return $mockupRecordCollection;
  }
}