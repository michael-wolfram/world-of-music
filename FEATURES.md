# WorldOfMusic XML-Aggregator feature requests

Feature requests of customer WorldOfMusic

<br>

## Feature request #1

>  The MusicMoz Data is not available anymore - but there is a new fancy online service with nearly the same data but a different XML structure

<br>

A new InputScheme class has to be created, which implements App\Xml\InputScheme\InputSchemeInterface and defines\
new name of record and record field names in XML document.

This InputScheme must be used in XmlDocumentParser::getRecordCollectionByInputSchemeAndXmlDocumentFilepath().\
**see console.php:10**

<br>

## Feature request #2

> "WorldOfMusic" wants to have another XML written, which lists all Releases with at least 2 Compact Discs

<br>

A new RecordFilter class has to be created, which implements App\Record\Filter\RecordFilterInterface. This\
RecordFilter must be appended to the existing RecordFilter array.\
**see console.php:18**

<br>

The filter conditions depends on how a release including 2 Compact Discs is defined:

1. Record attribute "formats" contains keyword "CD" twice times or more.
2. Record has keyword "CD" in attribute "formats" and contains two track lists or more
3. combination of first and second condition

<br>

## Estimated efforts

**15 min** feature request #1\
**45 min** feature request #2