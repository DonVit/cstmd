<?php
class Comment extends DBManager {
	public $id;
	public $parent_id;
	public $user_id;
	public $item_type;
	public $item_id;
	public $name='';
	public $phone='';
	public $email='';
	public $web='';
	public $comment;
	public $valid;
	public $date;

	function __construct() {
	}
 
	public function getAproveLink(){
		return $this->getUrlWithSpecialCharsConverted(Config::$mainsite."/comments.php","action=validate&id=".$this->id);
	}
	public function getLink(){
		$link=$this->getUrlWithSpecialCharsConverted(Config::$mainsite."/index.php");
		if ($this->item_type=="n"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewnews&id=".$this->item_id);
		}
		if ($this->item_type=="p"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$imagessite."/index.php","action=viewimage&id=".$this->item_id);
		}
		if ($this->item_type=="r"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewraion&id=".$this->item_id);
		}	
		if ($this->item_type=="l"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$locationssite."/index.php","action=viewlocalitate&id=".$this->item_id);
		}
		if ($this->item_type=="m"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$mapssite."/index.php","action=viewmap&id=".$this->item_id);
		}
		if ($this->item_type=="c"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$companiesite."/index.php","action=viewcompany&id=".$this->item_id);
		}		
		if ($this->item_type=="i"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$companiesite."/companies.php","action=viewcompany&id=".$this->item_id);
		}		
		if ($this->item_type=="d"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$dictionarsite."/index.php","action=viewdictionar&id=".$this->item_id);
		}
		if ($this->item_type=="x"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$primariisite."/index.php","action=viewraion&id=".$this->item_id);
		}
		if ($this->item_type=="y"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$primariisite."/index.php","action=viewprimarie&id=".$this->item_id);
		}
		if ($this->item_type=="f"){
			$link=$this->getUrlWithSpecialCharsConverted(Config::$numesite."/index.php","action=viewnume&id=".$this->item_id);
		}	
		return $link;						
	}

	public static function getComments($webpage,$item_type,$item_id){
		$c=new Comment();
		$c->item_type=$item_type;
		$c->parent_id=0;
		$c->user_id=User::getCurrentUser();
		$c->item_id=$item_id;
				
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

		$out.='<div class="fb-like" data-send="false" data-width="100%" data-show-faces="false"></div>';		
		$out.='<div class="fb-comments" data-href="'.$c->getLink().'" data-num-posts="10" data-width="100%"></div>';		
		// fb end
		
		$errormsg='';
		$infomsg='';
		if (!empty($_POST['commentformpost'])){
			$recaptcha = new \ReCaptcha\ReCaptcha(Config::$privatekey);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			if ($resp->isSuccess()){
				$isValidCommnet = System::hasPrefix($webpage->comment,'<p>');
				if(isset($webpage->name)&&$isValidCommnet&&isset($webpage->email)){
					$c->name=$webpage->name;
					$c->phone=$webpage->phone;
					$c->email=$webpage->email;
					$c->web=$webpage->web;
					$c->comment=$webpage->comment;
					$c->date=System::getCurentDateTime();
					$c->valid=0;
					$c->save();
					$webpage->comment="";		
					$infomsg="Mesajul a fost salvat si urmeaza a fi verificat de operator !";	
					Mail::send_comment_email($c);
				} else {
					$errormsg="Completeaza cimpurile obligatorii!";
				}
			} else {
				$errormsg="Code de validare gresit!";
			}
		}

		$cs=$c->getAll('valid=1 and item_type=\''.$item_type.'\' and item_id='.$item_id,"`date`");	
		$url=$webpage->getServerName().$webpage->getRequestURI();
		if (count($cs)!=0){
			$out.='<div>';
			$i=1;
			foreach($cs as $c){
				$out.='<div class="newscomment_head" style="font-size:85%;">';
				$out.='<span><a name="'.$i.'"><a><a href="'.$webpage->getRequestURI().'#'.$i.'"># '.$i.'</a> </span>';
				if ($c->web==""){
					$out.='<span><b>Nume:</b> '.System::getHTML($c->name).' </span>';
				} else {
					$out.='<span><b>Nume:</b> <a href="'.System::getValidUrl($c->web).'">'.System::getHTML($c->name).'</a> </span>';
				}
				if ($c->email!=""){
					$out.='<span><b>Email:</b> '.$c->email.' </span>';
				}
				if ($c->phone!=""){
					$out.='<span><b>Tel:</b> '.$c->phone.' </span>';
				}
				$out.='<span><b>Data:</b> '.$c->date.' </span>';				
				$out.='</div>';
				$out.='<div class="newscomment_body">'.$c->comment.'</div>';								
				$i=$i+1;		
			}
			$out.="</div>";
		} else {
			$out.='<div><div class="newscomment_body">Nu exista</div></div>';
		}
		$out.='<div class="container groupboxheader">';
		$out.='<a id="99"></a><h3>Comentează:</h3>';		
		$out.='</div>';
		$out.='<form method="post" name="commentform" action="'.$webpage->getRequestURI().'#99">';
		
		$out.='<table style="width:100%;">';
		$out.='<tr><td>Nume:<span style="color:red">*</span></td><td><input type="text" name="name" style="width:98%;" value="'.((isset($webpage->name))?($webpage->name):'').'"></td></tr>';
		$out.='<tr><td>Telefon:</td><td><input type="text" name="phone" style="width:98%;" value="'.((isset($webpage->phone))?($webpage->phone):'').'"></td></tr>';
		$out.='<tr><td>Email:<span style="color:red">*</span></td><td><input type="email" name="email" style="width:98%;" value="'.((isset($webpage->email))?($webpage->email):'').'"></td></tr>';
		$out.='<tr><td>Web Site:</td><td><input type="url" name="web" style="width:98%;"  value="'.((isset($webpage->web))?($webpage->web):'').'"></td></tr>';
		$out.='<tr><td>Comentariu:<span style="color:red">*</span></td><td><textarea id="comment" name="comment" style="width:98%;height:100px;">'.((isset($webpage->comment))?($webpage->comment):'').'</textarea></td></tr>';
        //$out.='<textarea name="text">'.$this->currentnews->text.'</textarea>';
        $out.='<script>            CKEDITOR.replace( \'comment\' );        </script>';				
		$out.='<script type="text/javascript">';
		$out.='                lang : \'ru\', // Unavailable while writing this code (just for audio challenge)';
		$out.='                theme : \'red\',';
		$out.='        };';
		$out.='</script>';
		
		$out.='<tr><td>Validare:<span style="color:red">*</span></td><td>';
		$out.='<div class="g-recaptcha" data-sitekey="'.Config::$publickey.'"></div>';
		$out.='</td></tr>';	
		$out.='<tr><td></td><td><input type="submit" name="commentformpost" value="Postează"></td></tr>';
		if ($errormsg!=""){
			$out.='<tr><td></td><td><br><span style="color:red;font-weight: bold;font-size: 16px;margin: 20px;"">'.$errormsg.'</span><br><br></td></tr>';
		}
		if ($infomsg!=""){
			$out.='<tr><td></td><td><br><span style="color:green;font-weight: bold;font-size: 16px;margin: 20px;">'.$infomsg.'</span><br><br></td></tr>';
		}			
		$out.='<tr><td><br>Note:</td><td><br> 1. Cimpul marcat cu <span style="color:red">*</span> este obligator.<br> 2. Comentariul va fi publicat dupa ce va fi verificat de operator!</td></tr>';
		$out.='</table>';
		$out.='</form>';
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
					$out.='<span><b> Nume:</b> <a href="'.System::getValidUrl($c->web).'">'.System::getHTML($c->name).'</a></span>';
				}
				$out.='<span><b> Data:</b> '.$c->date.' </span>';
				$out.='</div>';
				$out.='<div class="newscomment_body">';
				$out.=$c->comment;
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
