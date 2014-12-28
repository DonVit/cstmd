<?php
/*
 * Created on 27 Feb 2009
 *
 */
class Table extends Object {
	public $name;
	public $fields=array();
	public $dataset;

	public function AddField($field){
		array_push($this->fields,$field);
	}
	public function setDataSource($dataset){
		$this->dataset=$dataset;
	}
	public function show(){
		$out="<table>";
		$out.="<tr>";
		foreach($this->fields as $field){
			$out.="<td>".$field->title."</td>";
		}
		$out.="</tr>";
		
		if (count($this->dataset)!=0){			
			foreach($this->dataset as $ds){
				//$out.='<a href="?r='.$r->id.'">'.$r->getFullNameDescription().'</a><br>';
				//$url=$this->getUrl("index.php","action=viewlocalitate&lc=".$l->id);
				//$out.='<tr><td>'.$c.'</td><td><a href="'.$url.'">'.$l->getFullNameDescription().'</a></td></tr>';
				$c=$c+1;	
			}
		}
		
		$out.="</table>";
		return $out;	
	}			
}
class TableField extends Object{
	public $id;
	public $title;
	public $source;
	function __construct($id,$title,$source){
		$this->id=$id;
		$this->title=$title;
		$this->source=$source;
	}
}
?>
