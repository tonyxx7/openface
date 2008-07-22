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
include_once($rDir.'openface/php/models/OpfSqlInsert.php');

class OpfSqlUpdate extends OpfSqlInsert {

  protected $whereClause;

  public function __construct($tableName) {
    parent::__construct($tableName);
  } // __construct

  protected function formatOnDuplicate($field, $value) {
    return($field.'='.$value);
  } // formatOnDuplicate

  public function addStringFieldValuePair($field, $value, $ignore=null) {
    parent::addStringFieldValuePair($field, $value, 1);
  } // addStringFieldValuePair

  public function addNonStringFieldValuePair($field, $value, $ignore=null) {
    parent::addNonStringFieldValuePair($field, $value, 1);
  } // addNonStringFieldValuePair

  public function addNowFieldValuePair($field, $ignore=null) {
    $this->addNonStringFieldValuePair($field, 'NOW()', 1);
  } // addNowFieldValuePair

  public function setWhereClause($whereClause) {
    $this->whereClause = $whereClause;
  } // setWhereClause

  public function setWhereObjidClause($objid) {
    $this->whereClause = OpfDataTable::FIELD_OBJID.'='.$objid;
  } // setWhereClause

  public function toString($ignore=0) {
    if (count($this->onDuplicateUpdateArray)==0) {
      return(null);
    } // if

    if (empty($this->whereClause)) {
      return(null);
    } // if

    $str = ' UPDATE '.$this->tableName.' SET '
      .implode(',', $this->onDuplicateUpdateArray)
      .' WHERE '.$this->whereClause.';';
    return($str);
  } // toString

} // OpfSqlUpdate
?>
