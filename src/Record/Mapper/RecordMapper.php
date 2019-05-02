<?php
namespace App\Record\Mapper;

use App\Exception\Mapper\InvalidXmlArraySchemeException;
use App\Record\Collection\RecordCollection;
use App\Record\DAO\RecordDao;
use App\Xml\InputScheme\InputSchemeInterface;

/**
 * Class RecordMapper maps xml array elements into RecordDaos inside of RecordCollection
 *
 * @package App\Record\Mapper
 */
class RecordMapper {

  /**
   * Maps array of records to RecordDaos and return them inside a RecordCollection.
   * InputSchemeInterface defines the name of a single record and key names of record array.
   * If record array is empty, an empty RecordCollection will be returned.
   * An App\Exception\Mapper\InvalidXmlArraySchemeException will be thrown, if the record
   * array is not empty and contains no entries defined by name of single record.
   *
   * @param InputSchemeInterface $inputScheme
   * @param array $xmlArray
   * @return RecordCollection
   * @throws InvalidXmlArraySchemeException
   */
  public function getRecordCollectionByXmlArray(InputSchemeInterface $inputScheme, array $xmlArray) : RecordCollection {
    if(empty($xmlArray)) {
      return new RecordCollection(new RecordDao());
    }
    if(!isset($xmlArray[$inputScheme->getXmlRecordElementIdentifier()])) {
      throw new InvalidXmlArraySchemeException(InvalidXmlArraySchemeException::XML_ARRAY_DOES_NOT_MATCH_INPUT_SCHEME);
    }
    $recordDaoArray = [];
    foreach($xmlArray[$inputScheme->getXmlRecordElementIdentifier()] as $record) {
      $recordDao = new RecordDao();
      $recordDao->setTitle(trim($record[$inputScheme->getRecordTitleFieldName()] ?? ''))
                ->setName(trim($record[$inputScheme->getRecordNameFieldName()]  ?? ''))
                ->setGenres($this->_convertDelimitedElementValuesToArray($record[$inputScheme->getRecordGenreFieldName()] ?? []))
                ->setReleaseDate($this->_getReleaseDateTimeByInputSchemeAndRecordElement($inputScheme, $record[$inputScheme->getRecordReleaseDateFieldName()] ?? ''))
                ->setLabel($this->_formatLabelByRecordElement($record[$inputScheme->getRecordLabelFieldName()] ?? ''))
                ->setFormats($this->_convertDelimitedElementValuesToArray($record[$inputScheme->getRecordFormatFieldName()] ?? []))
                ->setTrackLists($record[$inputScheme->getRecordTrackListFieldName()] ?? []);
      $recordDaoArray[] = $recordDao;
    }
    return new RecordCollection(new RecordDao(), $recordDaoArray);
  }

  /**
   * Converts comma and slash separated strings of given value to an array.
   * If the value is already a string, an array with this string will be returned.
   *
   *
   * @param $elementValue
   * @return array
   */
  private function _convertDelimitedElementValuesToArray($elementValue) : array {
    if(is_array($elementValue)) {
      return $elementValue;
    }
    // element contains multiple values ex. genres and formats
    if(is_string($elementValue) && (strpos($elementValue, ',') !== false || strpos($elementValue, '/') !== false)) {
      $delimiter = strpos($elementValue, ',') !== false ? ',' : '/';
      $valueArray = [];
      $explodedValues = explode($delimiter, $elementValue);
      foreach($explodedValues as $value) {
        if(trim($value) !== '') {
          $valueArray[] = trim($value);
        }
      }
      return $valueArray;
    }
    // element contains only one value
    return trim($elementValue) === '' ? [] : [trim($elementValue)];
  }

  /**
   * Converts the label value of xml array to comma separated string,
   * if label value is an array.
   *
   * @param $label
   * @return string
   */
  private function _formatLabelByRecordElement($label) : string {
    if(is_array($label)) {
      return implode(', ', $label);
    }
    return trim($label);
  }

  /**
   * Converts release date string of xml array to an \DateTime object
   * by date format defined by InputScheme. If the release date string
   * is empty, null will be returned.
   *
   *
   * @param InputSchemeInterface $inputScheme
   * @param string $releaseDateString
   * @return \DateTime|null
   */
  private function _getReleaseDateTimeByInputSchemeAndRecordElement(InputSchemeInterface $inputScheme, string $releaseDateString) : ?\DateTime {
    return $releaseDateString === '' ? null : \DateTime::createFromFormat($inputScheme->getRecordReleaseDateFormat(), $releaseDateString);
  }
}