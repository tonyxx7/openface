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
include_once($rDir.'openface/php/models/OpfSqlStatement.php');

class OpfSqlSelect extends OpfSqlStatement {

  protected $selectFieldArray;
  protected $where;
  protected $orderBy;

  public function __construct($tableName) {
    parent::__construct($tableName);
    $this->selectFieldArray = Array();
    $this->where = null;
    $this->orderBy = null;
  } // __construct

  public function addSelectField($field) {
    if (is_array($field)) {
      foreach($field as $f) {
        $this->selectFieldArray[] = $f;
      } // foreach
    } else {
      $this->selectFieldArray[] = $field;
    }
  } // addSelectField

  public function setWhere($where) {
    $this->where = $where;
  } // setWhere

  public function setOrderBy($orderBy) {
    $this->orderBy = $orderBy;
  } // setOrderBy

  public function toString() {
    if (count($this->selectFieldArray)==0) {
      return(null);
    } // if

    $str = ' SELECT ' .implode(',', $this->selectFieldArray)
      .' FROM '.$this->tableName;

    if (!is_null($this->where)) {
      $str .= ' WHERE '.$this->where;
    } // if

    if (!is_null($this->orderBy)) {
      $str .= ' ORDER BY '.$this->orderBy;
    } // if

    return($str);
  } // toString

} // OpfSqlSelect
?>
