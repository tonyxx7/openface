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
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/models/OpfDataTable.php');
include_once($rDir.'openface/php/models/OpfSqlStatement.php');

class OpfSqlInsert extends OpfSqlStatement {

  protected $valuePairArray;
  protected $onDuplicateUpdateArray;
  public $ignore;
  public $setLastInsertId;

  public function __construct($tableName) {
    parent::__construct($tableName);
    $this->valuePairArray = Array();
    $this->onDuplicateUpdateArray = Array();
    $this->ignore = 0;
    $this->setLastInsertId = 1;
  } // __construct

  protected function formatOnDuplicate($field, $value) {
    return($field.'=VALUES('.$field.')');
  } // formatOnDuplicate

  private function _addToArray($field, $value, $onDuplicateUpdate) {
    $this->valuePairArray[$field] = $value;
    if ($onDuplicateUpdate == 1) {
      $this->onDuplicateUpdateArray[] = $this->formatOnDuplicate($field, $value);
    } // if
  } // _addToArray

  public function addStringFieldValuePair($field, $value, $onDuplicateUpdate=0) {
    $this->_addToArray($field, 
      (is_null($value)?'NULL':'"'.addslashes($value).'"'),
      $onDuplicateUpdate);
  } // addStringFieldValuePair

  public function addNonStringFieldValuePair($field, $value, $onDuplicateUpdate=0) {
    $this->_addToArray($field,
      (is_null($value)?'NULL':$value),
      $onDuplicateUpdate);
  } // addNonStringFieldValuePair

  public function addNowFieldValuePair($field, $onDuplicateUpdate=0) {
    $this->addNonStringFieldValuePair($field, 'NOW()', $onDuplicateUpdate);
  } // addNowFieldValuePair

  public function addNullFieldValuePair($field, $onDuplicateUpdate=0) {
    $this->addNonStringFieldValuePair($field, 'NULL', $onDuplicateUpdate);
  } // addNullFieldValuePair

  public function addDateTimeFieldValuePair($field, $value, $onDuplicateUpdate=0) {
    $timestamp = strtotime($value);
    if ($timestamp === FALSE) {
      return;
    } // if
    $this->addStringFieldValuePair($field, date(DATE_ATOM, $timestamp),
      $onDuplicateUpdate);
  } // addDateTimeFieldValuePair

  public function toString($doIgnore=null) {
    if (count($this->valuePairArray)==0) {
      return(null);
    } // if
    $ignore = (is_null($doIgnore)?$this->ignore:$doIgnore);

    $str = ' INSERT ';
    if ($ignore==1) {
      $str .= ' IGNORE ';
    } // if
    $str .= 'INTO '.$this->tableName
      .'('.implode(',', array_keys($this->valuePairArray)).')'
      .' VALUES ('.implode(',', array_values($this->valuePairArray)).')';
    $duaCount = count($this->onDuplicateUpdateArray);
    if (($duaCount > 0) || ($this->setLastInsertId == 1)) {
      $str .= ' ON DUPLICATE KEY UPDATE ';
      if ($this->setLastInsertId == 1) {
         $str .= ' '.OpfDataTable::FIELD_OBJID.'=LAST_INSERT_ID('
           .OpfDataTable::FIELD_OBJID.')';
         if ($duaCount > 0) {
           $str .= ',';
         } // if
      } // if
      $str .= implode(',', $this->onDuplicateUpdateArray);
    } // if
    $str .= ';';
    return($str);
  } // toString

} // OpfSqlInsert
?>
