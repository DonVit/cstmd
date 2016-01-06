<?php
class Table extends Object {
	private $pagination=true;
	private $sql;
	private $sqlcount;
	private $name;
	private $fields=array();
	private $dataset;
	private $rowscount;
	private $page;
	private $rowsperpage;
	private $navigationlink;
	
	
	public function __construct(){
	}
	public function addField($field){
		array_push($this->fields,$field);
	}
	public function setNavigationLink($navigationlink){
		$this->navigationlink=$navigationlink;
	}	
	public function setPage($page){
		$this->page=$page;
	}
	
	public function setRowsPerPage($rowsperpage){
		$this->rowsperpage=$rowsperpage;
	}
	public function setRowsCount($count){
		$this->rowscount=$count;
	}
	public function getRowsCount(){
		return $this->rowscount;
	}	
	public function setSql($sql){
		$this->sql=$sql;
		$this->sqlcount="select count(*) as contor from ".$this->after("from", $this->sql);
	}	
	public function setDataSet($dataset){
		$this->dataset=$dataset;
	}
	public function getDataSet(){
		if (is_null($this->dataset)){
			$this->dataset=$this->getResultSet();
		}
		return $this->dataset;
	}
	public function setPagination($pagination){
		$this->pagination=$pagination;
	}	
	public function show(){
		$out='<div class="groupboxtable">';
		$out.='<table style="width: 100%;border:1px;">';		
		if (count($this->fields)!=0){
			$out.='<tr>';
			$out.='<th>Nr. Ord.</th>';
			foreach($this->fields as $field){
				$out.='<th>'.$field->title.'</th>';
			}
			$out.='</tr>';

			if (!(is_bool($this->getDataSet()) === true)) {
				$fields=mysql_num_fields($this->getDataSet());
				$arr=array();
				$cnt=$this->page*$this->rowsperpage+1;
				while($row = mysql_fetch_object($this->getDataSet())){
					$out.='<tr>';
					$out.='<td style="text-align: center;">'.$cnt.'</td>';
					foreach($this->fields as $field){
						$n=$field->source;
						$s=$field->style;
						$o=$field->openlink;
						if ($o!=""){
							$out.='<td  style="'.$s.'">'.$o($row).'</td>';
						} else {
							$out.='<td  style="'.$s.'">'.$row->$n.'</td>';
						}
					}
					$cnt=$cnt+1;
					$out.='</tr>';
				}
			}
		
		} else {

			if (!(is_bool($this->getDataSet()) === true)) {
				$fields=mysql_num_fields($this->getDataSet());
				
				//add column titles
				$out.='<th>';
				$out.='<th>Nr. Ord.</th>';
				for ($i=0; $i < $fields; $i++) {
					$n=mysql_field_name($this->getDataSet(), $i);
					$out.='<td>'.$n.'</td>';
				}
				$out.='</th>';
	
				//add column values
				while($row = mysql_fetch_object($this->getDataSet())){
					$out.='<tr>';
					for ($i=0; $i < $fields; $i++) {
						$n=mysql_field_name($this->getDataSet(), $i);
						$out.='<td>'.$row->$n.'</td>';
					}
					$out.='</tr>';
				}
			}
				
			
		}
		$out.='</table>';
		$out.='</div>';
		
		if ($this->pagination){
			if ($this->rowscount>$this->rowsperpage){
				$out.='<div class="groupbox">';
				$out.='<table style="width:100%" ><tr><td style="align:center;" >';
				$n=$this->navigationlink;
				if ($this->page!=0){
					$out.='<a href="'.$n().'&page='.($this->page-1).'" class="link_button">< Inapoi</a>';
				}
				$out.=" ";
				if ((($this->page+1)*$this->rowsperpage)<$this->rowscount){
					$out.='<a href="'.$n().'&page='.($this->page+1).'" class="link_button">Inainte ></a>';
				}
				$out.='</td></tr></table>';
				$out.='</div>';
			}
		}
		
		return $out;	
	}
	public function getResultSet(){
		return DBManager::sql($this->sql);
	}
	public function after ($this1, $inthat){
		if (!is_bool(strpos($inthat, $this1)))
			return substr($inthat, strpos($inthat,$this1)+strlen($this1));
	}
}
?>
