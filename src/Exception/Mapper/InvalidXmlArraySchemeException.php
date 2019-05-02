<?php
namespace App\Exception\Mapper;

/**
 * Class InvalidXmlArraySchemeException
 *
 * @package App\Exception\Mapper
 */
class InvalidXmlArraySchemeException extends \Exception {

  public const XML_ARRAY_DOES_NOT_MATCH_INPUT_SCHEME = 'xml array does not match scheme defined by input scheme';
}