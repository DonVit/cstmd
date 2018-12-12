<?php
require_once(__DIR__ . '/../main/loader.php');
 
class PasswordWebPage extends MainWebPage {
	private $html="";
	private $errormessage="";
	function __construct(){
		parent::__construct();
		$this->setCSS("styles/password.css");
		$this->setTitle("Recuperare Parola");
		$this->setLogoTitle("CONT PERSONAL");
		if (isset($this->email)&&isset($this->validationcode)){
			if (User::getValidationCode()==$this->validationcode){
				$u=new User;
				$o=$u->getAll("email='".$this->email."'","");
				if (!is_null($o)){
					Mail::send_password_email($o[0]->email,$o[0]->password);
					$this->setCenterContainer($this->setInfoForm());
				} else {
					$this->errormessage="Utililzator cu emailul [".$this->email."] nu exista !";
					$this->setCenterContainer($this->setPasswordForm());
				}
			} else {
				$this->errormessage="Cod de validare gresit !";
				$this->setCenterContainer($this->setPasswordForm());
			}
		} else {
			$this->setCenterContainer($this->setPasswordForm());
		}
		$this->create();
	}
function actionDefault(){
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
		$out.=$this->html;
		$out.='</div>';
		MainWebPage::show($out);
	}

	function setPasswordForm(){
		$out='<form method="post" name="passwordform">';
		$out.=' <div id="formcontrols">';
		$out.='  <div class="form_row">';
		$out.='   <label id="label_email">Email:</label>';
		$out.='   <input name="email" type="text" class="input" value="'.(isset($this->email)?$this->email:'').'">';
		$out.='  </div>';
		$out.='  <div class="form_row">';
		$out.='   <label id="label_email">Valideaza:</label>';
		$out.='   <input id="validationcode" type="text" name="validationcode" class="input"><img src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle">';
		$out.='  </div>';
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>'; 
		$out.='  </div>';
		$outb='  <div id="form_row_button" class="form_footer" style="text-align: right;">';
		$outb.='   <input name="submit" type="submit" class="button" value="Trimite Parola">';
		$outb.=' </div>';
		$outb.=' </form>';
		return $this->getGroupBoxWizard("Ai uitat parola ?",$out,$outb);
	}
	function setInfoForm(){
		$out=' <div id="formcontrols">';
		$out.='  <div class="form_row">';
		$out.='   Parola a fost expediata pe email, vezi emailul si incearca sa faci login';
		$out.='  </div>';
		$out.=' </div>';
		$outb='  <div id="form_row_button" class="form_footer" style="text-align: right;">';
		$outb.='   <a href="login.php" class="link_button">Login</a>';
		$outb.=' </div>';
		return $this->getGroupBoxWizard("Ai uitat parola ?",$out,$outb);
	}
}
$n=new PasswordWebPage();

?>
