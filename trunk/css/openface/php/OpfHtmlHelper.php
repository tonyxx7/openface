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
/* OpfHtmlHelper.php*/
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfSystemProfile.php');
include_once($rDir.'php/opf/OpfApplicationConfig.php');

class  OpfHtmlHelper {
  const CSS_FILENAME = 'css/application.css';
  const IMAGES_FB_DIR = 'openface/images.fb';
  const TFW_SCRIPT_DIR = 'openface/js';
  const TFW_CSS_DIR = 'openface/css';
  const APP_SCRIPT_DIR = 'js';

  private $emulatorMode;
  private $applicationTitle;
  private $profile;

  public function __construct($emulatorMode, $applicationTitle) {
    $this->emulatorMode = $emulatorMode;
    $this->applicationTitle = $applicationTitle;
    $this->profile = OpfSystemProfile::getHtmlProfile();
  } // __construct

  private function getFullPath($path) {
    $rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
    return($rDir.$path);
  } // getFullPath

  private function getFilesInDir($dirName) {
    $results = array();
    $dir = opendir($dirName);
    while (($file = readdir($dir)) !== false) {
      if ($file != '.' && $file != '..') {
            $results[] = $file;
      } // if
    } // while
    closedir($dir);
    return($results);
  } // getFilesInDir

  public function renderHeader() {
    if ($this->profile['showHtmlTag']) {
      echo('<HTML><HEAD><TITLE>'.$this->applicationTitle.'</TITLE></HEAD>');
      echo('<body class="fbframe"> <div id="book"> <div class="canvas_rel_positioning">');
    } else if ($this->profile['showDivTag']) {
      echo('<div class="fbframe">');
    } // if
    $this->renderStyle();
    $this->renderScriptSrc();
  } // renderHeader

  public function renderFooter() {
    if ($this->profile['showHtmlTag']) {
      echo('</div></div></BODY></HTML>');
    } else if ($this->profile['showDivTag']) {
      echo('</div>');
    } // if
  } // renderFooter

  private function renderStyle() {
    /* openface styles */
    $cssFileNameList = $this->getFilesInDir($this->getFullPath(self::TFW_CSS_DIR));
    foreach($cssFileNameList as $css) {
      $cssPath = $this->getFullPath(self::TFW_CSS_DIR).'/'.$css;
?>
<style type="text/css"> <?php require($cssPath); ?> </style>
<?php
    } // foreach

    /* fb styles: used for debugging */
    if ($this->profile['renderFbStyles']) {
      /* include the Facebook css */
      $cssList = array( "actionspro.css", "canvas.css", "common.css", "confirmation.css", "pages.css");
      foreach ($cssList as $css) {
        $cssPath = $this->getFullPath(self::IMAGES_FB_DIR).'/'.$css;
?>
<style type="text/css"> <?php require($cssPath); ?> </style>
<?php
      } // foreach
    } // if
?>
<style type="text/css"> <?php require($this->getFullPath(self::CSS_FILENAME)); ?> </style>
<?php
  } // renderStyle

  private function expandJsFileName($dirName, $f) {
      return(OpfApplicationConfig::APP_CALLBACK_URL.'/'.$dirName.'/'.$f);
  } // expandJsFileName

  private function renderScriptSrcDir($dirName) {
    $str = null;
    $fileNameList = $this->getFilesInDir($this->getFullPath($dirName));
    foreach($fileNameList as $f) {
      $str .= '<script src="'.$this->expandJsFileName($dirName,$f).'"></script>';
    } // foreach
    return($str);
  } // renderScriptSrcDir

  private function renderScriptSrc() {
    if ($this->profile['renderScriptSrc']==0) return;
    /* order is important */
    $jsList = Array (
      'openfaceUtil.js',
      'openfaceDebug.js',
      'openfaceConfig.js',
      'openfaceLocalization.js',
      'openfaceView.js',
      'openfaceMultiCanvasView.js',
      'openfaceInvite.js',
      'openfaceMvc.js'
    );
    $jsFileNameList = $this->getFilesInDir($this->getFullPath(self::TFW_SCRIPT_DIR));
    foreach($jsList as $f) {
      if (array_search($f, $jsFileNameList) !== FALSE) {
        echo('<script src="'.$this->expandJsFileName(self::TFW_SCRIPT_DIR,$f).'"></script>');
      } // if
    } // foreach
    echo($this->renderScriptSrcDir(self::APP_SCRIPT_DIR));
  } // renderScriptSrc

} //  OpfHtmlHelper
?>
