<?php
namespace AppTest\Record\DAO;

use App\Record\DAO\RecordDao;
use PHPUnit\Framework\TestCase;

class RecordDaoTest extends TestCase {

  /**
   * @param array $trackListArray array of track lists
   * @param int $trackCount expected track list count
   *
   * @dataProvider providerCorrectRecordCountByTrackLists
   */
  public function testCorrectRecordCountByTrackLists(array $trackListArray, int $trackCount) : void {
    $recordDao = new RecordDao();
    $recordDao->setTrackLists($trackListArray);

    $this->assertEquals($trackCount, $recordDao->getTrackCount());
  }

  public function providerCorrectRecordCountByTrackLists() : array {
    return [
      [
        [[]],
        0
      ], [
        [[], [], []],
        0
      ], [
        [['Track 1'], []],
        1
      ], [
        [['Track 1', 'Track 2']],
        2
      ], [
        [['Track 1', 'Track 2'], ['Track 1']],
        3
      ], [
        [['Track 1'], ['Track 1'], ['Track 1'], ['Track 1']],
        4
      ], [
        [['Track 1'], ['Track 1', 'Track2'], [], ['Track 1'], ['Track 1']],
        5
      ]
    ];
  }
}