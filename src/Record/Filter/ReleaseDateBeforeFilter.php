<?php
namespace App\Record\Filter;

use App\Record\DAO\RecordDao;
use App\Record\DAO\RecordDaoInterface;

/**
 * Class ReleaseDateBeforeFilter
 *
 * @package App\Record\Filter
 */
class ReleaseDateBeforeFilter implements RecordFilterInterface {

  private const APPLICABLE_RECORD_DAOS = [
    RecordDao::class
  ];

  private $_beforeDateTime;

  public function __construct(\DateTime $beforeDateTime) {
    $this->_beforeDateTime = $beforeDateTime;
  }

  public function __toString() {
    return __CLASS__;
  }

  public function filterCanByAppliedOnRecordDao(RecordDaoInterface $recordDao): bool {
    return \in_array((string) $recordDao, self::APPLICABLE_RECORD_DAOS, true);
  }

  /**
   * Checks, if value of field RecordDaoInterface::releaseDate
   * is before date defined in the constructor.
   *
   * @param RecordDaoInterface $recordDao
   * @return bool
   */
  public function applyFilter(RecordDaoInterface $recordDao): bool {
    return $recordDao->getReleaseDate() < $this->_beforeDateTime;
  }
}