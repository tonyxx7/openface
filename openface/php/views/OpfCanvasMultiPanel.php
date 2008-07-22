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
/* views/OpfCanvasMultiPanel.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfConfig.php');
include_once($rDir.'openface/php/views/OpfUrlParam.php');
include_once($rDir.'openface/php/views/OpfCanvas.php');

class OpfCanvasMultiPanel extends OpfCanvas {

  protected $panelList;
  protected $currentPanelTag;
  protected $currentPanel;

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
    $this->panelList = null;
    $this->currentPanelTag = null;
  } // __construct

  /* getters */
  /* to be defined in child */
  protected function getPanelList() {
    return(null);
  } // getPanelList

  /* to be defined in child */
  protected function getDefaultPanelTag() {
    return(null);
  } // getDefaultPanelTag

  /* methods */
  /* to be defined in child */
  protected function renderTop() {
    return(null);
  } // renderTop

  private function setUpCurrentPanelTag() {
    if (!is_null($this->panelList)) {
      $cmd2 = $this->getRequestParameter(OpfConfig::PARAM_CMDLEVEL2);
      if (isset($cmd2)) {
        $this->currentPanelTag = $cmd2;
      } else {
        $this->currentPanelTag = $this->getDefaultPanelTag();
      } // if
    } else {
      $this->currentPanelTag = null;
    } // if
  } // setUpCurrentPanelTag

  private function constructPanelUrl($panel, $hrefClass) {
    $uParam = new OpfUrlParam();
    $uParam->appendAllCurrentParameters(0, 0);
    $uParam->appendKeyValuePair(OpfConfig::PARAM_CMDLEVEL2, $panel->getTag());

    $ds = $this->getDataSource();
    $icon = $ds->renderImg($panel->getIcon(), $panel->getLabel());
    $str = $ds->renderHrefInString($ds->getCurrentPageName(), $uParam->toString(),
       $icon.$panel->getLabel(), $hrefClass);
    return($str);
  } // constructPanelUrl

  protected function renderPanelBar() {
    $str = '<div class="tabs clearfix"><center><div class="left_tabs">'
      .'<ul class="toggle_tabs clearfix" id="toggle_tabs_unused">';
    $panelCount = count($this->panelList);
    $index=0;
    foreach($this->panelList as $panel) {
      $str .= '<li';
      /* no class name for entries in the middle */
      if($index==0) {
        $str .= ' class="first"';
      } else if ($index == $panelCount-1) {
        $str .= ' class="last"';
      } // else
      $str .= '>';

      $hrefClass = null;
      if ($panel->getTag()==$this->currentPanelTag) {
        $hrefClass = 'selected';
        $this->currentPanel = $panel;
      } // if
      $str .= $this->constructPanelUrl($panel, $hrefClass);
      $str .= '</li>';
    } // foreach
    $str .= '</ul></div></center></div>';
    $index++;
    return($str);
  } // renderPanelBar

  public function render() {
    $this->panelList = $this->getPanelList();
    $this->setUpCurrentPanelTag();
    $str = $this->renderTop();
    $str .= $this->renderPanelBar();
    if (isset($this->currentPanel)) {
      $str .= $this->currentPanel->render();
    } // if
    return($str);
  } // render

} // OpfCanvasMultiPanel
?>
