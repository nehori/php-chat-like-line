<?php

// 変更箇所 ====================================

// fontawesomeのファイル置き場
$FONTAWESOME = 'fontawesome-free-6.4.0-web';

// 1人目のアイコン
$PERSON1     = 'image/person1.png';
// 2人目のアイコン
$PERSON2     = 'image/person2.png';

//==============================================

include('common.php'); // 共通部分の分離

date_default_timezone_set('Asia/Tokyo'); // タイムゾーンを日本にセット

file_put_contents($PUT_t_file, date('Y/m/d H:i'), LOCK_EX);

if (isset($_POST['submit']) && $_POST['submit'] === '送信') { // #1

    $chat = [];
    $chat['person'] = 'person1';
    $chat['imgPath'] = $PERSON1;
    if ($_GET['man']) {
        $chat['person'] = 'person2';
        $chat['imgPath'] = $PERSON2;
    }
    $chat['time'] = date('Y/m/d H:i');
    $chat['text'] = htmlspecialchars($_POST['text'], ENT_QUOTES);
    $pattern = '/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/';
    $replace = '<a target="_blank" href="$1">$1</a>';
    $chat['text'] = preg_replace($pattern, $replace, $chat['text']);

    // 入力値格納処理
    if ($file = file_get_contents($J_file)) { // #2
      // ファイルがある場合 追記処理
      $file = str_replace(array("\n", "\r"), '', $file);
        $file = mb_substr($file, 0, mb_strlen($file) - 2);
        $json = json_encode($chat);
        $json = $file.','.$json.']}';
        file_put_contents($J_file, $json, LOCK_EX);
      // 時間保存
      file_put_contents($PUT_t_file, $chat['time'], LOCK_EX);
    } else { // #2
      // ファイルがない場合 新規作成処理
      $json = json_encode($chat);
        $json = '{"chatlog":['.$json.']}';
        file_put_contents($J_file, $json, FILE_APPEND | LOCK_EX);
      // 時間保存
      file_put_contents($PUT_t_file, $chat['time'], LOCK_EX);
    } // #2
     header('Location:./index.php?'.$_SERVER['QUERY_STRING']);
    exit;
} // #1

// ファイルサイズが違った時
if ($file = file_get_contents($J_file)) {
    $result = output_message($file, $GET_t_file);
    // 現在のファイルサイズと旧ファイルサイズを表示
    $result = $result.'<input id="preFilesize" type="hidden" value="'.$_SESSION['filesize'].'"><input  id="aftFilesize" type="hidden" value="'.$filesize.'">';
} else {
    // チャット履歴がない場合はチャットが増えたときに備える
    $result = '<input id="preFilesize" type="hidden" value="'.$_SESSION['filesize'].'"><input  id="aftFilesize" type="hidden" value="'.$filesize.'">';
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0">
  <title>LINE風チャット</title>
  <link rel="stylesheet" href="css/style.css?<?php echo date('Ymd-Hi'); ?>">
  <link rel="stylesheet" href="<?php echo $FONTAWESOME; ?>/css/all.min.css">
  <script src="js/main.js"></script>
</head>

<body class="<?php if($_GET['man']){echo "second";}?>">
  <main class="main">

  <div class="chat-system">
    <div class="chat-box">
      <div class="chat-area" id="chat-area">
        <?php echo $result; ?>
      </div>
      <!-- 最初の入力フォーム -->
      <form class="send-box flex-box" action="<?php 'echo index.php?' . $_SERVER['QUERY_STRING']; ?>#chat-area" method="post">
        <textarea id="textarea" type="text" name="text" rows="1" required placeholder="message.."></textarea>
        <input type="submit" name="submit" value="送信" id="search">
        <label for="search"><i class="far fa-paper-plane"></i></label>
      </form>
      <!-- 最初の入力フォーム -->
    </div>
  </div>

  </main>
</body>
</html>
