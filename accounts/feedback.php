<?php
require_once(__DIR__ . '/../main/loader.php');
 
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
		
		$this->setTitle("Feedback");
		$this->setLogoTitle("FEEDBACK");
		
		if (!empty($_POST)){
			if (isset($this->name)&&isset($this->email)&&isset($this->comment)&&isset($this->validationcode)){
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
					$c->comment=$this->comment;
					$c->date=System::getCurentDateTime();
					$c->save();
					$this->name="";
					$this->email="";
					$this->phone="";
					$this->comment="";
					//$this->errormessage="Messajul a fost trimis !";
					$this->setCenterContainer($this->getThankYou());
					Mail::send_comment_email($c);
				} else {
					$this->errormessage="Cod de validare gresit !";
					$this->setCenterContainer($this->getFeedbackForm());
				}
			} else {
				$this->errormessage="Toate cimpurile sunt obligatorii !";
				$this->setCenterContainer($this->getFeedbackForm());
			}
		} else {
			$this->setCenterContainer($this->getFeedbackForm());
		}
		$this->create();		
	}
	function actionDefault(){
				
		$this->showIn3Columns();
	}		

	function getFeedbackForm(){

		$out='<form method="post" name="fbform" class="form-horizontal">';
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="name">Nume:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="name" type="text" class="form-control" id="name" placeholder="Nume" value="'.(isset($this->name)?$this->name:'').'" required>';
		$out.='</div>';
		$out.='</div>';		 
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="email">Email:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="email" type="email" class="form-control" id="email" placeholder="Email" value="'.(isset($this->email)?$this->email:'').'" required>';
		$out.='</div>';
		$out.='</div>';
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label" for="phone">Telefon:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<input name="phone" type="text" class="form-control" id="phone" placeholder="Fix" value="'.(isset($this->phone)?$this->phone:'').'" >';
		$out.='</div>';
		$out.='</div>';			
		
		$out.='<div class="form-group">';
		$out.='<label class="col-sm-4 control-label">Text:</label>';
		$out.='<div class="col-sm-8">';
		$out.='<textarea id="note" name="comment" class="form-control" placeholder="Text" required>'.(isset($this->comment)?$this->comment:'').'</textarea>';
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
		$out.='<input name="Trimite" type="submit" class="btn btn-default pull-right" value="Trimite">';
		$out.='</div>';
		$out.='</div>';		
		$out.='</form>';

		return $this->getGroupBoxWizard("Feedback",$out);
	}
	function getThankYou(){
	
		$out='<div class="alert alert-success">';
		$out.='Multumim. Messajul a fost trimis !';
		$out.='</div>';
	
		return $this->getGroupBoxWizard("Mesaj Trimis!",$out);
	}	
}

$n=new FeedBackWebPage();

?>
