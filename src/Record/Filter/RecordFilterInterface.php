<?php
namespace App\Record\Filter;

use App\Record\DAO\RecordDaoInterface;

/**
 * Interface RecordFilterInterface
 *
 * @package App\Record\Filter
 */
interface RecordFilterInterface {

  public function __toString();

  public function applyFilter(RecordDaoInterface $record) : bool;

  public function filterCanByAppliedOnRecordDao(RecordDaoInterface $recordDao) : bool;
}