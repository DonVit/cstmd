<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
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
		//$this->setCSS("styles/login.css");
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
		//$this->setTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLogoTitle($this->getConstants("IndexLocationsWebPageRaioaneTitle"));
		//$this->setLeftContainer($this->getGroupBoxH3("",$this->getAdd()));
		//$this->setLeftContainer($this->getGroupBoxH3("",$this->getMenu()));
		//$this->setLeftContainer($this->getGroupBoxH3("",$this->getRssLink()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getCompanyList()));
		//$this->setLeftContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getRaions($this->raion)));				
		//$this->setCenterContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageRaioaneTitle"),$this->getMain()));
		$this->setRightContainer($this->getLogInForm());
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
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
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function getLogInForm(){
		//$out='<div id="form_container" class="form_container">';
		//$out.='<div id="form" class="form">';
		//$out.='<div id="formtitle" class="form_header">';
		//$out.='Autentificare';
		//$out.='</div>';
		$out='<form method="post" name="loginform" role="form" class="pure-form pure-form-aligned">';
		$out.='<fieldset>';
		$out.='<legend>A Stacked Form</legend>';
		$out.='<div class="pure-control-group">';
		$out.='<label for="email">Email:</label>';
		$out.='<input id="email" name="email" type="text" class="form-control" value="'.$this->email.'" required>';
		$out.='</div>';
		$out.='<div class="pure-control-group">';
		$out.='<label for="password">Parola:</label>';
		$out.='<input id="password" name="password" type="password" class="form-control" value="" required>';
		$out.='</div>';
		$out.='<div class="pure-control-group">';
		$out.='<label for="validationcode">Valideaza:</label>';
		$out.='<input id="validationcode" type="text" name="validationcode" class="form-control" required><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" class="img-thumbnail"></img>';
		$out.='</div>';
		$out.='<div class="pure-control-group">';
		$out.=$this->errormessage;
		$out.='</div>';		
		$out.='<div class="pure-control-group">';
		$out.='<label ></label>';
		$out.='<input name="Login" type="submit"  class="pure-button pure-button-primary" value="Login">';
		$out.='</div>';
		$out.='<div class="pure-control-group">';
		$out.='   <a href="register.php">Inregistreaza-te!</a> | <a href="password.php">Ai uitat parola ?</a>';
		$out.='</div>';		
		$out.='</fieldset>'; 
		$out.='</form>';
		//$out.='</div>';
		//$out.='</div>';
		return $this->getGroupBoxWizard("Autentificare",$out,"");
	}
}

$n=new LoginWebPage();

?>
