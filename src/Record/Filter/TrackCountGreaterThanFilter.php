<?php
namespace App\Record\Filter;

use App\Record\DAO\RecordDao;
use App\Record\DAO\RecordDaoInterface;

/**
 * Class TrackCountGreaterThanFilter
 *
 * @package App\Record\Filter
 */
class TrackCountGreaterThanFilter extends AbstractNumericFilter {

  private $_trackCount;

  private const APPLICABLE_RECORD_DAOS = [
    RecordDao::class
  ];

  public function __construct(int $trackCount) {
    $this->_trackCount = $trackCount;
  }

  public function __toString() {
    return __CLASS__;
  }

  public function filterCanByAppliedOnRecordDao(RecordDaoInterface $recordDao): bool {
    return \in_array((string) $recordDao, self::APPLICABLE_RECORD_DAOS, true);
  }

  /**
   * Checks, if value of field RecordDaoInterface::getTrackCount
   * is greater than trackCount defined in the constructor. Throws
   * App\Exception\Filter\NumericFilterException  if
   * comparisonOperator is unknown.
   *
   * @param RecordDaoInterface $recordDao
   * @return bool
   * @throws \App\Exception\Filter\NumericFilterException
   */
  public function applyFilter(RecordDaoInterface $recordDao): bool {
    return $this->compare($recordDao->getTrackCount(), self::GT, $this->_trackCount);
  }
}