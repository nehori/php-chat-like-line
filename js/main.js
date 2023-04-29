document.addEventListener('DOMContentLoaded', function() {
  // �t�@�C���T�C�Y�X�VAjax��0.1�b���ƂɎ��s
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
        // �t�@�C���T�C�Y�������ꍇ
        // XMLHttpRequest�I�u�W�F�N�g�𐶐�
        let xhr = new XMLHttpRequest();

        // �񓯊��ʐM���J�n
        xhr.open('GET', 'chatlog.php?ajax=' + "OFF" + '&man=' + man, true);
        xhr.send(null);
        // onreadystatechange���ʐM�̏�Ԃ��ω������^�C�~���O�ŌĂяo�����C�x���g�n���h���[
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) { //�ʐM������
                  // readyState��HTTP�ʐM�̏�Ԃ��擾
                  if (xhr.status === 200) { //�ʐM������
                      // ���݂̃t�@�C���T�C�Y���擾���V�����t�@�C���T�C�Y�̂ݍX�V
                      aftFS.value = xhr.responseText;
                  }
            }
        }
      } else {
        // �t�@�C���T�C�Y���Ⴄ�ꍇ
        
        let chatArea = document.getElementById('chat-area');
        // XMLHttpRequest�I�u�W�F�N�g�𐶐�
        let xhr = new XMLHttpRequest();
          
          // �񓯊��ʐM���J�n
          xhr.open('GET', 'chatlog.php?ajax=' + "ON" + '&man=' + man, true);
          xhr.send(null);
          xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) { //�ʐM������
                    if (xhr.status === 200) { //�ʐM������
                      // �`���b�g���O���X�V+FS���X�V
                      chatArea.insertAdjacentHTML('afterbegin', xhr.responseText);

                      // �`���b�g������1�ԉ��Ƀt�H�[�J�X�������Ă���
                      let chatAreaHeight = chatArea.scrollHeight;
                      chatArea.scrollTop = chatAreaHeight;
                      // �`���b�g������1�ԉ��Ƀt�H�[�J�X�������Ă���
                    }
                } else { //�ʐM����������O
                  // �ʐM�����O�ɍŏ��̃`���b�g���O��FS�����Z�b�g
                  chatArea.textContent = '';
                }
          }
      };
    };
}, false);
