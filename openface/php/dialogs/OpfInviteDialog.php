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
/*OpfInviteDialog.php*/
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/opf/OpfApplicationConfig.php');
include_once($rDir.'openface/php/dialogs/OpfInviteDialogBase.php');
include_once($rDir.'openface/php/views/OpfUrlParam.php');
include_once($rDir.'openface/php/OpfLocalization.php');

class OpfInviteDialog extends OpfInviteDialogBase {

  const FORM_NAME_INVITE = 'inviteForm';

  protected $buttonLabel;
  protected $onlyFriendsWithoutApp;
  protected $excludeIdList;

  public function __construct($ctxt, $urlAfterInvite, $dialogName,
      $dialogTitle, $action, $inviteMode=1, $onlyFriendsWithoutApp=FALSE,
      $excludeIdList=null) {
    parent::__construct($ctxt, $urlAfterInvite, $dialogName, $dialogTitle,
      $action, $inviteMode);
    /* set default */
    $locale = $this->context->getCurrentLocale();
    $this->buttonLabel = sprintf($locale[OpfLocalization::MSG_JOIN_APP],
      OpfApplicationConfig::APPLICATION_TITLE);
    $this->onlyFriendsWithoutApp = $onlyFriendsWithoutApp;
    $this->excludeIdList = $excludeIdList;
  } // __construct

  public function setButtonLabel($label) {
    $this->buttonLabel = $label;
  } // setButtonLabel

  protected function getInstallAppStr() {
    return($this->buttonLabel);
  } // getInstallAppStr

  public function renderDialogInString() {
    $str = $this->dataSource->renderDialogInString(
      $this->urlAfterInvite,
      $this->getInstallAppStr(),
      $this->action.'?'.$this->context->forwardUrlParametersInString(0),
      $this->messageToFriend,
      $this->inviteModeStr,
      $this->renderAllHiddenParameters(),
      $this->explanationToUser,
      $this->dialogName, $this->dialogTitle, 
      self::FORM_NAME_INVITE, TRUE);
    return($str);
  } // renderDialogInString

  public function renderPopUpButtonInString() {
    $str = $this->dataSource->renderMockAjaxButtonInString(null, null, null,
      htmlEntities($this->dialogTitle), null, $this->dialogName);
    return($str);
  } // renderPopUpButtonInString

  public function renderFormContentInString($additionalActionParam) {
    $uParam = new OpfUrlParam();
    $uParam->appendAllCurrentParameters(0, 1);
    if (!is_null($additionalActionParam)) {
      $uParam->appendKeyValueArray($additionalActionParam);
    } // if
    $str = $this->dataSource->renderInviteForm(
      $this->urlAfterInvite,
      $this->getInstallAppStr(),
      $this->action.'?'.$uParam->toString(),
      $this->messageToFriend,
      $this->inviteModeStr,
      $this->renderAllHiddenParameters(),
      $this->explanationToUser,
      $this->onlyFriendsWithoutApp,
      $this->excludeIdList);
    return($str);
  } // renderFormContentInString

} // OpfInviteDialog
?>
