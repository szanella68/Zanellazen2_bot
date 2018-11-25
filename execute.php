<?php
   include 'config.php';
  
  $update = file_get_contents('php://input');
  $update = json_decode($update, TRUE);
  
  $chatId = $update['message']['from']['id'];
  $nome = $update['message']['from']['first_name'];
  $text = $update['message']['text'];
  
  $query = $update['callback_query'];
  $queryid = $query['id'];
  $queryUserId = $query['from']['id'];
  $queryusername = $query['from']['username'];
  $querydata = $query['data'];
  $querymsgid = $query['message']['message_id'];
  
  $inlinequery = $update['inline_query'];
  $inlineid = $inlinequery['id'];
  $inlineUserId = $inlinequery['from']['id'];
  $inlinequerydata = $inlinequery['query'];
    
  $agg = json_encode($update,JSON_PRETTY_PRINT);
   //sendMessage($chatId,$agg);
  //exit();
  
  if(isset($update['inline_query'])) {
    gestisciInlineQuery($inlineid,$inlineUserId,$inlinequerydata,$agg);
    exit();
  }
    
  //$text = strtolower($text);
  /* if($querydata == "StampaMessaggio"){
    answerQuery($queryid,"Ciao $queryusername! Come stai?!");
    exit();
  }
  if($querydata == "ModificaMessaggio"){
    editMessageText($queryUserId,$querymsgid,"HEYLA!");
    exit();
  }*/
  
	if($querydata == "vedilistaspesa"){
     $messaggio= "lista vuota";
     $Search = mysql_query("SELECT  * FROM `ListaSpesa` WHERE `chatID` = '$queryUserId'");
     while ($Row = mysql_fetch_assoc($Search)) 
		{
			 $messaggio = $Row["ID"]."_".$Row["oggetto"]; 
       	     sendMessage($queryUserId,"$messaggio");
        } 
    if(  $messaggio == "lista vuota")
    	{
		     sendMessage($queryUserId,"$messaggio");
        } 
    exit();
  }
  if($querydata == "inseriscioggetto"){
        sendMessage($queryUserId,"Per aggiungere oggetto scrivi @@oggetto");
       	exit();
  }
  if($querydata == "cancellaoggetto"){
        sendMessage($queryUserId,"Per cancellare oggetto scrivi ##numero");
       	exit();
  }
  if(strpos($text,"@@") === 0){
   		$str = substr ($text,2, strlen($text)-2);	
        $q = mysql_query("INSERT INTO `ListaSpesa` (`chatID`, `oggetto`, `ID`) VALUES ('$chatId', '$str', NULL)");
    	exit();
  }
  if(strpos($text,"##") === 0){
  		$pos = substr ($text,2, strlen($text)-2);
   		sendMessage($chatId,"cancello numero : ".$pos);
  		$q = mysql_query("DELETE  FROM ListaSpesa WHERE chatID = $chatId AND ID = $pos ");
   		exit();
  }
/* if(strpos($text,"+")!==false){
         sendMessage($chatId,eval('return '.$text.';'));
         exit();
   }
*/  
 // $esempiotastierainline = '[{"text":"url","url":"http://www.google.it"},{"text":"Inline","switch_inline_query":"Ciao!"}],
  	//[{"text":"callbackdata stampmess","callback_data":"StampaMessaggio"},{"text":"callbackdata Modifica Messaggio","callback_data":"ModificaMessaggio"}]';
 
 $menuspesa = '[{"text":"Vedi lista spesa","callback_data":"vedilistaspesa"},
                    {"text":"Aggiungi Oggetto con @@oggetto","callback_data":"inseriscioggetto"}],
                    [{"text":"Cancella Oggetto con ##numero","callback_data":"cancellaoggetto"}]';
 $tastierabenvenuto = '["Bene"],["Listaspesa"]';
           
 switch($text){
    case "/start":
        sendMessage($chatId,"Weyla!");
      	sendMessage($chatId,"Ciao <b>$nome</b>! Come stai?",$tastierabenvenuto,"fisica");
        break;
    case "Bene":
        sendMessage($chatId,"Ottimo!");
        break;
    case "Listaspesa":
 	    sendMessage($chatId,"Test tastiera Inline!",$menuspesa,"inline");
        break;
    default:
      $tastierabenvenuto = '["Bene"],["Tu?"],["Listaspesa"]';
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
  
  function answerQuery($callback_query_id,$text){
    $url = $GLOBALS[website]."/answerCallbackQuery?callback_query_id=$callback_query_id&text=".urlencode($text);
    file_get_contents($url);
  }
  
  function editMessageText($chatId,$message_id,$newText)
  {
    $url = $GLOBALS[website]."/editMessageText?chat_id=$chatId&message_id=$message_id&parse_mode=HTML&text=".urlencode($newText);
    file_get_contents($url);
  }
  
  function gestisciInlineQuery($ilqueryId,$ilchatId,$ilquerydata,$agg)
  {
   /*
       $infoUtente = "<b>Ciao! Io sono io </b>\n\nIl mio chatId è <code>$ilchatId</code>\nIl mio nome è io\nIl mio cognome è io";
       $risultati=[[
          "type" => "article",
          "id" => "0",
          "title" => "Titolo del Result",
          "input_message_content" => array("message_text" => "Testo del Result", "parse_mode" => "HTML"),
          "reply_markup" => array("inline_keyboard" => [[array("text" => "CLICCA QUI","url" => "yt.alexgaming.me")],[array("text" => "CLICCA QUI","callback_data" => "StampaMessaggio")]]),
          "description" => "Descrizione del result",
          ],
          [
              "type" => "article",
              "id" => "1",
              "title" => "Invia le tue informazioni",
              "input_message_content" => array("message_text" => "$infoUtente", "parse_mode" => "HTML"),
              "reply_markup" => array("inline_keyboard" => [[array("text" => "CLICCA QUI","url" => "yt.alexgaming.me")],[array("text" => "CLICCA QUI","callback_data" => "StampaMessaggio")]]),
              "description" => "Descrizione del result",
              ],
      ]; 
      */
          $risultati=[[
          "type" => "article",
          "id" => "0",
          "title" => "Titolo del Result",
          "input_message_content" => array("message_text" => "Testo del Result", "parse_mode" => "HTML"),
          ],
      ]; 
      $risultati = json_encode($risultati,true);
      $url = $GLOBALS[website]."/answerInlineQuery?inline_query_id='$ilqueryId'&results=$risultati";
      //&switch_pm_text=Vai al Bot&switch_pm_parameter=123
      file_get_contents($url);
      sendMessage($ilchatId,$ilquerydata);
  }
?>
