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
/* OpfDialogModalList.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/views/OpfFormParam.php');
include_once($rDir.'openface/php/dialogs/OpfDialogModal.php');
include_once($rDir.'php/opf/OpfApplicationConfig.php');

class OpfDialogModalList extends OpfDialogModal {
  const MAX_PER_COLUMN = 10;
  const MAX_COLUMN_COUNT = 3;

  /* http constants */
  const VALUE_ON = 'on';

  /* html constants */
  const BUTTON_TYPE_CHECKBOX = 'checkbox';
  const BUTTON_TYPE_RADIO = 'radio';

  /* variables */
  protected $maxPerColumn;
  protected $maxColumnCount;
  protected $formName;
  protected $parameterPrefix;
  protected $fullList;
  protected $requestIdList;
  protected $buttonType;
  protected $formMethod;
  protected $formAction;
  protected $additionalParamArray;

  /* constructors */
  public function __construct($parentUIObject, $dialogName, $dialogTitle, 
    $buttonLabel,
    $formName, $formMethod, $formAction, $buttonType, $paramPrefix, 
    $additionalParamArray, $fullList, $buttonSize) {

    parent::__construct($parentUIObject, $dialogName, $dialogTitle, 
      $buttonLabel, $formName, $formAction, $buttonSize);

    $this->maxPerColumn = self::MAX_PER_COLUMN;
    $this->maxColumnCount = self::MAX_COLUMN_COUNT;
    $this->formName = $formName;
    $this->formMethod = $formMethod;
    $this->parameterPrefix = $paramPrefix;
    $this->buttonType = $buttonType;
    $this->additionalParamArray = $additionalParamArray;
    $this->fullList = $fullList;
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
    $dialogStr = '<table border="0"><tr><td vAlign="top" nowrap>';

    $choiceCount = 0;
    $columnCount = 0;
    /* friends or groups */
    foreach ($this->fullList as $entry) {
      /* open a new column if too many */
      if($choiceCount > $this->maxPerColumn) {
        $dialogStr .= '</td><td>';
        $choiceCount = 0;
        $columnCount++;
        if ($columnCount >= $this->maxColumnCount) {
          break;
        } // if
      } // if
      $uid = $entry->getId();
      switch($this->buttonType) {
      case self::BUTTON_TYPE_CHECKBOX:
        $nameValue = 'name="'.$this->parameterPrefix.$uid.'"';
        break;
      case self::BUTTON_TYPE_RADIO:
        $nameValue = 'name="'.$this->parameterPrefix.'" value="'.$uid.'"';
        break;
      } // switch
      $dialogStr .= '<input type="'.$this->buttonType.'" '.$nameValue.'>'
        .$entry->renderAsString().'</input><br/>';
      $choiceCount++;
    } // foreach
    $dialogStr .= '</td></tr></table>';
    return($dialogStr);
  } // getFormContents

  /**
   *  getIdListFromRequest returns a list of GET request parameters whose
   *    names start with the parameterPrefix.
   */
  protected function getIdListFromRequest() {
    if (isset($this->requestIdList))
      return;
    $parameterPrefixLen = strlen($this->parameterPrefix);
    $this->requestIdList = array();
    foreach($_GET as $key => $value) {
      if (substr($key, 0, $parameterPrefixLen) == $this->parameterPrefix) {
        /* the part of the key after the prefix is the uid */
        $realKey = urldecode(substr($key, $parameterPrefixLen, 
           strlen($key)-$parameterPrefixLen));
        $this->requestIdList[] = $realKey;
      } // if
    } // foreach
    return($this->requestIdList);
  } // getIdListFromRequest

  /**
   *  getRequestIdParamAsString returns a String separated by a leading '&'
   */
  public function getRequestIdParamAsString() {
      $paramStr = '';
      $prefixLen = strlen($this->parameterPrefix);
      foreach ($_GET as $key => $value) {
        if (substr($key, 0, $prefixLen) == $this->parameterPrefix) {
	  $paramStr .= '&'.urlencode($key).'='.urlencode($value);
        } // if
      } // foreach
      return($paramStr);
  } // getRequestIdParamAsString

} // OpfDialogModalList
?>
