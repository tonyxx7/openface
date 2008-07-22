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
/* PortletCardStore.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/helpers/OpfHelperHtmlSite.php');
include_once($rDir.'php/dialogs/DialogGiveToFriendSelect.php');
include_once($rDir.'php/actions/Action.php');
include_once($rDir.'php/portlets/PortletCardList.php');

class PortletCardStore extends PortletCardList {

  const DIALOG_PREFIX_GIVE = 'giveToFriendDialog';
  const FORM_PREFIX_GIVE = 'formGiveToFriend';

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
  } // __construct

  /* methods */
  protected function renderCardButtons($personObjid, $cardObjid, $data) {
    /* override parent */
    return($this->renderGiveLink($personObjid, $cardObjid, $data));
  } // renderCardButtons

  protected function renderGiveLink($giverPersonObjid, $cardObjid, $data) {
    $ds = $this->getDataSource();
    $appLocale = $this->parentUIObject->getAppLocale();

    $fParam = new OpfFormParam();
    $fParam->appendAllCurrentParameters(0, 1);
    $parentCanvas = $this->getParentCanvas();
    $fParam->appendKeyValueArray($parentCanvas->getWebParametersToMe());

    $fParam->appendKeyValuePair(Action::PARAM_PERSON_OBJID, 
      $giverPersonObjid);
    $fParam->appendKeyValuePair(Action::PARAM_CARD_OBJID,
      $cardObjid);
    $fParam->appendKeyValuePair(Action::PARAM_PERSON_NAME,
      implode(' ',$ds->getUserFirstLastName()));
    $fParam->appendActionClass(Action::GIVE_TO_PERSON_ACTION_CLASS);

    $buttonLabel = $appLocale[Localization::SEND_TO_FRIEND];
    $friendUidList = $ds->getFriendList();
    $dialog = new DialogGiveToFriendSelect($this, $appLocale,
      self::DIALOG_PREFIX_GIVE.$cardObjid,
      $buttonLabel, 
      self::FORM_PREFIX_GIVE.$cardObjid,
      $ds->getCurrentPageName(),
      Action::PARAM_RECEIVING_PERSON_UID,
      Action::PARAM_RECEIVING_PERSON_UID,
      $fParam->getKeyValueArray());

    return($dialog->render());
  } // renderGiveLink

} // PortletCardStore
?>
