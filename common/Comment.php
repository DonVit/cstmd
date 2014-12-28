<?php
/*
 * Created on 30 Jul 2009
 *
 */
//require_once 'lib/fck/fckeditor.php';


class Comment extends DBManager {
	//public $webpage;
	public $id;
	public $parent_id;
	public $user_id;
	public $item_type;
	public $item_id;
	//public $item_id2;		
	public $name='';
	public $phone='';
	public $email='';
	public $web='';
	public $comment;
	public $valid;
	public $date;
   function __construct() {
	//$this->publickey = '6LcrnMUSAAAAALynnjZ63OqlyUu2__MLp0t4bES_'; 
	//$this->privatekey = '6LcrnMUSAAAAAKl3eUf4caIhzI2jMNbX8j2-KSFF';       
   }
   public function getWeb(){
   	if (substr($this->web,0,7)=="http://")   		
   		return $this->web;
   	else
   		return "http://".$this->web;
   }  
   public function getAproveLink(){
   		return $this->getUrl(Config::$mainsite."/comments.php","action=validate&id=".$this->id);
   }
	public function getLink(){
		$link=$this->getUrl(Config::$mainsite."/index.php");
		if ($this->item_type=="n"){
			$link=$this->getUrl(Config::$newssite."/index.php","action=viewnews&id=".$this->item_id);
		}
		if ($this->item_type=="p"){
			$link=$this->getUrl(Config::$imagessite."/index.php","action=viewimage&id=".$this->item_id);
		}
		if ($this->item_type=="r"){
			$link=$this->getUrl(Config::$locationssite."/index.php","action=viewraion&id=".$this->item_id);
		}	
		if ($this->item_type=="l"){
			$link=$this->getUrl(Config::$locationssite."/index.php","action=viewlocalitate&id=".$this->item_id);
		}
		if ($this->item_type=="m"){
			$link=$this->getUrl(Config::$mapssite."/index.php","action=viewmap&id=".$this->item_id);
		}
		if ($this->item_type=="c"){
			$link=$this->getUrl(Config::$companiesite."/index.php","action=viewcompany&id=".$this->item_id);
		}		
		if ($this->item_type=="d"){
			$link=$this->getUrl(Config::$dictionarsite."/index.php","action=viewdictionar&id=".$this->item_id);
		}
		if ($this->item_type=="x"){
			$link=$this->getUrl(Config::$primariisite."/index.php","action=viewraion&id=".$this->item_id);
		}
		if ($this->item_type=="y"){
			$link=$this->getUrl(Config::$primariisite."/index.php","action=viewprimarie&id=".$this->item_id);
		}
		if ($this->item_type=="f"){
			$link=$this->getUrl(Config::$numesite."/index.php","action=viewnume&id=".$this->item_id);
		}	
		return $link;						
	}
	public static function getComments($webpage,$item_type,$item_id){
	//public static function getComments($webpage,$item_type,$item_id,$item_id2){	

		$c=new Comment();
		$c->item_type=$item_type;
		$c->parent_id=0;
		$c->user_id=User::getCurrentUser();
		$c->item_id=$item_id;
		
		//$c->item_id2=$item_id2;		
				
		$out='';
				
		// fb start

		$out.='<div id="fb-root"></div>';
		$out.='<script>';
		$out.='window.fbAsyncInit = function() {';
		$out.='FB.init({';
		$out.='appId      : \'209032902551666\','; // App ID
		$out.='channelUrl : \'//WWW.CASATA.MD/channel.html\','; // Channel File
		$out.='status     : true,'; // check login status
		$out.='cookie     : true,'; // enable cookies to allow the server to access the session
		$out.='xfbml      : true';  // parse XFBML
		$out.='});';
		
		// Additional initialization code here
		$out.='};';
		
		// Load the SDK Asynchronously
		$out.='(function(d){';
		$out.='var js, id = \'facebook-jssdk\', ref = d.getElementsByTagName(\'script\')[0];';
		$out.='if (d.getElementById(id)) {';
		$out.='return;';
		$out.='}';
		$out.='js = d.createElement(\'script\'); js.id = id; js.async = true;';
		$out.='js.src = "//connect.facebook.net/en_US/all.js";';
		$out.='ref.parentNode.insertBefore(js, ref);';
		$out.='}(document));';
		$out.='</script>';		

		$out.='<div class="fb-like" data-send="false" data-width="580" data-show-faces="false"></div>';		
		$out.='<div class="fb-comments" data-href="'.$c->getLink().'" data-num-posts="10" data-width="580"></div>';		
		// fb end
		

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
					$c->valid=0;
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
		$cs=$c->getAll('valid=1 and item_type=\''.$item_type.'\' and item_id='.$item_id,"`date`");	
		$url=$webpage->getServerName().$webpage->getRequestURI();
		//$out='<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=112580805963&amp;xfbml=1"></script><fb:comments href="'.$webpage->getServerName().$webpage->getRequestURI().'" num_posts="2" width="500"></fb:comments>';
		//$out='<iframe src="http://www.facebook.com/plugins/like.php?href='.$url.'&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>';
		if (count($cs)!=0){
			//$out.='<div class="container groupboxtitle"><h3>Comentarii:</h3></div>';
			//$out.='<div class="container groupboxheader">';
			//$out.='<h3>Comentarii:</h3>';		
			//$out.='</div>';
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
		//$out.=$this->getGroupBoxH3("Comentarii:",$out);
		//$out.='<div class="container groupboxtitle"><h3>Comentează:</h3></div>';
		//$out.='<form method="post" name="comment_form" action="'.$webpage.'">';
		$out.='<div class="container groupboxheader">';
		$out.='<h3>Comentează:</h3>';		
		$out.='</div>';
		$out.='<form method="post" name="commentform">';
		$out.='<table style="width:100%;">';
		$out.='<tr><td>Nume:<span style="color:red">*</span></td><td><input type="text" name="name" style="width:98%;" value="'.((isset($webpage->name))?($webpage->name):'').'"></td></tr>';
		$out.='<tr><td>Telefon:</td><td><input type="text" name="phone" style="width:98%;" value="'.((isset($webpage->phone))?($webpage->phone):'').'"></td></tr>';
		$out.='<tr><td>Email:<span style="color:red">*</span></td><td><input type="text" name="email" style="width:98%;" value="'.((isset($webpage->email))?($webpage->email):'').'"></td></tr>';
		$out.='<tr><td>Web Site:</td><td><input type="text" name="web" style="width:98%;"  value="'.((isset($webpage->web))?($webpage->web):'').'"></td></tr>';
		//$out.='<tr><td>Comentariu:</td><td>';
		////$oFCKeditor2 = new FCKeditor('comment') ;
		////$oFCKeditor2->BasePath='/../../common/lib/fck/';
		//$oFCKeditor2->BasePath=Config::$mainsite.'/common/lib/fck/';
		//echo $oFCKeditor2->BasePath;
		//$oFCKeditor2->Config["CustomConfigurationsPath"] = Config::$mainsite.'/common/lib/fck/myfckconfig.js';//"/fck/lib/fck/fckcfg.js" ;
		//$oFCKeditor2->Height = "150px";
		//$oFCKeditor2->Config['DefaultLanguage']		= 'en' ;
		//$oFCKeditor->Config['AutoDetectLanguage'] = false;
		//$oFCKeditor2->ToolbarSet = 'MyToolbar';
		////$oFCKeditor2->ToolbarSet = "Basic";
		//$oFCKeditor2->Config['SkinPath'] = "/fck/lib/fck/editor/skins/silver/" ;
		////$oFCKeditor2->Value = $webpage->comment;
		////$out.=$oFCKeditor2->CreateHtml();

//<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.0r4/build/assets/skins/sam/skin.css">
//<script src="http://yui.yahooapis.com/2.8.0r4/build/yahoo-dom-event/yahoo-dom-event.js"></script> 
//<script src="http://yui.yahooapis.com/2.8.0r4/build/element/element-min.js"></script> 
//<script src="http://yui.yahooapis.com/2.8.0r4/build/container/container_core-min.js"></script>
//<script src="http://yui.yahooapis.com/2.8.0r4/build/editor/simpleeditor-min.js"></script>		
		
		
		//$out.='<span style="color:red">*</span></td></tr>';
		$out.='<tr><td>Comentariu:<span style="color:red">*</span></td><td><textarea id="comment" name="comment" style="width:98%;height:100px;">'.((isset($webpage->comment))?($webpage->comment):'').'</textarea></td></tr>';
		//$out.='<tr><td>Valideaza:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" style="vertical-align: middle;" src="'.Config::$mainsite.'/validationimage.php"></img><span style="color:red">*</span></td></tr>';		
		$out.='<script type="text/javascript">';
		//$out.='        var RecaptchaOptions = {';
		//$out.='                custom_translations : {';
		//$out.='                        instructions_visual : "Scrivi le due parole:",';
		//$out.='                        instructions_audio : "Trascrivi ci\u00f2 che senti:",';
		//$out.='                        play_again : "Riascolta la traccia audio",';
		//$out.='                        cant_hear_this : "Scarica la traccia in formato MP3",';
		//$out.='                        visual_challenge : "Modalit\u00e0 visiva",';
		//$out.='                        audio_challenge : "Modalit\u00e0 auditiva",';
		//$out.='                        refresh_btn : "Chiedi due nuove parole",';
		//$out.='                        help_btn : "Aiuto",';
		//$out.='                        incorrect_try_again : "Scorretto. Riprova.",';
		//$out.='                },';
		$out.='                lang : \'ru\', // Unavailable while writing this code (just for audio challenge)';
		$out.='                theme : \'red\',';
		$out.='        };';
		$out.='</script>';
		$out.='<tr><td>Validare:<span style="color:red">*</span></td><td>'.recaptcha_get_html($publickey).'</td></tr>';
		//$out.='<tr><td></td><td><span style="color:red">*</span> - cîmp obligator</td></tr>';
		if ($errormsg!=""){
			$out.='<tr><td><br><span style="color:red">Eroare:</span></td><td><br><span style="color:red">'.$errormsg.'</span><br></td></tr>';
		}
		$out.='<tr><td></td><td><input type="submit" name="commentformpost" value="Postează"></td></tr>';
		$out.='<tr><td><br>Note:</td><td><br> 1. Cimpul marcat cu <span style="color:red">*</span> este obligator.<br> 2. Comentariul va fi publicat dupa ce va fi verificat de operator!</td></tr>';
		$out.='</table>';
		$out.='</form>';
		//$out.='</div>';
		
		//$out.='var myEditor = new YAHOO.widget.Editor(\'comment\', {height: '300px',width: '300px',dompath: true,animate: true});';
		//$out.='myEditor.render();';
//$out.="
//<script>  
//(function() {
//    var myEditor = new YAHOO.widget.SimpleEditor('comment',{height: '250px',width: '500px',dompath: true,animate: true});
//    myEditor.render(); 
//})();
//</script>"; 		
	
		return $out;
	}	
	public static function getAllComments(){
	
		$out='';
		$c=new Comment;
		$cs=$c->getAll('valid=1','date desc','0','10');
		if (count($cs)!=0){
			$out.='<div>';
			foreach($cs as $c){
				$out.='<div class="newscomment_head" style="font-size:85%;">';
				$out.='<span><a href="'.$c->getLink().'"># '.$c->id.'</a></span>';
				if ($c->web==""){
					$out.='<span><b> Nume:</b> '.System::getHTML($c->name).'</span>';
				} else {
					$out.='<span><b> Nume:</b> <a href="'.$c->getWeb().'">'.System::getHTML($c->name).'</a></span>';
				}
				//if ($c->email!=""){
				//	$out.='<span><b>Email:</b> '.$c->email.' </span>';
				//}
				//if ($c->phone!=""){
				//	$out.='<span><b>Tel:</b> '.$c->phone.' </span>';
				//}
				$out.='<span><b> Data:</b> '.$c->date.' </span>';
				$out.='</div>';
				$out.='<div class="newscomment_body">';
				$out.=System::getHTML($c->comment);
				$out.=' <a href="'.$c->getLink().'">vezi mai mult</a>';
				$out.='</div>';
			}
			$out.="</div>";
		} else {
			$out.='<div><div class="newscomment_body">Nu exista</div></div>';
		}
		return $out;
	}
}

?>
