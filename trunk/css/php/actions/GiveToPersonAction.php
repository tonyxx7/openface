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
/*GiveToPersonAction.php*/
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/Localization.php');
include_once($rDir.'php/actions/Action.php');
include_once($rDir.'php/models/TablePossession.php');
include_once($rDir.'openface/php/actions/OpfAction.php');
include_once($rDir.'openface/php/OpfDebugUtil.php');

class GiveToPersonAction extends OpfAction {
  const ACTION_TITLE = 'GiveToPersonAction';

  private  $sentCount;

  public function __construct($dbConnect=null, $appCtxt=null,
      $dataSource=null) {
    parent::__construct($dbConnect, $appCtxt, $dataSource, self::ACTION_TITLE);
    $this->uidList = null;
    $this->sentCount = 1;
  } // __construct

  public function execute() {
    $this->parseParameters();

    $requiredParam = Array(
      Action::PARAM_PERSON_OBJID,
      Action::PARAM_CARD_OBJID,
      Action::PARAM_RECEIVING_PERSON_UID,
    );
    $msg = $this->checkRequiredParameters($requiredParam);
    if (!is_null($msg)) {
      return($msg);
    } // if

    $table = new TablePossession($this->getDbConnect());
    $table->insertPossession(
      $this->parameterBlock[Action::PARAM_RECEIVING_PERSON_UID],
      $this->parameterBlock[Action::PARAM_PERSON_OBJID],
      $this->parameterBlock[Action::PARAM_CARD_OBJID]);
    $this->sentCount = 1;

    $appLocale = $this->getApplicationContext()->getLocale();
    $msg = sprintf($appLocale[Localization::MSG_YOU_JUST_RECEIVED_A_CARD],
        OpfApplicationConfig::APP_INVOCATION_URL);
    $this->getDataSource()->notificationsSend(
      $this->parameterBlock[Action::PARAM_RECEIVING_PERSON_UID], $msg);

  } // execute

  protected function getSuccessMessage() {
    $locale = $this->getApplicationContext()->getLocale();
    $msg = sprintf($locale[Localization::MSG_CARD_SENT], $this->sentCount);
    return($msg);
  } // getSuccessMessage

} // GiveToPersonAction
?>
