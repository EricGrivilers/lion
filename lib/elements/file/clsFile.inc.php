<?php

class file  {

	function display() {
		$t=utils::parseFilename($this->content);
		$ext=$t[1];
		$out="<a href=\"".$this->content."\" target=\"_blank\" class=\"file ".$ext."\">".$this->paramsArray['title']."</a><br/><span class='file'>".utils::formatBytes(filesize(__root__.$this->content)."</span>");
		$this->content=$out;
		return $this->content;
	}
}


?>