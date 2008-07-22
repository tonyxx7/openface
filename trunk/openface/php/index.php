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
/* index.php */
/**
 * This is the main entry point of the application.  On Facebook you should register
 * the callback URL as $APP_CALLBACK_URL/openface/php/index.php.
 *
 */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
/**
 * include_once
 */
include_once($rDir.'php/opf/OpfApplicationConfig.php');
include_once($rDir.'openface/php/OpfConfig.php');
include_once($rDir.'openface/php/controllers/OpfController.php');
include_once($rDir.'openface/php/controllers/OpfControllerPostInstall.php');
include_once($rDir.'openface/php/controllers/OpfControllerPostUninstall.php');
include_once($rDir.'openface/php/OpfHtmlHelper.php');
include_once($rDir.'openface/php/OpfDebugUtil.php');
/**
 * The main line is invoked immediately.  This is not a class.
 *
 */

  /* main */
  $emulatorMode = 0;
  $helper = new OpfHtmlHelper($emulatorMode, OpfApplicationConfig::APPLICATION_TITLE);
  $helper->renderHeader();

  if (isset($_GET[OpfConfig::PARAM_POST_INSTALL])) {
    $controller = new OpfControllerPostInstall();
  } else if (isset($_GET[OpfConfig::PARAM_POST_UNINSTALL])) {
    $controller = new OpfControllerPostUninstall();
  } else {
    $controller = new OpfController();
  } // else
  $controller->execute();

  $helper->renderFooter();
?>
