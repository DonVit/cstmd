<?php
class CompanyList extends Object{
	public function getUserCompanyList($page=0,$rowsperpage=50){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$c=new Company();
		if ($userid!=0){
			$cs=$c->getAll("user_id=".$userid,"",$page,$rowsperpage);
		} else {
			$cs=$c->getAll("","id desc",$page,$rowsperpage);
		}
		$imobil_result_output="";
		$imobil_result_output.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"groupboxtable\" style=\"font-size:85%;\">";
		$imobil_result_output.="<tr>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Deschide</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Editeaza</th>";
		$imobil_result_output.="<th class=\"gridth\" align=\"center\">Sterge</th>";		
		$imobil_result_output.="<th class=\"gridth\" align=\"center\" width=\"100%\">Nume</th>";
		$imobil_result_output.="</tr>";
		$i=0;
		if(count($cs)!=0){
			foreach($cs as $row){
				$i++;
				$imobil_result_output.= "" .
				"<tr class=\"gridtr".($i & 1)."\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"".$this->getFunctions($row->id)."" .
		  		"<td class=\"gridtd\" align=\"left\">".$row->name."</td>";
			}
		}
		$imobil_result_output.="</table>";
		return $imobil_result_output;
	}
	function getFunctions($id){			
		$r='<td class="gridtd" align="center"><a href="'.Config::$companiesite.'/index.php?id='.$id.'"><img src="'.Config::$commonsite.'/img/view.jpg" border=0 align="middle"></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$companiesite.'/add.php?id='.$id.'"><img src="'.Config::$commonsite.'/img/edit.png" border=0 align="middle"></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$companiesite.'/add.php?action=delete&id='.$id.'"><img src="'.Config::$commonsite.'/img/delete.png" border=0 align="middle"></a></td>';
		return $r;
	}

 	
 }
?>
