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
/* OpfSiteWrapper.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'php/opf/OpfApplicationConfig.php');
include_once($rDir.'openface/php/OpfConfig.php');
include_once($rDir.'openface/php/OpfDebugUtil.php');
include_once($rDir.'openface/php/helpers/OpfHelperJs.php');
include_once($rDir.'openface/php/helpers/OpfHelperHtmlSite.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/client/facebook.php');

/* this is referenced from facebookapi_php5_restlib.php */
$facebook_config['debug'] = 0;

class OpfSiteWrapper {
  /* constants */
  const CANVAS_WIDTH = 646;

  const PARAM_SITE_USER_ID = 'uid';
  const PARAM_IDS = 'ids';
  const PARAMETER_PREFIX = 'fb'; // url parameter name prefix reserved by Facebook
  const DB_FIELD_USER_BIRTHDAY = 'birthday';
  const DB_FIELD_USER_UID = 'uid';
  const DB_FIELD_USER_FIRST_NAME = 'first_name';
  const DB_FIELD_USER_LAST_NAME = 'last_name';
  const DB_FIELD_USER_NAME = 'name';
  const DB_FIELD_TIMEZONE = 'timezone';
  const DB_FIELD_AFFILIATIONS = 'affiliations';
  const DB_FIELD_AFFILIATION_NID = 'nid';
  const DB_FIELD_AFFILIATION_NAME = 'name';
  const DB_FIELD_AFFILIATION_TYPE = 'type';
  const DB_FIELD_AFFILIATION_STATUS = 'status';
  const DB_FIELD_AFFILIATION_YEAR = 'year';

  /* FBML profile picture size option */
  //const DEFAULT_PIC_SIZE = 'thumb';
  const DEFAULT_PIC_SIZE = 'square';

  /* invite dialog option */
  const FORM_MAX_SELECTOR = 20;
  const FORM_MAX_ROW = 5;

  const CLOSE_BUTTON_LABEL = 'Close';

  /* variables */
  protected $facebook; // object
  protected $userId; // int
  protected $addUrl; // String
  protected $isAppAdded; // boolean
  protected $friendList; // object array
  protected $friendIsAppAddedList; // object array
  protected $affiliationList; // object array

  /* methods */
  public function __construct() {
    $facebook = new Facebook( OpfApplicationConfig::SITE_API_KEY,
      OpfApplicationConfig::SITE_API_SECRET);
    $this->facebook = $facebook;

    $facebook->require_frame();
    $this->userId = $facebook->require_login();
    try {
      $this->addUrl = $facebook->get_add_url();
      $this->isAppAdded = $facebook->api_client->users_isAppAdded();
    } catch (Exception $e) {
      $facebook->set_user(null, null);
      $facebook->redirect(OpfApplicationConfig::APP_CALLBACK_URL);
    }
  } // __construct

  public function __destruct() {
  } // __destruct

  private function booleanToString($bool) {
    if ($bool) {
      return('true');
    } else {
      return('false');
    }
  } // booleanToString

  /* getters */
  static function isSocialNetworkParameter($key) {
    if (substr($key, 0, strlen(self::PARAMETER_PREFIX)) == self::PARAMETER_PREFIX) {
      return(1);
    } // if
    return(0);
  } // isSocialNetworkParameter

  public function getUserId() {
    return($this->userId);
  } // getUserId

  public function getUserTimeZone() {
    $timeZoneArray = $this->facebook->api_client->users_getinfo($this->userId, self::DB_FIELD_TIMEZONE);
    if (isset($timeZoneArray[0])) {
      return($timeZoneArray[0][self::DB_FIELD_TIMEZONE]);
    } else if (isset($timeZoneArray[self::DB_FIELD_TIMEZONE])) {
      return($timeZoneArray[self::DB_FIELD_TIMEZONE]);
    } else {
      return(null);
    }
  } // getUserTimeZone

  public function getUserFirstLastName($uid=null) {
    if (is_null($uid)) {
      $uid = $this->getUserId();
    } // if
    $nameArray = $this->facebook->api_client->users_getinfo($uid,
      Array(self::DB_FIELD_USER_FIRST_NAME, self::DB_FIELD_USER_LAST_NAME));
    if (is_null($nameArray)) return('');
    if (count($nameArray)==0) return('');
    $userName = $nameArray[0];
    $userNameFields = Array (
      $userName[self::DB_FIELD_USER_FIRST_NAME],
      $userName[self::DB_FIELD_USER_LAST_NAME]
    );
    return($userNameFields);
  } // getUserFirstLastName

  public function getFriendList() {
    /* returns an array of uid */
    if (!isset($this->friendList)) {
	$this->friendList = $this->facebook->api_client->friends_get();
    } // if
    return($this->friendList);
  } // getFriendList

  public function getFriendWithApp() {
    /* returns an array of uid */
    $queryStr = 'SELECT uid FROM user WHERE has_added_app=1 and uid IN (SELECT uid2 FROM friend WHERE uid1 = '.$this->getUserId().')';
    $fwaList = $this->executeFQL($queryStr);
    /* strip only the uid */
    $uidList = Array();
    if (!is_null($fwaList)) {
      foreach($fwaList as $fwa) {
        $uidList[] = $fwa[self::DB_FIELD_USER_UID];
      } // foreach
    } // if
    return($uidList);
  } // getFriendWithApp

  public  function getFriendIsAppAddedList() {
    if (!isset($this->friendIsAppAddedList)) {
      $this->friendIsAppAddedList = $this->facebook->api_client->users_isAppAdded($this->getFriendList());
    } // if
    return($this->friendIsAppAddedList);
  } // getFriendIsAppAddedList

  public  function getFriendIsAppAdded($uid) {
    $uidArray = Array (
      self::PARAM_SITE_USER_ID => $uid
    ); // Array
    $isAppAddedList = $this->facebook->api_client->users_isAppAdded($uidArray);
    return($isAppAddedList);
  } // getFriendIsAppAdded

  public function isAppAdded() {
    return($this->isAppAdded);
  } // isAppAdded

  public function getAddApplicationURLBase() {
    //return('http://www.facebook.com/add.php?api_key='.OpfApplicationConfig::SITE_API_KEY);
    return($this->addUrl);
  } // getAddApplicationURLBase

  public function getUserAffiliationList($uid=null) {
    if (is_null($uid)) {
      $uid = $this->getUserId();
    } // if
    if (!isset($this->affiliationList)) {
	$userAffList = $this->facebook->api_client->users_getinfo(
          $uid, self::DB_FIELD_AFFILIATIONS);
        if(is_array($userAffList)) {
          $userAff = current($userAffList);
          $this->affiliationList = $userAff[self::DB_FIELD_AFFILIATIONS];
        } else {
          $this->affiliationList = null;
        } // if
    } // if
    return($this->affiliationList);
  } // getUserAffiliationList

  public function getUserPrimaryAffiliation($uid=null) {
    if (is_null($uid)) {
      $uid = $this->getUserId();
    } // if
    $affiliationList = $this->getUserAffiliationList($uid);
    if (empty($affiliationList)) return(null);
    if (count($affiliationList)==0) return(null);
    $primaryNetwork = current($affiliationList);
    if ($primaryNetwork === FALSE) return(Array(null,null));
    return(Array(
      $primaryNetwork[self::DB_FIELD_AFFILIATION_NID],
      $primaryNetwork[self::DB_FIELD_AFFILIATION_NAME]
    ));
  } // getUserPrimaryAffiliation

  public function profile_getFBML($uid=null) {
    if (is_null($uid)) {
      $uid = $this->getUserId();
    } // if
    return($this->facebook->api_client->profile_getFBML($uid));
  } // profile_getFBML

  public function generateProfileLink($uid=null) {
    if (is_null($uid)) {
      $uid = $this->getUserId();
    } // if
    $userName = $this->getUserFirstLastName($uid);
    $href= 'http://www.facebook.com/profile.php?id='.$uid.'&ref=nf';
    return('<a href="'.$href.'">'.implode(' ',$userName).'</a>');
  } // generateProfileLink

  public function beginBatch() {
      $this->facebook->api_client->begin_batch();
  } // beginBatch

  public function endBatch() {
      $this->facebook->api_client->end_batch();
  } // endBatch

  /*
    Publishes a News Feed story to the user corresponding to the session_key parameter.

    Applications are limited to calling this function once every 12 hours for each user.  
    The story may or may not show up in the user's News Feed, depending on the number and 
    quality of competing stories.  

    If an app developer calls feed.publishStoryToUser for his own user id, the story is always published. 
    This is to allow for testing and display tweaks.
  */
  public function feedPublishStoryToUser($feedTitle, $feedBody=null, 
      $image_1=null, $image_1_link=null, $image_2=null, $image_2_link=null,
      $image_3=null, $image_3_link=null, $image_4=null, $image_4_link=null) {
    /* The function returns 1 on success, 0 on permissions error, or otherwise an error response. */
    $response = $this->facebook->api_client->feed_publishStoryToUser($feedTitle, $feedBody,
      $image_1, $image_1_link, $image_2, $image_2_link,
      $image_3, $image_3_link, $image_4, $image_4_link);
    return($response);
  } // feedPublishStoryToUser

  /*
    Publishes a Mini-Feed story to the user corresponding to the session_key parameter, 
    and publishes News Feed stories to the friends of that user who have added the application.

    Applications are limited to calling this function ten (10) times for each user in a 
    rolling 48-hour window.  The story may or may not show up in the user's friends' News Feeds, 
    depending on the number and quality of competing stories.

    Unlike feed_publishStoryToUser, there is no unlimited rule for feed_publishActionOfUser, 
    since this method affects all Facebook friends of the developer.
   */
  public function feedPublishActionOfUser($feedTitle, $feedBody=null,
      $image_1=null, $image_1_link=null, $image_2=null, $image_2_link=null,
      $image_3=null, $image_3_link=null, $image_4=null, $image_4_link=null) {
    /* The function returns 1 on success, 0 on permissions error, or otherwise an error response. */
    $response = $this->facebook->api_client->feed_publishActionOfUser($feedTitle, $feedBody,
      $image_1, $image_1_link, $image_2, $image_2_link,
      $image_3, $image_3_link, $image_4, $image_4_link);
    return($response);
  } // feedPublishActionOfUser

  /*
    Publishes a Mini-Feed story to the user corresponding to the session_key parameter, 
    and publishes News Feed stories to the friends of that user who have added the application.

    Applications are limited to calling this function ten (10) times for each user in a 
    rolling 48-hour window.  The story may or may not show up in the user's friends' News Feeds, 
    depending on the number and quality of competing stories.

    You may use the Feed Preview Console to experiment with this method and see previews of your stories.
   */
  public function feedPublishTemplatizedAction($title_template, $title_data,
      $body_template, $body_data, $body_general, $image_1=null, $image_1_link=null,
      $image_2=null, $image_2_link=null, $image_3=null, $image_3_link=null,
      $image_4=null, $image_4_link=null, $target_ids='', $page_actor_id=null) {
    /* The function returns 1 on success, 0 on permissions error, or otherwise an error response. */
    $response = $this->facebook->api_client->feed_publishTemplatizedAction($title_template, $title_data,
      $body_template, $body_data, $body_general, $image_1, $image_1_link,
      $image_2, $image_2_link, $image_3, $image_3_link,
      $image_4, $image_4_link, $target_ids, $page_actor_id);
    return($response);
  } // feedPublishTemplatizedAction

  public function notificationsSend($uid, $message) {
    if (OpfConfig::DEBUG_LEVEL > 0) {
      OpfDebugUtil::logOutput('notificationsSend $uid='.$uid
        .' $message="'.$message.'"');
    } // if
    $this->facebook->api_client->notifications_send($uid, $message, 'user_to_user');
  } // notificationsSend

  public function getRequestIdList() {
    if(isset($_GET[self::PARAM_IDS])) {
      return($_GET[self::PARAM_IDS]);
    } // if
    if(isset($_POST[self::PARAM_IDS])) {
      return($_POST[self::PARAM_IDS]);
    } // if
    return(null);
  } // getRequestIdList

  /* fbml */
  public function fbName($uid=null, $firstNameOnly="true", $useYou="false", 
      $linked="true") {
    if (is_null($uid)) {
      $uid = $this->getUserId();
    } // if
    return('<fb:name firstnameonly="'.$firstNameOnly
      .'" uid="'.$uid.'" useyou="'.$useYou.'" linked="'.$linked.'"/>');
  } // fbName

  public function fbProfilePic($uid, $linked='true', $picSize=self::DEFAULT_PIC_SIZE) {
    return('<fb:profile-pic linked="'.$linked.'" uid="'.$uid
      .'" size="'.$picSize.'"/>');
  } // fbProfilePic

  public function fbPronoun($uid, $possessive="true") {
    return('<fb:pronoun possessive="'.$possessive.'" uid="'.$uid.'"/>');
  } // fbPronoun

  public function fbUserLink($uid, $showNetwork="false") {
    return('<fb:userlink uid="'.$uid.'" shownetwork="'.$showNetwork.'"/>');
  } // fbuserLink

  public function fbGoogleAnalytics() {
    return('<fb:google-analytics uacct="" page="'.APPLICATION_TITLE.'" />');
  } // fbGoogleAnalytics

  public function fbShareUrl($url) {
    return('<fb:share-button class="url" href="'.$url.'"/>');
  } // fbShareUrl

  public function fbShareMeta($meta, $link) {
    $str = '<fb:share-button class="meta" meta="'.$meta.'"';
    if (!is_null($link)) {
      $str .= ' link="'.$link.'" ';
    } // if
    $str .= '/>';
    return($str);
  } // fbShareUrl

  public function renderShareStyleButton($label, $href='#', $title=null,
      $onClick=null) {
    $str ='<div class="share_and_hide clearfix">'
      .$this->renderHrefStyleButton($label, $href, 'share', $title, $onClick)
      .'</div>';
    return($str);
  } // renderShareStyleButton

  public function renderHrefStyleButton($label, $href='#', $htmlClass=null, 
      $title=null, $onClick=null) {
    $str = '<a style="cursor: pointer" ';
    if (!is_null($href)) {
      $str .= ' href="'.$href.'"';
    } // if
    if (!is_null($onClick)) {
      $str .= ' onclick="'.$onClick.'"';
    } // if
    if (!is_null($htmlClass)) {
      $str .= ' class="'.$htmlClass.'" ';
    } // if
    if (!is_null($title)) {
      $str .= ' title="'.$title.'"';
    } // if
    $str .= '>';
    if (!is_null($label)) {
      $str .= $label;
    } // if
    $str .= '</a>';
    return($str);
  } // renderHrefStyleButton

  public function renderHrefStyleSubmit($label, $formId, $htmlClass=null,
       $title=null) {
    $onClick = OpfHelperJs::submitById($formId);
    return($this->renderHrefStyleButton($label, '#', $htmlClass,
      $title, $onClick));
  } // renderHrefStyleSubmit

  public function renderShareStyleSubmit($label, $formId, $title=null) {
    $str = '<div class="share_and_hide clearfix">';
    $str .= $this->renderHrefStyleSubmit($label, $formId, 'share', $title);
    $str .= '</div>';
    return($str);
  } // renderHrefStyleSubmit

  public function registerWindowOnLoad($str) {
    /* FBJS disallow access to the window object so we cannot do window.onload */
    return($str.'();');
  } // registerWindowOnLoad

  public function getMyPageName() {
      $sName = $_SERVER['SCRIPT_NAME'];
      $pageName = strrchr($sName, '/');
      if ($pageName === FALSE) return($sName);
      return(substr($pageName, 1));
  } // getMyPageName

  private function executeFQL($queryString) {
    $array = $this->facebook->api_client->fql_query($queryString);
    if ($array == '') return(null);
    return($array);
    /* use $array[i]['attributeName'] to access each data */
    /* use if ($array != NULL) to check for empty result set */
  } // executeFQL

  /* user interface */
  /* TODO: pass in to override fb:req-choice */
  public function renderDialogInString($urlAfterInvite, 
      $installButtonLabel, $formAction, $messageToFriend, $inviteModeStr,
      $hiddenParameters, $explanationToUser, $dialogName, $dialogTitle, 
      $formName, $allFriends=FALSE) {

    $dialogContent = '<fb:fbml>'.$this->renderInviteForm($urlAfterInvite, 
      $installButtonLabel, $formAction, $messageToFriend, $inviteModeStr,
      $hiddenParameters, $explanationToUser, $allFriends).'</fb:fbml>';
    return('<fb:dialog id="'.$dialogName.'" cancel_button=1>'
      .'<fb:dialog-title>'.$dialogTitle.'</fb:dialog-title>'
      .'<fb:dialog-content>'.$dialogContent.'</fb:dialog-content>'
      .'<fb:dialog-button type="submit" value="Yes" form_id="'.$formName.'" />'
      .'</fb:dialog>');
  } // renderDialogInString

  public function renderInviteForm($urlAfterInvite, 
      $installButtonLabel, $formAction, $messageToFriend, $inviteModeStr,
      $hiddenParameters, $explanationToUser, $onlyFriendsWithoutApp=FALSE,
      $excludeIdList=null) {
    $myUid = $this->getUserId(); 

    /* Get list of friends who have this app installed... */
    $rs = null;
    if ($onlyFriendsWithoutApp === TRUE) {
      $rs = $this->getFriendWithApp();
    } // if

    /* Build an delimited list of users...  */
    $excludeIdListStr = null;
    if (!is_null($rs)) {
      if (!is_null($excludeIdList)) {
        $excludeIdListStr = implode(',', array_merge($rs, $excludeIdList));
      } else {
        $excludeIdListStr = implode(',', $rs);
      } // else
    } else {
      if (!is_null($excludeIdList)) {
        $excludeIdListStr = implode(',', $excludeIdList);
      } // if
    } // if
    
    /* Build a confirm install button.  'next' is where the user will land after install */
    /* TODO: pass in param to override addApplicationUrl */
    $addApplicationUrl = $this->getAddApplicationURLBase()
      .'&next='.urlencode($urlAfterInvite); 
    $confirmInstallButton = '<fb:req-choice url="'.$addApplicationUrl
      .'" label="'.$installButtonLabel.'" /> ';

    /* return FBML */
    $str = '<fb:fbml><fb:request-form method="POST" type="'.OpfApplicationConfig::APPLICATION_TITLE
      .'" action="'.$formAction
      .'" content="'.htmlentities($messageToFriend.$confirmInstallButton)
      .'" invite="'.$inviteModeStr.'"> ';
    $str .= $hiddenParameters;
    $str .='<fb:multi-friend-selector max="'.self::FORM_MAX_SELECTOR.'" actiontext="'
      .$explanationToUser
      .'" showborder="true" rows="'.self::FORM_MAX_ROW.'" exclude_ids="'.$excludeIdListStr.'"> '
      .'</fb:request-form></fb:fbml>';
    return($str);
  } // renderInviteForm

  public function renderMockAjaxButtonInString($form, $url, $rewriteDivId, 
      $label, $showDiv=null, $showDialog=null) {
    $labelStr = '<div class="tr"><div class="bl"><div class="br">';
    if(!is_null($label)) {
      $labelStr .= '<span>'.$label.'</span>';
    } else {
      $labelStr .= '&nbsp;';
    } // if
    $labelStr .= '</div></div></div>';

    $str = '<div class="dh_new_media_shell">'
      .$this->renderMockAjaxHrefInString($form, $url, $rewriteDivId, $labelStr, 
         'dh_new_media', $showDiv, $showDialog)
      .'</div>';
    return($str);
  } // renderMockAjaxButtonInString

  public function renderMockAjaxButtonInShareStyle($formId, $url, $rewriteDivId, 
      $label, $showDiv=null, $showDialog=null) {
    $str = '<div class="share_and_hide clearfix">'
      .$this->renderMockAjaxHrefInString($formId, $url, $rewriteDivId, $label, 
         'share', $showDiv, $showDialog)
      .'</div>';
    return($str);
  } // renderMockAjaxButtonInShareStyle

  public function renderMockAjaxHrefInString($formId, $url, $rewriteDivId, 
      $label, $htmlClass, $showDiv=null, $showDialog=null, $onClick=null) {
/*
FBML supports mock AJAX functionality. On any element, you specify the attributes 
clickrewriteid, clickrewriteform, and clickrewriteurl. When the user clicks on that 
element, the contents of the form specified with clickrewriteform gets POSTed to 
Facebook's servers and then relayed to the URL specified with clickrewriteurl. 
This URL should return FBML, not the HTML that a normal Web server would. The Facebook 
server then renders this FBML into HTML and replace the the innerHTML of the DOM element 
you specified with clickrewriteid.
*/
    $str = '<a href="#" class="'.$htmlClass.'" ';
    if (!is_null($formId)) {
      $str .= 'clickrewriteform="'.$formId .'"';
    } // if
    if (!is_null($showDiv)) {
      $str .= ' clicktoshow="'.$showDiv.'"';
    } // if
    if (!is_null($url)) {
        $str .= ' clickrewriteurl="'.OpfApplicationConfig::APP_CALLBACK_URL.'/'.$url.'"';
    } // if
    if (!is_null($rewriteDivId)) {
      $str .= ' clickrewriteid="'.$rewriteDivId.'"';
    } // if
    if (!is_null($showDialog)) {
      $str .= ' clicktoshowdialog="'.$showDialog.'"';
    } // if
    if (!is_null($onClick)) {
      $str .= ' onClick="'.addslashes($onClick).'"';
    } // if
    $str .= '>'.$label.'</a>';
    return($str);
  } // renderMockAjaxHrefInString

  public function renderOnClickButtonInString($onClickStr, $titleText) {
    /* TODO factor out common code with renderMockAjaxButtonInString */
    $str = '<div class="dh_new_media_shell">'
      .'<a href="#" class="dh_new_media" onClick="'.$onClickStr.'"';
    $str .= '>'
      .'<div class="tr"><div class="bl"><div class="br">';
    if(!is_null($titleText)) {
      $str .= '<span>'.$titleText.'</span>';
    } else {
      $str .= '&nbsp;';
    } // if
    $str .= '</div></div></div>'
      .'</a></div>';
    return($str);
  } // renderOnClickButtonInString

  public function renderMockAjaxFormStartInString($dialogName, $dialogSubmitUrl) {
    echo('<form method="POST" id="'.$dialogName.'">');
  } // renderMockAjaxFormStartInString

  public function renderHrefUrl($page, $urlParameters) {
    $str = $page;
    if (!is_null($urlParameters)) {
      $str .= '?'.$urlParameters;
    } // if
    return($str);
  } // renderHrefUrl

  public function renderHrefInString($page, $urlParameters, $label, $htmlClass,
      $target=null, $title=null) {
    $url = $this->renderHrefUrl($page, $urlParameters);
    /* OpfApplicationConfig::APP_CALLBACK_URL is automatically prepended to the href by fb */
    $str = '<a ';
    if (!is_null($htmlClass)) $str .= ' class="'.$htmlClass.'" ';
    if (!is_null($target)) $str .= ' target="'.$target.'" ';
    if (!is_null($title)) $str .= ' title="'.$title.'" ';
    $str .= ' href="'.$url.'">'.$label.'</a>';
    return($str);
  } // renderHrefInString

  public function renderImg($src, $title, $htmlClass,$onclick=null) {
    return(OpfHelperHtmlSite::renderImg($src, $title, $htmlClass,$onclick));
  } // renderImg

  public function renderCallBackUrl($subUrl) {
    return(OpfApplicationConfig::APP_CALLBACK_URL.'/'.$subUrl); 
  } // renderCallBackUrl

  public function renderApplicationHiddenParam($key, $value) {
      $str .= '<input type="hidden" fb_protected="true" id="'.$key
        .'" name="'.$key.'" value="'.$value.'"/>';
      return($str);
  } // renderApplicationHiddenParam

  public function renderApplicationTitle() {
    return('<h2 style="background-image: url('.OpfApplicationConfig::APPLICATION_ICON.')">'
      .OpfApplicationConfig::APPLICATION_TITLE.'</h2>');
  } // renderApplicationTitle

  public function getCurrentPageName() {
    $self = $_SERVER['PHP_SELF'];
    /* skip the first 5 segments */
    $pos = 0;
    for($i=0; $i<5; $i++) {
      $pos = strpos($self, '/', $pos)+1;
    } // for
    $page = substr($self, $pos);
    return($page);
  } // getCurrentPageName

  static function renderDialogResponse($responseTitle, $responseText) {
    return('<fb:dialogresponse>'
      .'<fb:dialog-title>'.$responseTitle.'</fb:dialog-title>'
      .'<fb:dialog-content>'.$responseText.'</fb:dialog-content>'
      .'<fb:dialog-button type="button" value="'.self::CLOSE_BUTTON_LABEL.'" close_dialog=1 />'
      .'</fb:dialogresponse>');
  } // renderDialogResponse

  public function renderFriendSelector($uid, $formElementName, $formElementId,
      $includeMe=FALSE, $excludeIdListStr=null, $includeLists=FALSE) {
    /* Renders a predictive friend selector input for a given person. You can use this tag inside an fb:request-form to select users for whom a request can be sent.  */
/*
(optional)  uid 	 int 	 The user whose friends you can select. (default value is the uid of the currently logged-in user) 	
	name 	string 	The name of the form element. (default value is friend_selector_name) 	
	idname 	string 	The name of the hidden form element that contains the user ID of the selected friend. If you are using this tag inside fb:request-form, do not override the default. (default value is friend_selector_id) 	
	include_me 	bool 	Indicates whether or not to include the logged in user in the suggested options. (default value is false) 	
	exclude_ids 	array 	A list of user IDs to exclude from the selector. (comma-separated) 	
	include_lists 	bool 	Indicates whether or not to include friend lists in the suggested options. (default value is false)
*/
    if (is_null($uid)) {
      $uid = $this->getUserId();
    } // if
    $str ='<fb:friend-selector uid="'.$uid.'" name="'.$formElementName
        .'" idname="'.$formElementId.'" include_me="'
        .$this->booleanToString($includeMe).'" exclude_ids="'.$excludeIdListStr
        .'" include_lists="'.$this->booleanToString($includeLists).'" />';
    return($str);
  } // renderFriendSelector

} // OpfSiteWrapper
?>
