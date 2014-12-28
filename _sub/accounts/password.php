<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class PasswordWebPage extends MainWebPage {
	private $html="";
	private $errormessage="";
	function __construct(){
		parent::__construct();
		//$this->setCSS("styles/password.css");
		$this->setTitle("Recuperare Parola");
		//$this->setLogoTitle("Recuperare Parola");
		$this->setLogoTitle("CONT PERSONAL");
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
			//$this->setPasswordForm();
			$this->setCenterContainer($this->setPasswordForm());
		}
		$this->create();
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
		//$this->setRightContainer($this->getFeedbackForm());
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
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
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
		//$out='<div id="form_container" class="form_container">';
		//$out.='<div id="form" class="form">';
		//$out.=' <div id="form_title" class="form_header">Ai uitat parola ?</div>';
		$out='<form method="post" name="passwordform">';
		$out.=' <div id="formcontrols">';
		$out.='  <div class="form-group">';
		$out.='   <label id="label_email" for="email">Email:</label>';
		$out.='   <input id="email" name="email" type="text" class="form-control" value="'.(isset($this->email)?$this->email:'').'">';
		$out.='  </div>';
		$out.='  <div class="form-group">';
		$out.='   <label id="label_email" for="validationcode">Valideaza:</label>';
		$out.='   <input id="validationcode" type="text" name="validationcode" class="form-control"><img src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle">';
		$out.='  </div>';
		$out.='	 <div class="error">';
		$out.=$this->errormessage;
		$out.='  </div>'; 
		$out.='  </div>';
		$outb='<div id="form_row_button" class="form_footer">';
		$outb='  <div id="form_row_button" style="text-align: right;">';
		$outb.='   <input name="submit" type="submit" class="btn btn-default" value="Trimite Parola">';
		$outb.=' </div>';
		$outb.=' </div>';
		$outb.=' </form>';
		//$out.='</div>';
		//$out.='</div>';
		return $this->getGroupBoxWizard("Ai uitat parola ?",$out,$outb);
	}
	function setInfoForm(){
		$out=' <div id="formcontrols">';
		$out.='  <div class="form-group">';
		$out.='   Parola a fost expediata pe email, vezi emailul si incearca sa faci login';
		$out.='  </div>';
		$out.=' </div>';
		//$out.='</div>';
		$outb='  <div id="form_row_button" class="form-group" style="text-align: right;">';
		$outb.='   <a href="login.php">Login</a>';
		$outb.=' </div>';
		return $this->getGroupBoxH3("Ai uitat parola ?",$out,$outb);
	}
}
$n=new PasswordWebPage();

?>
