<?php
namespace App\Record\Filter\Chain;

use App\Exception\Filter\Chain\FilterAlreadyChainedException;
use App\Exception\Filter\Chain\FilterNotApplicableOnGivenRecordDaoException;
use App\Exception\Filter\Chain\FilterNotChainedException;
use App\Record\DAO\RecordDaoInterface;
use App\Record\Filter\RecordFilterInterface;

/**
 * Class RecordFilterChain holds different filters of type
 * RecordFilterInterface and performs validation and filter operations
 *
 * @package App\Record\Filter\Chain
 */
class RecordFilterChain {

  /**
   * @var RecordFilterInterface[]
   */
  private $_filters = [];

  /**
   * Adds an RecordFilterInterface to the chain. An
   * App\Exception\Filter\Chain\FilterAlreadyChainedException
   * will be thrown, if the class is already chained.
   *
   * @param RecordFilterInterface $filter
   * @return RecordFilterChain
   * @throws FilterAlreadyChainedException
   */
  public function add(RecordFilterInterface $filter) : RecordFilterChain {
    if(array_key_exists((string) $filter, $this->_filters)) {
      throw new FilterAlreadyChainedException(sprintf('filter %s is already chained', (string) $filter));
    }
    $this->_filters[(string) $filter] = $filter;
    return $this;
  }

  /**
   * Checks, if RecordFilterInterface is already chained.
   *
   * @param RecordFilterInterface $filter
   * @return bool
   */
  public function contains(RecordFilterInterface $filter) : bool {
    return array_key_exists((string) $filter, $this->_filters);
  }

  /**
   * Removes RecordFilterInterface from the chain.
   * An App\Exception\Filter\Chain\FilterNotChainedException
   * will be thrown, if the filter is not chained.
   *
   * @param RecordFilterInterface $filter
   * @throws FilterNotChainedException
   */
  public function remove(RecordFilterInterface $filter) : void {
    if(!$this->contains($filter)) {
      throw new FilterNotChainedException(sprintf('filter %s is not chained', (string) $filter));
    }
    unset($this->_filters[(string) $filter]);
  }

  /**
   * Checks, if chained filters can be applied to RecordDao type. An
   * App\Exception\Filter\Chain\FilterNotApplicableOnGivenRecordDaoException will
   * be thrown, if one or more filters are not applicable on given RecordDaoInterface.
   *
   * @param RecordDaoInterface $recordDaoType
   * @throws FilterNotApplicableOnGivenRecordDaoException
   */
  public function isRecordFilterChainApplicableToRecordDaoType(RecordDaoInterface $recordDaoType) : void {
    foreach($this->_filters as $filter) {
      if(!$filter->filterCanByAppliedOnRecordDao($recordDaoType)) {
        throw new FilterNotApplicableOnGivenRecordDaoException(sprintf('filter %s is not applicable to record of type %s', (string) $filter, (string) $recordDaoType));
      }
    }
  }

  /**
   * Checks, if RecordDaoInterface matches all chained filters.
   *
   * @param RecordDaoInterface $record
   * @return bool
   */
  public function recordMatchesChainedFilters(RecordDaoInterface $record) : bool {
    foreach($this->_filters as $filter) {
      if(!$filter->applyFilter($record)) {
        return false;
      }
    }
    return true;
  }
}