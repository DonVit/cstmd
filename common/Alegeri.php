<?php
class Alegeri extends DBManager {
	public $id;
	public $data;
	private static $alegeri=null;
	function getTableName(){
		return "alegeri";
	}
	public static function getAlegeriInstance(){
		if (Alegeri::$alegeri==null){
			$alegeri=new Alegeri();
		}
		return $alegeri;
	}
	public static function getResultsByLocation($raion_id,$localitate_id){
			$sql="select a.id, data, voturi_valabile, pcrm, round(pcrm*100/voturi_valabile,0) as pcrmp, psrm, round(psrm*100/voturi_valabile,0) as psrmp, pldm, round(pldm*100/voturi_valabile,0) as pldmp, pdm, round(pdm*100/voturi_valabile,0) as pdmp, pl, round(pl*100/voturi_valabile,0) as plp, altii,round(altii*100/voturi_valabile,0) as altiip from alegeri a, (SELECT '090405' as alegeri_id,sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, 0 as psrm,sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-090405` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr where  r.raion_id=$raion_id and s.localitate_id=$localitate_id and s.alegeri_id='090405' union all SELECT '090729',sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, 0 as psrm,sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-090729` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr where  r.raion_id=$raion_id and s.localitate_id=$localitate_id and s.alegeri_id='090729' union all SELECT '101128',sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, 0 as psrm,sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-101128` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr where  r.raion_id=$raion_id and s.localitate_id=$localitate_id and s.alegeri_id='101128' union all SELECT '141130',sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, sum(psrm) as psrm, sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(psrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-141130` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr where  r.raion_id=$raion_id and s.localitate_id=$localitate_id and s.alegeri_id='141130') as r where a.id=r.alegeri_id order by a.id desc";
			$rs=Alegeri::getAlegeriInstance()->sql($sql);
			return Alegeri::showResults($rs);
	}
	public static function getResultsByPrimarie($raion_id,$localitate_id){
			$sql="select a.id, data, voturi_valabile, pcrm, round(pcrm*100/voturi_valabile,0) as pcrmp, psrm, round(psrm*100/voturi_valabile,0) as psrmp, pldm, round(pldm*100/voturi_valabile,0) as pldmp, pdm, round(pdm*100/voturi_valabile,0) as pdmp, pl, round(pl*100/voturi_valabile,0) as plp, altii,round(altii*100/voturi_valabile,0) as altiip from alegeri a, (SELECT '090405' as alegeri_id,sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, 0 as psrm, sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-090405` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr inner join localitate as l on s.localitate_id=l.id where  r.raion_id=$raion_id and (l.id=$localitate_id or l.parent_id=$localitate_id) and s.alegeri_id='090405' union all SELECT '090729',sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, 0 as psrm, sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-090729` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr inner join localitate as l on s.localitate_id=l.id where  r.raion_id=$raion_id and (l.id=$localitate_id or l.parent_id=$localitate_id) and s.alegeri_id='090729' union all SELECT '101128',sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, 0 as psrm, sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-101128` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr inner join localitate as l on s.localitate_id=l.id where  r.raion_id=$raion_id and (l.id=$localitate_id or l.parent_id=$localitate_id) and s.alegeri_id='101128' union all SELECT '141130',sum(voturi_valabile) as voturi_valabile,sum(pcrm) as pcrm, sum(psrm) as psrm, sum(pldm) as pldm,sum(pdm) as pdm,sum(pl) as pl,sum(voturi_valabile)-(sum(pcrm)+sum(psrm)+sum(pldm)+sum(pdm)+sum(pl)) as altii FROM `rezultate-141130` as r inner join sectii as s on r.raion_id=s.raion_id and r.nr=s.nr inner join localitate as l on s.localitate_id=l.id where  r.raion_id=$raion_id and (l.id=$localitate_id or l.parent_id=$localitate_id) and s.alegeri_id='141130') as r where a.id=r.alegeri_id order by a.id desc";
			$rs=Alegeri::getAlegeriInstance()->sql($sql);
			return Alegeri::showResults($rs);
	}			
	public static function showResults($rs){
			$out="";
			if (count($rs)!=0){
			//if ((count($rs)!=0)&&(!is_null($rs[0]->voturi_valabile))){
				$rows="";
				$trows="";
				while($r = mysql_fetch_object($rs)){
					if ($r->psrmp!=0) {
						$rows.="['$r->data', $r->psrmp, 'color: #eb1c23;', '$r->psrmp%',$r->pcrmp, 'color: #d65151', '$r->pcrmp%',  $r->pdmp, 'color: #224499','$r->pdmp%', $r->pldmp, 'color: #008000','$r->pldmp%', $r->plp,'color: #ADD8E6','$r->plp%', $r->altiip, 'color: #A52A2A', '$r->altiip%'],";
					} else {
						$rows.="['$r->data', 0.001, 'color: #eb1c23', '',$r->pcrmp, 'color: #d65151;', '$r->pcrmp%', $r->pdmp, 'color: #224499','$r->pdmp%', $r->pldmp, 'color: #008000','$r->pldmp%', $r->plp,'color: #ADD8E6','$r->plp%', $r->altiip, 'color: #A52A2A', '$r->altiip%'],";
					}
					$trows.="<tr><td style=\"text-align:center;\">$r->data</td><td style=\"text-align:center;\">$r->psrm</td><td style=\"text-align:center;\">$r->pcrm</td><td style=\"text-align:center;\">$r->pdm</td><td style=\"text-align:center;\">$r->pldm</td><td style=\"text-align:center;\">$r->pl</td><td style=\"text-align:center;\">$r->altii</td><td style=\"text-align:center;\">$r->voturi_valabile</td></tr>";
				}
				$rows=rtrim($rows,",");
				$out.="<script type=\"text/javascript\">google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});google.setOnLoadCallback(drawChart);
			      function drawChart() {
			      var data = google.visualization.arrayToDataTable([
			        ['Year', 'PSRM', { role: 'style' }, { role: 'annotation' },'PCRM', { role: 'style' }, { role: 'annotation' }, 'PDM', { role: 'style' }, { role: 'annotation' }, 'PLDM', { role: 'style' }, { role: 'annotation' }, 'PL', { role: 'style' }, { role: 'annotation' },'Altii', { role: 'style' }, { role: 'annotation' } ],
			      	$rows]);
			
			      var options = {
			        title: 'Preferintele electorale pe parcursul anilor',
					vAxis: {title: 'Anii Electorali',  titleTextStyle: {color: 'red'}},
					hAxis: {title: 'Procentul de voturi',  titleTextStyle: {color: 'red'}},
					width: 580,
			        height: 360,
			        legend: { position: 'none'},
			        bar: { groupWidth: '75%' },        
			        isStacked: true,
			        backgroundColor:'eeeeee',
			          annotations: {
					    textStyle: {
					      fontName: 'Times-Roman',
					      fontSize: 12,
					
					    }
					 }
			      };		
					
					
			        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
			
			        chart.draw(data, options);
			      }
			    </script>
			    <div id=\"chart_div\" style=\"width: 580px; height: 360px;\"></div>"; 
			   	$out.='<br>';
			    $out.='<div class="groupboxtable">';
			    
			    mysql_data_seek($rs, 0);
			    
			    $table=new Table();
			    $table->setDataSet($rs);
			    $table->setShowNrOrd(false);
			    $table->addField(new TableField(1, "Data Alegerilor", "data", "text-align: center;",""));
			    $table->addField(new TableField(2, "PSRM", "psrm", "text-align: center;",""));
			    $table->addField(new TableField(3, "PCRM", "pcrm", "text-align: center;",""));
			    $table->addField(new TableField(4, "PDM", "pdm", "text-align: center;",""));
			    $table->addField(new TableField(5, "PLDM", "pldm", "text-align: center;",""));
			    $table->addField(new TableField(6, "PL", "pl", "text-align: center;",""));
			    $table->addField(new TableField(7, "Altii", "altii", "text-align: center;",""));
			    $table->addField(new TableField(8, "Total Voturi", "voturi_valabile", "text-align: center;",""));			    
			    $out.=$table->show();
			    $out.="</div>";
			}
			//rint htmlspecialchars($out);
			return $out;
	}
	public static function getImageUrlByPrimarie($raion_id,$localitate_id){
		$url="";
		if ($localitate_id!=101){
			$sql="select image_url from `rezultate-141130-imagini` where  r_id=$raion_id and l_id=$localitate_id and sectia=0";
			$rs=DBManager::doSql($sql);
			if ((count($rs)!=0)){
				foreach($rs as $r){
					$url.='<div class="groupboxtable">';
					$url.='<img style="width:100%" src="'.$r->image_url.'">';
					$url.='</div>';
				}
			}
		}
		return $url;	
	}				
	public static function getPresidentialImageUrlByPrimarie($raion_id,$localitate_id){
		$old_url="http://www.voteaza.md/r/r/52";
		$new_url_t1="http://cec.md/r/procese-verbale/prezidentiale2016/79";
		$new_url_t2="http://cec.md/r/procese-verbale/prezidentiale-t2-2016/82";
		$url="";
		if ($localitate_id!=101){
			$sql="select image_url from `rezultate-141130-imagini` where  r_id=$raion_id and l_id=$localitate_id and sectia=0";
			$rs=DBManager::doSql($sql);
			if ((count($rs)!=0)){
				foreach($rs as $r){
					$url.='<div class="groupboxtable">';
					$url.='<a href="'.str_replace($old_url, $new_url_t2, $r->image_url).'">Imagine Tur 2</a><br>';
					$url.='<a href="'.str_replace($old_url, $new_url_t1, $r->image_url).'">Imagine Tur 1</a>';
					$url.='</div>';
				}
			}
		}
		return $url;
	}
	
	public static function getAlegeriPresidentialeByLocaliateAndTur($currentPage, $raion_id,$localitate_id, $tur){
		$out="";
		$sql='SELECT "1" as ordine , candidat, sum(voturi)  as voturi FROM ap_voturi v inner join ap_sectiidevot s on v.sectie_id=s.sectie_id where s.localitate_id='.$localitate_id.' and tur='.$tur.' group by candidat';
		$sql.=' UNION';
		$sql.=' SELECT "2" as ordine , "TOTAL:", sum(voturi)  as voturi FROM ap_voturi v inner join ap_sectiidevot s on v.sectie_id=s.sectie_id where s.localitate_id='.$localitate_id.' and tur='.$tur;
		$sql.=' order by ordine, voturi desc';
		$rs=Alegeri::getAlegeriInstance()->sql($sql);

	
		$voturi=function($row) use ($currentPage){
			return number_format($row->voturi, 0, ',', ' ');
		};

		if (count($rs)!=0){
			$out.='<div class="groupboxtable">';
			$table=new Table();
			$table->setDataSet($rs);
			//$table->setShowNrOrd(false);
			$table->addField(new TableField(1, "Candidat", "candidat", "text-align: left;",""));
			$table->addField(new TableField(2, "Voturi", "voturi", "text-align: center;",$voturi));
			$out.=$table->show();
			$out.="</div>";
		}

		//$out.=Alegeri::getPresidentialImageUrlByPrimarie($raion_id, $localitate_id);
		//$out.=Alegeri::getAlegeriDatePresidentialeByLocaliateAndTur($currentPage, $localitate_id, $tur);
		
		return $out;
	}
	public static function getAlegeriDatePresidentialeByLocaliateAndTur($currentPage, $localitate_id, $tur){
		$out="";
		$sql='SELECT  v.k, k.description, sum(v) as v FROM ap_prezenta v inner join ap_keys k on v.k=k.key inner join ap_sectiidevot s on v.sectie_id=s.sectie_id where s.localitate_id='.$localitate_id.' and tur='.$tur.' group by k order by k';
		
		$rs=Alegeri::getAlegeriInstance()->sql($sql);
	
	
		$voturi=function($row) use ($currentPage){
			return number_format($row->v, 0, ',', ' ');
		};
	
		if (count($rs)!=0){
			$out.='<div class="groupboxtable">';
			$table=new Table();
			$table->setDataSet($rs);
			//$table->setShowNrOrd(false);
			$table->addField(new TableField(1, "Candidat", "description", "text-align: left;",""));
			$table->addField(new TableField(2, "Voturi", "v", "text-align: center;",$voturi));
			$out.=$table->show();
			$out.="</div>";
		}
	
		return $out;
	}	
}

?>
