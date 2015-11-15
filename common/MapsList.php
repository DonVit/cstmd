<?php
class MapsList extends Object {
	public function getUserMapsList($page=0,$rowsperpage=50){	
		
		$userid=0;
		if (User::getCurrentUser()->role_id==1){
			$userid=User::getCurrentUser()->id;
		}
		$p=new Map();
		if ($userid!=0){
			$ps=$p->getAll("user_id=".$userid,"",$page,$rowsperpage);
		} else {
			$ps=$p->getAll("","id desc",$page,$rowsperpage);
		}
		$result_output="";
		$result_output.='<table width="100%" class="table table-hover">';
		$result_output.='<tr>';
		$result_output.='<th></th>';
		$result_output.='<th></th>';
		$result_output.='<th></th>';		
		$result_output.='<th width="100%">Nume</th>';
		$result_output.='</tr>';
		
		$i=0;
		if(count($ps)!=0){
			foreach($ps as $row){
				$i++;
				$result_output.= "" .
				"<tr onmouseover=\"ImobilTableRowMouseOver(this,".($i & 1).");\" onmouseout=\"ImobilTableRowMouseOut(this,".($i & 1).");\">" .
				"".$this->getFunctions($row->id)."" .
		  		"<td>".$row->title."</td>";
			}
		}
		$result_output.="</table>";
		return $result_output;
	}
	function getFunctions($id){			
		$r='<td class="gridtd" align="center"><a href="'.Config::$mapssite.'/index.php?action=viewmap&id='.$id.'"><span class="glyphicon glyphicon-search"></span></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$mapssite.'/add.php?id='.$id.'"><span class="glyphicon glyphicon-edit"></span></a></td>';
		$r.='<td class="gridtd" align="center"><a href="'.Config::$mapssite.'/add.php?action=delete&id='.$id.'"><span class="glyphicon glyphicon-remove"></span></a></td>';
		return $r;
	}

 	
 }
?>
