<?php

include('config.inc.php');
include(__lib__."init.inc.php");

require(__lib__.'/fpdf/fpdf.php');

$db=new DB;
$db->query="SELECT * FROM items WHERE num='".$_GET['itemId']."'";
$db->setQuery();
$row=$db->output[0];
if(!file_exists(__root__.'photos/big/'.$row['photo'].'.jpg')) {
	$img=new imageManip;
	$img->load('http://www.immo-lelion.be/photos/big/'.$row['photo'].'.jpg');
	$img->save(__root__.'photos/big/'.$row['photo'].'.jpg');	
}

class PDF extends FPDF {

	var $B;
	var $I;
	var $U;
	var $HREF;

	function PDF($orientation='P',$unit='mm',$format='A4') {
		//Appel au constructeur parent
		$this->FPDF($orientation,$unit,$format);
		//Initialisation
		$this->B=0;
		$this->I=0;
		$this->U=0;
		$this->HREF='';
	}

	//En-tête
	function Header() {
		$this->SetDrawColor(90,20,0);
		//$this->SetLineWidth(0.3);
		//$this->Rect(10, 18.7, 190, 254.3);
			//Logo
			//$this->Image('medias/pdf/header.jpg',67.5,8,75,21);
			$this->Image('medias/pdf/header.jpg',0,0,210,40.5);
			//Police Arial gras 15
			$this->SetFont('Arial','B',15);
			//Décalage à droite
		   // $this->Cell(80);
			//Titre
			
			//$this->SetY(10);
			//$this->Cell(0,263,'',1,0,'C');
			//Saut de ligne
			
			$this->Ln(20);
	}

	//Pied de page
	function Footer() {
		$txt="Document informatif et non-contractuel, sous réserve de modification\n\n";
		$txt.="Immobilière Le LION s.a. Avenue Delleur 8 | B-1170 Bruxelles | Tel +32 2 672 71 11 - Fax +32 2 672 67 17\n e-mail : info@immo-lelion.be - www.immo-lelion.be";
		//Positionnement à 1,5 cm du bas
		$this->SetY(-34);
		//Police Arial italique 8
		$this->SetTextColor(149,132,104);
		$this->SetFont('Arial','',9);
		//$this->SetFont('Arial','',11);
		//Numéro de page
		
		$this->MultiCell(0,6,utf8_decode($txt),0,'C');
	}
	
	
	function WriteHTML($html) {
		//Parseur HTML
		$html=str_replace("\n",' ',$html);
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Texte
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				//Balise
				if($e{0}=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extraction des attributs
					$a2=explode(' ',$e);
					$tag=strtoupper(array_shift($a2));
					$attr=array();
					foreach($a2 as $v)
						if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
							$attr[strtoupper($a3[1])]=$a3[2];
					$this->OpenTag($tag,$attr);
				}
			}
		}
	
	
	
	}

	function OpenTag($tag,$attr) {
		//Balise ouvrante
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF=$attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}
	
	function CloseTag($tag) {
		//Balise fermante
		if($tag=='B' or $tag=='I' or $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
	}
	
	function SetStyle($tag,$enable) {
		//Modifie le style et sélectionne la police correspondante
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
			if($this->$s>0)
				$style.=$s;
		$this->SetFont('',$style);
	}
									
	function PutLink($URL,$txt) {
		//Place un hyperlien
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}


}

//Instanciation de la classe dérivée
$pdf=new PDF();
$pdf->setMargins(10,20);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetDisplayMode('real');

$pdf->SetLineWidth(0.3);

$pdf->SetAutoPageBreak(true,30);


$pdf->SetDrawColor(149,132,104);



//echo $x;
//$pdf->Image('photos/thumbs/'.$row['photo'].'.jpg',$x,40,$src_w1,$src_h1);

$pdf->SetFont('Arial','',11);	
$pdf->setY(45);
$pdf->setX(15);
$pdf->MultiCell(200,4,"Ref: ".$row['reference']);	

if(!file_exists(__root__.'photos/print/'.$row['photo'].'.jpg')) {
	$img=new imageManip;
	$img->load('http://www.immo-lelion.be/photos/big/'.$row['photo'].'.jpg');
	$img->resizeToHeight('250');
	$img->save(__root__.'photos/print/'.$row['photo'].'.jpg');
	
}
$pdf->Image('photos/print/'.$row['photo'].'.jpg',15,55);


	
$pdf->SetFont('Arial','',11);	
$pdf->setY(55);
$pdf->setX(135);

$infos="<B>".$row['locfr']."</B>

Superficie: ".$row['area']." m²
Chambres: ".$row['rooms']."
Salles d'eau: ".$row['bathroom']."
Garages: ".$row['garage']."

";

if($row['prix']>0 && $row['surdemande']=='') {
	$infos.="Prix : ".number_format($row['prix'], 0, '.', '.')." euros";
}
else {
	$infos.="Prix sur demande";
}

$infos=specialchars($infos);

$pdf->MultiCell(170,4,"$infos",0,'J');					



//$pdf->SetFont('Arial','',10);
//$pdf->Cell(0,8,"",0,1,'L');
$pdf->SetY($pdf->GetY()+5);

$pdf->SetX(15);
$pdf->SetY(165);
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(0,0,0);
$cont=specialchars($row['descrfr']);
$pdf->MultiCell(170,4,"$cont",0,'J');



$pdf->Output();
//header('location:http://www.galeriearyjan.com/pdfs/'.$fileName);

function specialchars($string) {
	$string=trim($string);
	$string=html_entity_decode($string);
	$string=html_entity_decode($string);
	$string=preg_replace("/²/",'2',$string);
	$string=preg_replace("/&#8232;/","",$string);
	
	$string=preg_replace("/&rsquo;/","'",$string);
	$string=preg_replace("/&lsquo;/","'",$string);
	$string=preg_replace("/&ldquo;/",'"',$string);
	$string=preg_replace("/&rdquo;/",'"',$string);
	$string=preg_replace("/&oelig;/",'oe',$string);
	
	$string=preg_replace("/<em>/",'',$string);
	$string=preg_replace("/<\\em>/",'',$string);
	$string=preg_replace("/em>/",'I>',$string);
	$string=preg_replace("/strong>/",'B>',$string);
	$string=preg_replace("/b>/",'B>',$string);
	$string=preg_replace("/i>/",'I>',$string);
	$string=preg_replace("/u>/",'U>',$string);
	
	
	
$string=preg_replace("/<br> /",'
',$string);
$string=preg_replace("/<br\/> /",'
',$string);
$string=preg_replace("/<\/p> /",'
',$string);
$string=preg_replace("/<p> /",'
',$string);
$string=preg_replace("/<br \/> /",'
',$string);


$string=preg_replace("/<br>/",'
',$string);
$string=preg_replace("/<br\/>/",'
',$string);
$string=preg_replace("/<\/p>/",'
',$string);
$string=preg_replace("/<p>/",'
',$string);
$string=preg_replace("/<br \/>/",'
',$string);



	$string=trim(strip_tags($string));
	
	return $string;
}
?>