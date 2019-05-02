<?php
namespace App\Xml\InputScheme;

/**
 * Class MusicMozInputScheme defines name of a single record and key names of record array
 *
 * @package App\Xml\InputScheme
 */
class MusicMozInputScheme implements InputSchemeInterface {

  public function getXmlRecordElementIdentifier(): string {
    return 'record';
  }

  public function getRecordReleaseDateFormat(): string {
    return 'Y.m.d';
  }

  public function getRecordTitleFieldName(): string {
    return 'title';
  }

  public function getRecordNameFieldName(): string {
    return 'name';
  }

  public function getRecordGenreFieldName(): string {
    return 'genre';
  }

  public function getRecordReleaseDateFieldName(): string {
    return 'releasedate';
  }

  public function getRecordLabelFieldName(): string {
    return 'label';
  }

  public function getRecordFormatFieldName(): string {
    return 'formats';
  }

  public function getRecordTrackListFieldName(): string {
    return 'tracklisting';
  }
}