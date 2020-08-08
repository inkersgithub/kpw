<?php


function random($length)
{

	$chars = implode(range('a','z'));
	$chars .= implode(range('2','8'));
	$shuffled = str_shuffle($chars);
	return substr($shuffled, 0, $length);
}
function session_token()
{
	return random(8).random(8).random(8).random(8);
}

function pushMessage($tle,$mssg,$topics){
$msgdata = array (
                    "message" => $mssg,
                    "title"=>$tle
            );
$data = json_encode($msgdata);

    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'to' => '/topics/'.$topics,
            'data' => array("data"=>$data)
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAAGYpiuZA:APA91bG4u4E18hQSSwaE_p-jVdV_xKFDsVLQS2iXacKrCROIw_VKE8_MhagnX-j-60qUJPhX_qWwwzo6z_fwHRUmYm3od_JmrOT3Cmap9KXhz7zeewmi-lYk8kWcjoQrnmyEm0MPY27i",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    curl_close ( $ch );
    //echo "<script>console.log('$result');</script>";
}



?>
