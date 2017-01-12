<?php
// Show all errors for testing
error_reporting(E_ALL);

// SDK is installed via composer
require_once __DIR__ . "/includes/vendor/autoload.php";

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\GuzzleHTTPClient;

// Set these
$config = [
    'channelId' => '1496327818',
    'channelSecret' => '1a7a5b40caee9ca1d47de7fa5813d0c9',
    'channelMid' => 'ub6c47206ab9cdaa0badabfd29bb5b55c',
];
$sdk = new LINEBot($config, new GuzzleHTTPClient($config));

$postdata = @file_get_contents("php://input");
$messages = $sdk->createReceivesFromJSON($postdata);

// Verify the signature
// REF: http://line.github.io/line-bot-api-doc/en/api/callback/post.html#signature-verification
$sigheader = 'X-LINE-ChannelSignature';
// REF: http://stackoverflow.com/a/541450
$signature = @$_SERVER[ 'HTTP_'.strtoupper(str_replace('-','_',$sigheader)) ];
if($signature && $sdk->validateSignature($postdata, $signature)) {
    // Next, extract the messages
    if(is_array($messages)) {
        foreach ($messages as $message) {
            if ($message instanceof LINEBot\Receive\Message\Text) {
                $text = $message->getText();
                if ($text == "mid") {
                    $fromMid = $message->getFromMid();

                    // Send the mid back to the sender and check if the message was delivered
                    $result = $sdk->sendText([$fromMid], 'mid: ' . $fromMid);
                    if(!$result instanceof LINE\LINEBot\Response\SucceededResponse) {
                        error_log('LINE error: ' . json_encode($result));
                    }
                } else {
                    // Process normally, or do nothing
                }
            } else {
                // Process other types of LINE messages like image, video, sticker, etc.
            }
        }
    } // Else, error
} else {
    error_log('LINE signatures didn\'t match');
}