<?php
namespace App\Xml;

use App\Exception\Xml\OutputScheme\OutputSchemeNotApplicableOnGivenRecordDaoException;
use App\Record\Collection\RecordCollection;
use App\Xml\OutputScheme\OutputSchemeInterface;

/**
 * Class XmlDocumentWriter writes RecordDaos of RecordCollection into an xml output file
 *
 * @package App\Xml
 */
class XmlDocumentWriter {

  private const XML_VERSION = '1.0';

  private const XML_ENCODING = 'UTF-8';

  /**
   * Writes RecordDaos of RecordCollection into an xml output file defined by $outputFilepath.
   * Information about output scheme will be injected by OutputSchemeInterface. If
   * OutputSchemeInterface is not applicable on given RecordDao type, an
   * App\Exception\Xml\OutputScheme\OutputSchemeNotApplicableOnGivenRecordDaoException
   * will be thrown.
   *
   * @param RecordCollection $recordCollection
   * @param OutputSchemeInterface $outputScheme
   * @param string $outputFilepath
   * @param bool $xmlIndent
   * @return bool
   * @throws OutputSchemeNotApplicableOnGivenRecordDaoException
   */
  public function writeXmlOutputFileByRecordCollectionAndOutputScheme(RecordCollection $recordCollection, OutputSchemeInterface $outputScheme, string $outputFilepath, bool $xmlIndent = true) : bool {
    if(!$outputScheme->outputSchemeCanByAppliedOnRecordDao($recordCollection->getRecordDaoType())) {
      throw new OutputSchemeNotApplicableOnGivenRecordDaoException(sprintf('output scheme %s is not applicable on records of type %s', (string) $outputScheme, (string) $recordCollection->getRecordDaoType()));
    }

    $xmlWriter = new \XMLWriter();
    $xmlWriter->openUri($outputFilepath);
    $xmlWriter->setIndent($xmlIndent);
    $xmlWriter->setIndentString('  ');
    $xmlWriter->startDocument(self::XML_VERSION, self::XML_ENCODING);
    $xmlWriter->startElement($outputScheme->getXmlRootElementName());

    foreach($recordCollection as $record) {
      $xmlWriter->startElement($outputScheme->getXmlRecordElementName());
      $outputScheme->addRecordElementsToXmlWriterByRecordDao($xmlWriter, $record);
      $xmlWriter->endElement();
    }
    $xmlWriter->endElement();
    $xmlWriter->endDocument();
    return is_file($outputFilepath);
  }
}