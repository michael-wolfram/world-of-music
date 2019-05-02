<?php
namespace App\Xml\OutputScheme;

use App\Record\DAO\RecordDaoInterface;

/**
 * Interface OutputSchemeInterface
 *
 * @package App\Xml\OutputScheme
 */
interface OutputSchemeInterface {

  public function __toString(): string;

  public function getXmlRootElementName(): string;

  public function getXmlRecordElementName(): string;

  public function outputSchemeCanByAppliedOnRecordDao(RecordDaoInterface $recordDao): bool;

  public function addRecordElementsToXmlWriterByRecordDao(\XMLWriter $xmlWriter, RecordDaoInterface $recordDao): void;
}