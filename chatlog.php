<?php

include('common.php'); // 共通部分の分離
$filesize = filesize($J_file); // 最新のファイルサイズ

if (isset($_GET['ajax']) && $_GET['ajax'] === "ON"){
    // ファイルサイズが違った時
    if($file = file_get_contents($J_file)){
        $result = output_message($file, $GET_t_file);
    }
    // チャットリセットされた時もファイルサイズが一瞬違うため9行目にfalseが返ってもinputを表示させる
    $result = $result .'<input  id="preFilesize" type="hidden" value="'.$filesize.'"><input  id="aftFilesize" type="hidden" value="'.$filesize.'">';
    echo $result;
    exit;

} elseif(isset($_GET['ajax']) && $_GET['ajax'] === "OFF"){
    // ファイルサイズが同じ時
    echo $filesize; 
    exit;
}

?>
