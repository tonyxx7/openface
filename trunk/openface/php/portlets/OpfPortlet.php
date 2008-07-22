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
/* views/OpfPortlet.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/views/OpfUIObject.php');
include_once($rDir.'openface/php/views/OpfUrlParam.php');

class OpfPortlet extends OpfUIObject {

  protected $context;

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
    $this->context = $parentUIObject->getContext();
  } // __construct

  public function loadData() {
    /* to be defined in child */
  } // loadData

  public function render() {
    return('');
  } // render

  protected function generateUrlBackToCurrent() {
    $ds = $this->getDataSource();
    $parentCanvas = $this->getParentCanvas();
    $canvasParams = new OpfUrlParam();
    $canvasParams->appendKeyValueArray($parentCanvas->getWebParametersToMe());

    return($ds->renderHrefUrl($ds->getCurrentPageName(),
      $canvasParams->toString()));
  } // generateUrlBackToCurrent

  protected function generateUrlToTag($tag) {
    $ds = $this->getDataSource();
    $parentCanvas = $this->getParentCanvas();
    $pathParam = $parentCanvas->getWebParametersToCanvas($tag);
    $uParam = new OpfUrlParam();
    $uParam->appendKeyValueArray($pathParam);
    return($ds->renderHrefUrl($ds->getCurrentPageName(),
      $uParam->toString()));
  } // generateUrlToTag

} // OpfPortlet
?>
