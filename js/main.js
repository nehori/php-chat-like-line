document.addEventListener('DOMContentLoaded', function() {
  // ファイルサイズ更新Ajaxを0.1秒ごとに実行
  setInterval(resultLog, 1000); 
  
  function resultLog() {
    let preFS = document.getElementById('preFilesize');
    let aftFS = document.getElementById('aftFilesize');
    var params = (new URL(document.location)).searchParams;
    var man = params.get('man');
    if (man == null) {
       man = "";
    }
    
    if (preFS.value === aftFS.value) {
        // ファイルサイズが同じ場合
        // XMLHttpRequestオブジェクトを生成
        let xhr = new XMLHttpRequest();

        // 非同期通信を開始
        xhr.open('GET', 'chatlog.php?ajax=' + "OFF" + '&man=' + man, true);
        xhr.send(null);
        // onreadystatechange→通信の状態が変化したタイミングで呼び出されるイベントハンドラー
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) { //通信が完了
                  // readyState→HTTP通信の状態を取得
                  if (xhr.status === 200) { //通信が成功
                      // 現在のファイルサイズを取得し新しいファイルサイズのみ更新
                      aftFS.value = xhr.responseText;
                  }
            }
        }
      } else {
        // ファイルサイズが違う場合
        
        let chatArea = document.getElementById('chat-area');
        // XMLHttpRequestオブジェクトを生成
        let xhr = new XMLHttpRequest();
          
          // 非同期通信を開始
          xhr.open('GET', 'chatlog.php?ajax=' + "ON" + '&man=' + man, true);
          xhr.send(null);
          xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) { //通信が完了
                    if (xhr.status === 200) { //通信が成功
                      // チャットログを更新+FSも更新
                      chatArea.insertAdjacentHTML('afterbegin', xhr.responseText);

                      // チャット履歴の1番下にフォーカスを持ってくる
                      let chatAreaHeight = chatArea.scrollHeight;
                      chatArea.scrollTop = chatAreaHeight;
                      // チャット履歴の1番下にフォーカスを持ってくる
                    }
                } else { //通信が完了する前
                  // 通信完了前に最初のチャットログとFSをリセット
                  chatArea.textContent = '';
                }
          }
      };
    };
}, false);
