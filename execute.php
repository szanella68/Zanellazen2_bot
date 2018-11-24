<?php
  $botToken = "681369794:AAGuUAUjHDGAOsmo3GXX7VSdO9E5-OTISYE";
  $website = "https://api.telegram.org/bot".$botToken;
  $update = file_get_contents('php://input');
  $update = json_decode($update, TRUE);
  
  $chatId = $update['message']['from']['id'];
  $nome = $update['message']['from']['first_name'];
  $text = $update['message']['text'];
 
  $agg = json_encode($update,JSON_PRETTY_PRINT);

switch($text){
    case "/start":
        sendMessage($chatId,"Weyla!");
        break;
    case "bene":
        sendMessage($chatId,"Ottimo!");
        break;
    case "tu?":
        sendMessage($chatId,"Eh... Sono ancora in via di sviluppo!");
        break;
    default:
      $tastierabenvenuto = '["bene"],["tu?"],["'.$nome.'"]';
      sendMessage($chatId,"Ciao <b>$nome</b>! Come stai?",$tastierabenvenuto);
      break;
  }

  function sendMessage($chatId,$text,$tastiera){
    if(isset($tastiera)){
         $tastierino = '&reply_markup={"keyboard":['.$tastiera.'],"resize_keyboard":true}';
    
    }
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatId&parse_mode=HTML&text=".urlencode($text).$tastierino;
    file_get_contents($url);
  }
     
 ?>
   
