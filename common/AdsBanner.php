<?php
/*
 * Created on 27 Feb 2009
 *
 */
class AdsBanner extends DBManager {
	public $id;
	public $title;
	public $image_url;
	public $link_url;
	public $contor_views;//contor views
	public $contor;//contor clicks
	public $created_at;
	function getTableName(){
		return "ads_banner";
	}
	public static function getRandomBanner(){
			$rtn=null;
			$ab=new AdsBanner();
			$abs=$ab->getAll();
			if (!is_null($abs)){
				$rnd=rand(1,count($abs));
				$cnt=1;
				foreach($abs as $b){
					if ($cnt==$rnd){
						$rtn=$b;
					}
					$cnt++;
				}
			}
			//$rtn->count_views();
			return $rtn;
	}
	function count_views(){
		if ($this->isMember("contor_views")){
			$sql=" update `".$this->getTableName()."` set contor_views=".($this->contor_views+1)." where `id`='".$this->id."'";
			$this->sql($sql);
		}
	}		
}

?>
