<?php
/*
 * Created on 27 Feb 2009
 *
 */
class PropertyView  {

	function getPropertiesListView($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage){
	
		$p=new Property();
		$rs=$p->getPropertiesByPage($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage);
		$imobil_result_output='<div class="groupboxtable">';
		$imobil_result_output.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"grid\">";
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Link</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Foto</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Harta</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Data Pub.</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Tip Anunt</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Tip Imobil</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Addresa</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Aria</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Pret</th>";
		$imobil_result_output.="</tr>";
		$i=0;
		if(count($rs)!=0){
			foreach($rs as $row){
				$i++;
				$url=Config::$imobilsite."/property.php?id=".$row->id;
				$imobil_result_output.= "" .

				"<tr class=\"gridtr".($i & 1)."\" onclick=\"ImobilTableRowClick(".$url.");\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"<td class=\"gridtd\" align=\"center\">".$this->getFunctions($row->id)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAlbum($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getMap($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".date("Y-m-d", strtotime($row->data))."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$row->scop_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$row->tipimobil_name." - ".$row->subtipimobil_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$this->getAdress($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAria($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getPret($row)."</td>";
			}
		}
		$imobil_result_output.="</table>";
		$imobil_result_output.="</div>";
		
		$cnt=$p->getPropertiesByCount($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid);
		
		//$cnt=count($rs);
		//$cnt=0;
		$out='<div class="container groupbox">';;
		$out.="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" class=\"pages1\">";
		$out.="<tr>";
		$out.="<td align=\"left\"><a href=\"add.php\" class=\"link_button\">Adauga Anunt</a></td>";
		$out.="<td align=\"right\">";
		
		//$url=$this->getBaseName()."?scopid=".User::getImobilCurrentScop()."&tipimobil=".User::getImobilCurrentTipImobil()."&subtipimobil=".User::getImobilCurrentSubTipImobil()."&raionid=".User::getImobilCurrentRaion()->id."&locationid=".User::getImobilCurrentLocation()->id."&sectorid=".User::getImobilCurrentSector()->id;
		$url="?scopid=".User::getImobilCurrentScop()."&tipimobil=".User::getImobilCurrentTipImobil()."&subtipimobil=".User::getImobilCurrentSubTipImobil()."&raionid=".User::getImobilCurrentRaion()->id."&locationid=".User::getImobilCurrentLocation()->id."&sectorid=".User::getImobilCurrentSector()->id;
		if ($cnt==0){
			return "";
		}
		if ($page!=0){
			$out.="<a href=/".$url."&page=".($page-1)." class=\"link_button\"><<<</a>";
		}
		if ((($page+1)*$rowsperpage)>$cnt){
			$out.=" ".(($page*$rowsperpage)+1)." - ".$cnt." din ".$cnt." ";
		} else {
			$out.=" ".(($page*$rowsperpage)+1)." - ".(($page+1)*$rowsperpage)." din ".$cnt." ";
		}
		if ((($page+1)*$rowsperpage)<$cnt){
			$out.="<a href=/".$url."&page=".($page+1)." class=\"link_button\">>>></a>";
		}
		$out.="</td>";
		$out.="</tr>";
		$out.="</table>";
		$out.="</div>";		
		
		
		return $imobil_result_output.$out;
	}
	function getPropertiesSnapListView($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage){
	
		$p=new Property();
		$rs=$p->getPropertiesByPage($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid,$page,$rowsperpage);
		$imobil_result_output='<div class="groupboxtable">';
		$imobil_result_output.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"grid\">";
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Link</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Foto</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Harta</th>";
		//$imobil_result_output.="<th class=\"gridth\" align=\"center\">Data Pub.</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Tip Anunt</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Tip Imobil</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Addresa</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Aria</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Pret</th>";
		$imobil_result_output.="</tr>";
		$i=0;
		if(count($rs)!=0){
			foreach($rs as $row){
				$i++;
				$url=Config::$imobilsite."/property.php?id=".$row->id;
				$imobil_result_output.= "" .
	
						"<tr class=\"gridtr".($i & 1)."\" onclick=\"ImobilTableRowClick(".$url.");\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
						"<td class=\"gridtd\" align=\"center\">".$this->getFunctions($row->id)."</td>" .
						"<td class=\"gridtd\" align=\"center\">".$this->getAlbum($row)."</td>" .
						"<td class=\"gridtd\" align=\"center\">".$this->getMap($row)."</td>" .
						//"<td class=\"gridtd\" align=\"center\">".date("Y-m-d", strtotime($row->data))."</td>" .
						"<td class=\"gridtd\" align=\"left\">".$row->scop_name."</td>" .
						"<td class=\"gridtd\" align=\"left\">".$row->tipimobil_name." - ".$row->subtipimobil_name."</td>" .
						"<td class=\"gridtd\" align=\"left\">".$this->getAdress($row)."</td>" .
						"<td class=\"gridtd\" align=\"center\">".$this->getAria($row)."</td>" .
						"<td class=\"gridtd\" align=\"center\">".$this->getPret($row)."</td>";
			}
		}
		$imobil_result_output.="</table>";
		$imobil_result_output.="</div>";
	
		$cnt=$p->getPropertiesByCount($userid,$imobilsauchirie,$scopid,$tipimobilid,$subtipimobilid,$raionid,$localitateid,$sectorid);
	
		//$cnt=count($rs);
		//$cnt=0;
		$out='<div class="container groupbox">';;
		$out.="<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" class=\"pages1\">";
		$out.="<tr>";
		$out.="<td align=\"left\"><a href=\"".Config::$imobilsite."/add.php\" class=\"link_button\">Adauga Anunt</a></td>";
		$out.="<td align=\"right\">";
	
		//$url=$this->getBaseName()."?scopid=".User::getImobilCurrentScop()."&tipimobil=".User::getImobilCurrentTipImobil()."&subtipimobil=".User::getImobilCurrentSubTipImobil()."&raionid=".User::getImobilCurrentRaion()->id."&locationid=".User::getImobilCurrentLocation()->id."&sectorid=".User::getImobilCurrentSector()->id;
		$url=Config::$imobilsite."/index.php?scopid=".$scopid."&tipimobil=".$tipimobilid."&subtipimobil=".$subtipimobilid."&raionid=".$raionid."&locationid=".$localitateid."&sectorid=".$sectorid;
		$out.="<a href=\"".$url."\" class=\"link_button\">Vezi mai multe</a>";
		/*
		if ($cnt==0){
			return "";
		}
		if ($page!=0){
			$out.="<a href=/".$url."&page=".($page-1)." class=\"link_button\"><<<</a>";
		}
		if ((($page+1)*$rowsperpage)>$cnt){
			$out.=" ".(($page*$rowsperpage)+1)." - ".$cnt." din ".$cnt." ";
		} else {
			$out.=" ".(($page*$rowsperpage)+1)." - ".(($page+1)*$rowsperpage)." din ".$cnt." ";
		}
		if ((($page+1)*$rowsperpage)<$cnt){
			$out.="<a href=/".$url."&page=".($page+1)." class=\"link_button\">>>></a>";
		}
		*/
		$out.="</td>";
		$out.="</tr>";
		$out.="</table>";
		$out.="</div>";
	
	
		return $imobil_result_output.$out;
	}		
	function getFunctions($imobilid){
		return "<a href=\"".Config::$imobilsite."/property.php?id=".$imobilid."\"><img src=\"".Config::$mainsite."/common/img/view.jpg\" border=0 align=\"middle\"></a>";
	}
	function getAlbum($row){
		if (is_numeric($row->image_id)){
			return "<a href=\"".Config::$imobilsite."/property.php?id=".$row->id."\"><img src=\"".Config::$mainsite."/common/img/photo.gif\" border=0 align=\"middle\"></a>";
		} else {
			return "";
		}
	}
	function getMap($row){
		if (($row->lat!=0)&&($row->lng!=0)){
			return "<a href=\"".Config::$imobilsite."/property.php?id=".$row->id."\"><img src=\"".Config::$mainsite."/common/img/map.png\" border=0 align=\"middle\"></a>";
		} else {
			return "";
		}
	}
	function getAdress($row){
		if ($row->municipiu){
			$address="m. ".$row->raion_name;
		} else {
			$address="r. ".$row->raion_name;
		}
		if ($row->localitate_name!=""){
			$address.=", ".$row->localitate_tip." ".$row->localitate_name;
		}
		if ($row->sector_name!=""){
			$address.=", sect. ".$row->sector_name;
		}
		return $address;
	}
	function getTelefon($row){
		$telefon="";
		if ($row->contact_phone!=""){
			$telefon.=$row->contact_phone.", ";
		}
		if ($row->contact_mobile!=""){
			$telefon.=$row->contact_mobile;
		}
		return $telefon;
	}
	function getPret($row){
		$pret="";
		if ($row->pret!=0){
			$pret=number_format($row->pret,0,"."," ")." ".$row->valuta_name."/".$row->masura_name;
		}
		return $pret;
	}
	function getAria($row){
		$aria="";
		if ($row->aria_lot!=0){
			if ($row->aria_lot!=0){
				$aria.=number_format($row->aria_lot,1,"."," ")." ".$row->aria_masura_name;
			} else {
				$aria.="";
			}
		} else {
			if ($row->aria_totala!=0){
				$aria.=number_format($row->aria_totala,1,"."," ")." m2";
			} else {
				$aria.="";
			}
		}
		return $aria;
	}
	function getAriaTotala($row){
		$ariatotala="";
		if ($row->aria_totala!=0){
			$ariatotala.=number_format($row->aria_totala,1,"."," ");
		}
		return $ariatotala;
	}
	
}

?>
