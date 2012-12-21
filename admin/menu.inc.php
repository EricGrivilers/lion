<?php
class menu {

		function getMenu() {
		$chapters=array("vente","location","nouveautes","presentation","contact");
		$lchapters=array("vente","location","nouveautes","presentation","contact");
		if($_SESSION['language']=='en') {
			$lchapters=array("sale","rent","new","presentation","contact");
		}
		$q="select * from chapters order by rank";
		$r=mysql_query($q) or die(mysql_error());
		
		$menu="<ul>";
		$tmenu=array();
		$i=0;
		while($row=mysql_fetch_array($r)) {
			if(in_array($row['stringId'],$chapters)) {
				$label="/".ucfirst($lchapters[$i]);
				
				if($_GET['chap']==$row['id']) {
					if($row['id']==4){
						$tmenu[]="<li><a href='".$label."' ><img src=\"/medias/".$_SESSION['language']."/".$row['stringId']."_on.gif\" id=\"menutop_".$chapters[$i]."\" alt=\"".$chapters[$i]."\"/></a></li>";
					}else{
						$tmenu[]="<li><a href='".$label."' ><img src=\"/medias/".$_SESSION['language']."/".$row['stringId']."_on.gif\" id=\"menutop_".$chapters[$i]."\" alt=\"".$chapters[$i]."\"/></a></li>";
					}
				}else{
					if($row['id']==4){
						$tmenu[]="<li><a href='".$label."' ><img src=\"/medias/".$_SESSION['language']."/".$row['stringId'].".gif\" id=\"menutop_".$chapters[$i]."\" onmouseover=\"menutop_mouseover(this,1,'".$_SESSION['language']."')\" onmouseout=\"menutop_mouseover(this,0,'".$_SESSION['language']."')\" alt=\"".$chapters[$i]."\"/></a></li>";
					}else{
						$tmenu[]="<li><a href='".$label."' ><img src=\"/medias/".$_SESSION['language']."/".$row['stringId'].".gif\" id=\"menutop_".$chapters[$i]."\" onmouseover=\"menutop_mouseover(this,1,'".$_SESSION['language']."')\" onmouseout=\"menutop_mouseover(this,0,'".$_SESSION['language']."')\" alt=\"".$chapters[$i]."\"/></a></li>";
					}
				}
				$i++;
		}
	  }
	  $menu.=implode("<img src='medias/menutop_separator.gif'>",$tmenu);
	  $menu.="</ul>";
	  return $menu;
		
		}
		
		
		function setNouveautesForm($aVars) {
		$content.="<div id='news_search_form'><form name='news_search_form' method='post' action='index.php?chap=6'><input type='hidden' name='order_by' value='".$aVars['order_by']."'>";
	$content.="<div style='float:left;'><b>Filtrer par</b>&nbsp;&nbsp;<input type='radio' name='news_offer_type' value='1' ".($aVars['news_offer_type']==1?'checked':'')." >Ventes <input type='radio' name='news_offer_type' value='2' ".($aVars['news_offer_type']==2?'checked':'')." >Locations";
	$content.="&nbsp;|&nbsp;<input type='radio' name='news_date_type' value='3' ".($aVars['news_date_type']==3?'checked':'')." >Nouveautés <input type='radio' name='news_date_type' value='4' ".($aVars['news_date_type']==4?'checked':'')." >Mises à jour</div>";
	$content.="<div class='button' style='float:right;width:50px;text-align:center'><a href='javascript:document.news_search_form.submit()' >Trier </a></div>";
	
	$content.="</form></div>";
		return $content;
	}
	
	
	}
?>
