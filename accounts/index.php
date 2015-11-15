<?php
require_once(__DIR__ . '/../main/loader.php');
 
class IndexWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		//check if user is login
		$u=User::getCurrentUser();
		if ($u->id==0){
			$this->redirect("login.php");
		}

		$t="Contul Personal";
		$this->setTitle($t);

		$this->setLogoTitle("CONTUL PERSONAL");
		$this->create();		
	}
	function actionDefault(){		
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga",$this->getAddMenu()));
		$this->setCenterContainer($this->getGroupBoxH3("Lista de Anunturi",$this->getImobil()));		
		$this->showIn2Columns();
	}
	function actionCompanii(){
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga",$this->getAddMenu()));
		$this->setCenterContainer($this->getGroupBoxH3("Lista de Companii",$this->getCompanii()));		
		$this->showIn2Columns();
	}
	function actionFotos(){
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga",$this->getAddMenu()));
		$this->setCenterContainer($this->getGroupBoxH3("Lista de Imagini",$this->getFotos()));		
		$this->showIn2Columns();
	}
	function actionMaps(){
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga",$this->getAddMenu()));
		$this->setCenterContainer($this->getGroupBoxH3("Lista de Maps",$this->getMaps()));
		$this->showIn2Columns();
	}
	function actionNews(){
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenu()));
		$this->setLeftContainer($this->getGroupBoxH3("Adauga",$this->getAddMenu()));
		$this->setCenterContainer($this->getGroupBoxH3("Lista de Stiri",$this->getFotos()));
		$this->showIn2Columns();
	}	
	function actionSettings(){
		$this->setTitle("Setari");		
		$this->setLeftContainer($this->getGroupBoxH3("Menu",$this->getMenu()));
		$this->setRightContainer($this->getGroupBoxH3("Adauga",$this->getAddMenu()));

		//check if user is login
		$u=User::getCurrentUser();
		if ($u->id==0){
			$this->redirect("login.php");
		}

		if (!is_null($this->name)&&!is_null($this->email)&&!is_null($this->password)){
			$u=User::getCurrentUser();
			$t=new User;
			$o=$t->getAll("id!='".$u->id."' and email='".$this->email."'","");
			if (is_null($o)){
				$t=$u;
				//$t->name=$this->name;
				$t->name=$this->name;
				$t->url=$this->url;
				$t->phone=$this->phone;
				$t->mobile=$this->mobile;
				$t->email=$this->email;
				$t->password=$this->password;
				$t->note=$this->note;
				$t->save();
				User::setCurrentUser($t);
				//Mail::send_registration_email($t->email,$t->password);

			} else {
				$this->errormessage='Erroare, utilizator cu emailul ['.$this->email.'] exista deja ! Vrei sa recuperzi parola ? intra aici <a href="password.php?email='.$this->email.'">Recupereaza Parola</a>';
			}
		}
		$u=User::getCurrentUser();
		$this->name=$u->name;
		$this->url=$u->url;
		$this->phone=$u->phone;
		$this->mobile=$u->mobile;
		$this->email=$u->email;
		$this->password=$u->password;
		$this->repassword=$u->password;
		$this->note=$u->note;
		$this->setCenterContainer($this->setSettingsForm());
		$this->showIn3Columns();
	}

	function getMenu(){
		$out='<div  class="list-group">';
		$out.='<a class="list-group-item" href="'.$this->getUrlWithSpecialCharsConverted("index.php").'">Anunturile Mele</a>';
		$out.='<a class="list-group-item" href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=companii">Companiile Mele</a>';
		$out.='<a class="list-group-item" href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=fotos">Fotografiile Mele</a>';
		$out.='<a class="list-group-item" href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=maps">Hartile Mele</a>';		
		$out.='<a class="list-group-item" href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=news">Stirile Mele</a>';						
		$out.='<a class="list-group-item" href="'.$this->getUrlWithSpecialCharsConverted("index.php").'&action=settings">Setarile Mele</a>';		
		$out.='</div>';
		return $out;
	}
	function getImobil(){
		$out="";
		$p=new PropertyList();
		$out.=$p->getUserPropertyList();
		return $out;
	}	
	function getCompanii(){
		$out="";
		$p=new CompanyList();
		$out.=$p->getUserCompanyList();
		return $out;
	}
	function getFotos(){
		$out="";
		$p=new FotosList();
		$out.=$p->getUserFotosList();
		return $out;
	}
	function getMaps(){
		$out="";
		$m=new MapsList();
		$out.=$m->getUserMapsList();
		return $out;
	}	
	
	function setSettingsForm(){

		$out='<form id="frmRegister" name="frmRegister" method="POST" class="form-horizontal">';

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
		$out.='<input name="salveaza" type="submit" class="btn btn-default pull-right" value="salveaza">';
		$out.='</div>';
		$out.='</div>';
		
		$out.='</form>';

		return $this->getGroupBoxWizard("Setari",$out);
	}


}
$n=new IndexWebPage();

?>
