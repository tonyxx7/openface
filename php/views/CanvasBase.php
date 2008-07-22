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
/* CanvasBase.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/views/OpfCanvas.php');

class CanvasBase extends OpfCanvas {

  const PARAM_DISPLAY_OBJID = 'displayObjid';

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
  } // __construct

  /* getters */
  public function getAppLocale() {
    return($this->getContext()->getApplicationContext()->getLocale());
  } // getAppLocale

  /* methods */

} // CanvasBase
?>
