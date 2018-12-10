<?php
ini_set("memory_limit","200M"); 
set_time_limit(10720);
require_once(__DIR__ . '/../main/loader.php');

class OSMMigratorWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setTitle("Migrator");
		$this->setLogoTitle("Migrator");
		$this->show();		
	}
	function show($html="LocationsWebPageHTML"){
		$out='<div id="container">';
		$out.='<div id="left">my left';
		$out.='</div>';
		$out.='<div id="center">';
		//$out.=$this->loadOsm();
		$out.='</div>';
		$out.='<div id="right"> my right';
		$out.='</div>';
		$out.='<div style="clear: both;"></div>';
		$out.='</div>';
		MainWebPage::show($out);
	}
	function loadOsm(){
		$out="";
		
		$sql="TRUNCATE TABLE `node_tags`";
		DBManager::doJustSql($sql);

		$sql="TRUNCATE TABLE  `nodes`;";
		DBManager::doJustSql($sql);		

		$objDOM = new DOMDocument('1.0','utf-8'); 
		$objDOM->load("moldova.osm"); //make sure path is correct 

		$nodes = $objDOM->getElementsByTagName("node"); 

		foreach( $nodes as $value ) { 
		$id=$value->getAttribute("id");
		$timestamp=$value->getAttribute("timestamp");
		$version=$value->getAttribute("version");
		$uid=$value->getAttribute("uid");
		$user=$value->getAttribute("user");
		$changeset=$value->getAttribute("changeset");
		$lat=$value->getAttribute("lat");
		$lon=$value->getAttribute("lon");
		if ($value->hasChildNodes()){
			$sql="INSERT INTO `nodes` (`id`, `latitude`, `longitude`, `changeset_id`, `timestamp`, `version`, `uid`, `user`) VALUES ('$id', '$lat', '$lon', '$changeset', '$timestamp', '$version', '$uid', '$user');";
			DBManager::doJustSql($sql);
			
			$tags="";
			$tags = $value->getElementsByTagName("tag"); 
			foreach( $tags as $value ) {
				$k=$value->getAttribute("k");
				$v=$value->getAttribute("v");
				$sql="INSERT INTO `node_tags` (`id`, `k`, `v`) VALUES ('$id', '$k', '".mysql_real_escape_string($v,DBConnection::getConnection())."');";
				DBManager::doJustSql($sql);
	
				
				if($value->hasAttributes()) { 
					$attributes = $value->attributes; 
					if(!is_null($attributes))  
						foreach ($attributes as $index=>$attr){		
							$tagvalues.="$attr->name = $attr->value, ";
						}
							
				}
				
			}
		}
		
		//$out.="$id, $timestamp, $version, $uid, $user, $changeset, $lat, $lon, $tagvalues <br>"; 	
		$out="done!";	
			
		
		}
		return $out;
	}
}
$n=new OSMMigratorWebPage();
?>
