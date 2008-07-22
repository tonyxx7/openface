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
/* OpfHelperHtmlSite.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/opf/OpfApplicationConfig.php');
include_once($rDir.'openface/php/helpers/OpfHelperHtml.php');

class OpfHelperHtmlSite extends OpfHelperHtml {

  /* these are in fb common.css */
  const CLASS_STANDARD_MESSAGE = 'standard_message has_padding';
  const CLASS_STATUS = 'status';
  const CLASS_EXPLANATION_NOTE = 'explanation_note';

  static public function messageInYellowishBox($header, $body, $width='610px') {
    $str = '<div style="width: '.$width.';">'
      .'<div class="'.self::CLASS_STANDARD_MESSAGE.'">'
      .'<div class="'.self::CLASS_STATUS.'"><h1>'.$header.'</h1>';
    if (!is_null($body)) {
      $str .= $body;
    } // if
    $str .= '</div></div></div>';
    return($str);
  } // messageInYellowishBox

  static public function renderImg($src, $title, $htmlClass,$onclick=null) {
    /* src needs to be an absolute path */
    $str = '<img ';
    $str .= 'src="'.OpfApplicationConfig::APP_CALLBACK_URL.'/'.$src.'" ';
    if (!is_null($title)) {
      $str .= ' title="'.htmlEntities($title).'" ';
    } // if
    if (!is_null($htmlClass)) {
      $str .= ' class="'.$htmlClass.'" ';
    } // if
    if (!is_null($onclick)) {
      $str .= 'style="cursor: pointer;" onclick="'.$onclick.'" ';
    } // if
    $str .= '>';
    return($str);
  } // renderImg

} // OpfHelperHtmlSite
?>
