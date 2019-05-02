<?php
namespace AppTest\Record\Mapper;

use App\Exception\Mapper\InvalidXmlArraySchemeException;
use App\Record\Collection\RecordCollection;
use App\Record\DAO\RecordDao;
use App\Record\Mapper\RecordMapper;
use App\Xml\InputScheme\InputSchemeInterface;
use PHPUnit\Framework\TestCase;

class RecordMapperTest extends TestCase {

  private $_mockupInputScheme;

  private $_recordMapper;

  public function setUp(): void {
    $this->_mockupInputScheme = $this->createMock(InputSchemeInterface::class);
    $this->_mockupInputScheme->method('getXmlRecordElementIdentifier')->willReturn('record');
    $this->_mockupInputScheme->method('getRecordReleaseDateFormat')->willReturn('d.m.Y');
    $this->_mockupInputScheme->method('getRecordTitleFieldName')->willReturn('title');
    $this->_mockupInputScheme->method('getRecordNameFieldName')->willReturn('name');
    $this->_mockupInputScheme->method('getRecordGenreFieldName')->willReturn('genre');
    $this->_mockupInputScheme->method('getRecordReleaseDateFieldName')->willReturn('releaseDate');
    $this->_mockupInputScheme->method('getRecordLabelFieldName')->willReturn('label');
    $this->_mockupInputScheme->method('getRecordFormatFieldName')->willReturn('format');
    $this->_mockupInputScheme->method('getRecordTrackListFieldName')->willReturn('trackList');
    $this->_recordMapper = new RecordMapper();
  }

  public function tearDown(): void {
    $this->_mockupInputScheme = null;
    $this->_recordMapper = null;
  }

  public function testCreateEmptyRecordCollectionByEmptyRecordXmlArray() : void {
    $recordCollection = $this->_recordMapper->getRecordCollectionByXmlArray($this->_mockupInputScheme, []);
    $this->assertInstanceOf(RecordCollection::class, $recordCollection);
    $this->assertCount(0, $recordCollection);
  }

  /**
   * @param string $value
   * @param array $expectedArray
   *
   * @dataProvider providerArrayConversionOfCommaAndSlashSeparatedValues
   */
  public function testArrayConversionOfCommaAndSlashSeparatedValues(string $value, array $expectedArray) : void {
    $recordCollection = $this->_recordMapper->getRecordCollectionByXmlArray($this->_mockupInputScheme, [
      $this->_mockupInputScheme->getXmlRecordElementIdentifier() => [[
        $this->_mockupInputScheme->getRecordGenreFieldName() => $value
      ]]
    ]);
    $this->assertInstanceOf(RecordDao::class, $recordCollection->current());
    $this->assertEquals($expectedArray, $recordCollection->current()->getGenres());
  }

  public function testRecordDateStringConversionToDateTimeObject() : void {
    $dateString = '08.04.2019';

    $recordCollection = $this->_recordMapper->getRecordCollectionByXmlArray($this->_mockupInputScheme, [
      $this->_mockupInputScheme->getXmlRecordElementIdentifier() => [[
        $this->_mockupInputScheme->getRecordReleaseDateFieldName() => $dateString
      ]]
    ]);
    $this->assertInstanceOf(\DateTime::class, $recordCollection->current()->getReleaseDate());
    $this->assertEquals($dateString, $recordCollection->current()->getReleaseDate()->format('d.m.Y'));
  }

  public function testThrowExceptionOnInvalidXmlArraySchemeForDefinedInputScheme() : void {
    $this->expectException(InvalidXmlArraySchemeException::class);
    $this->_recordMapper->getRecordCollectionByXmlArray($this->_mockupInputScheme, ['recordEntry' => [[]]]);
  }

  public function providerArrayConversionOfCommaAndSlashSeparatedValues() : array {
    return [
      [
        '',
        []
      ], [
        'Rock',
        ['Rock']
      ], [
        'Rock,',
        ['Rock']
      ], [
        'Rock/',
        ['Rock']
      ], [
        'Rock, Pop, Hip-Hop',
        ['Rock', 'Pop', 'Hip-Hop']
      ], [
        'Rock/ Pop /Hip-Hop',
        ['Rock', 'Pop', 'Hip-Hop']
      ]
    ];
  }
}