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
/* PortletInBox.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/models/ViewPossession.php');
include_once($rDir.'php/portlets/PortletCardList.php');

class PortletInBox extends PortletCardList {

  const MAX_PER_ROW = 1;

  public $userObjid;

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
    $this->maxPerRow = self::MAX_PER_ROW;
  } // __construct

  /* methods */
  public function loadData() {
    if(!isset($this->userObjid)) {
      return;
    } // if
    $table = new ViewPossession($this->getDbConnect());
    $this->dataList = $table->fetchByPersonObjid($this->userObjid);
  } // loadData

  protected function renderPossessionDetails($data) {
    $appLocale = $this->parentUIObject->getAppLocale();
    $str = '<td>'.$appLocale[Localization::RECEIVED_ON]
      .'&nbsp;'.$data[ViewPossession::FIELD_POSSESSION_START]
      .'</td>';
    return($str);
  } // renderPossessionDetailInfo

} // PortletInBox
?>
