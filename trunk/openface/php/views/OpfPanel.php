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
/* views/OpfPanel.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/views/OpfCanvas.php');

class OpfPanel extends OpfCanvas {

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
  } // __construct

  public function getTag() {
    /* to be defined in child class */
  } // getTag

  public function getWebParametersToMe() {
    return($this->getWebParametersToPanel($this->getTag()));
  } // getWebParametersToMe

  public function getWebParametersToPanel($panelTag) {
    $parentCanvas = $this->getParentCanvas();
    $param = $parentCanvas->getWebParametersToMe();
    $param[OpfConfig::PARAM_CMDLEVEL2] = $panelTag;
    return($param);
  } // getWebParametersToPanel

} // OpfPanel
?>
