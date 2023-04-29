<?php

$J_file = "chatlog.json";

// 既読判定ファイル
$PUT_t_file = "t_person1.txt";
$GET_t_file = "t_person2.txt";
if ($_GET['man']) {
   $PUT_t_file = "t_person2.txt";
   $GET_t_file = "t_person1.txt";
}

function console_log($data)
{
    echo '<script>';
    echo 'console.log('.json_encode($data).')';
    echo '</script>';
}

function is_mobile()
{
    $useragents = array('iPhone','iPod','Android.*Mob','Opera.*Mini','blackberry','Windows.*Phone');
    $pattern = '/'.implode('|', $useragents).'/i';
    return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}

function output_message($file, $t_file)
{
    $result = "";
    // 新しいチャットログのHTMLを構築
    $file = json_decode($file);
    $array = $file->chatlog;
    $last_check = date('Y/m/d H:i',strtotime("-1 year"));
    if (file_exists($t_file)){
        $last_check = file_get_contents($t_file);
    }
    foreach($array as $object){
        $read = "";
        // 最終閲覧時間より書き込み記事が古い場合
        if ($_GET['man'] == "1" && $object->person == "person2"){
           if (strtotime($last_check) - strtotime($object->time) >= 0) {
               $read = "既読";
           }
        } else if ($_GET['man'] == "" && $object->person == "person1"){
            if (strtotime($last_check) - strtotime($object->time) >= 0) {
                $read = "既読";
            }
        }
        // 「 名前.jpg」があれば画像リンクする（ファイルがUploadされている前提）
        $text = preg_replace("/\s(\S+\.jpg|\S+\.png|\S+\.gif)/i", " <img width=\"300\" src=\"tmp/$1\">", $object->text);
        $time = $object->time;
        if (is_mobile()) {
            $pattern = '/.*? (\\d{2}:\\d{2})/';
            $time = preg_replace($pattern, '$1', $time);
        }
        $result =  $result.'<div class="'.$object->person.'"><p class="chat">'.str_replace("\r\n","<br>",$text).'<span class="chat-read">'.$read.'</span>'.'<span class="chat-time">'.$time.'</span></p><img src="'.$object->imgPath.'"></div>';
    }
    return $result;
}

?>
