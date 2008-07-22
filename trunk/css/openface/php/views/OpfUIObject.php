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
/* views/OpfUIObject.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfConfig.php');
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/views/OpfUrlParam.php');
include_once($rDir.'openface/php/models/OpfSiteWrapper.php');
include_once($rDir.'openface/php/models/OpfSqlStatement.php');
include_once($rDir.'openface/php/core/OpfCoreWebObject.php');

class OpfUIObject extends OpfCoreWebObject {

  const CANVAS_WIDTH = OpfSiteWrapper::CANVAS_WIDTH;

  protected $parentUIObject;
  protected $context;
  protected $pleaseWaitDiv;
  protected $responseDivId;

  public function __construct($parentUIObject=null) {
    if (OpfConfig::DEBUG_LEVEL > 0) {
      OpfDebugUtil::logOutput(get_class($this));
    } // if
    $this->parentUIObject = $parentUIObject;
    $this->responseDivId = OpfConfig::DIV_SHOW_PROGRESS;
  } // __construct

  /* setters */
  public function setContext($ctxt) {
    $this->context = $ctxt;
  } // setContext

  /* getters */
  public function getContext() {
    if (isset($this->context)) {
      return($this->context);
    } // if
    if (!is_null($this->parentUIObject->getContext())) {
      return($this->parentUIObject->getContext());
    } // if
    return(null);
  } // getContext

  public function getCurrentLocale() {
    $ctxt = $this->getContext();
    if (is_null($ctxt)) {
      return(null);
    } else {
      return($ctxt->getCurrentLocale());
    } // else
  } // getCurrentLocale

  public function getResponseDivId() {
    return($this->responseDivId);
  } // getResponseDivId

  public function lookUpLocale($key) {
    $locale = $this->getCurrentLocale();
    if (is_null($locale)) {
      return('STRING_'.$key);
    } // if
    return($locale[$key]);
  } // lookUpLocale

  /* convenience methods */
  protected function getDbConnect() {
    if (is_null($this->getContext())) return(null);
    return($this->getContext()->getDbConnect());
  } // getDbConnect

  protected function getDataSource() {
    if (is_null($this->getContext())) return(null);
    return($this->getContext()->getDataSource());
  } // getDataSource

  protected function getApplicationContext() {
    if (is_null($this->getContext())) return(null);
    return($this->getContext()->getApplicationContext());
  } // getApplicationContext

  protected function getEventClassParam($eventClassName) {
    return(Array(OpfConfig::PARAM_EVENT_CLASS => $eventClassName));
  } // appendEventClassToParamArray

  protected function getActionClassParam($ActionClassName) {
    return(Array(OpfConfig::PARAM_ACTION_CLASS => $ActionClassName));
  } // appendActionClassToParamArray

  protected function renderDatabaseDateValue($dateValue) {
    if (OpfSqlStatement::isNullDate($dateValue)) {
      return(null);
    } else {
      return($dateValue);
    } // else
  } // renderDatabaseDateValue

  public function getParentCanvas() {
    $p = $this->parentUIObject;
    while (!is_null($p)) {
      if (is_subclass_of($p, 'OpfCanvas')) {
        return($p);
      } // if
      $p = $p->parentUIObject;
    } // while
    return(null);
  } // getParentCanvas

  protected function renderPleaseWaitDivInString($visible=0) {
    $msg = $this->lookUpLocale(OpfLocalization::MSG_PLEASE_WAIT);
    $str = '<div id="'.$this->responseDivId.'"';
    if ($visible==0) {
      $str .= ' style="display:none">';
    } else if ($visible==1) {
      $str .= ' style="display">';
    } // else if
    $str .= '<img src="'.OpfApplicationConfig::APP_CALLBACK_URL
      .OpfConfig::IMAGES_DIRECTORY.'/'.OpfConfig::INPROGRESS_GIF.'">'
      .htmlEntities($msg).'</div>';
    $this->pleaseWaitDiv = $str;
    return($str);
  } // renderPleaseWaitDivInString

  protected function forwardHiddenPleaseWaitDiv() {
    if (!isset($this->pleaseWaitDiv)) return('');
    $fParam = new OpfFormParam();
    $fParam->appendKeyValuePair($this->responseDivId, $this->pleaseWaitDiv);
    return($fParam->toString());
  } // forwardHiddenPleaseWaitDiv

} // OpfUIObject
?>
