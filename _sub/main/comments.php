<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class CommentsWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
				
		$this->create();
	}
	function actionDefault(){
		$this->setLogoTitle("Comments");
		$this->setTitle("Comments");

		$this->show($this->getAllComments());
	}
	function actionValidate(){
		
		if (isset($this->id)){
			$c=new Comment();
			$c->loadById($this->id);
			$c->valid=1;
			$c->save();
			Mail::send_commentaprove_email($c);
		}
		$this->redirect(Config::$mainsite.'/comments.php');
	}
	function actionDelete(){
	
		if (isset($this->id)){
			$c=new Comment();
			$c->loadById($this->id);
			$c->deleted=1;
			$c->save();			
		}
		$this->redirect(Config::$mainsite.'/comments.php');
	}	
	function actionPrivatReply(){
	
		if (isset($this->id)){
			$c=new Comment();
			$c->loadById($this->id);
			$this->setTitle("Comments");
			$this->show($this->getComments($c->id,$this,$c->item_type,$c->id));			
		} else {
			$this->setTitle("Comments");
			$this->show("Comments");
		}
	}
	function actionPublicReply(){
	
		if (isset($this->id)){
			$c=new Comment();
			$c->loadById($this->id);
			$c->deleted=0;
			$c->save();
			Mail::send_commentaprove_email($c);
		}
		$this->setTitle("Comments");
	
		$this->show("Comment was approved");
	}	
	function show($html="FeedBackWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$html;
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	public function getAllComments(){
	
		$out='';
		$c=new Comment;
		$cs=$c->getAll('','id desc','0','30');
		if (count($cs)!=0){
			$out.='<div>';
			foreach($cs as $c){
				$out.='<div class="newscomment_head" style="font-size:85%;">';
				$out.='<span><a href="'.$c->getLink().'"># '.$c->id.'</a></span>';
				if ($c->web==""){
					$out.='<span><b> Nume:</b> '.System::getHTML($c->name).'</span>';
				} else {
					$out.='<span><b> Nume:</b> <a href="'.System::getValidUrl($c->web).'">'.System::getHTML($c->name).'</a></span>';
				}
				$out.='<span><b> Data:</b> '.$c->date.' </span>';
				$out.='</div>';
				$out.='<div class="newscomment_body">';
				$out.=System::getHTML($c->comment);
				$out.='<br>';
				$out.=' <a href="'.$c->getLink().'">vezi mai mult</a>';
				if ($c->valid==0){
				$out.=' || <a href="'.Config::$mainsite.'/comments.php?action=validate&id='.$c->id.'">Publica</a>';
				}				
				$out.=' || <a href="'.Config::$mainsite.'/comments.php?action=privatreply&id='.$c->id.'">Raspunde Privat</a>';
				$out.=' || <a href="'.Config::$mainsite.'/comments.php?action=publicreply&id='.$c->id.'">Raspunde Public</a>';
				$out.=' || <a href="'.Config::$mainsite.'/comments.php?action=delete&id='.$c->id.'">Sterge</a>';						
				$out.='</div>';
				$out.='<br>';
			}
			$out.="</div>";
		} else {
			$out.='<div><div class="newscomment_body">Nu exista</div></div>';
		}
		return $out;
	}
	public function getComments($comment_id,$webpage,$item_type,$item_id){
		//public static function getComments($webpage,$item_type,$item_id,$item_id2){
	
		$c=new Comment();
		$c->parent_id=$comment_id;
		$c->user_id=User::getCurrentUser();		
		$c->item_type=$item_type;
		$c->item_id=$item_id;
	
		//$c->item_id2=$item_id2;
	
		$out='';
		
	
		$publickey = '6LcrnMUSAAAAALynnjZ63OqlyUu2__MLp0t4bES_';
		$privatekey = '6LcrnMUSAAAAAKl3eUf4caIhzI2jMNbX8j2-KSFF';
	
	
		$errormsg='';
		if (!empty($_POST['commentformpost'])){
			$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
			//if (User::getValidationCode()==$webpage->validationcode){
			if ($resp->is_valid){
				if(isset($webpage->name)&&isset($webpage->comment)&&isset($webpage->email)){
					//$c=new Comment();
					//$c->item_type=$item_type;
					//$c->item_id=$item_id;
					$c->name=$webpage->name;
					$c->phone=$webpage->phone;
					$c->email=$webpage->email;
					$c->web=$webpage->web;
					$c->comment=$webpage->comment;
					$c->date=System::getCurentDateTime();
					$c->deleted=1;
					$c->save();
					$webpage->comment="";
					Mail::send_comment_email($c);
				} else {
					$errormsg="Completeaza cimpurile obligatorii!";
				}
			} else {
				$errormsg="Code de validare gresit!";
			}
		}
		//$c=new Comment;
		$cs=$c->getAll('id=\''.$comment_id.'\' or parent_id='.$comment_id,"`date`");
		//$url=$webpage->getServerName().$webpage->getRequestURI();
		$url="";
		if (count($cs)!=0){
			$out.='<div>';
			$i=1;
			foreach($cs as $c){
				$out.='<div class="newscomment_head" style="font-size:85%;">';
				//if (($c->web=="http://")||($c->web=="")){
				$out.='<span><a name="'.$i.'"><a><a href="'.$webpage->getRequestURI().'#'.$i.'"># '.$i.'</a> </span>';
				//} else {
				//	$out.='<div class="newscomment_head" style="font-size:85%;"><a name="'.$i.'"><a><a href="'.$webpage->getRequestURI().'#'.$i.'"># '.$i.'</a> | <a href="'.$c->web.'">'.System::getHTML($c->name).'</a> | <span>'.$c->date.'</span></div><div class="newscomment_body">'.System::getHTML($c->comment).'</div>';
				//}
				if ($c->web==""){
					$out.='<span><b>Nume:</b> '.System::getHTML($c->name).' </span>';
				} else {
					$out.='<span><b>Nume:</b> <a href="'.$c->getWeb().'">'.System::getHTML($c->name).'</a> </span>';
				}
				if ($c->email!=""){
					$out.='<span><b>Email:</b> '.$c->email.' </span>';
				}
				if ($c->phone!=""){
					$out.='<span><b>Tel:</b> '.$c->phone.' </span>';
				}
				$out.='<span><b>Data:</b> '.$c->date.' </span>';
				//if ($c->web!=""){
				//	$out.='<span><b>Web:</b> '.$c->web.' </span>';
				//}
				$out.='</div>';
				$out.='<div class="newscomment_body">'.System::getHTML($c->comment).'</div>';
				$i=$i+1;
			}
			$out.="</div>";
		} else {
			$out.='<div><div class="newscomment_body">Nu exista</div></div>';
		}

		$out.='<div class="container groupboxheader">';
		$out.='<h3>Comentează:</h3>';
		$out.='</div>';
		$out.='<form method="post" name="commentform">';
		$out.='<table style="width:100%;">';
		$out.='<tr><td>Nume:<span style="color:red">*</span></td><td><input type="text" name="name" style="width:98%;" value="'.((isset($webpage->name))?($webpage->name):'').'"></td></tr>';
		$out.='<tr><td>Telefon:</td><td><input type="text" name="phone" style="width:98%;" value="'.((isset($webpage->phone))?($webpage->phone):'').'"></td></tr>';
		$out.='<tr><td>Email:<span style="color:red">*</span></td><td><input type="text" name="email" style="width:98%;" value="'.((isset($webpage->email))?($webpage->email):'').'"></td></tr>';
		$out.='<tr><td>Web Site:</td><td><input type="text" name="web" style="width:98%;"  value="'.((isset($webpage->web))?($webpage->web):'').'"></td></tr>';

		$out.='<tr><td>Comentariu:<span style="color:red">*</span></td><td><textarea id="comment" name="comment" style="width:98%;height:100px;">'.((isset($webpage->comment))?($webpage->comment):'').'</textarea></td></tr>';
		$out.='<script type="text/javascript">';
		$out.='                lang : \'ru\', // Unavailable while writing this code (just for audio challenge)';
		$out.='                theme : \'red\',';
		$out.='        };';
		$out.='</script>';
		$out.='<tr><td>Validare:<span style="color:red">*</span></td><td>'.recaptcha_get_html($publickey).'</td></tr>';
		if ($errormsg!=""){
		$out.='<tr><td><br><span style="color:red">Eroare:</span></td><td><br><span style="color:red">'.$errormsg.'</span><br></td></tr>';
		}
		$out.='<tr><td></td><td><input type="submit" name="commentformpost" value="Postează"></td></tr>';
		$out.='<tr><td><br>Note:</td><td><br> 1. Cimpul marcat cu <span style="color:red">*</span> este obligator.<br> 2. Comentariul va fi publicat dupa ce va fi verificat de operator!</td></tr>';
		$out.='</table>';
		$out.='</form>';

		return $out;
	}			
}
$n=new CommentsWebPage();

?>
