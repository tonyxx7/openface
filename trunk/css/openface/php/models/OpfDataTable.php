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
/* models/OpfDataTable.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/models/OpfSqlInsert.php');
include_once($rDir.'openface/php/models/OpfSqlSelect.php');
include_once($rDir.'openface/php/models/OpfSqlUpdate.php');

class OpfDataTable {
  /* constants */
  const FIELD_OBJID = 'objid';
  const EMPTY_JSON_STRING = '[ ]';
  const ERROR_MISSING_CHILD_METHOD = 'Error: missing child method';
  const RANDOM_SORT_ORDER = 'RAND()';

  /* variables */
  private $saveDebug;

  protected $dbConnect;
  protected $fieldNameList;
  protected $tableName;
  protected $defaultSortOrder;
  protected $limitCount;

  /* methods */
  public function __construct($dbConnect, $tableName, $fieldNameList) {
    $this->dbConnect = $dbConnect;
    $this->tableName = $tableName;
    $this->fieldNameList = $fieldNameList;
    $this->defaultSortOrder = self::FIELD_OBJID;
  } // __construct

  /* setters */
  public function setDefaultSortOrder($sortOrder) {
    $this->defaultSortOrder = $sortOrder;
  } // setDefaultSortOrder

  public function setDefaultSortOrderToRandom() {
    $this->setDefaultSortOrder(self::RANDOM_SORT_ORDER);
  } // setDefaultSortOrder

  public function setLimitCount($count) {
    $this->limitCount = $count;
  } // setLimitCount

  /* methods */
  protected function enableDebug() {
    $this->saveDebug = $this->dbConnect->debug;
    $this->dbConnect->debug = 1;
  } // enableDebug

  protected function disableDebug() {
    $this->saveDebug = $this->dbConnect->debug;
    $this->dbConnect->debug = 1;
  } // disnableDebug

  protected function restoreDebug() {
    $this->dbConnect->debug = $this->saveDebug;
  } // restoreeDebug

  protected function wrapNull($str) {
    return(is_null($str)?'NULL':$str);
  } // wrapNull

  protected function wrapNullAndQuote($str) {
    return(is_null($str)?'NULL':'"'.addslashes($str).'"');
  } // wrapNullAndQuote

  protected function laterThanNow($dateTime) {
    return('(('.$dateTime.' IS NOT NULL) AND ('.$dateTime.' >= NOW()))');
  } // laterThanNow

  protected function notLaterThanNow($dateTime) {
    return('(('.$dateTime.' IS NULL) OR ('.$dateTime.' < NOW()))');
  } // laterThanNow

  protected function getFieldListAsString() {
    return(implode(',', $this->fieldNameList));
  } // getFieldListAsString

  protected function getSelectPhrase() {
    return('SELECT '.$this->getFieldListAsString());
  } // getSelectPhrase

  protected function executeSql($sql) {
    if (is_null($sql)) return(null);
    if (!is_array($sql)) {
      return($this->dbConnect->query($sql));
    } else {
      return($this->executeTransaction($sqlArray));
    }
  } // executeSql

  protected function executeSqlAndReturnNewId($sql) {
    if (!is_array($sql)) {
      $sqlArray = Array();
      $sqlArray[] = $sql;
      $sqlArray[] = 'SELECT LAST_INSERT_ID();';
      return($this->executeTransaction($sqlArray));
    } else {
      $sql[] = 'SELECT LAST_INSERT_ID();';
      return($this->executeTransaction($sql));
    } // else
  } // executeSqlAndReturnNewId

  protected function executeTransaction($sqlArray) {
    return($this->dbConnect->transaction($sqlArray));
  } // executeTransaction

 private function _selectFromWhere($filter, $sortOrder, $limitCount) {
    if (!isset($this->fieldNameList) || !isset($this->tableName)) {
      return(self::ERROR_MISSING_CHILD_METHOD);
    } // if
    $sql = $this->getSelectPhrase()
      .' FROM '.$this->tableName;
    if (!is_null($filter)) {
      $sql .= ' WHERE '.$filter;
    } // if
    if (!is_null($sortOrder)) {
      $sql .= ' ORDER BY '.$sortOrder;
    } // if
    if (isset($limitCount)) {
      $sql .= ' LIMIT '.$limitCount;
    } // if
    return($sql);
  } // _selectFromWhere

  public function fetchSome($filter=null) {
     $sql = $this->_selectFromWhere($filter, 
       (isset($this->defaultSortOrder)?$this->defaultSortOrder:null),
       (isset($this->limitCount)?$this->limitCount:null));
    return($this->executeSql($sql));
  } // fetchSome

  public function fetchSomeAsArray($filter) {
    $result = $this->fetchSome($filter);
    $list = $this->dbConnect->convertDatabaseResultToList($result);
    return($list);
  } // fetchSomeAsArray

  public function fetchAll($excludeObjidArray=null) {
    $filter = null;
    if (!is_null($excludeObjidArray)) {
      $filter = self::FIELD_OBJID.' NOT IN('.implode(',',$excludeObjidArray).')';
    } // if
    return($this->fetchSome($filter));
  } // fetchAll

  public function fetchAllAsArray($excludeObjidArray=null) {
    $result = $this->fetchAll($excludeObjidArray);
    $list = $this->dbConnect->convertDatabaseResultToList($result);
    return($list);
  } // fetchAllAsArray

  public function fetchOne($filter) {
    $result = $this->fetchSome($filter);
    return($this->dbConnect->convertResultToOneRow($result));
  } // fetchOne

  public function fetchOneByObjid($objid) {
    if (is_null($objid)) return(null);
    $filter = self::FIELD_OBJID.'='.$objid;
    return($this->fetchOne($filter));
  } // fetchOneByObjid

  public function fetchRandomOne($filter=null) {
    $sql = $this->_selectFromWhere($filter, self::RANDOM_SORT_ORDER, '1');
    $result = $this->executeSql($sql);
    return($this->dbConnect->convertResultToOneRow($result));
  } // fetchRandomOne

  public function selectOneObjidByFilter($filter) {
    $data = $this->fetchOne($filter);
    if (is_null($data)) return(null);
    return($data[self::FIELD_OBJID]);
  } // selectObjidByUrl

  public function generateSqlSetLastInsertId($var) {
    return('SET '.$var.'=LAST_INSERT_ID();');
  } // generateSqlSetLastInsertId

  public function generateStartTransactionSql() {
    return('START TRANSACTION;');
  } // generateStartTransactionSql

  public function generateCommitSql() {
    return('COMMIT;');
  } // generateCommitSql

  protected function jsonify($result) {
    if (!isset($this->fieldNameList) || !isset($this->tableName)) {
      return(self::ERROR_MISSING_CHILD_METHOD);
    } // if
    $str = '';
    while ($row = mysql_fetch_assoc($result)) {
      $str .= '[ "';
      $count = 0;
      foreach ($this->fieldNameList as $field) {
        if ($count > 0) $str .= ', ';
        $str .= '"'.$row[$field].'"';
        $count++;
      } // foreach
      $str .'" ],';
    } // while
    return($str);
  } // jsonify

  private function _jsonifyResult($result) {
    $str = '[ ';
    $count =0;
    while ($row = mysql_fetch_row($result)) {
      if ($count > 0) {
        $str .= ', ';
      } // if
      $str .= '[ "' ;
        for ($i=0; $i<count($row); $i++) {
          if ($i > 0) {
            $str .= '", "';
          } // if
          $str .= addslashes($row[$i]);
        } // for
      $str .= '" ]' ;
      $count++;
    } // while
    $str .= ' ]';
    return($str);
  } // _jsonifyResult

  public function jsonifyAll() {
    $result = $this->fetchAll();
    if (is_null($result)) {
      return(self::EMPTY_JSON_STRING);
    } // if
    $str = $this->_jsonifyResult($result);
    mysql_free_result($result);
    return($str);
  } // jsonifyAll

  public function joinArray($arr, $sepChar) {
    if (is_null($arr)) return('');
    if (count($arr)==0) return('');
    $str = null;
    $index =0;
    foreach ($arr as $objid) {
      if ($index > 0) {
        $str .= $sepChar;
      } // if
      $str .= $objid;
      $index++;
    } // foreach
    return($str);
  } // getWhereInObjidArray

  protected function getInClause($fieldName, $valueStr) {
    return($fieldName.' IN (' .$valueStr.')');
  } // getInClause

  protected function newInsertStatement() {
    return(new OpfSqlInsert($this->tableName));
  } // newInsertStatement

  protected function newSelectStatement() {
    return(new OpfSqlSelect($this->tableName));
  } // newSelectStatement

  public function newUpdateStatement() {
    return(new OpfSqlUpdate($this->tableName));
  } // newUpdateStatement

  public function executeSqlSelect($sqlSelect) {
    $result = $this->executeSql($sqlSelect->toString());
    $list = $this->dbConnect->convertDatabaseResultToList($result, TRUE);
    return($list);
  } // executeSqlSelect

  protected function customizeUpdateByObjid($objid, $stmt) {
    /* to be defined in child class */
    return;
  } // customizeUpdateByObjid

  public function updateByObjid($objid, $paramArray) {
    if (count($paramArray) == 0) return;
    $stmt = $this->newUpdateStatement();
    $stmt->setWhereClause(self::FIELD_OBJID.'='.$objid);
    foreach($paramArray as $field => $value) {
      if (array_search($field, $this->fieldNameList)===FALSE) continue;
      $stmt->addStringFieldValuePair($field, $value);
    } // foreach
    $this->customizeUpdateByObjid($objid, $stmt);
    $sql = $stmt->toString();
    return($this->executeSql($sql));
  } // updateByObjid

  public function getSqlDeleteByFilter($filter) {
    $sql = 'DELETE FROM '.$this->tableName.' WHERE '.$filter;
    return($sql);
  } // getSqlDeleteByFilter

  public function getSqlDeleteByObjid($objid) {
    return($this->getSqlDeleteByFilter(self::FIELD_OBJID.'='.$objid));
  } // getSqlDeleteByObjid

  public function deleteByObjid($objid) {
    $sql = $this->getSqlDeleteByObjid($objid);
    return($this->executeSql($sql));
  } // deleteByObjid

  public function deleteByFilter($filter) {
    $sql = $this->getSqlDeleteByFilter($filter);
    return($this->executeSql($sql));
  } // deleteByObjid

  protected function customizeInsert($stmt) {
    /* to be defined in child class */
    return;
  } // customizeInsert

  public function insert($paramArray, $onDuplicateUpdate=0) {
    if (count($paramArray) == 0) return;
    $stmt = $this->newInsertStatement();
    foreach($paramArray as $field => $value) {
      if (array_search($field, $this->fieldNameList)===FALSE) continue;
      $stmt->addStringFieldValuePair($field, $value, $onDuplicateUpdate);
    } // foreach
    $this->customizeInsert($stmt);
    $sql = $stmt->toString();
    $this->executeSql($sql);
  } // insert

  /**
   * reIndex($dataList, $dataField)
   * If $dataField is not unique, the returned list will contain the 'last' record
   *   with the given $dataField.
   */
  public function reIndex($dataList, $dataField) {
    if (is_null($dataList)) return(null);
    $newList = Array();
    foreach ($dataList as $data) {
      $newList[$data[$dataField]] = $data;
    } // foreach
    return($newList);
  } // reIndex

} // OpfDataTable
?>
