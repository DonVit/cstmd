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
	public static $dateFormat="Y-m-d";
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
	public function getNewsByRaion($parent,$raion_id,$limit=50){
		$sql="SELECT t4.feeditemid as news_id, t2.title as news_title, t2.createdat as news_date, t5.id as company_id, t5.name as company_name  from (select t1.feeditemid FROM feeditemlocation t1 inner join localitate t3 on t1.localitateid=t3.id where t3.raion_id=".$raion_id." group by t1.feeditemid) as t4 inner join feeditem t2 on t4.feeditemid=t2.id inner join company t5 on t2.companyid=t5.id order by t4.feeditemid desc";
		//$ns=DBManager::doSql($sql);
		$out=$this->getNewsTableNew($parent,$sql);
		return $out;
	}
	public function getNewsByPrimarie($parent,$primarie_id,$limit=50){
		$sql="SELECT t2.id as news_id, t2.title as news_title, t2.createdat as news_date, t5.id as company_id, t5.name as company_name FROM feeditemlocation t1 inner join feeditem t2 on t1.feeditemid=t2.id inner join localitate t3 on t1.localitateid=t3.id inner join company t5 on t2.companyid=t5.id where (t3.id=$primarie_id or t3.parent_id=$primarie_id) order by t1.feeditemid desc";
		//$ns=DBManager::doSql($sql);
		$out=$this->getNewsTableNew($parent,$sql);
		return $out;
	}	
	public function getNewsByLocalitate($parent,$localitate_id,$limit=50){
		$sql="SELECT t2.id as news_id, t2.title as news_title, t2.createdat as news_date, t5.id as company_id, t5.name as company_name FROM feeditemlocation t1 inner join feeditem t2 on t1.feeditemid=t2.id inner join company t5 on t2.companyid=t5.id where t1.localitateid=".$localitate_id." order by t1.feeditemid desc";
		//$ns=DBManager::doSql($sql);
		$out=$this->getNewsTableNew($parent,$sql);
		return $out;
		
	}
	public function getNewsByCompany($parent,$company_id,$limit=50){
		$sql="SELECT t2.id as news_id, t2.title as news_title, t2.createdat as news_date, t5.id as company_id, t5.name as company_name FROM feeditem t2 inner join company t5 on t2.companyid=t5.id where t2.companyid=".$company_id." order by t2.id desc";
		//$ns=DBManager::doSql($sql);
		$out=$this->getNewsTableNew($parent,$sql);
		return $out;
		
	}
	public function getNewsByDate($parent,$date,$limit=50){
		$sql="SELECT t2.id as news_id, t2.title as news_title, t2.createdat as news_date, t5.id as company_id, t5.name as company_name from feeditem t2 inner join company t5 on t2.companyid=t5.id where date(t2.createdat)=\"".$date."\" order by t2.id desc";
		//$ns=DBManager::doSql($sql);
		$out=$this->getNewsTableNew($parent,$sql);
		return $out;
	}
	public function getTopCompanies(){
		$sql="select t1.companyid as id, t2.name, t1.cnt from (SELECT companyid,count(*) as cnt FROM feeditem group by companyid)  as t1 inner join company as t2 on t1.companyid=t2.id order by t1.cnt desc";
		$ns=DBManager::doSql($sql);
		$out=$this->getNewsList($ns);
		return $out;
	}			
	public function getNewsTable($ns){
		$out='';
		if (count($ns)!=0){
			$out.='<div>';
			$out.='<ul style="list-style-type:disc">';
			$c=count($ns);
			foreach($ns as $n){
				$date=date(FeedItem::$dateFormat, strtotime($n->date));
				$urlnews=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=redirect&id=".$n->id);
				$urlcompany=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=company&id=".$n->c_id);
				$urldate=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=calendar&date=".$date);
				$out.='<li style="border-bottom: 1px dotted #777777;"><a href="'.$urlnews.'" target="_blank">'.$n->title.'</a><div style="font-size: 11px;  text-align: right;">Sursa:<a href="'.$urlcompany.'" target="_blank">'.$n->c_name.'</a> Din data:<a href="'.$urldate.'" target="_blank">'.$date.'</a></div></li>';
				$c=$c-1;
			}
			$out.='</ul>';
			$out.='</div>';
		}
		return $out;
	}
	function getNewsTableNew($currentPage,$sql){
		$out='';
		$out.='<div class="groupboxtable">';
	
		$table=new Table();
		$table->setPagination(false);
		$table->setShowNrOrd(false);
		$table->setCurrentPage($currentPage);
		//$table->setRowsPerPage(100);
		$table->setSql($sql);
	
		$navigationlink=function() use ($currentPage){
			return $currentPage->getUrlWithSpecialCharsConverted("index.php");
		};
		$table->setNavigationLink($navigationlink);
	
		$namelink=function($row) use ($currentPage){
	
			$newsurl=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=redirect&id=".$row->news_id);
			$companyurl=$currentPage->getUrlWithSpecialCharsConverted("index.php","action=viewnume&id=".$row->company_id);
			$title='<a href="'.$newsurl.'">'.$row->news_title.'</a>';
			$source='<a href="'.$companyurl.'">'.$row->company_name.'</a>';
			
			return "&#8226;".$title." (ref. ".$source.").";
		};
		
		$table->addField(new TableField(1, "Titlu", "news_title", "text-align: left;width:80%;padding:5px;",$namelink));
	
	
		$out.=$table->show();
	
		$out.="</div>";
	
		return $out;
	
	}	
	public function getNewsList($ns){
		$out='';
		if (count($ns)!=0){
			$out.='<div>';
			$out.='<ul style="list-style-type:disc">';
			$c=count($ns);
			foreach($ns as $n){
				$url=$this->getUrlWithSpecialCharsConverted(Config::$feedssite."/index.php","action=company&id=".$n->id);
				$out.='<li><a href="'.$url.'">'.$n->name.'</a></li>';
				$c=$c-1;
			}
			$out.='</ul>';
			$out.='</div>';
		}
		return $out;
	}	
}

?>
