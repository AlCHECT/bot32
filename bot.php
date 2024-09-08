<?php
$TOKEN = '7345410781:AAHuyGlMPgBZe-Iv-BM3g39eDp_OYQ--6l4'; 
$API_URL = 'https://api.telegram.org/bot' . $TOKEN . '/';

$update = file_get_contents('php://input');
file_put_contents('log.txt', "Received at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents('log.txt', $update . "\n\n", FILE_APPEND); 
$update = json_decode($update, TRUE);

if (!$update) {
    exit;
}

$message = isset($update['message']) ? $update['message'] : "";
$chat_id = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$chat_type = isset($message['chat']['type']) ? $message['chat']['type'] : "";
$text = isset($message['text']) ? $message['text'] : "";
$from_username = isset($message['from']['username']) ? $message['from']['username'] : "";
$from_firstname = isset($message['from']['first_name']) ? $message['from']['first_name'] : "";
$message_id = isset($message['message_id']) ? $message['message_id'] : "";

if ($chat_type === 'group' || $chat_type === 'supergroup') {
    $keywords = ['ابي', 'ابغى', 'حدا يساعدني', 'مشروع', 'واجب'];

    foreach ($keywords as $keyword) {
        if (strpos($text, $keyword) !== false) {
            // الرد داخل المجموعة على الرسالة الأصلية
            $reply_text = "لقد اكتشفت رسالتك التي تحتوي على '$keyword'!";
            sendMessage($chat_id, $reply_text, $message_id);
            break;
        }
    }
}

function sendMessage($chat_id, $text, $reply_to_message_id = null, $reply_markup = null) {
    global $API_URL;
    $url = $API_URL . "sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($text);
    if ($reply_to_message_id) {
        $url .= "&reply_to_message_id=" . $reply_to_message_id;
    }
    if ($reply_markup) {
        $url .= "&reply_markup=" . urlencode($reply_markup);
    }
    $response = file_get_contents($url);
    return $response;
}

