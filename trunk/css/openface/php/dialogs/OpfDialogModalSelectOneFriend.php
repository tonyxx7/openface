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
/* OpfDialogModalSelectOneFriend.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/views/OpfFormParam.php');
include_once($rDir.'openface/php/dialogs/OpfDialogModal.php');
include_once($rDir.'php/opf/OpfApplicationConfig.php');

class OpfDialogModalSelectOneFriend extends OpfDialogModal {

  /* variables */
  protected $formName;
  protected $requestIdList;
  protected $formMethod;
  protected $formAction;
  protected $additionalParamArray;
  protected $dataSource;
  protected $formElementName;
  protected $formElementId;

  /* constructors */
  public function __construct($parentUIObject, $dialogName, $dialogTitle,
    $buttonLabel, $formName, $formMethod, $formAction,
    $formElementName, $formElementId,
    $includeMe, $excludeIdListStr, $includeLists,
    $additionalParamArray, $buttonSize, $dataSource) {

    parent::__construct($parentUIObject, $dialogName, $dialogTitle,
      $buttonLabel, $formName, $formAction, $buttonSize);

    $this->formName = $formName;
    $this->formMethod = $formMethod;
    $this->additionalParamArray = $additionalParamArray;
    $this->dataSource = $dataSource;
    $this->formElementName = $formElementName;
    $this->formElementId = $formElementId;
    $this->includeMe= $includeMe;
    $this->excludeIdListStr= $excludeIdListStr;
    $this->includeLists= $includeLists;
  } // __construct

  /* methods */
  protected function getFormHeader() {
    $actionPrefix = null;
    if ($this->asyncDialogButtton == 1) {
      $actionPrefix = OpfApplicationConfig::APP_CALLBACK_URL.'/';
    }
    $header = Array(
      'method' => $this->formMethod,
      'action' => $actionPrefix.$this->formAction
    );
    return($header);
  } // getFormHeader

  protected function getFormParameters() {
    return($this->additionalParamArray);
  } // getFormParameters

  protected function getFormContents() {
    $str = $this->dataSource->renderFriendSelector(null,
      $this->formElementName, $this->formElementId, $this->includeMe,
      $this->excludeIdListStr, $this->includeLists);
    return($str);
  } // getFormContents

} // OpfDialogModalSelectOneFriend
?>
