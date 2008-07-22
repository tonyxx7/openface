<?php
/* Copyright (C) 2008 App Tsunami, Inc. */
/* OpfCoreWebObject.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);

class OpfCoreWebObject {

  const PARAM_INVITED_UIDS = 'ids';

  protected function getRequestParameter($key) {
    if (isset($_GET[$key])) return($_GET[$key]);
    if (isset($_POST[$key])) return($_POST[$key]);
  } // getRequestParameter

  protected function getInvitedUidList() {
    return($this->getRequestParameter(self::PARAM_INVITED_UIDS));
  } // getInvitedUidList

  protected function getEventClassFromParameter() {
    return($this->getRequestParameter(OpfConfig::PARAM_EVENT_CLASS));
  } // getEventClassFromParameter

  protected function getActionClassFromParameter() {
    return($this->getRequestParameter(OpfConfig::PARAM_ACTION_CLASS));
  } // getActionClassFromParameter
} // OpfCoreWebObject
?>
