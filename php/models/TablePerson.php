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
/* TablePerson.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/opf/OpfApplicationConfig.php');
include_once($rDir.'openface/php/models/OpfDataTable.php');

class TablePerson extends OpfDataTable {

  const TABLE_NAME = 'Person';
  const FIELD_SITE_NAME = 'siteName';
  const FIELD_SITE_USER_ID = 'siteUserId';
  const FIELD_INSTALL_DATE = 'installDate';
  const FIELD_REMOVE_DATE = 'removeDate';
  const FIELD_CURRENT_TIME = 'currentTime';

  public function __construct($dbConnect) {
    $fieldNameList = Array(
      self::FIELD_OBJID,
      self::FIELD_SITE_NAME,
      self::FIELD_SITE_USER_ID,
      self::FIELD_INSTALL_DATE,
      self::FIELD_REMOVE_DATE,
    );
    parent::__construct($dbConnect, self::TABLE_NAME, $fieldNameList);
  } // __construct

  static function getUserIdCriteria($uid) {
    return(self::FIELD_SITE_NAME.'="'.OpfApplicationConfig::SITE_NAME.'" AND '
      .self::FIELD_SITE_USER_ID.'="'.$uid.'"');
  } // getUserIdCriteria

  private function getWherePhrase($uid) {
    return(' WHERE '.self::getUserIdCriteria($uid));
  } // getWherePhrase

  public function selectByUserId($uid) {
    return($this->fetchOne($this->getUserIdCriteria($uid)));
  } // selectByUserId

  public function selectByUserIdArray($uidArray) {
    if (is_null($uidArray)) return(null);
    if (count($uidArray)==0) return(null);
    $uidClause = $this->getInClause(self::FIELD_SITE_USER_ID,
       $this->joinArray($uidArray, ','));
    $wherePhrase = $uidClause.' AND '
      .self::FIELD_SITE_NAME.'="'.OpfApplicationConfig::SITE_NAME.'"';
    return($this->fetchSomeAsArray($wherePhrase));
  } // selectByUserIdArray

  public function generateInsertPersonSql($uid, $installNow=1) {
    $sql = Array();

    $sqlInsert = $this->newInsertStatement();
    $sqlInsert->addStringFieldValuePair(self::FIELD_SITE_NAME,
      OpfApplicationConfig::SITE_NAME);
    $sqlInsert->addStringFieldValuePair(self::FIELD_SITE_USER_ID, $uid);
    if ($installNow == 1) {
      $sqlInsert->addNonStringFieldValuePair(self::FIELD_INSTALL_DATE, 'NOW()');
    } // if
    $sqlInsert->addNonStringFieldValuePair(self::FIELD_REMOVE_DATE, 'NULL', 1);
    $sql[] = $sqlInsert->toString();
    return($sql);
  } // generateInsertPersonSql

  public function insertPerson($uid) {
    $sql = $this->generateInsertPersonSql($uid, 1);
    $this->executeTransaction($sql);
  } // insertPerson

  public function remove($uid) {
    $sql = 'UPDATE '.self::TABLE_NAME.' SET '
      .self::FIELD_REMOVE_DATE.'=NOW() '
      .$this->getWherePhrase($uid);
    $this->dbConnect->query($sql);
  } // remove

  public function getSelectObjidByUidSql($uid) {
      $str = 'SELECT '.self::FIELD_OBJID.' FROM '.self::TABLE_NAME
        .$this->getWherePhrase($uid);
      return($str);
  } // getSelectByUidSql

} // TablePerson
?>
