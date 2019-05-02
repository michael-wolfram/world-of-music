<?php
require_once __DIR__ . '/vendor/autoload.php';

$consoleColorFormatter = new \App\Console\ConsoleColorFormatter();

// parse xml record file
try {
  $xmlDocumentParser = new \App\Xml\XmlDocumentParser();
  $recordCollection = $xmlDocumentParser->getRecordCollectionByInputSchemeAndXmlDocumentFilepath(
    new \App\Xml\InputScheme\MusicMozInputScheme(),
    __DIR__.'/data.xml'
  );
} catch(\App\Exception\Xml\XmlDocumentParserException | \App\Exception\Mapper\InvalidXmlArraySchemeException $e) {
  die($consoleColorFormatter->showErrorMessage($e->getMessage())."\r\n");
}

// configure record filters and add them to record filter chain
$recordFilters = [
  new \App\Record\Filter\ReleaseDateBeforeFilter(\DateTime::createFromFormat('d.m.Y', '01.01.2001')),
  new \App\Record\Filter\TrackCountGreaterThanFilter(10)
];
$recordFilterChain = new \App\Record\Filter\Chain\RecordFilterChain();
foreach($recordFilters as $recordFilter) {
  try {
    $recordFilterChain->add($recordFilter);
  } catch(\App\Exception\Filter\Chain\FilterAlreadyChainedException $e) {
    echo $consoleColorFormatter->showWarningMessage($e->getMessage())."\r\n";
  }
}

try {
  // apply record filters on record collection
  $recordCollection = $recordCollection->getFilteredRecordCollectionByRecordFilterChain($recordFilterChain);

  // write filtered records to output file
  $outputFilepath = __DIR__.'/output.xml';

  $xmlDocumentWriter = new \App\Xml\XmlDocumentWriter();
  $xmlDocumentWriter->writeXmlOutputFileByRecordCollectionAndOutputScheme(
    $recordCollection,
    new \App\Xml\OutputScheme\MusicMozOutputScheme(),
    $outputFilepath
  );
  echo $consoleColorFormatter->showSuccessMessage('file '.$outputFilepath.' successfully created')."\r\n";
} catch(\App\Exception\Filter\Chain\FilterNotApplicableOnGivenRecordDaoException | \App\Exception\Xml\OutputScheme\OutputSchemeNotApplicableOnGivenRecordDaoException $e) {
  die($consoleColorFormatter->showErrorMessage($e->getMessage())."\r\n");
}