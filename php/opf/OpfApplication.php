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
/* OpfApplication.php */
error_reporting(E_ALL | E_STRICT);  
ini_set('display_startup_errors', 1);  
ini_set('display_errors', 1); 

$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/models/TablePerson.php');
include_once($rDir.'php/views/FrameMultiCanvas.php');

class OpfApplication {

  static public function getMainFrame() {
    return(new FrameMultiCanvas());
  } // getMainFrame

  static function registerUser($uid, $dbConnect, $dataSource) {
    $table = new TablePerson($dbConnect);
    $table->insertPerson($uid);
  } // registerUser

  static function unregisterUser($uid, $dbConnect) {
    $table = new TablePerson($dbConnect);
    $table->remove($uid);
  } // unregisterUser

} // OpfApplication
?>
