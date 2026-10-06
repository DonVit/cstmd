<?php
require_once(__DIR__ . '/../main/loader.php');

class JsonAlegeriWebPage extends WebPage {
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
		$cacheKey = hash('sha256', __DIR__ . Config::$alegerisite);
		$cachePath = sys_get_temp_dir().'/casata-alegeri-markers-'.$cacheKey.'-'.date('Y-m-d').'.json';
		$lock = @fopen($cachePath.'.lock', 'c');
		if ($lock && flock($lock, LOCK_EX)){
			$cached = @file_get_contents($cachePath);
			if ($cached !== false){
				flock($lock, LOCK_UN);
				fclose($lock);
				return $cached;
			}

			$markers = array();
			$sections = (new Sectiidevot())->getAllSectii();
			foreach($sections as $section){
				$markers[] = array(
					'title' => $section->localitate,
					'description' => $section->adresa,
					'lat' => $section->lat,
					'lng' => $section->lng,
					'type' => ($section->winner==8 ? 'red' : 'yellow'),
					'link' => $section->getUrl(Config::$alegerisite.'/index.php','action=viewsectie&id='.$section->id)
				);
			}
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
		$markers = array();
		$sections = (new Sectiidevot())->getAllSectii();
		foreach($sections as $section){
			$markers[] = array(
				'title' => $section->localitate,
				'description' => $section->adresa,
				'lat' => $section->lat,
				'lng' => $section->lng,
				'type' => ($section->winner==8 ? 'red' : 'yellow'),
				'link' => $section->getUrl(Config::$alegerisite.'/index.php','action=viewsectie&id='.$section->id)
			);
		}
		return json_encode($markers, JSON_UNESCAPED_UNICODE) ?: '[]';
	}
}
$n=new JsonAlegeriWebPage();

?>