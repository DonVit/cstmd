<?php

class PropertyList extends Object{
	public function getUserPropertyList($page=0,$rowsperpage=50){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$p=new Property();
		$rs=$p->getProperties($userid,0,0,0,0,0,0,0,$page,$rowsperpage);
		$imobil_result_output="";
		$imobil_result_output.='<table width="100%" class="table table-hover">';
		$imobil_result_output.='<tr>';
		$imobil_result_output.='<th></th>';
		$imobil_result_output.='<th></th>';
		$imobil_result_output.='<th></th>';
		$imobil_result_output.='<th></th>';		
		$imobil_result_output.='<th></th>';
		$imobil_result_output.='<th></th>';
		$imobil_result_output.='<th>Data Pub.</th>';
		$imobil_result_output.='<th>Tip Anunt</th>';
		$imobil_result_output.='<th>Tip Imobil</th>';
		//$imobil_result_output.='<th>Addresa</th>';
		//$imobil_result_output.='<th>Aria</th>';
		$imobil_result_output.='<th>Pret</th>';
		$imobil_result_output.='</tr>';
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
				//"<td class=\"gridtd\" align=\"left\">".$this->getAdress($row)."</td>" .
				//"<td class=\"gridtd\" align=\"center\">".$this->getAria($row)."</td>" .
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
		$imobil_result_output.='<table width="100%" class="table table-hover">';
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th>1</th>";
		$imobil_result_output.="<th>2</th>";
		$imobil_result_output.="<th>3</th>";
		$imobil_result_output.="<th>4</th>";
		$imobil_result_output.="<th>Foto</th>";
		$imobil_result_output.="<th>Harta</th>";
		$imobil_result_output.="<th>Data Pub.</th>";
		$imobil_result_output.="<th>Tip Anunt</th>";
		$imobil_result_output.="<th>Tip Imobil</th>";
		$imobil_result_output.="<th>Addresa</th>";
		$imobil_result_output.="<th>Aria</th>";
		$imobil_result_output.="<th>Pret</th>";
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
		$imobil_result_output.='<table width="100%" class="table table-hover">';
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th></th>";
		$imobil_result_output.="<th></th>";
		$imobil_result_output.="<th></th>";
		$imobil_result_output.="<th></th>";						
		$imobil_result_output.="<th>Foto</th>";
		$imobil_result_output.="<th>Harta</th>";
		$imobil_result_output.="<th>Data Pub.</th>";
		$imobil_result_output.="<th>Tip Anunt</th>";
		$imobil_result_output.="<th>Tip Imobil</th>";
		$imobil_result_output.="<th>Addresa</th>";
		$imobil_result_output.="<th>Aria</th>";
		$imobil_result_output.="<th>Pret</th>";
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
				$r=$this->getFunctionsRow(Config::$imobilsite,$imobilid);								
			} else {
				$r=$this->getFunctionsRow(Config::$chiriesite,$imobilid);
			}
		return $r;
	}
	function getFunctionsRow($url,$imobilid){
		$r='<td class="gridtd" align="center"><a href="'.$url.'/property.php?id='.$imobilid.'"><span class="glyphicon glyphicon-search" title="click pentru detalii"></span></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.$url.'/add.php?id='.$imobilid.'"><span class="glyphicon glyphicon-edit" title="click pentru editare"></span></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.$url.'/add.php?action=republish&id='.$imobilid.'"><span class="glyphicon glyphicon-refresh" title="click pentru republicare"></span></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.$url.'/add.php?action=delete&id='.$imobilid.'"><span class="glyphicon glyphicon-remove" title="click pentru stergere"></span></a></td>';
		return $r;		
	}
	function getAlbum($row){
		if (is_numeric($row->image_id)){
			return '<a href="'.Config::$imobilsite.'/property.php?id='.$row->id.'"><span class="glyphicon glyphicon-camera"></span></a>';			
		} else {
			return "";
		}
	}
	function getMap($row){
		if (($row->lat!=0)&&($row->lng!=0)){
			return '<a href="'.Config::$imobilsite.'/property.php?id='.$row->id.'"><span class="glyphicon glyphicon-map-marker"></span></a>';
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
