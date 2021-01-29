<?php

namespace view\chat;

use classes\Config;
use models\{User, Message};

    class ChatComponent {
        public static function generate_chat_page_friend_contact($current_user_id, $user) {
            $user_id = $user->getPropertyValue("id");
            $user_name = $user->getPropertyValue("username");
            $user_picture = Config::get("root/path") . (empty($user->getPropertyValue("picture")) ? "assets/images/logos/logo512.png" : $user->getPropertyValue("picture"));
            if(strlen($user_name) > 25) {
                $user_name = substr($user_name, 0, 25) . " ..";
            }

            $now = strtotime(date("Y/m/d h:i:s"));
            $last_active_date = strtotime($user->getPropertyValue("last_active_update"));
            $interval  = abs($last_active_date - $now);
            $minutes   = round($interval / 60);

            $online_status = ($minutes < 5) ? "online.png" : "offline.png";

            echo <<<EOS
            <div class="friends-chat-item">
                <img src="$user_picture" class="image-style-3 contact-user-picture" alt="">
                <p class="regular-text" style="margin-left: 8px">$user_name</p>
                <img src="assets/images/icons/$online_status" class="image-style-4 right-pos-margin" alt="">
                <input type="hidden" class="sender" value="$current_user_id">
                <input type="hidden" class="receiver" value="$user_id">
            </div>
EOS;
        }

        public static function generate_chat_section($sender, $receiver) {
            $snd = new User();
            $snd->fetchUser("id", $sender);
            $rcv = new User();
            $rcv->fetchUser("id", $receiver);

            $friend_fullname = $rcv->getPropertyValue("firstname") . " " . $rcv->getPropertyValue("lastname");
            $friend_username = $rcv->getPropertyValue("username");
            $friend_picture = empty($rcv->getPropertyValue("picture")) ? "assets/images/logos/logo512.png" : $rcv->getPropertyValue("picture");

            echo <<<CHAT_SECTION
                <div id="second-chat-part" class="relative">
                    <input type="hidden" class="chat-sender" value="$sender">
                    <input type="hidden" class="chat-receiver" value="$receiver">
                    <div id="chat-header">
                        <div class="chat-disc-user-image">
                            <img src="$friend_picture" class="image-style-7" alt="">
                        </div>
                        <a href="" class="no-underline">
                            <div class="chat-disc-name-and-username">
                                <p class="bold-text-style-1">$friend_fullname</p>
                                <p class="message_writing_notifier_text">is writing ..</p>
                            </div>
                            <span>@$friend_username</span>
                        </a>

                        <div class="right-pos-margin">
                            <a href="" class="chat-header-more-button dotted-more-back"></a>
                        </div>
                    </div>
                    <div id="chat-container" class="relative">
                        <div class="chat-date">
                            <p class="regular-text" style="text-align: center; margin: 14px 0 20px 0">Jan 19, 2021 9:50 PM</p>
                        </div>
                    </div>
                    <div class="message-input-box">
                        <div class="relative">
                            <a href="" class="chat-message-settings-button white-dotted-more-back"></a>

                            <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                <div class="options-container-style-1 black">
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Delete message (under construction)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="text" form="send-message-form" placeholder="Type a new message" id="chat-text-input" class="chat-input-style send-button">
                        <input type="submit" value="send" form="send-message-form" id="send-message-button">
                    </div>        
                <div>
CHAT_SECTION;
        }

        public static function generate_current_user_message($user, $message, $message_date) {
            $user_profile = Config::get("root/path") .  (empty($user->getPropertyValue("picture")) ? "assets/images/logos/logo512.PNG" : $user->getPropertyValue("picture"));
            $user_profile_link = Config::get("root/path") . "profile.php?username=" . $user->getPropertyValue("username");
            $new = str_replace("/", "-", $message_date);
            $message_date = date("F d \a\\t Y H:i A",strtotime($new));
            return <<<CUM
                <div class="message-global-container">
                    <div class="current-user-message-container">
                        <div class="relative">
                            <div class="chat-message-more-button-container">
                                <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                            </div>
                            <div class="sub-options-container sub-options-container-style-2" style="z-index: 1;left: -150px">
                                <div class="options-container-style-1 black">
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Delete message (under construction)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="message-wrapper relative">
                            <p class="regular-text message-text">$message</p>
                            <div class="absolute current-user-message-date message-date">
                                <p class="regular-text-style-2">$message_date</p>
                            </div>
                        </div>
                        <a href="$user_profile_link"><img src="$user_profile" class="image-style-10" alt=""></a>
                    </div>
                </div>
CUM;
        }

        public static function generate_friend_message($user, $message, $message_date) {
            $user_profile = Config::get("root/path") .  (empty($user->getPropertyValue("picture")) ? "assets/images/logos/logo512.PNG" : $user->getPropertyValue("picture"));
            $user_profile_link = Config::get("root/path") . "profile.php?username=" . $user->getPropertyValue("username");
            $message_date = date("F d \a\\t Y H:i A",strtotime($message_date));
            return <<<FM
                <div class="message-global-container">
                    <div class="friend-message-container">
                        <a href="$user_profile_link"><img src="$user_profile" class="image-style-10" alt=""></a>
                        <div class="message-wrapper relative">
                            <p class="regular-text message-text">$message</p>
                            <div class="absolute message-date friend-message-date">
                                <p class="regular-text-style-2">$message_date</p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="chat-message-more-button-container">
                                <a href="" class="chat-message-more-button white-dotted-more-back"></a>
                            </div>
                            <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                <div class="options-container-style-1 black">
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Delete message (under construction)</a>
                                    </div>
                                    <div class="sub-option-style-2">
                                        <a href="" class="black-link">Reply (under construction)</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
FM;
        }

        public static function generate_discussion($current_user_id, $discussion) {

            /*
                First we need to determine who is the sender of message, because there's a special case where the sender
                is the same user is currently logged in, we need in this case to display, the username and picture of the receiver
                for that reason we need to compare the discussion message creator and receiver with the current user id
                to fetch the friend taht the current user is talking with                
            */

            // First get the message by id stored in $discussion->mid
            $message = new Message();
            $message->get_message("id", $discussion->mid);

            // Then we need to fetch the friend id by comparing current with the two sides to get the friend not the sender
            $friend_id = ($discussion->message_creator == $current_user_id) ? $discussion->message_receiver : $discussion->message_creator;
            $friend_user = new User();
            $friend_user->fetchUser("id", $friend_id);

            // When w get friend id we fetch the user with that id to place his data to discussion component
            $friend_picture = Config::get("root/path") . (empty($friend_user->getPropertyValue("picture")) ? 'assets/images/logos/logo512.png' : $friend_user->getPropertyValue("picture"));
            $friend_fullname = $friend_user->getPropertyValue("firstname") . " " . $friend_user->getPropertyValue("lastname");
            $friend_username = $friend_user->getPropertyValue("username");
            
            // if the message is sent by the current user, we add You: to show the user that he is the creator of the last message
            $msg = '';
            if($current_user_id == $discussion->message_creator) {
                $msg = "You: ";
            }
            $msg .= $message->get_property("message");
            // Here we need the message to be MAX length of 
            if(strlen($msg) > 28) {
                $msg = substr($msg, 0, 27) . " ..";
            }

            $message_life = '';

            // Get message date by substracting current date with the date of message
            $now = strtotime("now");
            $seconds = floor($now - strtotime($message->get_property("message_date")));
            
            if($seconds > 29030400) {
                $message_life = floor($seconds / 29030400) . "y";
            } else if($seconds > 2419200) {
                $message_life = floor($seconds / 604800) . "w";
            } else if($seconds < 604799 && $seconds > 86400) {
                $message_life = floor($seconds / 86400) . "d";
            } else if($seconds < 86400 && $seconds > 3600) {
                $message_life = floor($seconds / 3600) . "h";
            } else if($seconds < 3600 && $seconds > 60) {
                $message_life = floor($seconds / 60) . "min";
            } else {
                $message_life = $seconds . "sec";
            }

            return <<<FRIEND_DISCUSSION
                <div class="friend-chat-discussion-item-wraper relative">
                    <div class="chat-disc-user-image">
                        <img src="$friend_picture" class="image-style-7" alt="">
                    </div>
                    <div>
                        <div class="chat-disc-name-and-username">
                            <p class="bold-text-style-1">$friend_fullname</p><span class="chat-disc-item-username"> @$friend_username</span>
                        </div>
                        <p class="regular-text">$msg</p>
                    </div>
                    <div class="right-pos-margin">
                        <p class="regular-text-style-2">$message_life</p>
                    </div>
                    <div class="selected-chat-discussion">

                    </div>
                    <input type="hidden" class="uid">
                </div>
FRIEND_DISCUSSION;
        }
    }
?>