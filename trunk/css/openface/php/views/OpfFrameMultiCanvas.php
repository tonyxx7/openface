<?php
/* views/OpfFrameMultiCanvas.php */
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
include_once($rDir.'openface/php/helpers/OpfHelperHtmlSite.php');
include_once($rDir.'openface/php/views/OpfCanvasHelp.php');
include_once($rDir.'openface/php/views/OpfUIObject.php');
include_once($rDir.'openface/php/views/OpfFrameSingleCanvas.php');

class OpfFrameMultiCanvas extends OpfFrameSingleCanvas {

  const CLASS_EMPHASIZELINK = 'emphasizeLink';

  protected $currentCanvasTag;
  protected $canvasList;
  protected $canvasHelp;

  /* constructors */
  public function __construct() {
    parent::__construct();
    $this->canvasList = null;
    $loc = new OpfLocalization();
    $this->currentCanvasTag = null;
  } // __construct

  /* methods */
  protected function getCanvasList() {
    /* should be defined in subclass */
    return(Array());
  } // getCanvasList

  public function getResponseDivId() {
    /* there is none at the frame level */
    return(null);
  } // getResponseDivId

  protected function analyzeCanvasTag() {
    $canvasTag = $this->getRequestParameter($this->canvasParamName);
    if (!is_null($canvasTag)) {
      $this->currentCanvasTag = $canvasTag;
    } else {
      $this->currentCanvasTag = $this->getDefaultCanvas();
    } // if
  } // analyzeCanvasTag

  private function renderIcon($icon, $title) {
      return($this->context->getDataSource()->renderImg($icon, $title));
  } // renderIcon

  protected function renderTopNavigationBar() {
    $ds = $this->getDataSource();
    $subCanvas = $this->getCurrentCanvas();
    $str = '<div class="dh_actions">';
    $canvasCount = 0;
    foreach($this->canvasList as $canvas) {
      $label = $canvas->getLabel();
      if (is_null($label) || ($label == '')) continue;
      $selected = ($canvas->getTag() == $this->currentCanvasTag);
      if ($canvasCount > 0) {
        /* put a vertical bar to separate from the previous one */
        $str .= OpfHelperHtmlSite::pipe();
      } // if
      $str .= $this->constructNavigationUrl($canvas, $selected);
      $canvasCount++;
    } // foreach
    $str .= '</div>'; // dh_actions
    return($str);
  } // renderTopNavigationBar

  protected function getCurrentCanvas() {
    if (is_null($this->canvasList)) return(null);
    if (!isset($this->currentCanvasTag)) {
      $this->analyzeCanvasTag();
    } // if
    return($this->lookUpCurrentCanvas($this->canvasList));
  } // getCurrentCanvas

  protected function lookUpCurrentCanvas($canvasList) {
    if ($this->currentCanvasTag == OpfCanvasHelp::TAG) {
      if (!isset($this->helpCanvas)) {
        $this->helpCanvas = new OpfCanvasHelp($this);
      } // if
      return($this->helpCanvas);
    } // if
    foreach($canvasList as $canvas) {
      if ($canvas->getTag() == $this->currentCanvasTag) {
        $v = $canvas;
        if (!is_null($v)) {
          $v->setContext($this->context);
        } // if
        return($v);
      } // if
    } //foreach
    return(null);
  } // lookUpCurrentCanvas

  protected function getMustInstallCanvasTagList() {
    return(null);
  } // getMustInstallCanvasTagList

  protected function mustInstallBeforeUse($subCanvas) {
    /* can be defined in child class */
    /* no subcanvas */
    if (is_null($subCanvas)) return(FALSE);
    $tagList = $this->getMustInstallCanvasTagList();
    /* no must install canvas */
    if (is_null($tagList)) return(FALSE);
    if (array_search($subCanvas->getTag(), $tagList) === FALSE) {
      /* tag is not a must install canvas */
      return(FALSE);
    } // if
    return(TRUE);
  } // mustInstallBeforeUse

  private function renderCurrentCanvas() {
    $subCanvas = $this->getCurrentCanvas();
    $ds = $this->getDataSource();
    if ($ds->isAppAdded()==0) {
      if ($this->mustInstallBeforeUse($subCanvas)) {
        $msg1 = $this->lookUpLocale(OpfLocalization::MSG_PLEASE_INSTALL_FIRST);
        $msg2 = $this->lookUpLocale(OpfLocalization::MSG_YOU_MUST_INSTALL_FIRST);
        $buttonStr = $ds->renderHrefInString($ds->getAddApplicationURLBase(), null,
          ucwords($this->lookUpLocale(OpfLocalization::MSG_INSTALL)),
          self::CLASS_EMPHASIZELINK, null, null);
        $str = OpfHelperHtmlSite::messageInYellowishBox($msg1, $msg2.'<br>'.$buttonStr, '100%');
        return($str);
      } // if
    } // if

    if (!is_null($subCanvas)) {
      return($subCanvas->render());
    } else {
      return(null);
    } // if
  } // renderCurrentCanvas

  protected function renderMessageAboveCanvas() {
    /* to be defined in child class */
    return(null);
  } // renderMessageAboveCanvas

  public function renderCanvas() {
    $this->canvasList = $this->getCanvasList();
    $this->analyzeCanvasTag();
    $str = $this->renderTopDashBoard()
      .$this->renderMessageAboveCanvas()
      .$this->renderCurrentCanvas()
      .$this->renderFooter();
    echo($str);
  } // render

} // OpfFrameMultiCanvas
?>
