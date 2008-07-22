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
/* Localization.php */
$rDir = str_repeat('../', substr_count($_SERVER['PHP_SELF'],'/')-3);

class Localization {

  const CARD_INBOX = 1;
  const CARD_OUTBOX = 2;
  const CARD_STORE = 3;
  const CLICK_TO_VIEW_CARD = 4;
  const GIVE_TO_FRIEND = 5;
  const MSG_CARD_SENT = 6;
  const MSG_SEND_TO_ANY_FRIEND = 7;
  const MSG_YOU_JUST_RECEIVED_A_CARD = 8;
  const RECEIVED_ON = 9;
  const RECEIVER_MESSAGE = 10;
  const SEND_TO_FRIEND = 11;
  const SENT_ON = 12;
  const SUCCESS = 13;

  private $us_en;

  public function __construct() {
    $this->us_en = Array(
      self::CARD_INBOX => 'In Box',
      self::CARD_OUTBOX => 'Out Box',
      self::CARD_STORE => 'Card Store',
      self::CLICK_TO_VIEW_CARD => 'Click to view card',
      self::GIVE_TO_FRIEND => 'Give it to a friend',
      self::MSG_CARD_SENT => 'You have sent card to %d friends.',
      self::MSG_SEND_TO_ANY_FRIEND => 'You can send card to any of your friends.',
      self::MSG_YOU_JUST_RECEIVED_A_CARD => ' just sent you a card.  Click <a href="%s">'
        .OpfApplicationConfig::APPLICATION_TITLE.'</a> to receive your card.',
      self::RECEIVED_ON => 'Received on',
      self::RECEIVER_MESSAGE => '%s just sent you a coupon in '.OpfApplicationConfig::APPLICATION_TITLE,
      self::SEND_TO_FRIEND => 'Send card to friend',
      self::SENT_ON => 'Sent on',
      self::SUCCESS => 'Success',
    );
  } // __construct

  public function getCurrentLocale() {
    return($this->us_en);
  } // getCurrentLocale

} // Localization
?>
