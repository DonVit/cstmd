<?php

require_once(__DIR__ . '/../main/loader.php');
 
class PasswordWebPage extends MainWebPage {
	private $html="";
	private $errormessage="";
	public $email='';
	function __construct(){
		parent::__construct();

		$this->setTitle("Recuperare Parola");
		$this->setLogoTitle("CONT PERSONAL");
		
		if (!empty($_POST)){
			if (isset($this->email)&&isset($this->validationcode)){
				if (User::getValidationCode()==$this->validationcode){
					$u=new User;
					$o=$u->getAll("email='".$this->email."'","");
					if (!is_null($o)){
						Mail::send_password_email($o[0]->email,$o[0]->password);
						//$this->setInfoForm();
						$this->setCenterContainer($this->setInfoForm());
					} else {
						$this->errormessage="Utililzator cu emailul [".$this->email."] nu exista !";
						//$this->setPasswordForm();
						$this->setCenterContainer($this->setPasswordForm());
					}
				} else {
					$this->errormessage="Cod de validare gresit !";
					//$this->setPasswordForm();
					$this->setCenterContainer($this->setPasswordForm());
				}
			} else {
				$this->errormessage="Toate cimpurile sunt obligatorii!";
				$this->setCenterContainer($this->setPasswordForm());
			}
		} else {
			$this->setCenterContainer($this->setPasswordForm());
		}
		$this->create();
	}
	
	function actionDefault(){
		$this->showIn3Columns();
	}
		
	function setPasswordForm(){

		$out='<form method="post" name="passwordform" class="form-horizontal">';
	
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="email">Email:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="email" type="email" class="form-control" id="email" placeholder="Email" value="'.$this->email.'" required>';
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
		$out.='<input name="submit" type="submit" class="btn btn-default pull-right" value="Trimite Parola">';
		$out.='</div>';
		$out.='</div>';		
		$out.=' </form>';

		$outb='   <a href="register.php">Inregistreaza-te!</a> | <a href="login.php">Login</a>';
		return $this->getGroupBoxWizard("Recupereaza Parola",$out,$outb);
	}
	function setInfoForm(){

		$out='<div class="alert alert-success">';
		$out.='Parola a fost expediata pe email, vezi emailul si incearca sa faci <a href="login.php" class="link_button">Login</a>';
		$out.='</div>';

		return $this->getGroupBoxWizard("Parola recuperata!",$out);
	}
}
$n=new PasswordWebPage();

?>
