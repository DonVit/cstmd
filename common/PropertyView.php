<?php
class PropertyView  {

	public static function getPropertiesSnapListView($currentPage,$userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$aroundlocalitateid){
	
		$sql=Property::getPropertiesByPageSql($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$aroundlocalitateid);
		$out='';
		$out.='<div class="groupboxtable">';
		
		$propertyViewTable=new PropertyViewTable($currentPage,$userid);
		$propertyViewTable->setRowsPerPage(10);
		$propertyViewTable->setPagination(false);
		$propertyViewTable->setFooter(true);
		$navigationlink=function() use ($currentPage,$raionid,$localitateid){
			$url=$currentPage->getUrlWithSpecialCharsConverted(Config::$imobilsite.'/index.php');
			if ($raionid!=0){
				$url=$currentPage->getUrlWithSpecialCharsConverted(Config::$imobilsite.'/index.php','raionid='.$raionid);
				if ($localitateid!=0){
					$url=$currentPage->getUrlWithSpecialCharsConverted(Config::$imobilsite.'/index.php','raionid='.$raionid.'&locationid='.$localitateid);
				} 				
			}
			return '<a class="link_button" href="'.$url.'" target="_blank">Vezi mai mult</a>';
		};
		$propertyViewTable->setFooterLink($navigationlink);
				
		$propertyViewTable->setSql($sql);
		$out.=$propertyViewTable->show();
		
		$out.='</div>';
		
		return $out;
		
		
	}
	public static function getPropertiesListView($currentPage,$userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid){
	
		$sql=Property::getPropertiesByPageSql($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid);
		$out='';
		$out.='<div class="groupboxtable">';
	
		$propertyViewTable=new PropertyViewTable($currentPage,$userid);
		$propertyViewTable->setRowsPerPage(20);
		$navigationlink=function() use ($currentPage,$imobilsauchirie){
			if($imobilsauchirie==0){
				$url=$currentPage->getBaseName()."?scopid=".User::getImobilCurrentScop()."&tipimobil=".User::getImobilCurrentTipImobil()."&subtipimobil=".User::getImobilCurrentSubTipImobil()."&raionid=".User::getImobilCurrentRaion()->id."&locationid=".User::getImobilCurrentLocation()->id."&sectorid=".User::getImobilCurrentSector()->id;
			} else {
				$url=$currentPage->getBaseName()."?scopid=".User::getChirieCurrentScop()."&tipimobil=".User::getChirieCurrentTipImobil()."&subtipimobil=".User::getChirieCurrentSubTipImobil()."&raionid=".User::getChirieCurrentRaion()->id."&locationid=".User::getChirieCurrentLocation()->id."&sectorid=".User::getChirieCurrentSector()->id;
			}
			return $url;	
		};
		$propertyViewTable->setNavigationLink($navigationlink);
		$propertyViewTable->setSql($sql);
		$out.=$propertyViewTable->show();
		$out.='</div>';
		return $out;
	}
}

?>
