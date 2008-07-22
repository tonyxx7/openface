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
/* Opfmodels/OpfDataSource.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);
include_once($rDir.'openface/php/models/OpfSiteWrapper.php');

class OpfDataSource {

  protected $socialNetworkWrapper; // object
  protected $userId; // int
  protected $addUrl; // String
  protected $isAppAdded; // boolean
  protected $friendList; // object array
  protected $friendIsAppAddedList; // object array

  public function __construct() {
    /* no-op */
  } // __construct

  /* methods */
  private function getSiteWrapper() {
    if (!isset($this->socialNetworkWrapper)) {
      $this->socialNetworkWrapper = new OpfSiteWrapper();
    } // if
    return($this->socialNetworkWrapper);
  } // getSiteWrapper

  public function isSocialNetworkParameter($key) {
    return($this->getSiteWrapper()->isSocialNetworkParameter($key));
  } // isSocialNetworkParameter

  public function getUserId() {
    return($this->getSiteWrapper()->getUserId());
  } // getUserId

  public function getUserTimeZone() {
    return($this->getSiteWrapper()->getUserTimeZone());
  } // getUserTimeZone

  public function getUserFirstLastName($uid=null) {
    if (is_null($uid)) {
      return($this->getSiteWrapper()->getUserFirstLastName($this->getUserId()));
    } else {
      return($this->getSiteWrapper()->getUserFirstLastName($uid));
    }
  } // getUserFirstLastName

  public function getFriendList() {
    return($this->getSiteWrapper()->getFriendList());
  } // getFriendList

  public function getFriendWithApp() {
    return($this->getSiteWrapper()->getFriendWithApp());
  } // getFriendWithApp

  public  function getFriendIsAppAddedList() {
    return($this->getSiteWrapper()->getFriendIsAppAddedList());
  } // getFriendIsAppAddedList

  public  function getFriendIsAppAdded($uid) {
    return($this->getSiteWrapper()->getFriendIsAppAdded($uid));
  } // getFriendIsAppAdded

  public function isAppAdded() {
    return($this->getSiteWrapper()->isAppAdded());
  } // isAppAdded

  public function getAddApplicationURLBase() {
    return($this->getSiteWrapper()->getAddApplicationURLBase());
  } // getAddApplicationURLBase

  public function getUserAffiliationList($uid=null) {
    return($this->getSiteWrapper()->getUserAffiliationList($uid));
  } // getUserAffiliationList

  public function getUserPrimaryAffiliation($uid=null) {
    return($this->getSiteWrapper()->getUserPrimaryAffiliation($uid));
  } // getUserPrimaryAffiliation

  public function profile_getFBML($uid=null) {
    return($this->getSiteWrapper()->profile_getFBML($uid));
  } // profile_getFBML

  public function generateProfileLink($uid=null) {
    return($this->getSiteWrapper()->generateProfileLink($uid));
  } // generateProfileLink

  public function beginBatch() {
    $this->getSiteWrapper()->beginBatch();
  } // beginBatch

  public function endBatch() {
    $this->getSiteWrapper()->endBatch();
  } // endBatch

  public function feedPublishStoryToUser($feedTitle, $feedBody=null,
      $image_1=null, $image_1_link=null, $image_2=null, $image_2_link=null,
      $image_3=null, $image_3_link=null, $image_4=null, $image_4_link=null) {
    return($this->getSiteWrapper()->feedPublishStoryToUser($feedTitle, $feedBody,
      $image_1, $image_1_link, $image_2, $image_2_link,
      $image_3, $image_3_link, $image_4, $image_4_link));
  } // feedPublishStoryToUser

  public function feedPublishActionOfUser($feedTitle, $feedBody=null,
      $image_1=null, $image_1_link=null, $image_2=null, $image_2_link=null,
      $image_3=null, $image_3_link=null, $image_4=null, $image_4_link=null) {
    return($this->getSiteWrapper()->feedPublishActionOfUser($feedTitle, $feedBody,
      $image_1, $image_1_link, $image_2, $image_2_link,
      $image_3, $image_3_link, $image_4, $image_4_link));
  } // feedPublishActionOfUser

  public function feedPublishTemplatizedAction($title_template, $title_data,
      $body_template, $body_data, $body_general, $image_1=null, $image_1_link=null,
      $image_2=null, $image_2_link=null, $image_3=null, $image_3_link=null,
      $image_4=null, $image_4_link=null, $target_ids='', $page_actor_id=null) {
    return($this->facebook->api_client->feed_publishTemplatizedAction($title_template, $title_data,
      $body_template, $body_data, $body_general, $image_1, $image_1_link,
      $image_2, $image_2_link, $image_3, $image_3_link,
      $image_4, $image_4_link, $target_ids, $page_actor_id));
  } // feedPublishTemplatizedAction

  public function notificationsSend($uid, $message) {
    $this->getSiteWrapper()->notificationsSend($uid, $message);
  } // notificationsSend

  public function getRequestIdList() {
    return($this->getSiteWrapper()->getRequestIdList());
  } // getRequestIdList

  public function getMyPageName() {
    return($this->getSiteWrapper()->getMyPageName());
  } // getMyPageName

  public function renderDialogInString($urlAfterInvite, 
      $installButtonLabel, $formAction, $messageToFriend, $inviteModeStr,
      $hiddenParameters, $explanationToUser, $dialogName, $dialogTitle,
      $formName, $allFriends=FALSE) {

    return($this->getSiteWrapper()->renderDialogInString($urlAfterInvite,
      $installButtonLabel, $formAction, $messageToFriend, $inviteModeStr,
      $hiddenParameters, $explanationToUser, $dialogName, $dialogTitle,
      $formName, $allFriends));
  } // renderDialogInString

  public function renderMockAjaxButtonInString($form, $url, $divId, 
      $titleText, $showDiv=null, $showDialog=null) {
    return($this->getSiteWrapper()->renderMockAjaxButtonInString($form, 
      $url, $divId, $titleText, $showDiv, $showDialog));
  } // renderMockAjaxButtonInString

  public function renderMockAjaxHrefInString($formId, $url, $divId, 
      $label, $htmlClass, $showDiv=null, $showDialog=null, $onClick=null) {
    return($this->getSiteWrapper()->renderMockAjaxHrefInString($formId,
      $url, $divId, $label, $htmlClass, $showDiv, $showDialog, $onClick));
  } // renderMockAjaxHrefInString

  public function renderHrefUrl($page, $urlParameters) {
    return($this->getSiteWrapper()->renderHrefUrl($page, $urlParameters));
  } // renderHrefUrl

  public function renderOnClickButtonInString($onClickStr, 
      $titleText) {
    return($this->getSiteWrapper()->renderOnClickButtonInString($onClickStr,
      $titleText));
  } // renderOnClickButtonInString

  public function renderMockAjaxFormStartInString($dialogName, $dialogSubmitUrl) {
    return($this->getSiteWrapper()->renderMockAjaxFormStartInString($dialogName, 
      $dialogSubmitUrl));
  } // renderMockAjaxFormStartInString

  public function renderHrefInString($page, $urlParameters, $label, 
      $htmlClass=null, $target=null, $title=null) {
    return($this->getSiteWrapper()->renderHrefInString($page, 
      $urlParameters, $label, $htmlClass, $target, $title));
  } // renderHrefInString

  public function renderImg($icon , $title=null, $htmlClass=null, $onclick=null) {
    if (is_null($icon)) return(null);
    if ($icon == '') return(null);
    return($this->getSiteWrapper()->renderImg($icon, $title, $htmlClass, $onclick));
  } // renderImg

  public function renderApplicationTitle() {
    return($this->getSiteWrapper()->renderApplicationTitle());
  } // renderImg

  public function renderCallBackUrl($subUrl) {
    return($this->getSiteWrapper()->renderCallBackUrl($subUrl));
  } // renderCallBackUrl

  public function renderInviteForm($urlAfterInvite, 
      $installButtonLabel, $formAction, $messageToFriend, $inviteModeStr,
      $hiddenParameters, $explanationToUser, $onlyFriendsWithoutApp=FALSE,
      $excludeIdList=null) {
    return($this->getSiteWrapper()->renderInviteForm(
        $urlAfterInvite, $installButtonLabel, $formAction, $messageToFriend, 
        $inviteModeStr, $hiddenParameters, $explanationToUser,
        $onlyFriendsWithoutApp, $excludeIdList));
  } // renderInviteForm

  public function getCurrentPageName() {
    return($this->getSiteWrapper()->getCurrentPageName());
  } // getCurrentPageName

  /* fbml */
  public function fbName($uid=null, $firstNameOnly="true", $useYou="false",
      $linked="true") {
    return($this->getSiteWrapper()->fbName($uid, $firstNameOnly, $useYou,
      $linked));
  } // fbName

  public function fbProfilePic($uid, $picSize=OpfSiteWrapper::DEFAULT_PIC_SIZE) {
    return($this->getSiteWrapper()->fbProfilePic($uid, $picSize));
  } // fbProfilePic

  public function fbPronoun($uid, $possessive="true") {
    return($this->getSiteWrapper()->fbPronoun($uid, $possessive));
  } // fbPronoun

  public function fbUserLink($uid, $showNetwork="false") {
    return($this->getSiteWrapper()->fbUserLink($uid, $showNetwork));
  } // fbuserLink

  public function fbGoogleAnalytics() {
    return($this->getSiteWrapper()->fbGoogleAnalytics());
  } // fbGoogleAnalytics

  public function fbShareUrl($url) {
    return($this->getSiteWrapper()->fbShareUrl($url));
  } // fbShareUrl

  public function fbShareMeta($meta, $link) {
    return($this->getSiteWrapper()->fbShareMeta($meta, $link));
  } // fbShareUrl
  public function fbSetInnerHTMLFunction() {
    return($this->getSiteWrapper()->fbSetInnerHTMLFunction());
  } // fbSetInnerHTMLFunction

  public function renderShareStyleButton($label, $href, $title=null, 
      $onClick=null) {
    return($this->getSiteWrapper()->renderShareStyleButton($label, 
      $href, $title, $onClick));
  } // renderShareStyleButton

  public function renderHrefStyleButton($label, $href='#', $htmlClass=null, 
      $title=null, $onClick=null) {
    return($this->getSiteWrapper()->renderHrefStyleButton($label, $href, 
      $htmlClass, $title, $onClick));
  } // renderHrefStyleButton

  public function renderHrefStyleSubmit($label, $formId, $htmlClass=null,
       $title=null) {
    return($this->getSiteWrapper()->renderHrefStyleSubmit($label, $formId, 
      $htmlClass, $title));
  } // renderHrefStyleSubmit

  public function renderShareStyleSubmit($label, $formId, $title=null) {
    return($this->getSiteWrapper()->renderShareStyleSubmit($label, $formId, $title));
  } // renderHrefStyleSubmit

  public function renderMockAjaxButtonInShareStyle($formId, $url, $divId, 
      $label, $showDiv=null, $showDialog=null) {
    return($this->getSiteWrapper()->renderMockAjaxButtonInShareStyle($formId, 
      $url, $divId, $label, $showDiv, $showDialog));
  } // renderMockAjaxButtonInShareStyle

  public function registerWindowOnLoad($str) {
    return($this->getSiteWrapper()->registerWindowOnLoad($str));
  } // registerWindowOnLoad

  static function renderDialogResponse($responseTitle, $responseText) {
    return(OpfSiteWrapper::renderDialogResponse($responseTitle, 
      $responseText));
  } // renderDialogResponse

  public function renderFriendSelector($uid, $formElementName, $formElementId,
      $includeMe=FALSE, $excludeIdListStr=null, $includeLists=FALSE) {
    return($this->getSiteWrapper()->renderFriendSelector($uid, 
      $formElementName, $formElementId, $includeMe, $excludeIdListStr, 
      $includeLists));
  } // renderFriendSelector

} // OpfDataSource
?>
