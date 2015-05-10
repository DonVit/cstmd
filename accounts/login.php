<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once(__DIR__ . '/../main/loader.php');
 
class LoginWebPage extends MainWebPage {
	public $errormessage="";
	public $email='';
	function __construct(){
		parent::__construct();
		//check if user is login
		$u=User::getCurrentUser();
		if ($u->id!=0){
			$this->redirect("index.php");
		}
		$this->setCSS("styles/login.css");
		$this->setTitle("Autentificare");
		$this->setLogoTitle("CONT PERSONAL");
		Logger::setLogs(User::getCurrentUser());
		//$this->setL
		if (isset($this->email)&&isset($this->password)&&isset($this->validationcode)){
			if (User::getValidationCode()==$this->validationcode){
				$u=new User;
				$o=$u->getAll("email='".$this->email."' and password='".$this->password."'","");
				if (!is_null($o)){
					User::setCurrentUser($o[0]);
					$this->redirect("index.php");
				} else {
					$this->errormessage="Email/Parola incorecta !";
				}
			} else {
				$this->errormessage="Cod de validare gresit !";
			}
		}
		$this->create();		
	}
	function _show($html=""){
		$out='<div id="container">';
		$out.=$this->setLogInForm();
		$out.='</div>';
		MainWebPage::show($out);
	}
	function actionDefault(){
		$this->setRightContainer($this->getLogInForm());
		$this->show();
	}		
	function show($out=''){
		$out="";
		$out.='<div id="container" style="padding-top:4px;">';
		//$out.='<div id="left" class="container left" style="width:198px;">';
		//$out.=$this->getLeftContainer();
		//$out.='</div>';		
		//$out.='<div id="center" class="container center" style="width:798px;">';
		//$out.=$this->getCenterContainer();
		//$out.='</div>';
		$out.='<div id="center" class="container center" style="width:500px;float: none;margin: 0 auto;padding:20px;">';
		$out.=$this->getRightContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function getLogInForm(){
		//$out='<div id="form_container" class="form_container">';
		//$out.='<div id="form" class="form">';
		//$out.='<div id="formtitle" class="form_header">';
		//$out.='Autentificare';
		//$out.='</div>';
		$out='<form method="post" name="loginform">';
		$out.='<div id="formcontrols">';
		$out.='<div class="form_row">';
		$out.='<label class="label" >Email:</label>';
		$out.='<input name="email" type="text" class="input" value="'.$this->email.'">';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label class="label">Parola:</label>';
		$out.='<input name="password" type="password" class="input" value="">';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label class="label">Valideaza:</label>';
		$out.='<input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php"/>';
		$out.='</div>';
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>';
		$out.='</div>';
		$outb='<div id="form_row_button" class="form_footer">';
		$outb.='   <div class="form_footer_links"><a href="register.php">Inregistreaza-te!</a> | <a href="password.php">Ai uitat parola ?</a></div>';
		$outb.='   <div class="form_footer_button"><input name="Login" type="submit" class="button" value="Login"></div>';
		$outb.='   <div style="clear: both;"></div>';
		$outb.='</div>'; 
		$outb.='</form>';
		//$out.='</div>';
		//$out.='</div>';
		return $this->getGroupBoxWizard("Autentificare",$out,$outb);
	}
}

$n=new LoginWebPage();

?>
