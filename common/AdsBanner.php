<?php

class AdsBanner extends DBManager {
	public $id;
	public $title;
	public $image_url;
	public $bg_color;
	public $tx_color;	
	public $link_url;
	public $contor_views;//contor views
	public $contor;//contor clicks
	public $created_at;
	function getTableName(){
		return "ads_banner";
	}
	function count_views(){
		if ($this->isMember("contor_views")){
			$sql=" update `".$this->getTableName()."` set contor_views=".($this->contor_views+1)." where `id`='".$this->id."'";
			$this->sql($sql);
		}
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
			return AdsBanner::getBanner($rtn);
	}
	public static function getBanner($b){   
		$out='';
		if (!is_null($b)){
			if (empty($b->image_url)) {
				$out.='<div id="ad" class="container bar tophorlogo" style="text-align:center;padding: 5px;">';
				$out.='<a href="'.Config::$adssite.'/banner.php?id='.$b->id.'" target="_blank">';
				$out.='<div style="width: 100%; display: inline-block; vertical-align: middle; height: 77px; line-height: 77px; text-align: center; text-transform: uppercase; text-transform: uppercase; font-size: 30px; font-weight: bolder; text-decoration: blink; color:'.$b->tx_color.'; border: 1px solid '.$b->tx_color.'; background-color:'.$b->bg_color.'">'.$b->title.'	</div>';
				$out.='</a>';              
				$out.='<div style="clear: both;"></div>';
				$out.='</div>';
			} else {
				$out.='<div id="ad" class="container bar tophorlogo" style="text-align:center;padding: 5px;">';
				$out.='<a href="'.Config::$adssite.'/banner.php?id='.$b->id.'" target="_blank"><img src="'.$b->image_url.'" alt="'.$b->title.'" style="width: 980px;"></a>';               
				$out.='<div style="clear: both;"></div>';
				$out.='</div>';				
			}
		}
		return $out;
	}	
}

?>
