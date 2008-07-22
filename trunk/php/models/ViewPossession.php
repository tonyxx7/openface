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
/* ViewPossession.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'php/models/TableCard.php');
include_once($rDir.'php/models/TablePossession.php');

class ViewPossession extends TablePossession {

  const TABLE_NAME = 'ViewPossession';
  const FIELD_DESCRIPTION = TableCard::FIELD_DESCRIPTION;
  const FIELD_PICTURE_URL = TableCard::FIELD_PICTURE_URL;
  const FIELD_CARD_CATEGORY = TableCard::FIELD_CARD_CATEGORY;

  public function __construct($dbConnect) {
    $fieldNameList = Array(
      self::FIELD_OBJID,
      self::FIELD_PERSON_OBJID,
      self::FIELD_FROM_PERSON_OBJID,
      self::FIELD_CARD_OBJID,
      self::FIELD_POSSESSION_START,
      self::FIELD_DESCRIPTION,
      self::FIELD_PICTURE_URL,
      self::FIELD_CARD_CATEGORY,
    );
    parent::__construct($dbConnect, self::TABLE_NAME, $fieldNameList);
  } // __construct

} // ViewPossession
?>
