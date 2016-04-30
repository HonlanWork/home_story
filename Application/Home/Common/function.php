<?php
function genRandStr() {
	$str = '';
	for ($i = 0; $i < 20; $i++) { 
		$str .= rand(0, 9);
	}
	return substr(md5($str), 0, 8);
}
?>