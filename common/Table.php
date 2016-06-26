<?php
class Table extends Object {
	private $pagination=true;
	private $sql;
	private $sqlcount;
	private $showNrOrd=true;
	private $name;
	private $fields=array();
	private $dataset;
	private $rowscount;
	private $page=0;
	private $rowsperpage=50;
	private $navigationlink;
	private $currentPage;
	private $footer=false;
	private $footerlink;
	
	
	public function __construct(){
	}
	public function setCurrentPage($currentPage){
		$this->currentPage=$currentPage;
		if(isset($currentPage->page)){
			$this->setPage($currentPage->page);
		}
	}	
	public function getCurrentPage(){
		return $this->currentPage;
	}
	public function addField($field){
		array_push($this->fields,$field);
	}
	public function setNavigationLink($navigationlink){
		$this->navigationlink=$navigationlink;
	}
	public function setFooterLink($footerlink){
		$this->footerlink=$footerlink;
	}	
	public function setPage($page){
		$this->page=$page;
	}
	public function setShowNrOrd($showNrOrd){
		$this->showNrOrd=$showNrOrd;
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
		$this->sql=$sql." limit ".$this->page*$this->rowsperpage.",".$this->rowsperpage;
		if (is_null($this->dataset)){
			$this->dataset=DBManager::sql($this->sql);
		}
		$this->sqlcount="select count(*) as contor from ".$this->getFromSql($sql);
		
		$cs=DBManager::sql($this->sqlcount);
		if (is_null($this->rowscount)){
			
			$this->rowscount=0;
			while($row=mysql_fetch_assoc($cs)){
				$this->rowscount=$row["contor"];
			}
		}		
	}	
	public function setDataSet($dataset){
		$this->dataset=$dataset;
		if (mysql_num_rows($this->dataset)!=0){
			mysql_data_seek($this->dataset, 0);
		}
	}
	public function getDataSet(){
		return $this->dataset;
	}
	public function setPagination($pagination){
		$this->pagination=$pagination;
	}
	public function setFooter($footer){
		$this->footer=$footer;
	}	
	public function show(){
		$out='<div class="groupboxtable">';
		$out.='<table style="width: 100%;border:1px;">';		
		if (count($this->fields)!=0){
			$out.='<tr>';
			if ($this->showNrOrd){
				$out.='<th>Nr. Ord.</th>';
			}
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
					if ($this->showNrOrd){
						$out.='<td style="text-align: center;">'.$cnt.'</td>';
					}
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
		
		//echo $this->rowsperpage;
		if ($this->pagination){
			if ($this->rowscount>$this->rowsperpage){
				$out.='<div class="groupbox">';
				$out.='<table style="width:100%" ><tr><td style="text-align:center;border-style:none;" >';
				$n=$this->navigationlink;
				if ($this->page!=0){
					$out.='<a href="'.$n().'&page='.($this->page-1).'" class="link_button">< Inapoi</a>';
				}
				$out.=" ";
				if ((($this->page+1)*$this->rowsperpage)>$this->rowscount){
					$out.=" ".(($this->page*$this->rowsperpage)+1)." - ".$this->rowscount." din ".$this->rowscount." ";
				} else {
					$out.=" ".(($this->page*$this->rowsperpage)+1)." - ".(($this->page+1)*$this->rowsperpage)." din ".$this->rowscount." ";
				}
				if ((($this->page+1)*$this->rowsperpage)<$this->rowscount){
					$out.='<a href="'.$n().'&page='.($this->page+1).'" class="link_button">Inainte ></a>';
				}
				$out.='</td></tr></table>';				
				$out.='</div>';
			} else {
				$out.='<div class="groupbox">';
				$out.='<table style="width:100%" ><tr><td style="text-align:center;border-style:none;" >';
				if ((($this->page+1)*$this->rowsperpage)>$this->rowscount){
					$out.=' '.(($this->page*$this->rowsperpage)+1).' - '.$this->rowscount.' din '.$this->rowscount.' ';
				} else {
					$out.=' '.(($this->page*$this->rowsperpage)+1).' - '.(($this->page+1)*$this->rowsperpage).' din '.$this->rowscount.' ';
				}
				$out.='</td></tr></table>';
				$out.='</div>';
				
			}
		}
		if ($this->footer){
			$n=$this->footerlink;
			if (isset($n)){
				$out.='<div class="groupbox">';
				$out.='<table style="width:100%" ><tr><td style="align:center;" >';
				$out.=$n();
				$out.='</td></tr></table>';
				$out.='</div>';
			}
		}
		
		
		return $out;	
	}
	public function getResultSet(){
		return DBManager::sql($this->sql);
	}
	public function getFromSql($inthat){
		$this1="from";
		$this2="from as main";
		if (!is_bool(stripos($inthat, $this1)))
			return substr($inthat, strripos($inthat,$this1)+strlen($this1));
	}
}
?>
