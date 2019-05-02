<?php
namespace App\Exception\Xml;

/**
 * Class XmlDocumentParserException
 *
 * @package App\Exception\Xml
 */
class XmlDocumentParserException extends \Exception {

  public const INVALID_FILEPATH = 'input file "%s" does not exist';

  public const EMPTY_FILE = 'file "%s" is empty';

  public const NOT_AN_XML_FILE = 'file "%s" is not a valid xml document';
}