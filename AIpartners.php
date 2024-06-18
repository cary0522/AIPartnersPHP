<?php

header("Access-Control-Allow-Origin: *"); // 允許跨域請求
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: content-type");
header("Content-Type: Application/json ; charset=utf-8"); // type is JSON

$mysqli = new mysqli('127.0.0.1', 'root', '123456', 'AIpartners');
$mysqli->set_charset('utf8');

// $sql = "select * from image";
// $res = $mysqli->query($sql);
// while ($row = $res->fetch_object()) {
    // $id[] = $row->id;
//     $appearance[] = $row->appearance;
//     $image[] = base64_encode($row->image); // 先轉成 base64 資料格式再放進陣列中，才能被順利轉成 JSON 資料格式
// }

// $data = [
//     'id' => $id,
//     'appearance' => $appearance,
//     'image' => $image
// ];

// echo json_encode($data,JSON_UNESCAPED_UNICODE); // 只進行一次編碼及輸出

$sql = "select image from image where appearanceOne = ? && appearanceTwo = ? && appearanceThree = ? && appearanceFour = ? ";
$sql2 = "select feedback from traitList where traits = ? || traits = ? || traits = ? || traits = ? ";
$stmt = $mysqli->prepare($sql);
$stmt2 = $mysqli->prepare($sql2);
$stmt->bind_param('ssss', $appearanceOne, $appearanceTwo, $appearanceThree, $appearanceFour);
$stmt2->bind_param('ssss', $traitOne, $traitTwo, $traitThree, $traitFour);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents(('php://input'));
    $AiPartnerData = json_decode($data, true);
    if ($AiPartnerData !== null) {
        $appearanceOne = $AiPartnerData['appearance'][0];
        $appearanceTwo = $AiPartnerData['appearance'][1];
        $appearanceThree = $AiPartnerData['appearance'][2];
        $appearanceFour = $AiPartnerData['appearance'][3];
        $stmt->execute();
        $stmt->bind_result($image);
        $stmt->fetch();
        $stmt->free_result();

        $traitOne = $AiPartnerData['trait'][0];
        $traitTwo = $AiPartnerData['trait'][1];
        $traitThree = $AiPartnerData['trait'][2];
        $traitFour = $AiPartnerData['trait'][3];
        $stmt2->execute();
        $stmt2->bind_result($feedbackItem);
        $feedback = [];
        while($stmt2->fetch()){
            $feedback[] = $feedbackItem;
        }
        $stmt2->free_result();

        $data = [
            'image' => base64_encode($image),
            'feedback' => $feedback,
        ];

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo "null";
    }
} else {
    echo "xx";
}
?>