<?php
namespace App\Record\DAO;

/**
 * Interface RecordDaoInterface
 *
 * @package App\Record\DAO
 */
interface RecordDaoInterface {

  public function __toString();

  public function getTitle() : ?string;

  public function setTitle(string $title);

  public function getName() : ?string;

  public function setName(string $name);

  public function getGenres() : array;

  public function setGenres(array $genres);

  public function getReleaseDate() : ?\DateTime;

  public function setReleaseDate(?\DateTime $releaseDate);

  public function getLabel() : ?string;

  public function setLabel(string $label);

  public function getFormats() : array;

  public function setFormats(array $formats);

  public function getTrackLists() : array;

  public function setTrackLists(array $trackLists);

  public function getTrackCount() : int;
}