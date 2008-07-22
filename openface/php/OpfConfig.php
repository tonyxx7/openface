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
/* OpfConfig.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
/**
 * It contains global configuration parameters used by Open Face Framework.
 * The application should not directly reference these constants because they
 * may change.
 *
 */
class OpfConfig {

  const DEBUG_LEVEL = 0;

  const PARAM_PREFIX = 'opf_'; // url parameter prefix used by the framework
  const PARAM_UID = 'opf_uid';
  const PARAM_PLEASEWAITDIV = 'opf_pleaseWaitDiv';
  const PARAM_CMDLEVEL1 = 'opf_cmdL1';
  const PARAM_CMDLEVEL2 = 'opf_cmdL2';
  const PARAM_CMDLEVEL3 = 'opf_cmdL3';
  const PARAM_CMDLEVEL4 = 'opf_cmdL4';
  const PARAM_POST_INSTALL = 'opf_postInstall';
  const PARAM_POST_UNINSTALL = 'opf_postUninstall';
  const PARAM_MODAL_DIALOG_CALLBACK = 'opf_modalDialogCallback';
  const PARAM_EVENT_CLASS = 'opf_eventClass';
  const PARAM_ACTION_CLASS = 'opf_actionClass';

  const DIV_SHOW_PROGRESS = 'showProgressDiv';
  const URL_ROOT = '/openface/php';
  const IMAGES_DIRECTORY = '/openface/images';
  const INPROGRESS_GIF = 'inProgress.gif';

  /* hi5 */
  const PARAM_API_AUTH_TOKEN = 'Hi5AuthToken';
  const SOCIALNETWORK_NAME_ABBREV ='hi5';
  const API_NAMESPACE = 'http://api.hi5.com/';
  const SERVER_ADDR = 'http://api.hi5.com/rest';
  const AUTH_WSDL = 'http://api.hi5.com/hi5auth.wsdl';
  const PROFILE_WSDL = 'http://api.hi5.com/hi5profile.wsdl';
} // OpfConfig
?>
