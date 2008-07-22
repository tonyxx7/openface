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
/* views/OpfCanvas.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/views/OpfFormParam.php');
include_once($rDir.'openface/php/views/OpfUIObject.php');

class OpfCanvas extends OpfUIObject {

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
  } // __construct

  /* getters */
  public function getIcon() {
  /* to be defined in child */
    return(null);
  } // getIcon

  public function getTag() {
  /* to be defined in child */
    return(null);
  } // getTag

  public function getLabel() {
  /* to be defined in child */
    return('');
  } // getLabel

  /* methods */
  /* to be defined in child */
  public function render() {
    return('');
  } // render

  public function getWebParametersToMe() {
    return($this->getWebParametersToCanvas($this->getTag()));
  } // getWebParametersToMe

  public function getWebParametersToCanvas($canvasTag) {
    $param = Array (
      OpfConfig::PARAM_CMDLEVEL1 => $canvasTag
    );
    return($param);
  } // getWebParametersToCanvas

} // OpfCanvas
?>
