?php
  include 'token.php';
  $update = file_get_contents('php://input');
  $update = json_decode($update, TRUE);
  
  $chatId = $update['message']['from']['id'];
  $nome = $update['message']['from']['first_name'];
  $text = $update['message']['text'];
  $query = $update['callback_query'];
  
   if(strpos($text,"+")!==false){
          sendMessage($chatId,eval('return '.$text.';'));
         exit();
   }
   
     function sendMessage($chatId,$text){
      $url = $GLOBALS[website]."/sendMessage?chat_id=$chatId&text=".urlencode($text);
      file_get_contents($url);
     }
     
 ?>
   