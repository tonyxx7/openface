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
/* OpfPortletInvite.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/portlets/OpfPortlet.php');
include_once($rDir.'openface/php/dialogs/OpfInviteDialog.php');

class OpfPortletInvite extends OpfPortlet {

  private $inviteDialog;
  private $additionalActionParam; /* a key-value pair array */

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
  } //

  public function setDetails($dialogName, $dialogTitle,
      $fbml2friend, $explanationToUser, $additionalActionParam=null,
      $inviteMode=FALSE, $onlyFriendsWithoutApp=TRUE,
      $excludeIdList=null) {
    $ctxt = $this->getContext();
    $this->inviteDialog = new OpfInviteDialog($ctxt,
      $ctxt->getCurrentUrl(), $dialogName, $dialogTitle, null, $inviteMode,
      $onlyFriendsWithoutApp, $excludeIdList);
    /* customize the dialog */
    $this->inviteDialog->setMessageToFriend($fbml2friend);
    $this->inviteDialog->setExplanationToUser($explanationToUser);
    $this->additionalActionParam = $additionalActionParam;
  } // setDetails

  public function setButtonLabel($label) {
    $this->inviteDialog->setButtonLabel($label);
  } // setButtonLabel

  public function renderDialog() {
    return($this->inviteDialog->renderDialogInString());
  } // renderDialog

  public function renderButton() {
    return($this->inviteDialog->renderPopUpButtonInString());
  } // renderButton

  public function renderForm() {
    return($this->inviteDialog->renderFormContentInString($this->additionalActionParam));
  } // renderForm

} // OpfPortletInvite
?>
