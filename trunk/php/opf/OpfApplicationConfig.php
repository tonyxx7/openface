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
/* OpfApplicationConfig.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);

class OpfApplicationConfig {
  const APPLICATION_TITLE = 'Send A Card';

  const APP_CALLBACK_URL = 'http://12345678.fb.joyent.us/sendacard/fb.prod';
  const APP_INVOCATION_URL = 'http://apps.facebook.com/sendacard/';
  const HELP_PAGE = 'http://12345678.fb.joyent.us/sendacard/fb.prod/php/help.php';
  const IMAGES_DIRECTORY = 'http://12345678.fb.joyent.us/sendacard/fb.prod/images';
  const APPLICATION_ICON = 'http://12345678.fb.joyent.us/sendacard/fb.prod/images/sendacard.gif';
  const APPLICATION_SPLASH = 'http://12345678.fb.joyent.us/sendacard/fb.prod/images/sendacardSplash.gif';

  const MY_DATABASE_PATH ='localhost';
  const MY_DATABASE_NAME ='';
  const MY_DATABASE_USER ='';
  const MY_DATABASE_PASSWORD ='';

  const SITE_API_KEY = '';
  const SITE_API_SECRET = '';
  const SITE_USERNAME ='';
  const SITE_PASSWORD ='';

  const SITE_NAME = 'fb';

} // OpfApplicationConfig
?>
