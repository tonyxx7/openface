<?
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
/* models/OpfDbConnect.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/opf/OpfApplicationConfig.php');
include_once($rDir.'openface/php/OpfConfig.php');
include_once($rDir.'openface/php/OpfDebugUtil.php');

class OpfDbConnect {
  public  $debug;
  private $connection; // mysql link descriptor

  public function __construct($debug=0) {
    $this->debug = OpfConfig::DEBUG_LEVEL;

    /* Set up local db connection */
    $this->connection = mysql_connect(
      OpfApplicationConfig::MY_DATABASE_PATH, 
      OpfApplicationConfig::MY_DATABASE_USER, 
      OpfApplicationConfig::MY_DATABASE_PASSWORD)
      or die('Could not connect: '.mysql_error());
    mysql_select_db(OpfApplicationConfig::MY_DATABASE_NAME, $this->connection) 
      or die('Could not select db: '.mysql_error());
  } // __construct

  public function close() {
    if (is_null($this->connection)) {
      return;
    } // if
    mysql_close($this->connection);
    $this->connection = null;
  } // close

  public function query($sqlStr) {
    if ($this->debug> 0) {
      OpfDebugUtil::logOutput("query:\n".htmlEntities($sqlStr)."\n");
    } // if
    $result = mysql_query($sqlStr, $this->connection)
      or die('query failed: '.mysql_error()."\n".$sqlStr."\n");
    return($result);
  } // query

  public function getCurrentTime() {
    $result = $this->query('SELECT NOW()');
    $row = mysql_fetch_row($result);
    $currentTime = $row[0];
    mysql_free_result($result);
    return($currentTime);
  } // getCurrentTime

  /*
   *  convertDatabaseResultToList converts $result into an array
   *    and frees the result memory.
   */
  public function convertDatabaseResultToList($result, $convertSingularArray=FALSE) {
    /* bind results into an array */
    $list = array();
    if (!is_null($result)) {
      while ($row = mysql_fetch_assoc($result)) {
        $data = $row;
        if ($convertSingularArray===TRUE) {
          if (is_array($row) && count($row) == 1) {
            /* an array with only one item */
            $v = array_values($row);
            $data = $v[0];
          } // if
        } // if

        if (isset($row['objid'])) {
          /* use objid as array index if it has one */
          $list[$row['objid']] = $data;
        } else {
          /* otherwise just add to array */
          $list[] = $data;
        }
      } // while
    } // if
    mysql_free_result($result);
    return($list);
  } // convertDatabaseResultToList

  public function convertResultToOneRow($result) {
    if (!is_null($result)) {
      while ($row = mysql_fetch_assoc($result)) {
        if ($this->debug> 0) {
          OpfDebugUtil::echoPrintObjectVariables('convertResultToOneRow row=', $row);
        } // if
        /* returns the first row found */
        return($row);
      } // while
    } // if
    /* returns nothing */
    return(null);
  } // convertResultToOneRow

   /* Transactions functions */
   function begin(){
     $null = mysql_query("START TRANSACTION", $this->connection);
     return mysql_query("BEGIN", $this->connection);
   } // begin

   function commit(){
       if ($this->debug> 0) {
         OpfDebugUtil::logOutput("COMMIT\n");
       } // if
      return mysql_query("COMMIT", $this->connection);
   } // commit
  
   function rollback(){
       if ($this->debug> 0) {
         OpfDebugUtil::logOutput("ROLLBACK\n");
       } // if
      return mysql_query("ROLLBACK", $this->connection);
   } // rollback

   function isZeroAffected($sql) {
     $startingFrag = Array (
       'SET ',
       'INSERT IGNORE ',
       'DELETE IGNORE ',
     );
     $containsFrag = Array (
       'ON DUPLICATE KEY UPDATE'
     );
     $trimSql = strtoupper(trim($sql));
     foreach($startingFrag as $frag) {
       if (strpos($trimSql, $frag) == 0) {
         return(1);
       } // if
       if ($this->debug> 0) {
         OpfDebugUtil::logOutput('isZeroAffected('.strlen($frag).') "'
           .$frag.'"!="'.substr($trimSql,0,strlen($frag)).'"');
       } // if
     } // foreach
     foreach($containsFrag as $frag) {
       if (strpos($trimSql, $frag) === FALSE) {
         continue;
       } // if
       return(1);
     } // foreach
     return(0);
   } // isZeroAffected

   function transaction($sqlArray){
     $retval = 1;
     $this->begin();
     foreach($sqlArray as $sql){
       if ($this->debug> 0) {
         OpfDebugUtil::logOutput("tx:\t".htmlEntities($sql)."\n");
       } // if
       $result = mysql_query($sql, $this->connection);
       if(mysql_affected_rows() == 0){
         if ($this->isZeroAffected($sql) == 0) {
           if ($this->debug> 0) {
             OpfDebugUtil::logOutput("affected_rows=0:\t".htmlEntities($sql)."\n");
           } // if
           $retval = 0; 
         } // if
       } // if
     } // foreach
     if ($retval == 0) {
       $this->rollback();
       return(false);
     } else {
       $this->commit();
       return(true);
     } // else
  } // transaction

} // OpfDbConnect
?>

