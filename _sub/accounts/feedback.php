<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class FeedBackWebPage extends MainWebPage {
	private $errormessage="";
	public $name="";
	public $email="";
	public $phone="";
	public $web="";
	public $comment="";
	function __construct(){
		parent::__construct();
		
		if (User::getCurrentUser()->id!=0){
			$this->name=User::getCurrentUser()->name;
			$this->email=User::getCurrentUser()->email;
		} 
		
		$this->setCSS("styles/login.css");
		$this->setTitle("Feedback");
		$this->setLogoTitle("FEEDBACK");
		
		if (isset($this->name)&&isset($this->email)&&isset($this->text)&&isset($this->validationcode)){
			if (User::getValidationCode()==$this->validationcode){
				$c=new Comment();
				$c->user_id=User::getCurrentUser()->id;
				$c->parent_id="";
				$c->item_type="z";
				$c->item_id="";
				$c->name=$this->name;
				$c->phone=$this->phone;
				$c->email=$this->email;
				$c->web=$this->web;
				$c->comment=$this->text;
				$c->date=System::getCurentDateTime();
				$c->save();
				$this->name="";
				$this->email="";
				$this->phone="";
				$this->comment="";
				$this->errormessage="Messajul a fost trimis !";
				Mail::send_comment_email($c);
			} else {
				$this->errormessage="Cod de validare gresit !";
			}
		} else {
			$this->errormessage="Toate cimpurile sunt obligatorii !";
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
		$this->setRightContainer($this->getFeedbackForm());
		//$this->setRightContainer($this->getGroupBoxH3($this->getConstants("IndexLocationsWebPageReferinte"),$this->getNewsCompanies()));		
		$this->show();
	}		
	function show($html=""){
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

	function getFeedbackForm(){
		//$out='<div id="form_container" class="form_container">';
		//$out.='<div id="form" class="form">';
		//$out.='<div id="formtitle" class="form_header">';
		//$out.='Feedback';
		//$out.='</div>';
		$out='<form method="post" name="fbform">';
		$out.='<div id="formcontrols">';
		$out.='<div class="form_row">';
		$out.='<label class="label" >Nume:</label>';
		$out.='<input name="name" type="text" class="input" value="'.$this->name.'">';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label class="label" >Email:</label>';
		$out.='<input name="email" type="text" class="input" value="'.$this->email.'">';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label class="label" >Telefon:</label>';
		$out.='<input name="phone" type="text" class="input" value="'.$this->phone.'">';
		$out.='</div>';		
		$out.='<div class="form_row">';
		$out.='<label class="label">Text:</label>';
		$out.='<textarea name="text" style="width:150px;height:100px;">'.$this->comment.'</textarea>';
		$out.='</div>';
		$out.='<div class="form_row">';
		$out.='<label class="label">Valideaza:</label>';
		$out.='<input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php"/>';
		$out.='</div>';
		$out.='<div class="error">';
		$out.=$this->errormessage;
		$out.='</div>';
		$out.='</div>';
		$outb='<div id="form_row_button" class="form_footer" style="text-align: right;">';
		$outb.='<input name="Trimite" type="submit" class="button" value="Trimite">';
		$outb.='</div>'; 
		$outb.='</form>';
		//$out.='</div>';
		//$out.='</div>';
		return $this->getGroupBoxWizard("Feedback",$out,$outb);
	}
}

$n=new FeedBackWebPage();

?>
