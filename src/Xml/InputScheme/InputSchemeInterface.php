<?php
namespace App\Xml\InputScheme;

/**
 * Interface InputSchemeInterface
 *
 * @package App\Xml\InputScheme
 */
interface InputSchemeInterface {

  public function getXmlRecordElementIdentifier() : string;

  public function getRecordReleaseDateFormat() : string;

  public function getRecordTitleFieldName() : string;

  public function getRecordNameFieldName() : string;

  public function getRecordGenreFieldName() : string;

  public function getRecordReleaseDateFieldName() : string;

  public function getRecordLabelFieldName() : string;

  public function getRecordFormatFieldName() : string;

  public function getRecordTrackListFieldName() : string;
}