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
/* OpfHelperJs.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
class OpfHelperJs {

  static function getElementById($elementId) {
    return('document.getElementById(\''.$elementId.'\')');
  } // getElementById

  static function show($elementId) {
    $str = self::getElementById($elementId).'.setStyle(\'display\',\'\');';
    return($str);
  } // show

  static function hide($elementId) {
    $str = self::getElementById($elementId).'.setStyle(\'display\',\'none\');';
    return($str);
  } // hide

  static function enable($elementId) {
    $str = self::getElementById($elementId).'.setDisabled(false);';
    return($str);
  } // enable

  static function disable($elementId) {
    $str = self::getElementById($elementId).'.setDisabled(true);';
    return($str);
  } // disable

  static function submitById($elementId) {
    $str = self::getElementById($elementId).'.submit();';
    return($str);
  } // submitById

  static function setInnerHtmlById($elementId,$newHtml) {
    $wrappedHtml = '\'<div>\'.'.$newHtml.'.\'</div>\'';
    $str = self::getElementById($elementId).'.setInnerHTML('.$wrappedHtml.');';
    return($str);
  } // submitById

  static function fbSetInnerHTMLFunction() {
    return('function setInner(node, content) {node.setInnerXHTML(content);}');
  } // fbSetInnerHTMLFunction

} // OpfHelperJs
?>
