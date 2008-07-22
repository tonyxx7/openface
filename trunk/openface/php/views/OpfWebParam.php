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
/* OpfWebParam.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfConfig.php');

class OpfWebParam {

  protected $str;
  protected $paramList;

  public function __construct() {
    $this->paramList = Array();
    $this->str = null;
  } // __construct

  /* getters */
  public function getKeyValueArray() {
    return($this->paramList);
  } // getKeyValueArray

  public function toString() {
    $str = null;
    foreach($this->paramList as $key => $value) {
      $pStr = $this->renderKeyValuePair($key, $value);
      $str = $this->concatParameter($str, $pStr);
    } // foreach
    return($str);
  } // getString

  /* methods */
  protected function renderKeyValuePair($key, $value) {
    /* to be defined in child class */
    return('');
  } // renderKeyValuePair

  public function concatParameter($returnStr, $str) {
    /* to be defined in child class */
    return(null);
  } // concatParameter

  protected function renderHiddenParameterInString($key, $value, $fbPrefixLen=0,
      $opfPrefixLen=0) {
    if ($fbPrefixLen > 0) {
      /* skip the Facebook parameters */
      if (substr($key, 0, $fbPrefixLen) == OpfSiteWrapper::PARAMETER_PREFIX) {
        return(null);
      } // if
    } // if

    if ($opfPrefixLen > 0) {
      /* skip the non-opf parameters */
      if (substr($key, 0, $opfPrefixLen) != OpfConfig::PARAM_PREFIX) {
        return(null);
      } // if
    } // if

    if (!is_array($value)) {
      return($this->renderKeyValuePair($key, $value));
    } else {
      $k = $key.'[]';
      $str = null;
      foreach($value as $v) {
        $str = $this->renderKeyValuePair($k, $v);
      } // foreach
      return($str);
    } // else
  } // renderHiddenParameterInString

  public function appendEventClass($eventClassName) {
    $this->_appendKeyValuePair(OpfConfig::PARAM_EVENT_CLASS, $eventClassName);
  } // appendEventClass

  public function appendActionClass($actionClassName) {
    $this->_appendKeyValuePair(OpfConfig::PARAM_ACTION_CLASS, $actionClassName);
  } // appendActionClass

  public function appendKeyValuePair($key, $value) {
    $this->_appendKeyValuePair($key, $value);
  } // appendKeyValuePair

  private function _appendKeyValuePair($key, $value, $fbPrefixLen=0,
      $opfPrefixLen=0) {
    if ($fbPrefixLen > 0) {
      /* skip the Site parameters */
      if (substr($key, 0, $fbPrefixLen) == OpfSiteWrapper::PARAMETER_PREFIX) {
        return(null);
      } // if
    } // if

    if ($opfPrefixLen > 0) {
      /* skip the non-opf parameters */
      if (substr($key, 0, $opfPrefixLen) != OpfConfig::PARAM_PREFIX) {
        return(null);
      } // if
    } // if

    $this->paramList[$key] = $value;
  } // _appendKeyValuePair

  public function appendKeyValueArray($kvArray) {
    if (is_null($kvArray)) return;
    foreach($kvArray as $key => $value) {
      $this->_appendKeyValuePair($key, $value);
    } // foreach
  } // appendKeyValueArray

  public function removeKey($key) {
    if (isset($this->paramList[$key])) {
      unset($this->paramList[$key]);
    } // if
  } // removeKey

  private function appendRequestParameters($req, $fbPrefixLen, $opfPrefixLen) {
    foreach ($req as $key => $value) {
      /* some parameters are always skipped */
      if (($key == OpfConfig::PARAM_EVENT_CLASS) ||
          ($key == OpfConfig::PARAM_ACTION_CLASS) ||
          ($key == OpfConfig::PARAM_POST_INSTALL) ||
          ($key == OpfConfig::PARAM_POST_UNINSTALL)) {
        continue;
      } // if
      $this->_appendKeyValuePair($key, $value, $fbPrefixLen, $opfPrefixLen);
    } // foreach
  } // appendRequestParameters

  /**
   *  appendAllCurrentParameters: if $forwardFBParam == 0 then
   *   the site parameters will not be returned.
   */
  public function appendAllCurrentParameters($forwardFBParam=0, 
      $forwardNonOpfParam=0) {
    $fbPrefixLen = 0; /* everything goes */
    if ($forwardFBParam==0) {
      $fbPrefixLen = strlen(OpfSiteWrapper::PARAMETER_PREFIX);
    } // if
    $opfPrefixLen = 0;
    if ($forwardNonOpfParam==0) {
      $opfPrefixLen = strlen(OpfConfig::PARAM_PREFIX);
    } // if
    $this->appendRequestParameters($_GET, $fbPrefixLen, $opfPrefixLen);
    $this->appendRequestParameters($_POST, $fbPrefixLen, $opfPrefixLen);
  } // appendAllCurrentParameters

  public function merge($webParam) {
    if (is_null($webParam)) return;
    $this->appendKeyValueArray($webParam->getKeyValueArray());
  } // merge

} // OpfWebParam
?>
