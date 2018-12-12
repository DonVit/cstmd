<?php
class News extends DBManager {
	public $id;
	public $company_id;
	public $news_category;
	public $title;
	public $text;
	public $description;
	public $keywords;
	public $url;
	public $date;
	public $centerlat;
	public $centerlng;
	public $zoom;
	public $maptype;
	public $lat;
	public $lng;
	public $map_title;
	public $map_description;
	public $contor;
	public $valid;

	function getCompany(){
		$c=new Company();
		$c->loadById($this->company_id);
		return $c;
	}
	function getNewsCategory(){
		$c=new NewsCategory();
		$c->loadById($this->news_category);
		return $c;
	}	
	function getNewsVideos(){
		$v=new Video();
		$vs=$v->getAll("id in (select video_id from news_video where news_id=".$this->id.") and deleted=0");
		return $vs;	
	}
	function getNewsByCategory(){
		$n=new News();
		$ns=$n->getAll("news_category=".$this->news_category,"`date` desc",0,8);
		return $this->getNewsTable($ns);	
	}
	function getNewsByLocalitate($l){
		$n=new News();
		$ns=$n->getAll("id in (select news_id from news_localitate where localitate_id=".$l.") and deleted=0","`date` desc",0,8);
		return $this->getNewsTable($ns);	
	}
	function getNewsByCompany($c){
		$n=new News();
		$ns=$n->getAll("company_id=".$c->id,"`date` desc",0,8);	
		return $this->getNewsTable($ns);
	}		
	function getNewsLocalitati(){
		$sql="SELECT raion.name as r_name, localitate.id as l_id, localitate.name_ro as l_name FROM `news_localitate` INNER JOIN localitate ON news_localitate.localitate_id = localitate.id INNER JOIN raion ON localitate.raion_id = raion.id WHERE news_id =$this->id";
		$l=new Location;
		$ls=$l->doSql($sql);	
		//$n=new News();
		//$ns=$n->getAll("news_category=".$this->news_category,"`date` desc",0,8);
		return $ls;		
	}
	function delNewsLocalitati(){
		$sql="DELETE FROM `news_localitate` WHERE news_id =$this->id";
		$l=new Location;
		$ls=$l->doSql($sql);	
		return $ls;		
	}
	function getNewsTable($ns){
		$out="";
		//$ns=$n->getNewsByCategory();
		if (count($ns)!=0){
			$out.='<table style="font-size:85%;width:100%">';
			$i=0;
			foreach($ns as $n){
				if ($i==0){
					$out.='<tr>';
				}
				if ($i==4){
					$out.='</tr><tr>';
				}
				$img=Image::getMainImageByRefType('n', $n->id);
				if ($img!=null){
					$out.='<td style="width:25%"><div><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewnews").'&id='.$n->id.'" style="text-align: left;text-decoration: none;"><img src="'.Config::$newssite.'/data/t'.$img->imagepath.'" alt="'.$img->imagenote.'" class="imageborder"  style="width:120px;"/><p class="newsgroup_p">'.$n->title.'</p></a></div></td>';
				}else{
					$out.='<td style="width:25%"><div><a href="'.$this->getUrlWithSpecialCharsConverted(Config::$newssite."/index.php","action=viewnews").'&id='.$n->id.'" style="text-align: left;text-decoration: none;"><img src="'.Config::$commonsite.'/img/no_image_100x100.jpg" alt="no image" style1="height: 100px; width:130px;" class="imageborder"/><p class="newsgroup_p">'.$n->title.'</p></a></div></td>';
				}
				if ($i==8){
					$out.='</tr>';
				}
				$i=$i+1;
			}
			$out.='</table>';
		} else {
			$out.='Nu exista stiri.';
		}
	
		return $out;
	}					
}

?>
