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
/* OpfDialogModal.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfConfig.php');
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/views/OpfFormParam.php');
include_once($rDir.'openface/php/views/OpfUIObject.php');

class OpfDialogModal extends OpfUIObject {
  const DIALOG_BUTTON_VALUE = 'Yes';
  const METHOD_GET = 'GET';
  const METHOD_POST = 'POST';

  const BUTTON_SIZE_BIG = 'big';
  const BUTTON_SIZE_SMALL = 'small';

  /* variables */
  protected $dialogName;
  protected $dialogTitle;
  protected $buttonLabel;
  protected $formName;
  protected $formAction;
  protected $requestIdList;
  protected $asyncDialogButtton;
  protected $showDivOnClick;
  protected $buttonSize;
  protected $dialogButtonLabel;

  /* constructors */
  public function __construct($parentUIObject, $dialogName, $dialogTitle, 
    $buttonLabel, $formName, $formAction, $buttonSize) {

    parent::__construct($parentUIObject);

    $this->asyncDialogButtton = 1;
    $this->showDivOnClick = null;
    $this->showDivOnComplete = null;
    $this->dialogButtonLabel = self::DIALOG_BUTTON_VALUE;

    $this->dialogName = $dialogName;
    $this->dialogTitle = $dialogTitle;
    $this->buttonLabel = $buttonLabel;
    $this->formName = $formName;
    $this->formAction = $formAction;
    $this->buttonSize = $buttonSize;
  } // __construct

  /* methods */
  protected function renderFormDetails() {
    /* to be defined in child class */
    return(null);
  } // renderFormDetails

  protected function getFormHeader() {
    /* to be defined in child class */
    return(null);
  } // getFormHeader

  protected function getFormParameters() {
    /* to be defined in child class */
    return(null);
  } // getFormParameters

  protected function getFormContents() {
    /* to be defined in child class */
    return(null);
  } // getFormContents

  private function renderForm() {
    $formHeader = $this->getFormHeader();
    $formHeader['id'] = $this->formName;

    $formParam = $this->getFormParameters();
    $formContents = $this->getFormContents();

    $fParam = new OpfFormParam();
    $fParam->appendAllCurrentParameters();
    $fParam->appendKeyValueArray($formParam);
    if($this->asyncDialogButtton == 1) {
      $fParam->appendKeyValuePair(OpfConfig::PARAM_MODAL_DIALOG_CALLBACK, 1);
    } // if

    $str = '<form';
    if (!empty($formHeader)) {
      foreach($formHeader as $key => $value) {
        $str .= ' '.$key.'="'.addslashes($value).'" ';
      } // foreach
    } // if
    $str .='>'
      .$fParam->toString().$formContents.'</form>';
    return($str);
  } // renderForm

  public function render() {
    $ds = $this->parentUIObject->getDataSource();

    $dialogStr='<fb:dialog id="'.$this->dialogName.'" cancel_button=1>'
      .'<fb:dialog-title>'.$this->dialogTitle.'</fb:dialog-title>'
      .'<fb:dialog-content>'.$this->renderForm()
      .'</fb:dialog-content>';
    if($this->asyncDialogButtton == 0) {
      $dialogStr .= '<fb:dialog-button type="submit" value="'
        .$this->dialogButtonLabel.'" form_id="'
        .$this->formName.'" />';
    } else {
      $dialogStr .= '<fb:dialog-button type="submit" value="'
        .$this->dialogButtonLabel
        .'" clickrewriteid="'.$this->dialogName
        .'" clickrewriteurl="'.$ds->renderCallBackUrl($this->formAction)
        .'" clickrewriteform="'.$this->formName;

      if (!is_null($this->showDivOnClick)) {
        $dialogStr .= '" clicktoshow="'.$this->showDivOnClick;
      } // if
      if (!is_null($this->showDivOnComplete)) {
        $dialogStr .= '" clickrewriteid="'.$this->showDivOnComplete;
      } // if
      $dialogStr .= '" />';
    } // else
    $dialogStr .= '</fb:dialog>';

    /* buttons */
    switch($this->buttonSize) {
    case self::BUTTON_SIZE_BIG:
      $dialogStr .= $this->renderBigButton();
      break;
    case self::BUTTON_SIZE_SMALL:
      $dialogStr .= $this->renderLinkAsButton();
      break;
    } // switch

    return($dialogStr);

  } // render

  private function renderBigButton() {
    $str = '<div class="dh_new_media_shell">'
      .'<a href="" class="dh_new_media" clicktoshowdialog="'.$this->dialogName.'">'
      .'<div class="tr"><div class="bl"><div class="br">'
      .'<span>'.$this->buttonLabel.'</span>'
      .'</div></div></div>'
      .'</a></div>';
    return($str);
  } // renderBigButton

  private function renderLinkAsButton() {
    $str = '<a href="" clicktoshowdialog="'.$this->dialogName.'">'
      .'<span>'.$this->buttonLabel.'</span>'
      .'</a>';
    return($str);
  } // renderLinkAsButton

} // OpfDialogModal
?>
