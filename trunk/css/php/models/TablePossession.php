<?php
/* Copyright (C) 2008 App Tsunami, Inc. */
/* 
 *  This program is free software: you can redistribute it and/or modify 
 *  it under the terms of the GNU General Public License as published by 
 *  the Free Software Foundation, either version 3 of the License, or 
 *  (at your option) any later version. 
 * 
 *  This program is distributed in the hope that it will be useful, 
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of 
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 *  GNU General Public License for more details. 
 * 
 *  You should have received a copy of the GNU General Public License 
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 */
/* TablePossession.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/models/OpfDataTable.php');

class TablePossession extends OpfDataTable {

  const TABLE_NAME = 'Possession';
  const FIELD_PERSON_OBJID = 'personObjid';
  const FIELD_FROM_PERSON_OBJID = 'fromPersonObjid';
  const FIELD_CARD_OBJID = 'cardObjid';
  const FIELD_POSSESSION_START = 'possessionStart';

  public function __construct($dbConnect, $tableName=self::TABLE_NAME, 
      $fieldNameList=null) {
    if (is_null($fieldNameList)) {
      $fieldNameList = Array(
        self::FIELD_OBJID,
        self::FIELD_PERSON_OBJID,
        self::FIELD_FROM_PERSON_OBJID,
        self::FIELD_CARD_OBJID,
        self::FIELD_POSSESSION_START,
      );
    } // if
    parent::__construct($dbConnect, $tableName, $fieldNameList);
  } // __construct

  public function insertPossession($receiverUid, $fromPersonObjid, 
      $cardObjid) {
    $tablePerson = new TablePerson($this->dbConnect);
    $NEW_PERSON_OBJID = '@NEW_PERSON_OBJID';
    $sqlArray = $tablePerson->generateInsertPersonSql($receiverUid, 0);
    $sqlArray[] = $this->generateSqlSetLastInsertId($NEW_PERSON_OBJID);

    $stmt = $this->newInsertStatement();
    $stmt->addNonStringFieldValuePair(self::FIELD_PERSON_OBJID, $NEW_PERSON_OBJID);
    $stmt->addNonStringFieldValuePair(self::FIELD_CARD_OBJID,
      $cardObjid);
    if (!is_null($fromPersonObjid)) {
      $stmt->addNonStringFieldValuePair(self::FIELD_FROM_PERSON_OBJID,
        $fromPersonObjid);
    } // if
    $stmt->addNowFieldValuePair(self::FIELD_POSSESSION_START);
    $sqlArray[] = $stmt->toString(1);
    $this->executeTransaction($sqlArray);
  } // insertPossession

  public function fetchByPersonObjid($personObjid) {
    if (is_null($personObjid)) return(null);
    $filter = self::FIELD_PERSON_OBJID.'='.$personObjid;
    return($this->fetchSomeAsArray($filter));
  } // fetchByPersonObjid

  public function fetchBySenderObjid($senderObjid) {
    if (is_null($senderObjid)) return(null);
    $filter = self::FIELD_FROM_PERSON_OBJID.'='.$senderObjid;
    return($this->fetchSomeAsArray($filter));
  } // fetchBySenderObjid

} // TablePossession
?>
