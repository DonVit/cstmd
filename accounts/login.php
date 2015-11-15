<?php

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
		//$this->setCSS("styles/login.css");
		$this->setTitle("Autentificare");
		$this->setLogoTitle("CONT PERSONAL");
		Logger::setLogs(User::getCurrentUser());
		//$this->setL
		if (!empty($_POST)){
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
			} else {
				$this->errormessage="Toate cimpurile obligatorii !";
			}
		}
		
		$this->create();		
	}

	function actionDefault(){
		$this->setCenterContainer($this->getLogInForm());
		$this->showIn3Columns();
	}

	function getLogInForm(){
			
		$out='<form method="post" name="loginform" class="form-horizontal">';
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="email">Email:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="email" type="email" class="form-control" id="email" placeholder="Email" value="'.$this->email.'" required>';
		$out.='</div>';
		$out.='</div>';
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label">Parola:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="password" type="password" class="form-control" id="password" placeholder="Password" value="" required>';
		$out.='</div>';
		$out.='</div>';		
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label">Valideaza:</label>';		
		$out.='<div class="col-sm-8">';
		$out.='<div class="input-group">';
		$out.='<input id="validationcode" type="text" name="validationcode" class="form-control" placeholder="Codul din imagine" required>';
		$out.='<div class="input-group-addon"><img id="validationimage" class="center-block" src="'.Config::$mainsite.'/validationimage.php"/></div>';
		$out.='</div>';
		$out.='</div>';
		$out.='</div>';
		
		if (!empty($this->errormessage)){
			$out.='<div class="alert alert-danger">';
			$out.=$this->errormessage;
			$out.='</div>';
		}
		
		$out.='<div class="form-group">';
		$out.='<div class="col-sm-12" >';
		$out.='<input name="Login" type="submit" class="btn btn-default pull-right" value="Login">';
		$out.='</div>';
		$out.='</div>';

		$out.='</form>';
		
		$outb='   <a href="register.php">Inregistreaza-te!</a> | <a href="password.php">Ai uitat parola ?</a>';

		return $this->getGroupBoxWizard("Autentificare",$out,$outb);
	}
}

$n=new LoginWebPage();

?>
