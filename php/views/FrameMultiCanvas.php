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
/* FrameMultiCanvas.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/Localization.php');
include_once($rDir.'php/views/CanvasCardStore.php');
include_once($rDir.'php/views/CanvasInBox.php');
include_once($rDir.'php/views/CanvasOutBox.php');
include_once($rDir.'php/views/ExecuteAction.php');
include_once($rDir.'openface/php/helpers/OpfHelperHtmlSite.php');
include_once($rDir.'openface/php/views/OpfFrameMultiCanvas2.php');

class FrameMultiCanvas extends OpfFrameMultiCanvas2 {

  public function __construct() {
    parent::__construct();
  } // __construct

  /* setters */
  public function setContext($ctxt) {
    parent::setContext($ctxt);
    $this->executeAction = new ExecuteAction($this,
      $this->getApplicationContext(), $this->getDataSource(),
      $this->getDbConnect());
  } // setContext

  /* methods */

  protected function renderMessageAboveCanvas() {
    if (empty($this->actionCompletionMessage)) {
      return(null);
    } // if
    $appLocale = $this->getAppLocale();
    $str = OpfHelperHtmlSite::messageInYellowishBox(
       $appLocale[Localization::SUCCESS],
       $this->actionCompletionMessage, '100%');
    return($str);
  } // renderMessageAboveCanvas

  public function getApplicationContext() {
    return($this->getContext()->getApplicationContext());
  } // getApplicationContext

  public function getAppLocale() {
    return($this->getApplicationContext()->getLocale());
  } // getAppLocale

  protected function getCanvasList2() {
    $canvasList2 = Array();
    $canvasList2[] = new CanvasCardStore($this);
    $canvasList2[] = new CanvasInBox($this);
    $canvasList2[] = new CanvasOutBox($this);
    return($canvasList2);
  } // getCanvasList2

  protected function getDefaultCanvas() {
    return(CanvasCardStore::TAG);
  } // getDefaultCanvas

  protected function getMustInstallCanvasTagList() {
    /* the user must install before seeing these canvas */
    $tagArray = Array (
      CanvasCardStore::TAG,
      CanvasInBox::TAG,
      CanvasOutBox::TAG,
    );
    return($tagArray);
  } // getMustInstallCanvasTagList

} // FrameMultiCanvas
?>
