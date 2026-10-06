<?php
require_once(__DIR__ . '/../main/loader.php');

class JsonMapsWebPage extends WebPage {
	function __construct(){
		$this->setContentType("application/json");
		parent::__construct();

		$now = new DateTimeImmutable();
		$nextDay = $now->modify('tomorrow')->setTime(0, 0);
		header('Cache-Control: public, max-age='.max(0, $nextDay->getTimestamp() - $now->getTimestamp()));
		$this->show();
	}
	function show($html=""){
		WebPage::show($this->getJson());
	}
	function getJson(){
		$cacheKey = hash('sha256', __DIR__ . Config::$mapssite . '|tuple-v2');
		$cachePath = sys_get_temp_dir().'/casata-maps-markers-'.$cacheKey.'-'.date('Y-m-d').'.json';
		$lock = @fopen($cachePath.'.lock', 'c');
		if ($lock && flock($lock, LOCK_EX)){
			$cached = @file_get_contents($cachePath);
			if ($cached !== false){
				flock($lock, LOCK_UN);
				fclose($lock);
				return $cached;
			}

			$markers = $this->getMarkers();
			$json = json_encode($markers, JSON_UNESCAPED_UNICODE);
			if ($json === false){
				$json = '[]';
			}
			@file_put_contents($cachePath, $json, LOCK_EX);
			flock($lock, LOCK_UN);
			fclose($lock);
			return $json;
		}

		if ($lock){
			fclose($lock);
		}
		return json_encode($this->getMarkers(), JSON_UNESCAPED_UNICODE) ?: '[]';
	}
	function getMarkers(){
		$markers = array();

		$m=new Map();
		$ms=$m->getAll();
		foreach($ms as $m){
			$marker = $this->makeMarker($m->lat,$m->lng,'link',$m->title,$m->description,$m->getUrl(Config::$mapssite.'/index.php','action=viewmap&id='.$m->id));
			if ($marker){
				$markers[] = $marker;
			}
		}

		$p=new Property();
		$ps=$p->getAll("lat!=\"\"");
		foreach($ps as $p){
			$description=$p->getShortDescription();
			$type=($p->scop_id==1 ? 'imobil' : 'chirie');
			$link=($p->scop_id==1 ? Config::$imobilsite.'/property.php?id='.$p->id : Config::$chiriesite.'/property.php?id='.$p->id);
			$marker = $this->makeMarker($p->lat,$p->lng,$type,$description,$description,$link);
			if ($marker){
				$markers[] = $marker;
			}
		}

		$p=new Photo();
		$ps=$p->getAll("lat!=\"\" and lng!=\"\"","data desc",0,1000);
		foreach($ps as $p){
			$marker = $this->makeMarker($p->lat,$p->lng,'photo',$p->title,$p->note,Config::$imagessite.'/index.php?id='.$p->id);
			if ($marker){
				$markers[] = $marker;
			}
		}

		return $markers;
	}
	function makeMarker($latitude,$longitude,$type,$title,$description,$link){
		$latitude=(float)$latitude;
		$longitude=(float)$longitude;
		if (($latitude==0 && $longitude==0) || $latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180){
			return null;
		}
		return array($latitude,$longitude,$type,$title,$description,$link);
	}
}
$n=new JsonMapsWebPage();

?>