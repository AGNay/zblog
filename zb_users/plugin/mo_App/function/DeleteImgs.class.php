<?php
class mo_App_DelImgs {
	public $oldpostimg = array();
	public $newpostimg = array();
	public function GetImgArray($content,$type){
		preg_match_all('/(\s+src\s?\=)\s?[\'|"]([^\'|"]*)/is', $content , $a);//搜索$content中正则（src=）并将内容匹配到$a,$a为数组
		if ($a[2]) $this->$type = $a[2];
	}
	public function Process(){
		$this->DelFile(array_diff($this->oldpostimg,$this->newpostimg));
	}
	public function DelFile($trashy){
		global $zbp;
		if (!$trashy) return;
		foreach ($trashy as $img){
			$d = explode('/',$img);
			$search[] = array('ul_Name', end($d));
		}
		$upload = $zbp->GetUploadList('',array('array',$search));
		foreach ($upload as $u => $d){
			$_GET['id'] = $d->ID;
			DelUpload();
		}
	}
}