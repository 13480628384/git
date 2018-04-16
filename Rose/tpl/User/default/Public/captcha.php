<?php
session_start();
$consts = 'aAB2cC3dD4eE5fF6gG7hH89JkKL2mM3nN45pP6Q7rR8sS9tTuUvV2wW3xX4yY5zZ';
$lenght=strlen($consts);
$string = '';
for ($i=0;$i<4;$i++)
	$string .= substr($consts, mt_rand(0,$lenght-1),1);

$string = strtolower($string);
$_SESSION['checkcode'] = $string; 
setcookie('captcha',$string,time()+3600,'/');

//$imageX = strlen($string)*30;	//the image width
$imageY = 46;						//the image height
$imageX = 110;

$im = imagecreatetruecolor($imageX,$imageY);

$foregroundArr = array(imagecolorallocate($im, rand(0, 90), rand(0, 90), rand(0, 90)),
					   imagecolorallocate($im, rand(0, 60), rand(0, 60), rand(225, 255)),
					   imagecolorallocate($im, rand(0, 60), rand(125, 155), rand(225, 255)),
					   imagecolorallocate($im, rand(0, 90), rand(210, 240), rand(0, 90)),
					   imagecolorallocate($im, rand(225, 255), rand(125, 155), rand(0, 60)),
					   imagecolorallocate($im, rand(225, 255), rand(0, 60), rand(0, 60)),
					   imagecolorallocate($im, rand(225, 255), rand(0, 60), rand(225, 255)));

imagefill($im, 0, 0, imagecolorallocate($im, 250, 253, 254));

//$fontArray = array('font/bellb.ttf','font/belli.ttf','font/bod_b.ttf','font/bod_blai.ttf','font/bod_i.ttf','font/elephnt.ttf','font/imprisha.ttf','font/rocki.ttf','font/schlbkb.ttf','font/stencil.ttf','font/timesbi.ttf');
//$fontArray = array('fonts/bellb.ttf','bellb.ttf','font/bellb.ttf','font/bellb.ttf','font/bellb.ttf','font/bellb.ttf','font/bellb.ttf','font/bellb.ttf','font/bellb.ttf','font/bellb.ttf','font/bellb.ttf');
$fontArray = array('fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf','fonts/tahomabd.ttf');
imagettftext($im, 26, rand(-10,10),12,30+mt_rand(-3,3), $foregroundArr[rand(0,6)],$fontArray[rand(0,10)],$string[0]);
imagettftext($im, 26, rand(-10,10),36,30+mt_rand(-3,3), $foregroundArr[rand(0,6)],$fontArray[rand(0,10)],$string[1]);
imagettftext($im, 26, rand(-10,10),60,30+mt_rand(-3,3), $foregroundArr[rand(0,6)],$fontArray[rand(0,10)],$string[2]);
imagettftext($im, 26, rand(-10,10),84,30+mt_rand(-3,3), $foregroundArr[rand(0,6)],$fontArray[rand(0,10)],$string[3]);

//imagerectangle($im,0,0,$imageX-1,$imageY-1,imagecolorallocate($im, 133, 153, 193));


$middleground = imagecolorallocatealpha($im, rand(130, 200), rand(130, 200), rand(130, 200), 100);
$middleground2 = imagecolorallocatealpha($im, rand(180, 140), rand(180, 140), rand(180, 140),80);
	//random shapes
for ($x=0; $x<15;$x++){
	if(mt_rand(0,$x)%2==0){
		imageline($im, rand(0, 120), rand(0, 120), rand(0, $imageX), rand(0, $imageY), $middleground);
		imageellipse($im, rand(0, 120), rand(0, 120), rand(0, 120), rand(0, 120), $middleground2);
	}else{
		imageline($im, rand(0, 120), rand(0, 120), rand(0, $imageX*2), rand(0, $imageY*2), $middleground2);
		imageellipse($im, rand(0, 120), rand(0, 120), rand(0, 120), rand(0, 120), $middleground);
	}
}
	//output to browser
header("content-type:image/png\r\n");
imagepng($im);
imagedestroy($im);

?>
