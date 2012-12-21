<?php

class l {

	function t($string) {
	
		$trace=debug_backtrace();
		$caller=$trace[0]['file'];
		if(file_exists(pathinfo($caller,PATHINFO_DIRNAME)."/".$_SESSION['language'].".inc")) {
			//echo pathinfo($caller,PATHINFO_DIRNAME)."/".$_SESSION['language'].".inc";
			$tA=explode(".",pathinfo($caller,PATHINFO_BASENAME));
			if(empty($_SESSION['lang'][$tA[0]][$_SESSION['language']])) {
				$_SESSION['lang'][$tA[0]][$_SESSION['language']]=l::readLangFile(pathinfo($caller,PATHINFO_DIRNAME)."/".$_SESSION['language'].".inc");
			}
			if($_SESSION['lang'][$tA[0]][$_SESSION['language']][$string]!='') {
				return $_SESSION['lang'][$tA[0]][$_SESSION['language']][$string];
			}
			else {
				return $string;			
			}
		}
	}
	
	
	function readLangFile($source) {
		$lines = file($source);
		$rArray=array();
		foreach($lines as $v) {
			$t=explode("\t",$v);
			$rArray[$t[0]]=$t[1];
		}
		return $rArray;
	}
}



?>