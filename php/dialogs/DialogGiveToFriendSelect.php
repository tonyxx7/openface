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
/* DialogGiveToFriendSelect.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/dialogs/OpfDialogModalSelectOneFriend.php');
include_once($rDir.'php/Localization.php');

class DialogGiveToFriendSelect extends OpfDialogModalSelectOneFriend {

  /* variables */
  private $fullUidList;
  private $paramKeyword;

  /* constructors */
  public function __construct($parentUIObject, $appLocale, $dialogName,
      $buttonLabel, $formName, $formAction, $formElementName, $formElementId,
      $additionalParamArray) {

    $ds = $parentUIObject->getDataSource();
    $dialogTitle = $appLocale[Localization::GIVE_TO_FRIEND];
    $includeMe = FALSE;
    $excludeIdListStr = null;
    $includeLists = FALSE;

    parent::__construct($parentUIObject, $dialogName, $dialogTitle,
      $buttonLabel, $formName, self::METHOD_POST, $formAction, 
      $formElementName, $formElementId,
      $includeMe, $excludeIdListStr, $includeLists,
      $additionalParamArray, parent::BUTTON_SIZE_SMALL, $ds);
    $this->asyncDialogButtton = 0;
  } // __construct

} // DialogGiveToFriendSelect
?>
