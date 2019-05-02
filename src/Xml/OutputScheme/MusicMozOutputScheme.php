<?php
namespace App\Xml\OutputScheme;

use App\Record\DAO\RecordDao;
use App\Record\DAO\RecordDaoInterface;

/**
 * Class MusicMozInputScheme defines an xml root name and an xml record element name for the output xml file.
 * This class also adds xml elements to used XMLWriter instance
 *
 * @package App\Xml\InputScheme
 */
class MusicMozOutputScheme implements OutputSchemeInterface {

  private const APPLICABLE_RECORD_DAOS = [
    RecordDao::class
  ];

  private const XML_ROOT_ELEMENT_NAME = 'matchingReleases';

  private const XML_RECORD_ELEMENT_NAME = 'release';

  public function __toString(): string {
    return __CLASS__;
  }

  public function getXmlRootElementName(): string {
    return self::XML_ROOT_ELEMENT_NAME;
  }

  public function getXmlRecordElementName(): string {
    return self::XML_RECORD_ELEMENT_NAME;
  }

  public function outputSchemeCanByAppliedOnRecordDao(RecordDaoInterface $recordDao): bool {
    return in_array((string) $recordDao, self::APPLICABLE_RECORD_DAOS, true);
  }

  /**
   * Adds xml elements "name" and "trackCount" to given XMLWriter
   * instance from RecordDaoInterface values.
   *
   * @param \XMLWriter $xmlWriter
   * @param RecordDaoInterface $recordDao
   */
  public function addRecordElementsToXmlWriterByRecordDao(\XMLWriter $xmlWriter, RecordDaoInterface $recordDao) : void {
    $xmlWriter->writeElement('name', $recordDao->getName());
    $xmlWriter->writeElement('trackCount', $recordDao->getTrackCount());
  }
}