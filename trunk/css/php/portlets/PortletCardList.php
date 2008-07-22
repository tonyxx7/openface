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
/* PortletCardList.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/ApplicationConfig.php');
include_once($rDir.'php/models/TableCard.php');
include_once($rDir.'openface/php/helpers/OpfHelperHtmlSite.php');
include_once($rDir.'openface/php/portlets/OpfPortlet.php');

class PortletCardList extends OpfPortlet {

  const MAX_PER_ROW = 3;
  const DIV_CARD = 'divCard';
  const CLASS_CARD_LIST = 'cardList';

  protected $dataList;
  protected $maxPerRow;
  protected $tableStyle;
  protected $enableDetailLink;
  protected $showCardDescription;

  public function __construct($parentUIObject) {
    parent::__construct($parentUIObject);
    $this->maxPerRow = self::MAX_PER_ROW;
    $this->showCardDescription = 0;
  } // __construct

  /* methods */
  public function loadData() {
    $table = new TableCard($this->getDbConnect());
    $this->dataList = $table->fetchAllAsArray();
  } // loadData

  protected function renderCardButtons($personObjid, $cardObjid, $data) {
    /* to be defined in child class */
    return(null);
  } // renderCardButtons

  private function renderCardImage($imgSrc) {
    return('<img src="'.OpfHelperHtmlSite::fullImgPath($imgSrc,
      ApplicationConfig::CARD_IMAGES_DIRECTORY).'">');
  } // renderCardImage

  protected function renderPossessionDetails($data) {
    return(null);
  } // renderPossessionDetails

  protected function renderCardDetails($data) {
    /* may be overriden in child class */
    $str = null;
    if ($this->showCardDescription == 1) {
      $str .= '<br>'.htmlEntities($data[TableCard::FIELD_DESCRIPTION]);
    } // if
    return($str);
  } // renderCardDetails

  public function render() {
    $appCtxt = $this->getApplicationContext();
    $personObjid = $appCtxt->getCurrentUserObjid();

    $str =  '<div id="'.self::DIV_CARD.'">';

    if (isset($this->tableStyle)) {
      $str .= '<table';
      foreach ($this->tableStyle as $att=>$val) {
        $str .= ' '.$att.'="'.$val.'" ';
      } // foreach
      $str .= '>';
    } else {
      $str .= OpfHelperHtmlSite::tableHeaderPadded('100%');
    }

    if (!isset($this->dataList)) {
      $this->loadData();
    } // if

    $tdWidth = null;
    if ($this->maxPerRow > 1) {
      $tdWidth = 100/$this->maxPerRow;
    } // if
    $columnIndex = 0;
    $rowIndex = 0;
    $str .= '<tr>';
    if (!is_null($this->dataList)) {
      foreach ($this->dataList as $data) {
        /* start new row if reached the max per row */
        if ($columnIndex >= $this->maxPerRow) {
          $str .= '</tr><tr>';
          $columnIndex = 0;
          $rowIndex++;
        } // if

        $img = $this->renderCardImage($data[TableCard::FIELD_PICTURE_URL]);

        $str .= '<td vAlign="top" align="center" class="'
          .self::CLASS_CARD_LIST.'"';
        if (!is_null($tdWidth)) {
          $str .= ' width="'.$tdWidth.'%"';
        } // if
        $str .= '>'.$img.$this->renderCardDetails($data);

        $objid = $data[TableCard::FIELD_OBJID];

        $actionStr = $this->renderCardButtons($personObjid, $objid, $data);
        if (!is_null($actionStr)) {
          $str .= '<br>'.$actionStr;
        } // if

        $str .= '</td>'.$this->renderPossessionDetails($data);
        $columnIndex += 1;
      } // foreach
    } // if

    $str .= '</tr></table></div>';
    return($str);
  } // render

} // PortletCardList
?>
