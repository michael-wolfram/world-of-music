# WorldOfMusic XML-Aggregator

WorldOfMusic XML-Aggregator is a simple script, which filters an XML document of record elements and exports\
filtered records to a new XML document.

<br>

## Requirements

To run this project, following packages must be installed on your computer:

* PHP >= 7.2
* PHP-XML extension

<br>

## Installation

Download package as zip archive and extract it in your favorite folder. To install all required composer packages,\
navigate to project root and run composer install by entering the following commands in your console. 

```bash
$ cd path/to/project
$ composer install
```
<br>

## Execute XML-Aggregator

For conversion of XML file copy file into project root. The file must named "_data.xml_". Now, you are able to run\
aggregation by entering following commands in your console.

```bash
$ cd path/to/project
$ php console.php
```

If aggregation of xml file was successful, a message "[...]/output.xml successfully created" will be displayed in the console.\
If an error occurred, an error message will appear in console.

<br>

## Run PHPUnit tests

To run the PHPUnit tests of this project, run following commands on your console:

```bash
$ cd path/to/project
$ php vendor/bin/phpunit --bootstrap vendor/autoload.php tests/
```

<br>

## Time tracking

**5 hours** feature development\
**6 hours** writing phpunit tests\
**1 hour** documentation