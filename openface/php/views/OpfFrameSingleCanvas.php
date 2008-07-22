<?php
/* views/OpfFrameSingleCanvas.php */
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
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/opf/OpfApplicationConfig.php');
include_once($rDir.'openface/php/OpfConfig.php');
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/OpfLocalization.php');
include_once($rDir.'openface/php/helpers/OpfHelperHtml.php');
include_once($rDir.'openface/php/views/OpfExecuteAction.php');
include_once($rDir.'openface/php/views/OpfUIObject.php');

class OpfFrameSingleCanvas extends OpfUIObject {

  const HELP_PAGE = 'help.php';

  protected $mainCanvas;
  protected $canvasParamName;
  protected $executeAction;
  protected $actionCompletionMessage;

  /* constructors */
  public function __construct() {
    parent::__construct();
    $this->canvasParamName = OpfConfig::PARAM_CMDLEVEL1;
    //$loc = new OpfLocalization();
    $this->mainCanvas = null;
    $this->executeAction = new OpfExecuteAction($this);
    $this->actionCompletionMessage = null;
  } // __construct

  /* methods */
  protected function getDefaultCanvas() {
    /* should be defined in subclass */
    return('');
  } // getDefaultCanvas

  private function renderIcon($icon, $title) {
      return($this->context->getDataSource()->renderImg($icon, $title));
  } // renderIcon

  protected function renderInstallLink() {
    $ds = $this->context->getDataSource();
    if ($ds->isAppAdded()==0) {
      return('<a href="'.$ds->getAddApplicationURLBase().'">'
        .$this->lookUpLocale(OpfLocalization::MSG_INSTALL).'</a>');
    } // if
    return(null);
  } // renderInstallLink

  protected function constructNavigationUrl($canvas, $selected) {
    $ds = $this->getDataSource();
    $uParam = new OpfUrlParam();
    $uParam->appendAllCurrentParameters(0, 0);
    $uParam->removeKey(OpfConfig::PARAM_CMDLEVEL4);
    $uParam->removeKey(OpfConfig::PARAM_CMDLEVEL3);
    $uParam->removeKey(OpfConfig::PARAM_CMDLEVEL2);
    $uParam->appendKeyValuePair($this->canvasParamName, $canvas->getTag());

    $label = $canvas->getLabel();
    $canvasLink = $this->renderIcon($canvas->getIcon(), $label);
    /* bold means selected */
    if ($selected) {
      $canvasLink .= '<b>';
    } // if
    $canvasLink .= ucwords($label);
    if ($selected) {
      $canvasLink .= '</b>';
    } // if

    return($ds->renderHrefInString($ds->getMyPageName(), 
      $uParam->toString(), $canvasLink));
  } // constructNavigationUrl

  protected function renderHelpLink() {
    $this->helpCanvas = new OpfCanvasHelp($this);
    $str = $this->constructNavigationUrl($this->helpCanvas, FALSE);
    return($str);
/*
    return('<a href="'.self::HELP_PAGE.'">'
      .$this->lookUpLocale(OpfLocalization::MSG_HELP).'</a>');
*/
  } // renderHelpLink

  protected function renderRightHandSide() {
    $str = $this->renderInstallLink();
    if (!is_null($str)) {
      $str .= OpfHelperHtml::pipe();
    } // if
    $str .= $this->renderHelpLink();
    return($str);
  } // renderRightHandSide

  protected function renderTopNavigationBar() {
    return(null);
  } // renderTopNavigationBar

  protected function renderTopRow() {
    $str = '<div class="dh_links clearfix">'
      .$this->renderTopNavigationBar()
      .'<div class="dh_help">'
      .$this->renderRightHandSide()
      .'</div>' // dh_help
      .'</div>'; // dh_links clearfix
    return($str);
  } // renderTopRow

  protected function renderApplicationTitle() {
    return($this->context->getDataSource()->renderApplicationTitle());
  } // renderApplicationTitle

  protected function renderButtonsOnTop() {
    /* child class can define this */
    return(null);
  } // renderButtonsOnTop

  protected function renderMessageNextToTitle() {
    /* to be defined in child class */
    return(null);
  } // renderMessageNextToTitle

  protected function renderTopDashBoard() {
    $str = '<div class="dashboard_header">'
      .$this->renderTopRow()
      .'<div class="dh_titlebar clearfix">'
      .$this->renderApplicationTitle()
      .$this->renderMessageNextToTitle()
      .'<div class="topRightLinks">'
      .$this->renderButtonsOnTop()
      .'</div>'
      .'</div>'
      .'</div>';
    return($str);
  } // renderTopDashBoard

  private function renderCurrentCanvas() {
    $subCanvas = $this->mainCanvas;
    if (!is_null($subCanvas)) {
      return($subCanvas->render());
    } else {
      return(null);
    } // if
  } // renderCurrentCanvas

  protected function renderFooter() {
    return($this->lookUpLocale(OpfLocalization::MSG_POWERED_BY_TFW));
  } // renderFooter

  public function render() {
    /* execute actions first */
    if (!is_null($this->executeAction)) {
      $this->actionCompletionMessage = $this->executeAction->execute();
    } // if
    /* then draw */
    $this->renderCanvas();
  } // render

  protected function renderCanvas() {
    $this->mainCanvas = $this->getDefaultCanvas();
    $str = $this->renderTopDashBoard()
      .$this->renderCurrentCanvas()
      .$this->renderFooter();
    /* draw to screen */
    echo($str);
  } // render

} // OpfFrameSingleCanvas
?>
