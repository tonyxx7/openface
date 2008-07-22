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
/* OpfControlSelect.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);

class OpfControlSelect {

  protected $choiceList;
  protected $valueFieldName;
  protected $textFieldName;

  public function __construct($choiceList, $valueFieldName=null, $textFieldName=null) {
    $this->choiceList = $choiceList;
    $this->valueFieldName = $valueFieldName;
    $this->textFieldName = $textFieldName;
  } // __construct

  public function getChoiceCount() {
    if (empty($this->choiceList)) return(0);
    return(count($this->choiceList));
  } // getChoiceCount

  public function render($controlName, $selectedValue=null, $onChange=null) {
    $str = '<select';
    if (!is_null($controlName)) {
      $str .= ' name="'.$controlName.'" ';
    } // if
    if (!is_null($onChange)) {
      $str .= ' onchange="'.$onChange.'" ';
    } // if
    $str .= '>';
    if (is_null($this->valueFieldName)) {
      foreach($this->choiceList as $value=>$txt) {
        $str .= $this->renderOneEntry($value, $txt, $selectedValue);
      } // foreach
    } else {
      foreach($this->choiceList as $choice) {
        $str .= $this->renderOneEntry($choice[$this->valueFieldName], 
          $choice[$this->textFieldName], $selectedValue);
      } // foreach
    }
    $str .= '</select>';
    return($str);
  } // render

  private function renderOneEntry($value, $txt, $selectedValue) {
    $str = '<option value="'.$value.'"';
    if (!is_null($selectedValue)) {
      if ($selectedValue == $value) {
        $str .= ' selected ';
      } // if
    } // if
    $str .= '>'.htmlentities($txt).'</option>';
    return($str);
  } // renderOneEntry

} // OpfControlSelect
?>
