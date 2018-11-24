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
    case "/tastiera":
        sendMessage($chatId,"Test tastiera Inline!",$esempiotastierainline,"inline");
        break;
    case "Bene":
        sendMessage($chatId,"Ottimo!");
        break;
    case "Tu?":
        sendMessage($chatId,"Eh... Sono ancora in via di sviluppo!");
        break;
    default:
      $tastierabenvenuto = '["Bene"],["Tu?"],["'.$nome.'"]';
      sendMessage($chatId,"Ciao <b>$nome</b>! Come stai?",$tastierabenvenuto,"fisica");
      break;
  }

  function sendMessage($chatId,$text,$tastiera,$tipo){
    if(isset($tastiera)){
      if($tipo == "fisica"){
        $tastierino = '&reply_markup={"keyboard":['.urlencode($tastiera).'],"resize_keyboard":true}';
      }
      else {
        $tastierino = '&reply_markup={"inline_keyboard":['.urlencode($tastiera).'],"resize_keyboard":true}';
      }
    }
    $url = $GLOBALS[website]."/sendMessage?chat_id=$chatId&parse_mode=HTML&text=".urlencode($text).$tastierino;
    file_get_contents($url);
  }
     
 ?>
   
