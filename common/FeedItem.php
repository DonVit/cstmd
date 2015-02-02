<?php

class FeedItem extends DBManager {
	public $id;
	public $feedlogid;	
	public $companyid;
	public $title;
	public $link;
	public $description;
	public $pubdate;
	public $createdat;
	public $status;//0-just downloaded/draft; 1- new; 2-mapped; 
	public $contor;
	function getTableName(){
		return "feeditem";
	}
	public function isNew($feeditem){
		$fi=new FeedItem();
		$fis=$fi->getAll("companyid=".$feeditem->companyid." and link=\"".$feeditem->link."\" and status in (1,2)");
		if (is_null($fis)){
			return true;
		} else {
			return false;
		}
	}
	public function itentifyNewItems(){
		$fi=new FeedItem();
		
		$fis=$fi->getAll("status=0");
		
		if (count($fis)!=0){
			foreach($fis as $fi){
				if ($this->isNew($fi)){
					$fi->status=1;
					$fi->save();
				} else {
					$fi->delete();
				}
			}
		}
		DBManager::doSql("delete from feeditem where deleted=1");		
	}
	public function mapNewsToLocations(){
		DBManager::doSql("insert into feeditemlocation(feeditemid,localitateid)SELECT feeditem.id as feeditem_id, localitate.id as localitate_id FROM feeditem inner join localitate on ((feeditem.description LIKE CONCAT('%', localitate.name, '%')) or (feeditem.description LIKE CONCAT('%', localitate.name_ro, '%'))) or ((feeditem.title LIKE CONCAT('%', localitate.name, '%')) or (feeditem.title LIKE CONCAT('%', localitate.name_ro, '%'))) where status=1");
		DBManager::doSql("update feeditem set status=2 where status=1");	
	}
	public function getNewsByRaion($raion_id){
		$sql="SELECT t1.feeditemid as id,  t2.title FROM feeditemlocation t1 inner join feeditem t2 on t1.feeditemid=t2.id inner join localitate t3 on t1.localitateid=t3.id where t3.raion_id=".$raion_id." order by t1.feeditemid desc  limit 0,100";
		$ns=DBManager::doSql($sql);
		$out=$this->getNewsTable($ns);
		return $out;
	}
	public function getNewsByPrimarie($primarie_id){
		$sql="SELECT t1.feeditemid as id,  t2.title FROM feeditemlocation t1 inner join feeditem t2 on t1.feeditemid=t2.id inner join localitate t3 on t1.localitateid=t3.id where (t3.id=$primarie_id or t3.parent_id=$primarie_id) order by t1.feeditemid desc limit 0,100";
		$ns=DBManager::doSql($sql);
		$out=$this->getNewsTable($ns);
		return $out;
	}	
	public function getNewsByLocalitate($localitate_id){
		$sql="SELECT t1.feeditemid as id, t2.title FROM feeditemlocation t1 inner join feeditem t2 on t1.feeditemid=t2.id where t1.localitateid=".$localitate_id." order by t1.feeditemid desc limit 0,100";
		$ns=DBManager::doSql($sql);
		$out=$this->getNewsTable($ns);
		return $out;
		
	}
	public function getNewsTable($ns){
		$out='';
		if (count($ns)!=0){
			$out.='<div>';
			$out.='<ul style="list-style-type:disc">';
			$c=count($ns);
			foreach($ns as $n){
				$url=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=redirect&id=".$n->id);
				$out.='<li><a href="'.$url.'" target="_blank">'.$n->title.'</a></li>';
				$c=$c-1;
			}
			$out.='</ul>';
			$out.='</div>';	
		}
		return $out;
	}				
	
	
}

?>
