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
/* OpfSystemProfile.php*/
/**
It contains some system parameters used by Open Face Framework.
The application should not directly reference this class.
 */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);

class  OpfSystemProfile {

  static function getHtmlProfile() {
    $profile = Array (
      'showHtmlTag' => 0,
      'showDivTag' => 0,
      'renderFbStyles' => 0,
      'renderScriptSrc' => 1
    );
    return($profile);
  } // getHtmlProfile

} //  OpfSystemProfile
?>
