<?php
/*
 * Created on 6 Nov 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class PropertyList extends Object{
	public function getUserPropertyList($page=0,$rowsperpage=50){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$p=new Property();
		//$rs=$p->getImobilByPage($userid,0,0,0,0,0,0,$page,$rowsperpage);
		$rs=$p->getProperties($userid,0,0,0,0,0,0,0,$page,$rowsperpage);
		$imobil_result_output="";
		$imobil_result_output.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"groupboxtable\" style=\"font-size:85%;\">";
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";		
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
				$imobil_result_output.= "" .
				//print_r($row);
				//"<tr class=\"gridtr".($i & 1)."\" onclick=\"ImobilTableRowClick(".$row->id.");\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"<tr class=\"gridtr".($i & 1)."\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"".$this->getFunctions($row->id, $row->scop_id)."" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAlbum($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getMap($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".date("Y-m-d", strtotime($row->data))."</td>" .
		  		"<td class=\"gridtd\" align=\"left\">".$row->scop_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$row->tipimobil_name." - ".$row->subtipimobil_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$this->getAdress($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAria($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getPretFormat($row)."</td>";
			}
		}
		$imobil_result_output.="</table>";
		return $imobil_result_output;
	}
	public function getImobilList($page,$rowsperpage){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$p=new Property();
		//$rs=$p->getImobilByPage($userid,0,0,0,0,0,0,$page,$rowsperpage);
		$rs=$p->getProperties();
		$imobil_result_output="";
		$imobil_result_output.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"grid\">";
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">1</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">2</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">3</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">4</th>";
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
				$imobil_result_output.= "" .
				//print_r($row);
				//"<tr class=\"gridtr".($i & 1)."\" onclick=\"ImobilTableRowClick(".$row->id.");\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"<tr class=\"gridtr".($i & 1)."\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"".$this->getFunctions($row->id, $row->scop_id)."" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAlbum($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getMap($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".date("Y-m-d", strtotime($row->data))."</td>" .
		  		"<td class=\"gridtd\" align=\"left\">".$row->scop_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$row->tipimobil_name." - ".$row->subtipimobil_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$this->getAdress($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAria($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getPretFormat($row)."</td>" .
				"<td ></td>";
			}
		}
		$imobil_result_output.="</table>";
		return $imobil_result_output;
	}	
	public function getChirieList($page,$rowsperpage){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$p=new Property();
		$rs=$p->getChirieByPage($userid,0,0,0,0,0,0,$page,$rowsperpage);
		$imobil_result_output="";
		$imobil_result_output.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"grid\">";
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\"></th>";						
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
				$imobil_result_output.= "" .
				//print_r($row);
				"<tr class=\"gridtr".($i & 1)."\" onclick=\"ImobilTableRowClick(".$row->id.");\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"".$this->getFunctions($row->id)."" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAlbum($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getMap($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".date("Y-m-d", strtotime($row->data))."</td>" .
		  		"<td class=\"gridtd\" align=\"left\">".$row->scop_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$row->tipimobil_name." - ".$row->subtipimobil_name."</td>" .
				"<td class=\"gridtd\" align=\"left\">".$this->getAdress($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getAria($row)."</td>" .
				"<td class=\"gridtd\" align=\"center\">".$this->getPretFormat($row)."</td>" .
				"<td ></td>";
			}
		}
		$imobil_result_output.="</table>";
		return $imobil_result_output;
	}	
	function getFunctions($imobilid,$scopid){
			if (($scopid==1)||($scopid==3)){
				$r='<td class="gridtd" align="center"><a href="'.Config::$imobilsite.'/property.php?id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/view.jpg" border=0 align="middle"></a></td>';
				$r.='<td class="gridtd" align="center"><a href="'.Config::$imobilsite.'/add.php?id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/edit.png" border=0 align="middle"></a></td>';
				$r.='<td class="gridtd" align="center"><a href="'.Config::$imobilsite.'/add.php?action=republish&id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/reload.png" border=0 align="middle" alt="republica"></a></td>';
				$r.='<td class="gridtd" align="center"><a href="'.Config::$imobilsite.'/add.php?action=delete&id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/delete.png" border=0 align="middle"></a></td>';
				
			} else {
				$r='<td class="gridtd" align="center"><a href="'.Config::$chiriesite.'/property.php?id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/view.jpg" border=0 align="middle"></a></td>';
				$r.='<td class="gridtd" align="center"><a href="'.Config::$chiriesite.'/add.php?id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/edit.png" border=0 align="middle"></a></td>';
				$r.='<td class="gridtd" align="center"><a href="'.Config::$chiriesite.'/add.php?action=republish&id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/reload.png" border=0 align="middle" alt="republica"></a></td>';
				$r.='<td class="gridtd" align="center"><a href="'.Config::$chiriesite.'/add.php?action=delete&id='.$imobilid.'"><img src="'.Config::$commonsite.'/img/delete.png" border=0 align="middle"></a></td>';			
			}
		return $r;
	}
	function getAlbum($row){
		if (is_numeric($row->image_id)){
			//return '<a href="'.Config::$imobilsite.'/property.php?id='.$row->id.'"><img src="'.Config::$commonsite.'/img/photo.gif" border=0 align="middle"></a>';
			return '<img src="'.Config::$commonsite.'/img/photo.gif" border=0 align="middle">';			
		} else {
			return "";
		}
	}
	function getMap($row){
		if (($row->lat!=0)&&($row->lng!=0)){
			//return '<a href="'.Config::$imobilsite.'/property.php?id='.$row->id.'"><img src="'.Config::$commonsite.'/img/map.png" border=0 align="middle"></a>';
			return '<img src="'.Config::$commonsite.'/img/map.png" border=0 align="middle">';
		} else {
			return "";
		}
	}
	public function getAdress($row){
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
	public function getTelefon1($row){
		$telefon="";
		if ($row->contact_phone!=""){
			$telefon.=$row->contact_phone.", ";
		}
		if ($row->contact_mobile!=""){
			$telefon.=$row->contact_mobile;
		}
		return $telefon;
	}
	public function getPretFormat($row){
		$pret="";
		if ($row->pret!=0){
			$pret=number_format($row->pret,0,"."," ")." ".$row->valuta_name."/".$row->masura_name;
		}
		return $pret;
	}
	public function getAria($row){
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
	public function getAriaTotalaFormat($row){
		$ariatotala="";
		if ($row->aria_totala!=0){
			$ariatotala.=number_format($row->aria_totala,1,"."," ");
		}
		return $ariatotala;
} 	
 }
?>
