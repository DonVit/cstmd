<?php
/*
 * Created on 25 Feb 2009
 *
 */
require_once('loader.php');
 
class ImagesWebPage extends MainWebPage {
	function __construct(){
		parent::__construct();
		$this->setCSS("style/styles.css");
		$t="FOTOGRAFII, IMAGINI DIN REPUBLICA MOLDOVA";
		$this->setTitle($t);
		$this->setLogoTitle($t);
		$this->show();		
	}
	function show($html=""){
		$out='<div id="container">';		
		$out.='<div id="left">';
		$out.=$this->getLeftContainer();
		$out.='</div>';
		$out.='<div id="center">';
		$out.=$this->getCenterContainer();
		$out.='</div>';
		$out.='<div id="right">';
		$out.=$this->getRightContainer();		
		$out.='</div>';
		$out.='<div style="clear: both;"/></div>';
		$out.='</div>';	
		MainWebPage::show($out);
	}
	function getLeftContainer(){
		$out="";
		//$out.=$this->getAddImage();
		$out.=$this->getRssLink();
		return $out;	
	}	
	function getCenterContainer(){
		$out="";
		if (isset($this->id)){
			$out.=$this->getImageById($this->id);	
			$out.=Comment::getComments($this,'p',$this->id);	
			//$out.=$this->getLocalitatiByNews($this->id);
			//$out.=$this->getCommentsByNews($this->id);
			//$out.=$this->getNewsByNewsCategory($this->id);
		}
		elseif (isset($this->localitate_id)){
			$out.=$this->getImagesByLocalitateId($this->localitate_id,0,12);	
			//$out.=$this->getLocalitatiByNews($this->id);
			//$out.=$this->getCommentsByNews($this->id);
			//$out.=$this->getNewsByNewsCategory($this->id);
		} else {
			$rowsperpage=12;
			//if(!isset($this->page)){
			//	$page=0;
			//}
			$out.=$this->getImages($this->page,$rowsperpage);
			$out.=$this->getPages($this->page,$rowsperpage);
		}		
		return $out;
	}
	function getRightContainer(){
		$out="";
		//$out.=$this->getLocalitati();
		return $out;				
	}
	function getAddImage(){
		$out="<div class=\"groupbox\">";
		$out.="<ul class=\"leftmenulist\">";
		$out.="<li><a href=\"addimage.php\">+Imagine</a></li>";
		$out.="</ul>";
		$out.="</div>";
		return $out;
	}
	function getRssLink(){
		$out='<div class="groupbox">';
		$out.='<ul class="leftmenulist">';
		$out.='<li><a href="rss.php">Imagini in RSS <img src="img/rss.png" alt="Foto in format RSS" title="Foto in format RSS"></img></a></li>';
		$out.='</ul>';
		$out.='</div>';
		return $out;
	}
	function getImages($page,$rowsperpage){
		$sql="select id, title, file from photos order by data desc";
		$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
		$p=new Photo();
		$ps=$p->doSql($sql);
		$out='<div class="groupbox">';
		$out.="<table width=\"100%\"><tr><td align=\"center\">";
		$out.="<table>";
		$i=1;
		$o="";
		foreach($ps as $p){
		  	$o.='<td align="center" style="padding:10px;vertical-align:top;">';
	  		$o.='<div><a href="index.php?id='.$p->id.'"><img src="files/t'.$p->file.'" alt="'.$p->title.'" style="border: 2px solid #C3D9FF;padding:5px;"></img><p style="font-size:80%;">'.$p->title.'</p></a></div>';	  		
	  		$o.='</td>';
	  		
	  		if (($i % 3)==0){
	  			$out.="<tr>".$o."</tr>";
	  			$o="";
	  		}
	  		$i=$i+1;
		}
		$out.="</table>";
		$out.="</td></tr></table>";
		$out.="</div>";
		return $out;
	}
	function getImageById($id){
		$p=new Photo();
		$p->loadById($id);
		$p->contor=$p->contor+1;
		$p->save();
		$r=new Raion();
		$r->loadById($p->raion_id);
		$l=new Location();
		$l->loadById($p->localitate_id);
		$t=$r->getFullNameDescription()." ".$l->getFullNameDescription()." ".$p->title;
		$this->setTitle('Foto "Imobil Moldova": '.$t);
		
		$out='<div id="photos" class="groupbox">';
		$out.="<table width=\"100%\"><tr><td align=\"center\">";
		$out.="<table>";
		$out.="<tr>";
		$prev=$p->getPrevPhotoId();
		if ($prev!=0){
			$out.="<td><a href=\"index.php?id=".$prev."\"><img src=\"img/media_previous_arrow.gif\" alt=\"Precedenta\" ></img></a></td>";	
		}
		$out.="<td>";
  		$out.="<a href=\"index.php?id=".$p->id."\"><img src=\"files/s".$p->file."\" alt=\"".$t."\" style=\"border: 2px solid #C3D9FF;padding:5px;\"></img></a><p>".$t."</p>";
  		$out.="</td>";

		$next=$p->getNextPhotoId();
		if ($next!=0){
			$out.="<td><a href=\"index.php?id=".$next."\"><img src=\"img/media_next_arrow.gif\" alt=\"Urmatoarea\" ></img></a></td>";	
		}
		$out.="</tr>";
		$out.="</table>";
		$out.="</td></tr></table>";
		$out.="</div>";
		return $out;
	}
	function getCommentsByPhotos($id){
	
		$sql="SELECT id, photos_id, name, web, comment,date FROM `photos_comments`WHERE `photos_id` =$id ORDER BY `date`";
		$result=mysql_query($sql) or die(mysql_error());
		$out="<div id=\"photoscomments\">";
		$c=mysql_num_rows($result);
		if ($c!=0){
		$out.="<h2 class=\"photoscomments_h2\">Comentarii:</h2>";
		//$out.="<table class=\"newscomments_table\">";
		$out.="<div>";
		$i=1;
		while($row = mysql_fetch_object($result)){
			if (($row->web=="http://")||($row->web=="")){
				$out.="<div class=\"photoscomment_head\"><a name=\"$i\"></a><a href=\"index.php?id=$row->photos_id#$i\"># $i</a> | ".getHTML($row->name)." | <span>$row->date</span></div><div class=\"photoscomment_body\">".getHTML($row->comment)."</div>";
			} else {
				$out.="<div class=\"photoscomment_head\"><a name=\"$i\"></a><a href=\"index.php?id=$row->photos_id#$i\"># $i</a> | <a href=\"$row->web\">".getHTML($row->name)."</a> | <span>$row->date</span></div><div class=\"photoscomment_body\">".getHTML($row->comment)."</div>";
			}
			$i=$i+1;		
		}
		$out.="</div>";
		//$out.="</table>";
		}
		$out.="<h2 class=\"photosaddcomment_h2\">Comentează:</h2>";
		$out.="<form method=\"post\" name=\"comment_form\">";
		$out.="<table class=\"photoscomments_table\">";
		$out.="<tr><td>Nume:</td><td><input type=\"text\" name=\"name\"><span style=\"color:red\">*</span></td></tr>";
		$out.="<tr><td>Email:</td><td><input type=\"text\" name=\"email\"></td></tr>";
		$out.="<tr><td>Web Site:</td><td><input type=\"text\" name=\"web\" value=\"http://\"></td></tr>";
		$out.="<tr><td>Comentariu:</td><td><textarea name=\"comment\" class=\"comment_textarea\"></textarea><span style=\"color:red\">*</span></td></tr>";
		$out.="<tr><td></td><td><span style=\"color:red\">*</span> - cîmp obligator</td></tr>";
		$out.="<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\"Postează\"></td></tr>";
		$out.="</table>";
		$out.="</form>";
		$out.="</div>";
	
		return $out;
	}		
	function getLocalitati1(){
		//$sql="SELECT raion.name as r_name, localitate.id as l_id, localitate.name as l_name FROM `news_localitate` INNER JOIN localitate ON news_localitate.localitate_id = localitate.id INNER JOIN raion ON localitate.raion_id = raion.id WHERE news_id =$id";
		$sql="SELECT * FROM localitate as l inner join (SELECT localitate_id, count(*) as cnt FROM `photos` where localitate_id is not null group by localitate_id) as t on l.id=t.localitate_id";
		$l=new Location;
		$ls=$l->doSql($sql);	
		$out="";
		if (count($ls)!=0){
			$out.="<div class=\"groupbox\">";
			$out.="<h4 class=\"localitatigroup_h2\">Localitaţi in Imagini:</h4>";
			$out.="<ul class=\"leftmenulist\">";
			foreach ($ls as $l){
				$out.="<li><a href=\"".$this->getBaseName()."?localitate_id=".$l->id."\">".$l->name."</a></li>";
			}
			$out.="</ul>";			
			$out.="</div>";
		}
		return $out;
	}
	function getImagesByLocalitateId1($id,$page,$rowsperpage){
		$sql="select id, title, file from photos where localitate_id=$id order by data desc";
		$sql.=" limit ".$page*$rowsperpage.",".$rowsperpage;
		$p=new Photo();
		$ps=$p->getAll("localitate_id=".$id,"data desc",$page,$rowsperpage);
		//$result=mysql_query($sql) or die(mysql_error());
		$out="";
		$out.="<table width=\"100%\"><tr><td align=\"center\">";
		$out.="<table>";
		$i=1;
		foreach($ps as $p){
		  	$o.="<td align=\"center\">";
	  		$o.="<a href=\"index.php?id=".$p->id."\"><img src=\"files/t".$p->file."\" alt=\"".$p->title."\" style=\"border: 2px solid #C3D9FF;padding:5px;\"></img></a>";
	  		$o.="</td>";
	  		
	  		if (($i % 3)==0){
	  			$out.="<tr>".$o."</tr>";
	  			$o="";
	  		}
	  		$i=$i+1;
		}
		if($o!=""){
			$out.="<tr>".$o."</tr>";
		}		
		$out.="</table>";
		$out.="</td></tr></table>";
		return $out;
	}
	function getPages($page,$rowsperpage){
		$sql="select count(*) as cnt from photos where deleted=0";
		$p=new Photo();
		$ps=$p->doSql($sql);
		$out='<div class="groupbox">';
		$out.="<table width=\"100%\"><tr><td align=\"center\">";
		//while($row = mysql_fetch_object($result)){
	   	foreach($ps as $p){
	   		$cnt=$p->cnt;
		}
		if ($page!=0){
			$out.="<a href=\"index.php?page=".($page-1)."\" class=\"link_button\">< Inapoi</a>";
		}
		$out.=" ";
		if ((($page+1)*$rowsperpage)<$cnt){
			$out.="<a href=\"index.php?page=".($page+1)."\" class=\"link_button\">Inainte ></a>";
		}
		$out.="</td></tr></table>";
		$out.='</div>';		
		return $out;
	}								
	function getNewsCategory1($categid=0){
		$nc=new NewsCategory;
		$ncs=$nc->getAll();
		$out="<div class=\"groupbox\">";
		$out.="<ul class=\"leftmenulist\">";
		foreach ($ncs as $nc){
			if ($categid==$nc->id){
				$out.="<li><a href=\"".$this->getBaseName()."\">".$nc->name."</a></li>";
			}else{
				$out.="<li><a href=\"".$this->getBaseName()."\">".$nc->name."</a></li>";
			}
		}
		$out.="</ul>";
		$out.="</div>";
		return $out;
	}
	function getRaions1(){
		$r=new Raion;
		$rs=$r->getAll("","`municipiu` desc,`order`,`name`");
		$out="<div class=\"groupbox\">";
		$out.="<ul class=\"leftmenulist\">";
		foreach ($rs as $r){
			$out.="<li><a href=\"".$this->getBaseName()."\">".$r->getFullName()."</a></li>";
		}
		$out.="</ul>";
		$out.="</div>";
		return $out;
	}
		

	function getNews1($page,$rowsperpage,$categ,$raion,$localitate){
		$sql="select news.id as news_id, title, left(news.text,650) as t, url, date, news_source.id as news_source_id, news_source.name as news_source_name, news_category.id as news_category_id, news_category.name as news_category_name, website,image_file,image_url,image_description,news.contor as news_contor from news ";
		$sql.=" inner join news_source on news.news_source=news_source.id ";
		$sql.=" inner join news_category on news.news_category=news_category.id ";
		$sql.=" where news.valid=1";
		if ($categ!=0){
			$sql.=" and news_category=".$categ;
		}
		if ($raion!=0){
			$sql.=" and news.id in (select news_id from `news_localitate` inner join localitate on localitate.id=localitate_id where raion_id=".$raion.")";
		}
		if ($localitate!=0){
			$sql.=" and news.id in (select news_id from `news_localitate` where localitate_id=".$localitate.")";
		}
		$sql.=" order by date desc limit ".$page*$rowsperpage.",".$rowsperpage;
	
		$n=new News;
		$ns=$n->doSql($sql);	
		$out="";
		$out.="<div class=\"groupbox\">";	
		foreach($ns as $n){
		   		$out.="<h2 class=\"news_title\"><a href=\"".$this->getBaseName()."?id=".$n->news_id."\"\" class=\"title\" target=\"_self\">".$n->title."</a></h2>";
		  		$out.="<table class=\"news_body\"><tr><td style=\"padding-bottom:5px;\">";
		  		if ($n->image_file!=""){
		  			$out.="<table align=\"left\" style=\"margin-right:5px;\"><tr><td style=\"vertical-align: top;border: 1px;border-color: red;border-style: solid;margin-right:5px;\"><img src=\"images/".$n->image_file."\" alt=\"".$n->image_description."\"></img><p class=\"news_image_tag\"><a href=\"".System::getURLAmpReplace($n->image_url)."\" target=\"_blank\">".System::getDomainFromURL($n->image_url)."</a></p></td></tr></table>";
		  		}
		  		$out.="&nbsp;&nbsp;&nbsp;".$n->t." <a href=\"index.php?id=$n->news_id\" target=\"_self\">mai mult</a></td></tr>";
		  		$out.="<tr><td><table class=\"news_info\"><tr><td align=\"left\">Sursa: <a href=\"sources.php?id=".$n->news_source_id."\" target=\"_self\">".$n->news_source_name."</a>  din data: ".date("Y-m-d", strtotime($n->date))."</td><td>Categorie:<a href=\"index.php?categ=".$n->news_category_id."\" target=\"_self\">".$n->news_category_name."</a></td><td>Vizite:".$n->news_contor."</td><td align=\"right\"><a href=\"redirect.php?id=$n->news_id\" target=\"_blank\">ştirea originală</a>&nbsp;&nbsp;<a href=\"index.php?id=$n->news_id\" target=\"_self\">ştirea în cache</a></td></tr></table></td></tr>";
		  		$out.="</table>";
		}
		$out.="</div>";
		return $out;
	}
	function getNewsById1($id){
		$sql="select news.id as news_id, title, news.description, news.keywords, news.text as t, url, date, news_source.id as news_source_id, news_source.name as news_source_name, news_category.id as news_category_id, news_category.name as news_category_name, website,image_file,image_url,image_description,news.contor as news_contor,map_centerlat,map_centerlng,map_zoom,map_maptype,map_lat,map_lng,map_title,map_description from news ";
		$sql.=" inner join news_source on news.news_source=news_source.id ";
		$sql.=" inner join news_category on news.news_category=news_category.id ";
		$sql.=" where news.id=$id";
		$n=new News;
		$ns=$n->doSql($sql);
	
		$out="";
		$out.="<div class=\"groupbox\">";
		//$out.="<div id=\"items\">";
		foreach($ns as $n){
				$this->setTitle("Ştiri \"Imobil Moldova\": ".$n->title);
				$this->setDescription($n->description);
				$this->setKeywords($n->keywords);
		   		$out.="<h2 class=\"news_title\"><a href=\"index.php?id=$n->news_id\" class=\"title\" target=\"_self\">".$n->title."</a></h2>";
		  		$out.="<table class=\"news_body\"><tr><td style=\"padding-bottom:5px;\">";
		  		if ($n->image_file!=""){
		  			  		$out.="<table align=\"left\" style=\"margin-right:5px;\"><tr><td style=\"vertical-align: top;border: 1px;border-color: red;border-style: solid;margin-right:5px;\"><img src=\"images/".$n->image_file."\" alt=\"".$n->image_description."\"></img><p class=\"news_image_tag\"><a href=".System::getURLAmpReplace($n->image_url)." target=\"_blank\">".System::getDomainFromURL($n->image_url)."</a></p></td></tr></table>";
		  		}
		  		$out.="&nbsp;&nbsp;&nbsp;".nl2br($n->t)."</td></tr>";
				if (($n->map_lat!="")&&($n->map_lng!="")){
					$out.="<tr><td align=\"center\">";
					$out.=$this->getMap($n->map_centerlat,$n->map_centerlng,$n->map_zoom,$n->map_maptype,$n->map_lat,$n->map_lng,$n->map_title,$n->map_description);
					$out.="</td></tr>";
				}
		  		$out.="<tr><td><table class=\"news_info\"><tr><td align=\"left\">Sursa: <a href=\"sources.php?id=".$n->news_source_id."\" target=\"_self\">".$n->news_source_name."</a>  din data: ".date("Y-m-d", strtotime($n->date))."</td><td>Categorie:<a href=\"index.php?categ=".$n->news_category_id."\" target=\"_self\">".$n->news_category_name."</a></td><td>Vizite:".$n->news_contor."</td><td align=\"right\"><a href=\"redirect.php?id=$n->news_id\" target=\"_blank\">ştirea originală</a>&nbsp;&nbsp;<a href=\"index.php?id=$n->news_id\" target=\"_self\">ştirea în cache</a></td></tr></table></td></tr>";
		  		$out.="</table>";
		}
		//$out.="</div>";
		$out.="</div>";
		return $out;
	}

	function getCommentsByNews1($id){
		$sql="SELECT id, news_id, name, web, comment,date FROM `news_comments`WHERE `news_id` =$id ORDER BY `date`";
		$c=new Comment;
		$cs=$c->doSql($sql);		
		$out="<div class=\"groupbox\">";
		if (count($cs)!=0){
			$out.="<h2 class=\"newscomments_h2\">Comentarii:</h2>";
			$out.="<div>";
			$i=1;
			foreach($cs as $c){
				if (($c->web=="http://")||($c->web=="")){
					$out.="<div class=\"newscomment_head\"><a name=\"$i\"></a><a href=\"index.php?id=$c->news_id#$i\"># $i</a> | ".System::getHTML($c->name)." | <span>$c->date</span></div><div class=\"newscomment_body\">".getHTML($c->comment)."</div>";
				} else {
					$out.="<div class=\"newscomment_head\"><a name=\"$i\"></a><a href=\"index.php?id=$c->news_id#$i\"># $i</a> | <a href=\"$c->web\">".System::getHTML($c->name)."</a> | <span>$c->date</span></div><div class=\"newscomment_body\">".getHTML($c->comment)."</div>";
				}
				$i=$i+1;		
			}
			$out.="</div>";
		}
		$out.="<h2 class=\"newsaddcomment_h2\">Comentează:</h2>";
		$out.="<form method=\"post\" name=\"comment_form\">";
		$out.="<table class=\"newscomments_table\">";
		$out.="<tr><td>Nume:</td><td><input type=\"text\" name=\"name\"><span style=\"color:red\">*</span></td></tr>";
		$out.="<tr><td>Email:</td><td><input type=\"text\" name=\"email\"></td></tr>";
		$out.="<tr><td>Web Site:</td><td><input type=\"text\" name=\"web\" value=\"http://\"></td></tr>";
		$out.="<tr><td>Comentariu:</td><td><textarea name=\"comment\" class=\"comment_textarea\"></textarea><span style=\"color:red\">*</span></td></tr>";
		$out.="<tr><td></td><td><span style=\"color:red\">*</span> - cîmp obligator</td></tr>";
		$out.="<tr><td></td><td><input type=\"submit\" name=\"submit\" value=\"Postează\"></td></tr>";
		$out.="</table>";
		$out.="</form>";
		$out.="</div>";
	
		return $out;
	}	
function getNewsByNewsCategory1($id){
	$sql="SELECT news_category.id,news_category.name FROM `news` inner join news_category on news.news_category=news_category.id WHERE news.id=$id";
	$c=new NewsCategory;
	$cs=$c->doSql($sql);
	foreach($cs as $c){
		$categ=$c->id;
		$categ_name=$c->name;
	}
	
	$sql="SELECT id, title, image_file, image_url, image_description FROM `news`WHERE news.valid=1 AND `news_category` =$categ AND `image_file` != \"\" AND id!=$id ORDER BY `date` DESC LIMIT 0,8 ";
	$n=new News;
	$ns=$n->doSql($sql);
	$out="<div class=\"groupbox\">";
	$out.="<h2 class=\"newsgroup_h2\">Mai multe ştiri din categoria <a href=\"index.php?categ=$categ\">$categ_name</a>:</h2>";
	$out.="<table class=\"newsgroup_table\">";
	$i=0;
	foreach($ns as $n){
		if ($i==0){
			$out.="<tr>";
		}
		if ($i==4){
			$out.="</tr><tr>";
		}
		$out.="<td class=\"newsgroup_td\"><div><a href=\"".$this->getBaseName()."?id=$n->id\"><img src=\"images/$n->image_file\" alt=\"".$n->image_description."\" class=\"newsgroup_img\"></img><p class=\"newsgroup_p\">$n->title</p></a></div></td>";
		if ($i==8){
			$out.="</tr>";
		}
		$i=$i+1;
	}
	$out.="</table>";
	$out.="</div>";

	return $out;
}

function getTitle1($id,$categ,$raion,$localitate){
	$out="Ştiri \"Imobil Moldova\"";
	if ($id!=0){
		$sql="select title from news where id=$id";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out.=": ".$row->title;
		}
	}
	if ($categ!=0){
		$sql="select description from news_category where id=$categ";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out.=": ".$row->description;
		}
	}
	if ($raion!=0){
		$sql="select municipiu,name from raion where id=$raion";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		if ($row->municipiu==1){
	   			$out.=": Municipiul ".$row->name;
	   		} else {
	   			$out.=": Raionul ".$row->name;
	   		}
		}
	}
	if ($localitate!=0){
		$sql="select name from localitate where id=$localitate";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out.=": ".$row->name;
		}
	}
	return $out;
}
function getDescription1($id,$categ,$raion,$localitate){
	$out="Serviciu de colectare a ştirilor, noutăţilor, evenimentelor pe piaţa Imobilului în Republica Moldova ";
	if ($id!=0){
		$sql="select description from news where id=$id";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out=$row->description;
		}
	}
	if ($categ!=0){
		$sql="select description from news_category where id=$categ";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out.=$row->description;
		}
	}
	if ($raion!=0){
		$sql="select municipiu,name from raion where id=$raion";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		if ($row->municipiu==1){
	   			$out.=" Municipiul ".$row->name;
	   		} else {
	   			$out.=" Raionul ".$row->name;
	   		}
		}
	}
	if ($localitate!=0){
		$sql="select name from localitate where id=$localitate";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out.=$row->name;
		}
	}
	return $out;
}
function getKeyWords1($id,$categ,$raion,$localitate){
	$out="Ştiri Imobil Moldova, Noutăţi Imobil Moldova, Evenimente Imobil Moldova, Piaţa Imobiliară din Republica Moldova, ";
	if ($id!=0){
		$sql="select keywords from news where id=$id";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out=$row->keywords;
		}
	}
	if ($categ!=0){
		$sql="select description from news_category where id=$categ";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out.=$row->description;
		}
	}
	if ($raion!=0){
		$sql="select municipiu,name from raion where id=$raion";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		if ($row->municipiu==1){
	   			$out.=" Municipiul ".$row->name;
	   		} else {
	   			$out.=" Raionul ".$row->name;
	   		}
		}
	}
	if ($localitate!=0){
		$sql="select name from localitate where id=$localitate";
		$result=mysql_query($sql);
		while($row = mysql_fetch_object($result)){
	   		$out.=$row->name;
		}
	}
	return $out;
}
function getPages1($page,$rowsperpage,$categ,$raion,$localitate){
	$sql="select count(*) as cnt from news where valid=1";
	if ($categ!=0){
		$sql.=" and news_category=".$categ;
		$url="categ=".$categ;
	}
	if ($raion!=0){
		$sql.=" and news.id in (select news_id from `news_localitate` inner join localitate on localitate.id=localitate_id where raion_id=".$raion.")";
		$url="raion=".$raion;
	}
	if ($localitate!=0){
		$sql.=" and news.id in (select news_id from news_localitate where localitate_id=".$localitate.")";
		$url="localitate=".$localitate;
	}
	$result=mysql_query($sql) or die(mysql_error());
	$out="";
	while($row = mysql_fetch_object($result)){
   		$cnt=$row->cnt;
	}
	if ($page!=0){
		$out.="<a href=\"index.php?$url&amp;page=".($page-1)."\" class=\"prevpage\">< ştiri mai noi</a>";
	}
	$out.=" ";
	if ((($page+1)*$rowsperpage)<$cnt){
		$out.="<a href=\"index.php?$url&amp;page=".($page+1)."\" class=\"nextpage\">ştiri mai vechi ></a>";
	}
	return $out;
}

	function getMap1($centerlat,$centerlng,$zoom,$maptype,$lat,$lng,$title,$description){
		$out="";
		$out.="<table width=\"100%\">";
		$out.="<tr>";
		$out.="<td align=\"center\">$title";
		$out.="<input id=\"centerlat\" name=\"centerlat\" type=\"hidden\" value=\"$centerlat\" />";
		$out.="<input id=\"centerlng\" name=\"centerlng\" type=\"hidden\" value=\"$centerlng\" />";
		$out.="<input id=\"maptype\" name=\"maptype\" type=\"hidden\" value=\"$maptype\" />";
		$out.="<input id=\"zoom\" name=\"zoom\" type=\"hidden\" value=\"$zoom\" />";
		$out.="<input name=\"lat\" type=\"hidden\" id=\"lat\" readonly=\"true\" class=\"inptdisabled\" value=\"$lat\" /> ";
		$out.="<input name=\"lng\" type=\"hidden\" id=\"lng\" readonly=\"true\" class=\"inptdisabled\" value=\"$lng\" />";
		$out.="<input name=\"map_title\" type=\"hidden\" id=\"map_title\" readonly=\"true\" class=\"inptdisabled\" value=\"$title\" /> ";
		$out.="<input name=\"map_description\" type=\"hidden\" id=\"map_description\" readonly=\"true\" class=\"inptdisabled\" value=\"$description\" />";
		$out.="</td>";
		$out.="</tr>";
		$out.="<tr>";
		$out.="<td align=\"center\">";
		$out.="<div id=\"map\" style=\"width: 100%; height: 400px\"></div>";
		$out.="</td>";
		$out.="</tr>";
		$out.="</table>";
		return $out;
	}
}
$n=new ImagesWebPage();

?>
