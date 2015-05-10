<?php
require_once(__DIR__ . '/../main/loader.php');

class AddNewsWebPage extends MainWebPage {
	public $step=1;
	public $steps=5;
	public $step_title="";
	public $currentnews;	
	public $currentnewslocations;
	
	public $errormessage="";

	function __construct(){
		parent::__construct();
		$this->setJavascript("js/scripts.js");
		$this->setJavascript("http://cdn.ckeditor.com/4.4.6/basic/ckeditor.js");
		//$this->setCSS("style/news.css");
		$t="Adauga Stire";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		if (isset($this->id)){
			$n=new News();
			$n->loadById($this->id);
			User::setCurrentNews($n);
		}				
		//get news from session
		$this->currentnews=User::getCurrentNews();
		//get newslocations from session
		$this->currentnewslocations=User::getCurrentNewsLocations();

		//Logger::setLogs("current news before=".$this->currentnews);
		//print_r($_POST);
		foreach ($_POST as $field => $value) {
			if ($this->currentnews->isMember($field)){
				$this->currentnews->$field=$this->getParamValue($value);
			}
		}
		if (!empty($_FILES["image_file"]["name"])){
			$newfilename=System::getRandomFileName($_FILES["image_file"]["name"]);
			$this->currentnews->image_file=$newfilename;
			move_uploaded_file($_FILES["image_file"]["tmp_name"], "images/t".$this->currentnews->image_file);
			Photo::MakeThumb("images/t".$this->currentnews->image_file, "images/".$this->currentnews->image_file);
			unlink("images/t".$this->currentnews->image_file);
		}
		//set newslocations to db and session
		if (!empty($this->locations_selected)){
			$this->currentnewslocations=array();
			foreach($this->locations_selected as $location){
				$nl=new NewsLocation();	
				$nl->news_id=$this->currentnews->id;
				$nl->localitate_id=$location;
				$this->currentnewslocations[]=$nl;
			}
		}
		$this->currentnews->save();
		User::setCurrentNews($this->currentnews);
		User::setCurrentNewsLocations($this->currentnewslocations);
			
		$this->create();
	}	
	function actionDefault(){
		$this->step=1;
		$this->steptitle="Adauga Stire - Text";
		$this->setTitle($this->steptitle);
		if ($_POST["next"]){
				$this->redirect($this->getUrl("add.php","action=image"));
		}
		if ($_POST["cancel"]){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setNews());	
		$this->show();
	}
	function actionImage(){
		$this->step=2;
		$this->steptitle="Adauga Stire - Imagine";
		$this->setTitle($this->steptitle);
		if ($_POST["next"]){
				$this->redirect($this->getUrl("add.php","action=map"));
		}
		if ($_POST["prev"]){
				$this->redirect($this->getUrl("add.php"));
		}		
		if ($_POST["cancel"]){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		//$this->setCenterContainer($this->setImage());
		$this->setCenterContainer($this->getWizardPage($this->setFiles()));
		$this->show();
	}
	function actionMap(){
		$this->step=3;
		$this->steptitle="Adauga Stire - Harta";
		$this->setTitle($this->steptitle);
		if ($_POST["next"]){
				$this->redirect($this->getUrl("add.php","action=location"));
		}
		if ($_POST["prev"]){
				$this->redirect($this->getUrl("add.php","action=image"));
		}		
		if ($_POST["cancel"]){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->getWizardPage($this->setMap($this->currentnews)));	
		$this->show();
	}		
	function actionLocation(){
		$this->step=4;
		$this->steptitle="Adauga Stire - Locatii";
		$this->setTitle($this->steptitle);
		if ($_POST["next"]){
				$this->redirect($this->getUrl("add.php","action=validation"));
		}
		if ($_POST["prev"]){
				$this->redirect($this->getUrl("add.php","action=map"));
		}		
		if ($_POST["cancel"]){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setLocations());	
		$this->show();
	}
	function actionValidation(){
		$this->step=5;
		$this->steptitle="Adauga Stire - Valideaza";
		$this->setTitle($this->steptitle);
		if ($_POST["save"]){
				if (User::getValidationCode()==$this->validationcode){
					$this->currentnews->valid=1;
					$this->currentnews->save();
					$this->currentnews->delNewsLocalitati();
					foreach($this->currentnewslocations as $l){
						$l->save();
					}
					$fs=User::getCurrentFiles();
					foreach ($fs as $f){
						$f->reftype='n';
						$f->reftypeid=$this->currentnews->id;
						if ($f->imagename!=''){
							if (($f->id==NULL)&&($f->error==0)){
								Logger::setLogs("id=".$f->id);
								$f->save();
							}
						}
					}
					//delete property from cache
					User::delCurrentFiles();
					User::delCurrentNews();
					User::delCurrentNewsLocations();
					$this->redirect($this->getUrl("index.php","action=viewnews&id=".$this->currentnews->id));
				}				
		}
		if ($_POST["prev"]){
				$this->redirect($this->getUrl("add.php","action=location"));
		}		
		if ($_POST["cancel"]){
				$this->redirect($this->getUrl("add.php","action=cancel"));
		}			
		$this->setCenterContainer($this->setValidation());	
		$this->show();
	}
	function actionCancel(){
		if (!empty($this->currentnews->image_file)){
			unlink("images/".$this->currentnews->image_file);
		}
		User::delCurrentNews();
		User::delCurrentNewsLocations();
		
		$this->redirect($this->getUrl("add.php"));
	}			
	function show(){
		$out="";
		$out.='<div id="container">';
		$out.='<div id="center" class="container center" style="width:1000px;">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}	

	function setNews(){
		$out='';
		//$out.='<fieldset id="fieldset-files">';
		//$out.='<legend>Stire:</legend>';
		$out.='<table id="news" width="100%">';
		//$out.='<tr><td>Imaginea:</td><td><input type="file" id="file" name="file" ></td><td>Atentie! Doar fisiere (jpg,jpeg,gif,png) pina la 1MB altfel fisierele vor fi filtrare.</td></tr>';
		$out.='<tr><td>Categorie:</td><td>'.$this->getNewsCategory($this->currentnews->news_category).'</td></tr>';
		$out.='<tr><td>Sursa:</td><td>'.$this->getCompany($this->currentnews->company_id).'</td></tr>';	
		$out.='<tr><td>Url:</td><td><input type="input" id="url" name="url" style="width:600px;" value="'.$this->currentnews->url.'"></td></tr>';
		$out.='<tr><td>Titlu:</td><td><input type="input" id="title" name="title" style="width:600px;" value="'.$this->currentnews->title.'"></td></tr>';
		$out.='<tr><td>Descriere:</td><td><textarea id="note" name="description" style="width:600px;height:50px" >'.$this->currentnews->description.'</textarea></td></tr>';
		$out.='<tr><td>Keywords:</td><td><textarea id="note" name="keywords" style="width:600px;;height:50px" >'.$this->currentnews->keywords.'</textarea></td></tr>';			
		$out.='<tr><td>Data:</td><td><input type="input" id="date" name="date" value="'.$this->currentnews->date.'"></td></tr>';		
		//$out.='<tr><td>Text:</td><td><textarea id="text" name="text" style="width:600px;;height:500px" >'.$this->currentnews->text.'</textarea></td></tr>';
		
		$out.='<tr><td>Text:</td><td>';
        $out.='<textarea name="text">'.$this->currentnews->text.'</textarea>';
        $out.='<script>            CKEDITOR.replace( \'text\' );        </script>';		
		$out.='</td></tr>';
		$out.='</table>';			 
		//$out.='</fieldset>';
		return $this->getWizardPage($out);	 
	}
	function setImage(){
		$out='';
		//$out.='<fieldset id="fieldset-files">';
		//$out.='<legend>Imagine:</legend>';
		$out.='<table id="image" width="100%">';
		if (isset($this->currentnews->image_file)){
			$out.='<tr><td>Imaginea:</td><td><img style="width:150px;" src="images/'.$this->currentnews->image_file.'"></td><td></td></tr>';		
		}		
		$out.='<tr><td>Imagine:</td><td><input type="file" id="image_file" name="image_file" ></td></tr>';
		//$out.='<tr><td>Categorie:</td><td>'.$this->getNewsCategory($this->currentnews->news_category).'</td></tr>';
		//$out.='<tr><td>Sursa:</td><td>'.$this->getCompany($this->currentnews->company_id).'</td></tr>';	
		$out.='<tr><td>Url:</td><td><input type="input" id="image_url" name="image_url" style="width:600px;" value="'.$this->currentnews->image_url.'"></td></tr>';
		//$out.='<tr><td>Descriere:</td><td><input type="input" id="image_description" name="image_description" style="width:600px;" value="'.$this->currentnews->image_description.'"></td></tr>';
		$out.='<tr><td>Descriere:</td><td><textarea id="image_description" name="image_description" style="width:600px;;height:50px" >'.$this->currentnews->image_description.'</textarea></td></tr>';		
		//$out.='<tr><td>Descriere:</td><td><textarea id="note" name="description" style="width:600px;height:50px" >'.$this->currentnews->description.'</textarea></td></tr>';
		//$out.='<tr><td>Keywords:</td><td><textarea id="note" name="keywords" style="width:600px;;height:50px" >'.$this->currentnews->keywords.'</textarea></td></tr>';			
		//$out.='<tr><td>Data:</td><td><input type="input" id="date" name="date" value="'.$this->currentnews->date.'"></td></tr>';		
		//$out.='<tr><td>Text:</td><td><textarea id="text" name="text" style="width:600px;;height:500px" >'.$this->currentnews->text.'</textarea></td></tr>';
		$out.='</table>';			 
		//$out.='</fieldset>';
		return $this->getWizardPage($out);	 
	}
	function setLocations(){
		$out='';
		//$out.='<fieldset id="fieldset-locations">';
		//$out.='<legend>Localitati:</legend>';
		$out.='<table id="locations" align="center">';
		$sql="SELECT raion.id as raion_id, raion.name as raion_name, localitate.id as localitate_id, localitate.tip as localitate_tip, localitate.name as localitate_name FROM `news` inner join localitate on news.text LIKE CONCAT('%', localitate.name, '%') inner join raion on localitate.raion_id=raion.id where news.id=".$this->currentnews->id." order by raion.municipiu desc, raion.name, localitate.tip";
		$l=new Location();
		$ls=$l->doSql($sql);
		$out.='<tr><td>Localitati din text:</td><td></td><td>Localitati selectate:</td></tr>';
		$out.='<tr>';
		//$out.='<td>Localitati:</td>';
		$out_avilable='';
		$out_selected='';
		foreach($ls as $l){
			$selected=false;
			foreach($this->currentnewslocations as $ls){
				if ($l->localitate_id==$ls->localitate_id){
					$out_selected.='<option value='.$l->localitate_id.' selected>'.$l->raion_name.'-'.$l->localitate_tip.' '.$l->localitate_name.'</option>';
					$selected=true;
					break;
				}
			}
			if (!$selected){
				$out_avilable.='<option value='.$l->localitate_id.' selected>'.$l->raion_name.'-'.$l->localitate_tip.' '.$l->localitate_name.'</option>';
			}
		}
		//$out.='<option>'.$l->localitate_name.'</option>';			
		$out.='<td><select id="locations_avilable" name="locations_avilable[]" style="width:150px;" size="16" multiple>';
		$out.=$out_avilable;
		$out.='</select></td>';
		//$out.='<tr><td><select id="mySelect" size="4"><option>Apple</option><option>Pear</option><option>Banana</option><option>Orange</option></select><input type="button" onclick="removeOption()" value="Remove selected option"></td></tr>';
		
		$out.='<td><input type="button" style="width:40px;" onclick="WizardLocationsRemoveOptions(\'locations_avilable\',\'locations_selected\');" value="<<"><br>';
		$out.='<input type="button" style="width:40px;" onclick="WizardLocationsAddOptions(\'locations_avilable\',\'locations_selected\');" value=">>"></td>';		
		$out.='<td><select id="locations_selected" name="locations_selected[]" style="width:150px;" size="16" multiple>';		
		$out.=$out_selected;
		$out.='</select></td>';		
		//$out.='<tr><td>Imagine:</td><td><input type="file" id="image_file" name="image_file" ></td></tr>';
		//$out.='<tr><td>Categorie:</td><td>'.$this->getNewsCategory($this->currentnews->news_category).'</td></tr>';
		//$out.='<tr><td>Sursa:</td><td>'.$this->getCompany($this->currentnews->company_id).'</td></tr>';	
		//$out.='<tr><td>Url:</td><td><input type="input" id="image_url" name="image_url" style="width:600px;" value="'.$this->currentnews->image_url.'"></td></tr>';
		//$out.='<tr><td>Descriere:</td><td><input type="input" id="image_description" name="image_description" style="width:600px;" value="'.$this->currentnews->image_description.'"></td></tr>';
		//$out.='<tr><td>Descriere:</td><td><textarea id="image_description" name="image_description" style="width:600px;;height:50px" >'.$this->currentnews->image_description.'</textarea></td></tr>';		
		//$out.='<tr><td>Descriere:</td><td><textarea id="note" name="description" style="width:600px;height:50px" >'.$this->currentnews->description.'</textarea></td></tr>';
		//$out.='<tr><td>Keywords:</td><td><textarea id="note" name="keywords" style="width:600px;;height:50px" >'.$this->currentnews->keywords.'</textarea></td></tr>';			
		//$out.='<tr><td>Data:</td><td><input type="input" id="date" name="date" value="'.$this->currentnews->date.'"></td></tr>';		
		//$out.='<tr><td>Text:</td><td><textarea id="text" name="text" style="width:600px;;height:500px" >'.$this->currentnews->text.'</textarea></td></tr>';
		$out.='</table>';			 
		//$out.='</fieldset>';
		return $this->getWizardPage($out);
	}			
	function setValidation(){
		$out='';
		//$out.='<fieldset id="fieldset-files">';
		//$out.='<legend>Valideaza imaginea:</legend>';
		$out.='<table id="validations">';
		//$out.='<tr><td>Draft:</td><td><input type="checkbox" id="valid" name="valid" value="'.$this->currentnews->valid.'"></td></tr>';		
		$out.='<tr><td>Introdu codul din imagine:</td><td><input id="validationcode" type="text" name="validationcode" class="input"><img id="validationimage" src="'.Config::$mainsite.'/validationimage.php" style="vertical-align: middle"></td></tr>';
		$out.='</table>';			 
		//$out.='</fieldset>';
		return $this->getWizardPage($out);	 
	}	

	function getNewsCategory($newscategoryid){
		$n=new NewsCategory();
		$ns=$n->getAll();
		$out='<select id="news_category" name="news_category" style="width:600px;" size="1">';
		if (!is_null($ns)){
			foreach($ns as $nn){
				$name=$nn->name_ro.' ('.$nn->description_ro.')';
				if ($nn->id==$newscategoryid){
					$out.= '<option value='.$nn->id.' selected>'.$name.'</option>';
				} else {
					$out.= '<option value='.$nn->id.'>'.$name.'</option>';
				}
			}
		}
		$out.='</select>';
		return $out;
	}
	function getCompany($companyid){
		$c=new Company();
		$cs=$c->getAll();
		$cs=$c->doSql("SELECT `company`.id as company_id, `company`.name as company_name, `company_type`.id as company_type_id, `company_type`.name as company_type_name  FROM `company` inner join `company_type` on `company`.company_type_id=`company_type`.id where `company`.valid=1 order by company_type_id");
		$out="<select id=\"company_id\" name=\"company_id\" style=\"width:600px;\" size=\"1\">";
		$ctid=0;
		if (!is_null($cs)){
			foreach($cs as $cc){
				$name=$cc->name;
				if ($ctid!=$cc->company_type_id){$out.="<optgroup label=\"".$cc->company_type_name."\">";}
				if ($cc->company_id==$companyid){
					$out.= "<option value=".$cc->company_id." selected>".$cc->company_name."</option>";
				} else {
					$out.= "<option value=".$cc->company_id.">".$cc->company_name."</option>";
				}
				$ctid=$cc->company_type_id;
				if ($ctid!=$cc->company_type_id){$out.="</optgroup>";}
			}
		}
		$out.="</select>";
		return $out;
	}		
	function getLocationDropDown($raionid,$localitateid){
		$l=new Location();
		$ls=$l->getAll("raion_id=".$raionid,"`order`,`oras` desc,`name`");
		$out="<select id=\"localitate_id\" name=\"localitate_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnDropDownChange()\">";
		if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
			$out.= "<option value=\"0\">Nu Conteaza</option>";
		}
		if (!is_null($ls)){
			foreach($ls as $ll){
				if ($ll->id==$localitateid){
					$out.= "<option value=".$ll->id." selected>".$ll->tip." ".$ll->name."</option>";
				} else {
					$out.= "<option value=".$ll->id.">".$ll->tip." ".$ll->name."</option>";
				}
			}
		}
		$out.="</select>";
		return $out;
	}	
	function getSectorDropDown($localitateid,$sectorid){
		$s=new Sector();
		$ss=$s->getAll("localitate_id=".$localitateid,"`name`");
		$out="<select id=\"sector_id\" name=\"sector_id\" class=\"select\" size=\"1\" onchange=\"javascript:WizardOnSectorDropDownChange()\">";
		if (($this->currentproperty->scop_id==3)||($this->currentproperty->scop_id==4)){
			if ($sectorid==0){
				$out.= "<option value=\"0\" selected>Nu Conteaza</option>";
			} else {
				$out.= "<option value=\"0\">Nu Conteaza</option>";
			}
		}		
		if (!is_null($ss)){
			foreach($ss as $s){
				if ($s->id==$sectorid){
					$out.= "<option value=".$s->id." selected>".$s->name."</option>";
				} else {
					$out.= "<option value=".$s->id.">".$s->name."</option>";
				}
			}
		
			$out.= "<option value=\"0\" disabled=\"disabled\">---</option>";
			$out.= "<option value=\"-1\">Altul...</option>";
			if (($this->currentproperty->scop_id==1)||($this->currentproperty->scop_id==2)){
				if ($sectorid==0){
					$out.= "<option value=\"0\" selected>Nu Exista</option>";
				} else {
					$out.= "<option value=\"0\">Nu Exista</option>";
				}
			}
		}
		$out.="</select>";
		$out.="<input type=\"hidden\" id=\"sector_new\" name=\"sector_new\">";
		return $out;
	}
}
$n=new AddNewsWebPage();
?>
