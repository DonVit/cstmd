<?php
require_once(__DIR__ . '/../main/loader.php');
 
class RegisterWebPage extends MainWebPage {
	public $errormessage='';
	function __construct(){
		parent::__construct();
		//check if user is loginR
		$u=User::getCurrentUser();
		if ($u->id!=0){
			$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		}		
		$this->setCSS("styles/register.css");
		$this->setTitle("Inregistrare");
		$this->setLogoTitle("CONT PERSONAL");
		if (isset($this->name)&&isset($this->email)&&isset($this->password)&&isset($this->validationcode)){
			if (User::getValidationCode()==$this->validationcode){
				$u=new User;
				$o=$u->getAll("email='".$this->email."'","");
				if (is_null($o)){
					$u=new User;
					$u->role_id=1;
					$u->tipcontact_id=1;
					$u->tipcompanie_id=0;
					$u->name=$this->name;
					$u->name=$this->name;
					$u->url=$this->url;
					$u->phone=$this->phone;
					$u->mobile=$this->mobile;
					$u->email=$this->email;
					$u->password=$this->password;
					$u->note=$this->note;
					$u->save();
					User::setCurrentUser($u);
					Mail::send_registration_email($u->email,$u->password);
					$this->redirect("index.php");
				} else {
					$this->errormessage='Erroare, utilizator cu emailul ['.$this->email.'] exista deja ! Vrei sa recuperzi parola ? intra aici <a href="password.php?email='.$this->email.'">Recupereaza Parola</a>';
				}
			} else {
				$this->errormessage="Cod de validare gresit !";
			}
		}
		$this->create();		
	}
function actionDefault(){
		$this->setCenterContainer($this->setRegisterForm());		
		$this->show();
	}		
	function show($out=''){
		$out="";
		$out.='<div id="container" style="padding-top:4px;">';
		$out.='<div id="center" class="container center" style="width:500px;float: none;margin: 0 auto;padding:20px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}		
	function _show($html=""){
		$out='<div id="container">';
		$out.=$this->setRegisterForm();
		$out.='</div>';
		MainWebPage::show($out);
	}
	function setRegisterForm(){
		$out='<form id="frmRegister" name="frmRegister" method="POST" action="register.php">';
		$out.=' <div id="formcontrols">';
		$out.='<div class="form_row">';
		$out.='<label>Nume:</label>';
		$out.='<input id="name" type="text" name="name" class="input" value="'.(isset($this->name)?$this->name:'').'"> ! Obligator';			
		$out.='</div>';    
		$out.='<div class="form_row">';
		$out.='<label>Url:</label>';
		$out.='<input type="text" name="url" class="input" value="'.(isset($this->url)?$this->url:'').'">';	
		$out.='</div>';	    				
		$out.='<div class="form_row">';
		$out.='<label>Fix:</label>';
		$out.='<input type="text" name="phone" class="input" value="'.(isset($this->phone)?$this->phone:'').'">';		
		$out.='</div>';   		    				
		$out.='<div class="form_row">';
		$out.='<label>Mobil:</label>';
		$out.='<input type="text" name="mobile" class="input" value="'.(isset($this->mobile)?$this->mobile:'').'">';		
		$out.='</div>';    
		$out.='<div class="form_row">';
		$out.='<label>Email:</label>';
		$out.='<input id="email" type="text" name="email" class="input" value="'.(isset($this->email)?$this->email:'').'"> ! Obligator';		
		$out.='</div>';   
		$out.='<div class="form_row">';
		$out.='<label>Parola:</label>';
		$out.='<input id="password" name="password" type="password" class="input"> ! Obligator';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label>Reintr. Parola:</label>';
		$out.='<input id="repassword" name="repassword" type="password" class="input"> ! Obligator';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label>Note:</label>';
		$out.='<textarea id="note" name="note" class="text">'.(isset($this->note)?$this->note:'').'</textarea>';			
		$out.='</div>';  
		$out.='<div class="form_row">';
		$out.='<label>Valideaza:</label>';	
		$out.='<input id="validationcode" type="text" name="validationcode" class="input"><img src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"> ! Obligator';	
		$out.='</div>';
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>';
		$out.='</div>';
		$outb='<div id="form_row_button" class="form_footer" style="text-align: right;">';
		$outb.='<input name="salveaza" type="button" class="button" value="Inregistreaza-te" onclick="javascript:RegisterOnSave();">';
		$outb.='</div>';
		$outb.='</form>';
		return $this->getGroupBoxWizard("Inregistrare",$out,$outb);
	}
}
$n=new RegisterWebPage();

?>
