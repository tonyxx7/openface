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
/* ExecuteAction.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/views/OpfExecuteAction.php');
include_once($rDir.'php/actions/GiveToPersonAction.php');

class ExecuteAction extends OpfExecuteAction {

  protected $appCtxt;
  protected $dataSource;
  protected $dbConnect;

  public function __construct($parentUIObject, $appCtxt, $dataSource, $dbConnect) {
    parent::__construct($parentUIObject);
    $this->appCtxt = $appCtxt;
    $this->dataSource = $dataSource;
    $this->dbConnect = $dbConnect;
  } // __construct

  /* methods */
  public function execute() {
    if (is_null($this->actionClass)) return;
    $action = new $this->actionClass($this->dbConnect, $this->appCtxt, 
      $this->dataSource);
    $action->execute();
    return($action->render());
  } // execute

} // ExecuteAction
?>
