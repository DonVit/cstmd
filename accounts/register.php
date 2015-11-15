<?php

require_once(__DIR__ . '/../main/loader.php');
 
class RegisterWebPage extends MainWebPage {
	public $errormessage='';
	//public $name='';
	//private $url='';
	function __construct(){
		parent::__construct();
		//check if user is login
		$u=User::getCurrentUser();
		if ($u->id!=0){
			$this->redirect($this->getUrl(Config::$accountssite."/index.php"));
		}		
		$this->setTitle("Inregistrare");
		$this->setLogoTitle("CONT PERSONAL");
		if (!empty($_POST)){
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
			}else {
					$this->errormessage="Completeaza cimpurile obligatorii";
			}
		}
		$this->create();		
	}
function actionDefault(){
		$this->setCenterContainer($this->setRegisterForm());		
		$this->showIn3Columns();
	}		

	function setRegisterForm(){

		$out='<form id="frmRegister" name="frmRegister" method="POST" action="register.php" class="form-horizontal">';

		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="name">Nume:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="name" type="text" class="form-control" id="name" placeholder="Nume" value="'.(isset($this->name)?$this->name:'').'" required>';
		$out.='</div>';
		$out.='</div>';		 

		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="url">Url:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="url" type="url" class="form-control" id="url" placeholder="http://mysite.com" value="'.(isset($this->url)?$this->url:'').'">';
		$out.='</div>';
		$out.='</div>';		
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="phone">Fix:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="phone" type="text" class="form-control" id="phone" placeholder="Fix" value="'.(isset($this->phone)?$this->phone:'').'" >';
		$out.='</div>';
		$out.='</div>';		

		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="mobile">Mobil:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="mobile" type="text" class="form-control" id="mobile" placeholder="Mobil" value="'.(isset($this->mobile)?$this->mobile:'').'">';
		$out.='</div>';
		$out.='</div>';

		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="email">Email:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="email" type="email" class="form-control" id="email" placeholder="Email" value="'.(isset($this->email)?$this->email:'').'" required>';
		$out.='</div>';
		$out.='</div>';

		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label">Parola:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="password" type="password" class="form-control" id="password" placeholder="Parola" value="" required>';
		$out.='</div>';
		$out.='</div>';		

		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label">Note:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<textarea id="note" name="note" class="form-control" placeholder="Nota">'.(isset($this->note)?$this->note:'').'</textarea>';
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
		$out.='<input name="salveaza" type="submit" class="btn btn-default pull-right" value="Inregistreaza-te">';
		$out.='</div>';
		$out.='</div>';		
		
		$out.='</form>';
		return $this->getGroupBoxWizard("Inregistrare",$out);
	}
}
$n=new RegisterWebPage();

?>
