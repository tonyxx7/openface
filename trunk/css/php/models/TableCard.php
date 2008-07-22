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
/* TableCard.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/models/OpfDataTable.php');

class TableCard extends OpfDataTable {

  const TABLE_NAME = 'Card';
  const FIELD_DESCRIPTION = 'description';
  const FIELD_PICTURE_URL = 'pictureURL';
  const FIELD_CARD_CATEGORY = 'cardCategory';

  public function __construct($dbConnect) {
    $fieldNameList = Array(
      self::FIELD_OBJID,
      self::FIELD_DESCRIPTION,
      self::FIELD_PICTURE_URL,
      self::FIELD_CARD_CATEGORY,
    );
    parent::__construct($dbConnect, self::TABLE_NAME, $fieldNameList);
    /* sort by category */
    $this->setDefaultSortOrder(self::FIELD_CARD_CATEGORY);
  } // __construct

  public function selectByCategory($category) {
    $sql = self::FIELD_CARD_CATEGORY.'='.$this->wrapNullAndQuote($category);
    return($this->fetchSomeAsArray($sql));
  } // selectByCategoryObjid

} // TableCard
?>
