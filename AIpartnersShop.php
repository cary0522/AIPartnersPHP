<?php
header("Access-Control-Allow-Origin: *"); // 允許跨域請求
header("Access-Control-Allow-Methods: GET , POST");
header("Access-Control-Allow-Headers: content-type");
header("Content-Type: Application/json ; charset=utf-8"); // type is JSON

$mysqli = new mysqli('127.0.0.1', 'root', '123456', 'AIpartners');
$mysqli->set_charset('utf8');

// 一次給四筆，傳頁數再給當下頁面商品
// $page = isset($_GET['page'])? $_GET['page'] : 1 ;
// $start = ($page-1)*4;

// $sql = "select image , feature , price , goodIndex from image limit ? , 4 ";
// $stmt = $mysqli->prepare($sql);
// $stmt->bind_param('i',$start);
// $stmt->execute();
// $stmt->bind_result($image,$feature,$price,$goodIndex);
// $stmt->store_result();
// $data = [];
// while($stmt->fetch()){
//     $data[] = [
//         'image' => base64_encode($image),
//         'feature' => $feature,
//         'price' => $price,
//         'goodIndex' => $goodIndex,
//     ];
// }
// echo json_encode($data,JSON_UNESCAPED_UNICODE);

//一次傳全部商品，換頁由前端控制，下方月費可隨機出現
$sql = "select image , feature , price , goodIndex from image";
$stmt = $mysqli->query($sql);
$data = [];
while($result = $stmt->fetch_object() ){
    $data[] = [
        'image' => base64_encode($result->image),
        'feature' => $result->feature,
        'price' => $result->price,
        'goodIndex' => $result->goodIndex,
    ];
}
echo json_encode($data,JSON_UNESCAPED_UNICODE);

?>