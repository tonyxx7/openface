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
/* OpfContext.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/OpfLocalization.php');
include_once($rDir.'openface/php/models/OpfDataSource.php');
include_once($rDir.'openface/php/models/OpfDbConnect.php');
include_once($rDir.'openface/php/models/OpfSiteWrapper.php');
include_once($rDir.'openface/php/views/OpfUrlParam.php');
include_once($rDir.'php/ApplicationContext.php');

/**
 * It contains a runtime object used by Open Face Framework.
 * The application should not directly reference this class.
 */
class OpfContext {

  /* variables */
  private $dbConnect;
  private $dataSource;
  private $userId;
  private $userTimeZone;
  private $propertyList;
  private $applicationContext;
  private $locale;

  /* methods */
  public function __construct() {
    $this->dbConnect = new OpfDbConnect();
    $dataSource = new OpfDataSource();
    $this->dataSource = $dataSource;
    /* cache frequently needed data */
    $this->userId = $dataSource->getUserId();
    $this->userTimeZone = $dataSource->getUserTimeZone();
    $propertyList = Array();
    $this->applicationContext = new ApplicationContext($this);
    $loc = new OpfLocalization();
    $this->locale = $loc->getCurrentLocale();
  } // __construct

  public function close() {
    $this->dbConnect->close();
  }

  /* getters */
  public function getApplicationContext() {
    return($this->applicationContext);
  } // getApplicationContext

  public function getDbConnect() {
    return($this->dbConnect);
  } // getDbConnect

  public function getDataSource() {
    return($this->dataSource);
  } // getDataSource

  public function getUserId() {
    return($this->userId);
  } // getUserId

  public function getTimeZone() {
    return($this->userTimeZone);
  } // getTimeZone

  public function getCurrentLocale() {
    return($this->locale);
  } // getLocale

  public function getCurrentUrl() {
    $ds = $this->getDataSource();
    $page = $ds->getCurrentPageName();
    $urlParam = new OpfUrlParam();
    $urlParam->appendAllCurrentParameters(0, 1);
    $str = $ds->renderHrefUrl($page, $urlParam->toString());
    return($str);
  } // getCurrentUrl

  /* setters */
  public function setApplicationContext($ctx) {
    $this->applicationContext = $ctx;
  } // setApplicationContext

} // OpfContext
?>
