<?php
/*
 * Created on 6 Nov 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class CompanyList extends Object{
	public function getUserCompanyList($page=0,$rowsperpage=50){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$c=new Company();
		//$rs=$p->getImobilByPage($userid,0,0,0,0,0,0,$page,$rowsperpage);
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
				//print_r($row);
				//"<tr class=\"gridtr".($i & 1)."\" onclick=\"ImobilTableRowClick(".$row->id.");\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"<tr class=\"gridtr".($i & 1)."\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"".$this->getFunctions($row->id, $row->scop_id)."" .
		  		"<td class=\"gridtd\" align=\"left\">".$row->name."</td>";
			}
		}
		$imobil_result_output.="</table>";
		return $imobil_result_output;
	}
	function getFunctions($imobilid,$scopid){			
		$r='<td class="gridtd" align="center"><a href="'.Config::$companiesite.'/index.php?id='.$imobilid.'"><img src="'.Config::$mainsite.'/common/img/view.jpg" border=0 align="middle"></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$companiesite.'/add.php?id='.$imobilid.'"><img src="'.Config::$mainsite.'/common/img/edit.png" border=0 align="middle"></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$companiesite.'/add.php?action=delete&id='.$imobilid.'"><img src="'.Config::$mainsite.'/common/img/delete.png" border=0 align="middle"></a></td>';
		return $r;
	}

 	
 }
?>
