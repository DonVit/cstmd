<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class SendMessagesWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Send Messages");
		$this->setLogoTitle("Send Messages");
		
		$this->show();		
	}
	function show($html=""){
		$out='<div id="container">';
		//$out.='<div id="left">my left';
		//$out.='</div>';
		$out.='<div id="center" style="width:100%;">';
		$out.=$this->getSendMessages();
		$out.='</div>';		
		//$out.='<div id="right"> my right';
		//$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}

	function getSendMessages(){
		if (!isset($this->session)) {
			//need to set to last session
			$this->session=0;	
		}		
		$sql='SELECT ads_messageuser.id, email, title,text FROM `ads_messageuser` inner join ads_user on `ads_messageuser`.`ads_user_id`=ads_user.id inner join ads_message on `ads_messageuser`.`ads_message_id`=ads_message.id where sent=0';		
		$ms=DBManager::doSql($sql);
		$out='<h1>Send Messages</h1>';		
		$out.='<table class="grid">';
		$out.='<tr><th class="gridth">email</th><th>sent</th></tr>';
		if (count($ms)!=0){
			$i=0;
			foreach($ms as $m){
				$i++;
				if (Mail::send_email($m->email,$m->title, $m->text)){
					$mu=new AdsMessageUser();
					$mu->loadbyid($m->id);
					$mu->sent=1;
					$mu->save();
					$out.='<tr class="gridtr'.($i & 1).'"><td class="gridtd">'.$m->email.'</td><td class="gridtd">yes</td></td></tr>';
				} else {
					$out.='<tr class="gridtr'.($i & 1).'"><td class="gridtd">'.$m->email.'</td><td class="gridtd">no</td></td></tr>';
				}
			}
		}
		$out.='</table>';	
		return $out;		
	}		
}
$n=new SendMessagesWebPage();
?>
