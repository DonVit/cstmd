<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class SettingsWebPage extends MainWebPage {
	private $errormessage="";
	function __construct(){
		parent::__construct();
		//check if user is login
		$u=User::getCurrentUser();
		if ($u->id==0){
			$this->redirect("login.php");
		}
		$this->setCSS("styles/settings.css");
		$this->setTitle("Setari");
		$this->setLogoTitle("Setari");
		if (!is_null($this->name)&&!is_null($this->email)&&!is_null($this->password)){
			$u=User::getCurrentUser();
			$t=new User;
			$o=$t->getAll("id!='".$u->id."' and email='".$this->email."'","");
			if (is_null($o)){
				$t=$u;
				$t->name=$this->name;
				$t->name=$this->name;
				$t->url=$this->url;
				$t->phone=$this->phone;
				$t->mobile=$this->mobile;
				$t->email=$this->email;
				$t->password=$this->password;
				$t->note=$this->note;
				$t->save();
				User::setCurrentUser($t);
				//Mail::send_registration_email($t->email,$t->password);

			} else {
				$this->errormessage='Erroare, utilizator cu emailul ['.$this->email.'] exista deja ! Vrei sa recuperzi parola ? intra aici <a href="password.php?email='.$this->email.'">Recupereaza Parola</a>';
			}
		}
		$u=User::getCurrentUser();
		$this->name=$u->name;
		$this->url=$u->url;
		$this->phone=$u->phone;
		$this->mobile=$u->mobile;
		$this->email=$u->email;
		$this->password=$u->password;
		$this->repassword=$u->password;
		$this->note=$u->note;
		
		$this->show();		
	}
	function show($html=""){
		$out='<div id="container">';
		$out.=$this->setSettingsForm();
		$out.='</div>';
		MainWebPage::show($out);
	}
	function setSettingsForm(){
		$out='<div id="form_container" class="form_container">';
		$out.='<div id="form" class="form">';
		$out.='<div id="form_title" class="form_header">';
		$out.='Setari';
		$out.='</div>';
		$out.='<form id="frmRegister" name="frmRegister" method="POST" action="settings.php">';
		$out.=' <div id="formcontrols">';
		$out.='<div class="form_row">';
		$out.='<label>Nume:</label>';
		$out.='<input id="name" type="text" name="name" class="input" value="'.$this->name.'"> ! Obligator';			
		$out.='</div>';    
		$out.='<div class="form_row">';
		$out.='<label>Url:</label>';
		$out.='<input type="text" name="url" class="input" value="'.$this->url.'">';	
		$out.='</div>';	    				
		$out.='<div class="form_row">';
		$out.='<label>Fix:</label>';
		$out.='<input type="text" name="phone" class="input" value="'.$this->phone.'">';		
		$out.='</div>';   		    				
		$out.='<div class="form_row">';
		$out.='<label>Mobil:</label>';
		$out.='<input type="text" name="mobile" class="input" value="'.$this->mobile.'">';		
		$out.='</div>';    
		$out.='<div class="form_row">';
		$out.='<label>Email:</label>';
		$out.='<input id="email" type="text" name="email" class="input" value="'.$this->email.'"> ! Obligator';		
		$out.='</div>';   
		$out.='<div class="form_row">';
		$out.='<label>Parola:</label>';
		$out.='<input id="password" name="password" type="password" class="input" value="'.$this->password.'"> ! Obligator';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label>Reintrodu Parola:</label>';
		$out.='<input id="repassword" name="repassword" type="password" class="input" value="'.$this->password.'"> ! Obligator';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label>Note:</label>';
		$out.='<textarea id="note" name="note" class="text">'.$this->note.'</textarea>';			
		$out.='</div>';  
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>';
		$out.='</div>';
		$out.='<div id="form_row_button" class="form_footer">';
		$out.='<input name="salveaza" type="button" class="button" value="Salveaza" onclick="javascript:SettngsOnSave();">';
		$out.='</div>';
		$out.='</form>';
		$out.='</div>';
		$out.='</div>';
		return $out;
	}
}
$n=new SettingsWebPage();

?>
