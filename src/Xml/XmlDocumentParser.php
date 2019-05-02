<?php
namespace App\Xml;

use App\Exception\Mapper\InvalidXmlArraySchemeException;
use App\Exception\Xml\XmlDocumentParserException;
use App\Record\Collection\RecordCollection;
use App\Record\Mapper\RecordMapper;
use App\Xml\InputScheme\InputSchemeInterface;

/**
 * Class XmlDocumentParser converts xml file into RecordCollection
 *
 * @package App\Xml
 */
class XmlDocumentParser {

  /**
   * Returns a RecordCollection containing RecordDaos, which were parsed
   * from given xml file. An App\Exception\Xml\XmlDocumentParserException
   * will be thrown, if xml file is invalid. An
   * App\Exception\Mapper\InvalidXmlArraySchemeException will be thrown,
   * if the scheme of xml file does not match InputSchemeInterface.
   *
   * @param InputSchemeInterface $inputScheme
   * @param string $inputFilepath
   * @return RecordCollection
   * @throws XmlDocumentParserException
   * @throws InvalidXmlArraySchemeException
   */
  public function getRecordCollectionByInputSchemeAndXmlDocumentFilepath(InputSchemeInterface $inputScheme, string $inputFilepath) : RecordCollection {
    $this->_validateXmlDocument($inputFilepath);
    $recordMapper = new RecordMapper();
    return $recordMapper->getRecordCollectionByXmlArray(
      $inputScheme,
      $this->_convertXmlDocumentIntoArrayByXmlDocumentFilepath($inputFilepath)
    );
  }

  /**
   * Converts file content of the given xml document into an array.
   *
   * @param string $filepath
   * @return array
   */
  private function _convertXmlDocumentIntoArrayByXmlDocumentFilepath(string $filepath) : array {
    $xmlDocumentObject = simplexml_load_string(file_get_contents($filepath));
    return json_decode(json_encode($xmlDocumentObject), true);
  }

  /**
   * Validates filepath of xml file. Throws an App\Exception\Xml\XmlDocumentParserException,
   * if file was not found, file is empty or file is not an xml document.
   *
   * @param string $filepath
   * @throws XmlDocumentParserException
   */
  private function _validateXmlDocument(string $filepath) : void {
    // check file exists
    if(!is_file($filepath)) {
      throw new XmlDocumentParserException(sprintf(XmlDocumentParserException::INVALID_FILEPATH, $filepath));
    }
    // check file is not empty
    $xmlFileContent = file_get_contents($filepath);
    if(trim($xmlFileContent) === '') {
      throw new XmlDocumentParserException(sprintf(XmlDocumentParserException::EMPTY_FILE, $filepath));
    }
    // check file is a valid xml document
    libxml_use_internal_errors(true);
    (new \DOMDocument())->loadXML($xmlFileContent);
    if(!empty(libxml_get_errors())) {
      libxml_clear_errors();
      throw new XmlDocumentParserException(sprintf(XmlDocumentParserException::NOT_AN_XML_FILE, $filepath));
    }
  }
}