<?php

namespace App\Record\DAO;

/**
 * RecordDao represents single record and provides access to record attributes
 *
 * Class RecordDao
 * @package App\Record\DAO
 */
class RecordDao implements RecordDaoInterface {

  private $_title;

  private $_name;

  private $_genres = [];

  private $_releaseDate;

  private $_label;

  private $_formats = [];

  private $_trackLists = [];

  public function __toString() {
    return __CLASS__;
  }

  public function getTitle() : ?string {
    return $this->_title;
  }

  public function setTitle(string $title) {
    $this->_title = $title;
    return $this;
  }

  public function getName() : ?string {
    return $this->_name;
  }

  public function setName(string $name) {
    $this->_name = $name;
    return $this;
  }

  public function getGenres() : array {
    return $this->_genres;
  }

  public function setGenres(array $genres) {
    $this->_genres = $genres;
    return $this;
  }

  public function getReleaseDate() : ?\DateTime {
    return $this->_releaseDate;
  }

  public function setReleaseDate(?\DateTime $releaseDate) {
    $this->_releaseDate = $releaseDate;
    return $this;
  }

  public function getLabel() : ?string {
    return $this->_label;
  }

  public function setLabel(string $label) {
    $this->_label = $label;
    return $this;
  }

  public function getFormats() : array {
    return $this->_formats;
  }

  public function setFormats(array $formats) {
    $this->_formats = $formats;
    return $this;
  }

  public function getTrackLists() : array {
    return $this->_trackLists;
  }

  public function setTrackLists(array $trackLists) {
    $this->_trackLists = $trackLists;
    return $this;
  }

  public function getTrackCount() : int {
    $count = 0;
    foreach($this->_trackLists as $trackList) {
      $count += count($trackList);
    }
    return $count;
  }
}