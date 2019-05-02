<?php
namespace AppTest\Xml;

use App\Exception\Xml\XmlDocumentParserException;
use App\Record\Collection\RecordCollection;
use App\Xml\InputScheme\InputSchemeInterface;
use App\Xml\XmlDocumentParser;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class XmlDocumentParserTest extends TestCase {

  private $_mockupInputScheme;

  private $_xmlDocumentParser;

  private $_virtualFileSystem;

  public function setUp(): void {
    $this->_mockupInputScheme = $this->createMock(InputSchemeInterface::class);
    $this->_xmlDocumentParser = new XmlDocumentParser();
    // setup virtual file system
    $fileDirectory = [
      'input' => [
        'empty.file' => '',
        'invalid.file' => 'test1, test2, test3',
        'valid.file' => '<?xml version="1.0" encoding="UTF-8"?><records><record><title>Atom Sounds</title>'.
                        '<name>Atom Sounds</name><genre>Various</genre><releasedate>2004.04.02</releasedate>'.
                        '<label>Atom Sounds</label><formats>CD</formats><tracklisting /></record><record>'.
                        '<title>Elvis Live In Las Vegas</title><name>Elvis Live In Las Vegas</name>'.
                        '<genre>Rock / Pop</genre><releasedate>2001.07.10</releasedate><label>RCA Records</label>'.
                        '<formats>CD</formats><tracklisting /></record></records>'
      ]
    ];
    $this->_virtualFileSystem = vfsStream::setup('root', 444, $fileDirectory);
  }

  public function tearDown(): void {
    $this->_mockupInputScheme = null;
    $this->_xmlDocumentParser = null;
    $this->_virtualFileSystem  = null;
  }

  public function testThrowExceptionOnInvalidFilepath() : void {
    $this->expectException(XmlDocumentParserException::class);
    $this->expectExceptionMessage(sprintf(XmlDocumentParserException::INVALID_FILEPATH, $this->_virtualFileSystem->url().'/input/not-existing.file'));
    $this->_xmlDocumentParser->getRecordCollectionByInputSchemeAndXmlDocumentFilepath($this->_mockupInputScheme, $this->_virtualFileSystem->url().'/input/not-existing.file');
  }

  public function testThrowExceptionOnEmptyFile() : void {
    $this->expectException(XmlDocumentParserException::class);
    $this->expectExceptionMessage(sprintf(XmlDocumentParserException::EMPTY_FILE, $this->_virtualFileSystem->url().'/input/empty.file'));
    $this->_xmlDocumentParser->getRecordCollectionByInputSchemeAndXmlDocumentFilepath($this->_mockupInputScheme, $this->_virtualFileSystem->url().'/input/empty.file');
  }

  public function testThrowExceptionOnInvalidFileContent() : void {
    $this->expectException(XmlDocumentParserException::class);
    $this->expectExceptionMessage(sprintf(XmlDocumentParserException::NOT_AN_XML_FILE, $this->_virtualFileSystem->url().'/input/invalid.file'));
    $this->_xmlDocumentParser->getRecordCollectionByInputSchemeAndXmlDocumentFilepath($this->_mockupInputScheme, $this->_virtualFileSystem->url().'/input/invalid.file');
  }

  public function testValidXmlSchemeReturnsRecordCollection() : void {
    $this->_mockupInputScheme->method('getXmlRecordElementIdentifier')->willReturn('record');
    $recordCollection = $this->_xmlDocumentParser->getRecordCollectionByInputSchemeAndXmlDocumentFilepath($this->_mockupInputScheme, $this->_virtualFileSystem->url().'/input/valid.file');

    $this->assertInstanceOf(RecordCollection::class, $recordCollection);
    $this->assertEquals(2, $recordCollection->count());
  }
}