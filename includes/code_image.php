<?

include 'session.php';


/*
if (!session_is_registered ("Var_session")){
session_register ("Var_session");
}



$rand = rand (1,50);

$code = substr(hexdec(md5("".date("F j")."".$rand."")), 2, 6);
$_SESSION['Var_session'] = (int)$code;
        $image = file_exists ("../images/code_bg.jpg") ? ImageCreateFromJpeg("../images/code_bg.jpg") : ImageCreateFromJpeg("code_bg.jpg");
        $text_color = ImageColorAllocate($image, 100, 100, 100);
        Header("Content-type: image/jpeg");
        ImageString($image, 5, 12, 2, $code, $text_color);
        ImageJpeg($image, "", 50);
        ImageDestroy($image);
exit;
*/






/*
// Create a random string, leaving out 'o' to avoid confusion with '0'
$char = strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'), 0, 4));

// Concatenate the random string onto the random numbers
// The font 'Anorexia' doesn't have a character for '8', so the numbers will only go up to 7
// '0' is left out to avoid confusion with 'O'
$str = rand(1, 7) . rand(1, 7) . $char;

$_SESSION['Var_session'] = $str;

// Set the content type
header('Content-type: image/png');
header('Cache-control: no-cache');

// Create an image from button.png
$image = imagecreatefrompng('captcha/button.png');

// Set the font colour
$colour = imagecolorallocate($image, 183, 178, 152);

// Set the font
$font = 'captcha/Anorexia.ttf';

// Set a random integer for the rotation between -15 and 15 degrees


// Create an image using our original image and adding the detail
//imagettftext($image, 14, $rotate, 18, 30, $colour, $font, $str);
if (function_exists('imagettftext')){
$rotate = rand(-15, 15);
imagettftext($image, 14, $rotate, 18, 30, $colour, $font, $str);	
}else {
$rotateX = rand(1, 78);
$rotateY = rand(1, 28);
ImageString($image, 15, $rotateX, $rotateY, $str, $colour);
}
// Output the image as a png
imagepng($image);
exit;
*/

include 'kcaptcha/kcaptcha.php';
$captcha = new KCAPTCHA();
$_SESSION['Var_session'] = $captcha->getKeyString();

?>