<?php
/*
Uploadify v2.1.0
Release Date: August 24, 2009

Copyright (c) 2009 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
require_once("../../config.inc.php");
require_once("../config.inc.php");
require_once(__root__."lib/core/clsUtils.inc.php");

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = __root__ . $_REQUEST['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . strtolower($_FILES['Filedata']['name']);
	
	$path_parts = pathinfo($_FILES['Filedata']['name']);
	
	$hs=date('hs');
	$tFile=strtolower($_REQUEST['item_id']."_".utils::post_slug($path_parts['filename']).".".$path_parts['extension']);
	$targetFile=__root__ . $_REQUEST['folder'] . '/'.$tFile;
	$targetFile =  str_replace('//','/',$targetFile);
	$c=1;
	if(file_exists($targetFile)) {
		while(file_exists($targetFile=__root__ . $_REQUEST['folder'] . '/'.$_REQUEST['item_id']."_".utils::post_slug($path_parts['filename'])."_".$c.".".$path_parts['extension'])) {
			
			$c++;	
		}
		$tFile=$_REQUEST['item_id']."_".utils::post_slug($path_parts['filename'])."_".$c.".".$path_parts['extension'];
		$targetFile=__root__ . $_REQUEST['folder'] . '/'.$tFile;
		$targetFile =  str_replace('//','/',$targetFile);
	}
	
	
	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);
	
	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		// mkdir(str_replace('//','/',$targetPath), 0755, true);
	echo $targetFile;	
	$targeTfile=
	
	$q="INSERT INTO photo2item (item_id,photo) VALUES ('".$_REQUEST['item_id']."','".$tFile."')";
	$r=mysql_query($q) or die(mysql_error());
	
	
	$q="SELECT * FROM photo2item ORDER BY ranking ASC";
	$r=mysql_query($q);
	$c=1;
	while($row=mysql_fetch_array($r)) {
		if($c==1) {
			$tf=__root__."photos/big/".$row['photo'];
			$ff=$row['photo'];
		}
		$q="UPDATE photo2item SET ranking='".$c."' WHERE photo='".$row['photo']."'";
		$r=mysql_query($q);
		$c++;
	}
	
	
	$path_parts = pathinfo($tf);
	$ff=preg_replace("/\.jpg/","",$ff);
	$ff=preg_replace("/\.JPG/","",$ff);
	
	$q="UPDATE items SET photo='".preg_replace("/\.jpg/","",$ff)."' WHERE num='".$_REQUEST['item_id']."' ";
		//	$tr[0]
			//echo $q;
	$r=mysql_query($q) or die(mysql_error());
			
			
			
			
			
	
	//echo $q;
		//move_uploaded_file($tempFile,$targetFile);
		copy($tempFile,$targetFile);
		chmod($targetFile,0755);
		echo "1";
	// } else {
	// 	echo 'Invalid file type.';
	// }
}
?>