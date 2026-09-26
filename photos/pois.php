<?php
require_once(__DIR__ . '/../main/loader.php');

header('Content-Type: application/json; charset=utf-8');

class PoisWebPage {
	function __construct(){
		echo $this->getJson();
	}
	function getJson(){
		$markers = array();

		// Maps
		$m=new Map();
		$ms=$m->getAll();
		foreach($ms as $m){
			$markers[] = array(
				'title' => $m->title,
				'description' => $m->description,
				'lat' => $m->lat,
				'lng' => $m->lng,
				'type' => 'link',
				'link' => $m->getUrl(Config::$mapssite.'/index.php','action=viewmap&id='.$m->id)
			);
		}

		// Properties
		$p=new Property();
		$ps=$p->getAll("lat!=\"\"");
		foreach($ps as $p){
			$markers[] = array(
				'title' => $p->getShortDescription(),
				'description' => $p->getShortDescription(),
				'lat' => $p->lat,
				'lng' => $p->lng,
				'type' => ($p->scop_id==1 ? 'imobil' : 'chirie'),
				'link' => ($p->scop_id==1 ? Config::$imobilsite.'/property.php?id='.$p->id : Config::$chiriesite.'/property.php?id='.$p->id)
			);
		}

		// Photos - limit to 1000 for performance
		$p=new Photo();
		$ps=$p->getAll("","data desc",0,1000);
		foreach($ps as $p){
			$markers[] = array(
				'title' => $p->title,
				'description' => $p->note,
				'lat' => $p->lat,
				'lng' => $p->lng,
				'type' => 'photo',
				'link' => Config::$imagessite.'/index.php?id='.$p->id
			);
		}

		// News
		$n=new News();
		$ns=$n->getAll("lat!='' and lng!=''");
		foreach($ns as $n){
			$markers[] = array(
				'title' => $n->map_title,
				'description' => $n->map_description,
				'lat' => $n->lat,
				'lng' => $n->lng,
				'type' => 'news',
				'link' => Config::$newssite.'/index.php?action=viewnews&id='.$n->id
			);
		}

		return json_encode($markers);
	}
}
$n=new PoisWebPage();
?>