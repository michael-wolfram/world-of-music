<?php
namespace App\Record\Collection;

use App\Record\DAO\RecordDaoInterface;
use App\Record\Filter\Chain\RecordFilterChain;

/**
 * Class RecordCollection handles array of RecordDaoInterface values and provide methods
 * for filtering and iteration
 *
 * @package App\Record\Collection
 */
class RecordCollection implements \Countable, \Iterator {

  private $_recordDaoType;

  private $_records;

  private $_recordIteratorIndex = 0;

  /**
   * RecordCollection constructor. Throws \InvalidArgumentException if RecordDao type in
   * records array does not match RecordDao type of first constructor parameter.
   *
   * @param RecordDaoInterface $recordDaoType defines RecordDao type of elements in this collection.
   * @param array $records add records to collection
   * @throws \InvalidArgumentException
   */
  public function __construct(RecordDaoInterface $recordDaoType, array $records = []) {
    $this->_recordDaoType = $recordDaoType;
    foreach($records as $record) {
      $this->_validateElementRecordDaoType($record);
    }
    $this->_records = $records;
  }

  /**
   * Returns RecordDao type of elements in this collection.
   *
   * @return RecordDaoInterface
   */
  public function getRecordDaoType() : RecordDaoInterface {
    return $this->_recordDaoType;
  }

  /**
   * @see \Countable::count()
   * @return int
   */
  public function count() : int {
    return count($this->_records);
  }

  /**
   * @see \Iterator::current()
   * @return RecordDaoInterface|null
   */
  public function current() : ?RecordDaoInterface {
    return $this->_records[$this->_recordIteratorIndex];
  }

  /**
   * @see \Iterator::next()
   */
  public function next() : void {
    $this->_recordIteratorIndex ++;
  }

  /**
   * @see \Iterator::key()
   * @return int
   */
  public function key() : int {
    return $this->_recordIteratorIndex;
  }

  /**
   * @see \Iterator::valid()
   * @return bool
   */
  public function valid() : bool {
    return isset($this->_records[$this->_recordIteratorIndex]);
  }

  /**
   * @see \Iterator::rewind()
   */
  public function rewind() : void {
    $this->_recordIteratorIndex = 0;
  }

  /**
   * Adds an new element of type RecordDaoInterface to elements in this collection.
   * If type does not match RecordDao type defined in the constructor and returned
   * by method getRecordDaoType(), an \InvalidArgumentException will be thrown.
   *
   * @param RecordDaoInterface $record
   * @throws \InvalidArgumentException
   */
  public function add(RecordDaoInterface $record) {
    $this->_validateElementRecordDaoType($record);
    $this->_records[] = $record;
  }

  /**
   * Applies record filter chain on elements in this collection and return a new
   * instance of RecordCollection with filtered elements. If filter in chain is
   * not applicable to RecordDao type, an exception of type
   * \App\Exception\Filter\Chain\FilterNotApplicableOnGivenRecordDaoException
   * will be thrown.
   *
   * @param RecordFilterChain $recordFilterChain
   * @return RecordCollection
   * @throws \App\Exception\Filter\Chain\FilterNotApplicableOnGivenRecordDaoException
   */
  public function getFilteredRecordCollectionByRecordFilterChain(RecordFilterChain $recordFilterChain) : RecordCollection {
    $recordFilterChain->isRecordFilterChainApplicableToRecordDaoType($this->_recordDaoType);
    $filteredRecords = [];
    foreach($this->_records as $record) {
      if($recordFilterChain->recordMatchesChainedFilters($record)) {
        $filteredRecords[] = $record;
      }
    }
    return new self($this->_recordDaoType, $filteredRecords);
  }

  /**
   * Checks if parameter $record matches RecordDao type defined
   * in constructor. If type does not match, an
   * \InvalidArgumentException will be thrown.
   *
   * @param $record
   * @throws \InvalidArgumentException
   */
  private function _validateElementRecordDaoType($record) : void {
    if((string) $record !== (string) $this->_recordDaoType) {
      throw new \InvalidArgumentException('record of type '.get_class($record).' could not be added to record collection with elements of type '.$this->_recordDaoType);
    }
  }
}