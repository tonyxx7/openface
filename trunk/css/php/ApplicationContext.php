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
/* ApplicationContext.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/Localization.php');
include_once($rDir.'php/models/TablePerson.php');

class ApplicationContext {

  private $context;
  private $locale;
  private $currentUser;

  public function __construct($context) {
    $this->context = $context;
  } // __construct

  public function getLocale() {
    if (!isset($this->locale)) {
      $loc = new Localization();
      $this->locale = $loc->getCurrentLocale();
    } // if
    return($this->locale);
  } // getLocale

  public function getCurrentUserObject() {
    if (!isset($this->currentUser)) {
      $table = new TablePerson($this->context->getDbConnect());
      $this->currentUser = $table->selectByUserId($this->context->getUserId());
    } // if
    return($this->currentUser);
  } // getCurrentUserObject

  public function getCurrentUserObjid() {
    $userObj = $this->getCurrentUserObject();
    if (is_null($userObj)) return(null);
    return($userObj[TablePerson::FIELD_OBJID]);
  } // getCurrentUserObjid

} // ApplicationContext.php
?>
