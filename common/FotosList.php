<?php
class FotosList extends Object{
	public function getUserFotosList($page=0,$rowsperpage=50){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$p=new Photo();
		if ($userid!=0){
			$ps=$p->getAll("user_id=".$userid,"",$page,$rowsperpage);
		} else {
			$ps=$p->getAll("","id desc",$page,$rowsperpage);
		}
		$result_output="";
		$result_output.="<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" class=\"groupboxtable\" style=\"font-size:85%;\">";
		$result_output.="<tr>";
		$result_output.="<th class=\"gridth\" align=\"center\">Deschide</th>";
		$result_output.="<th class=\"gridth\" align=\"center\">Editeaza</th>";
		$result_output.="<th class=\"gridth\" align=\"center\">Sterge</th>";		
		$result_output.="<th class=\"gridth\" align=\"center\" width=\"100%\">Nume</th>";
		$result_output.="</tr>";
		$i=0;
		if(count($ps)!=0){
			foreach($ps as $row){
				$i++;
				$result_output.= "" .
				"<tr class=\"gridtr".($i & 1)."\" onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"".$this->getFunctions($row->id)."" .
		  		"<td class=\"gridtd\" align=\"left\">".$row->title."</td>";
			}
		}
		$result_output.="</table>";
		return $result_output;
	}
	function getFunctions($id){			
		$r='<td class="gridtd" align="center"><a href="'.Config::$imagessite.'/index.php?id='.$id.'"><img src="'.Config::$commonsite.'/img/view.jpg" border=0 align="middle"></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$imagessite.'/add.php?id='.$id.'"><img src="'.Config::$commonsite.'/img/edit.png" border=0 align="middle"></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$imagessite.'/add.php?action=delete&id='.$id.'"><img src="'.Config::$commonsite.'/img/delete.png" border=0 align="middle"></a></td>';
		return $r;
	}

 	
 }
?>
