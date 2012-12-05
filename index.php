<?php
// ffmpeg write progress to file
// http://stackoverflow.com/questions/747982/can-ffmpeg-show-a-progress-bar
// http://stackoverflow.com/questions/6481210/getting-progress-for-multiple-exec-processes-realtime
$shell="/bin/sh";
@ob_start("ob_gzhandler");
@ini_set("html_errors", false);
@ini_set("track_errors", false);
@ini_set("display_errors", false);
@ini_set("report_memleaks", false);
@ini_set("display_startup_errors", false);
@ini_set("docref_ext", "");
@ini_set("docref_root", "http://php.net/");
if(!defined("E_DEPRECATED")) { define("E_DEPRECATED", 0); }
@error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
@ini_set("ignore_user_abort", 1);
@set_time_limit(0); @ignore_user_abort(true);
@ini_set('zend.ze1_compatibility_mode', 0);
@ini_set("date.timezone","UTC"); 
@date_default_timezone_set("UTC");
@clearstatcache();
if(!file_exists($basewd.DIRECTORY_SEPARATOR."thumbnail")) {
	mkdir($basewd.DIRECTORY_SEPARATOR."thumbnail"); }
if(!file_exists($basewd.DIRECTORY_SEPARATOR."uploads")) {
	mkdir($basewd.DIRECTORY_SEPARATOR."uploads"); }
if(!file_exists($basewd.DIRECTORY_SEPARATOR."vidlogs")) {
	mkdir($basewd.DIRECTORY_SEPARATOR."vidlogs"); }
if(!file_exists($basewd.DIRECTORY_SEPARATOR."vidtmp")) {
	mkdir($basewd.DIRECTORY_SEPARATOR."vidtmp"); }
if(!isset($_GET['act'])) { $_GET['act']=null; }
if(!isset($_GET['filename'])) { $_GET['filename']=null; }
//if($_GET['act']=="delete") { unlink("./uploads/".$_GET['filename']); }
$basewd=getcwd();
if(!isset($_SERVER["REQUEST_SCHEME"])) {
if(!isset($_SERVER['HTTPS'])) {
	$_SERVER["REQUEST_SCHEME"] = "http"; }
if(isset($_SERVER['HTTPS'])) {
	$_SERVER["REQUEST_SCHEME"] = "https"; } }
function _format_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' KiB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MiB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GiB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) .' TiB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) .' PiB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) .' EiB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> MinUpload ( Minimalist Video Upload ) </title>
  <meta name="generator" content="Bluefish 2.2.3" />
  <meta name="author" content="Cool Dude 2k" />
  <meta name="keywords" content="MinUpload,Minimalist File Upload,Minimalist,File Upload,File,Upload" />
  <meta name="description" content="MinUpload ( Minimalist File Upload ) by Kazuki Przyborowski" />
  <base href="<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']); ?>/" />
<script type="text/javascript">
<!--
function getid(id) {
var itm;
itm = document.getElementById(id);
return itm; }

function toggletag(id) {
var itm;
itm = document.getElementById(id);
if (itm.style.display == "none") {
itm.style.display = ""; }
else {
itm.style.display = "none"; } }
//-->
</script>
 </head>

 <body>

<form name="file_upload" id="file_upload" action="index.php" method="post" enctype="multipart/form-data">
<input type="submit" name="submit" value="Submit" /> <button type="button" onclick="if(typeof uploadid === 'undefined') { uploadid = 1; }; var input = document.createElement('input'); input.type = 'file'; input.name = 'file[]'; input.id = 'file'+uploadid; document.getElementById('file_upload').appendChild(input); var brline = document.createElement('br'); document.getElementById('file_upload').appendChild(brline); uploadid=++uploadid;">Add More</button><br />
<label for="file0">Filename:</label><br />
<input type="file" name="file[]" id="file0" /><br />
</form>
 
<?php
$i=0;
$max=count($_FILES['file']['error'])-1;
while($i<=$max) {
if($_FILES['file']['error'][$i]===0) {
echo "<hr />";
echo $_FILES['file']['tmp_name'][$i]." => ".getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]."<br />\n";
echo "<a href=\"".$_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/uploads/".$_FILES['file']['name'][$i]."\" title=\"".$_FILES['file']['name'][$i]."\">".$_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/uploads/".$_FILES['file']['name'][$i]."</a><br />\n";
echo "\n<div style=\"white-space: pre-wrap;\">";
var_dump($_FILES['file']['name'][$i]);
echo "\n";
var_dump($_FILES['file']['type'][$i]);
echo "\n";
var_dump($_FILES['file']['tmp_name'][$i]);
echo "\n";
var_dump($_FILES['file']['error'][$i]);
echo "\n";
var_dump($_FILES['file']['size'][$i]);
echo "</div>\n";
move_uploaded_file($_FILES['file']['tmp_name'][$i], getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]); 
@clearstatcache(); } 
++$i; }
chdir(getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR);
var_dump(count(glob("*")));
if(count(glob("*"))>0) { echo "\n<hr />\n"; }
echo "\n<div style=\"white-space: pre-wrap;\">";
foreach (glob("*") as $filename) {
if(!file_exists($basewd.DIRECTORY_SEPARATOR."thumbnail".DIRECTORY_SEPARATOR.pathinfo($filename, PATHINFO_FILENAME).".png")) { 
shell_exec($shell." \"".$basewd.DIRECTORY_SEPARATOR."shell".DIRECTORY_SEPARATOR."thumbnail.sh\" \"".getcwd().DIRECTORY_SEPARATOR.$filename."\" \"".round($vidarray['timestamp'] / rand(4, 24), 2)."\""); }
$vidarray['width']=trim(shell_exec($shell." \"".$basewd.DIRECTORY_SEPARATOR."shell".DIRECTORY_SEPARATOR."getwidth.sh\" \"".getcwd().DIRECTORY_SEPARATOR.$filename."\""));
$vidarray['height']=trim(shell_exec($shell." \"".$basewd.DIRECTORY_SEPARATOR."shell".DIRECTORY_SEPARATOR."getheight.sh\" \"".getcwd().DIRECTORY_SEPARATOR.$filename."\""));
$vidarray['resolution']=$vidarray['width']."x".$vidarray['height'];
$vidarray['mininfo']=trim(shell_exec($shell." \"".$basewd.DIRECTORY_SEPARATOR."shell/getmininfo.sh\" \"".getcwd().DIRECTORY_SEPARATOR.$filename."\""));
preg_match('/.*Duration: ([0-9:]+).*/', $vidarray['mininfo'], $tmp_duration);
$vidarray['duration']=$tmp_duration[1];
preg_match('/([0-9]{2}):([0-9]{2}):([0-9]{2})/', $vidarray['duration'], $gettimestamp);
$vidarray['timestamp']=($gettimestamp[1]*3600)+($gettimestamp[2]*60)+($gettimestamp[3]*1);
?>
<div id="Hide<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>" style="display: none;"><a href="javascript:%20return%20false;" onclick="toggletag('Show<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>'); toggletag('Hide<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>');">Hide</a><br /><object type="application/x-shockwave-flash" data="<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']); ?>/mplayer/movie_player.swf" width="<?php echo $vidarray['width']; ?>" height="<?php echo $vidarray['height']; ?>">
<param name="movie" value="<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']); ?>/mplayer/movie_player.swf" />
<param name="allowFullScreen" value="true" />
<param name="FlashVars" value="flv=<?php echo rawurlencode($_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/uploads/".rawurlencode($filename)); ?>&amp;title=<?php echo rawurlencode(pathinfo($filename, PATHINFO_FILENAME)); ?>&amp;startimage=<?php echo rawurlencode($_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/thumbnail/".rawurlencode(pathinfo($filename, PATHINFO_FILENAME).".png")); ?>" />
</object><br /><a href="javascript:%20return%20false;" onclick="toggletag('Show<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>'); toggletag('Hide<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>');">Hide</a></div>
<div id="Show<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>"><a href="javascript:%20return%20false;" onclick="toggletag('Show<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>'); toggletag('Hide<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>');">Show</a><br /><img onclick="toggletag('Show<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>'); toggletag('Hide<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>');" src="<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/thumbnail/".rawurlencode(pathinfo($filename, PATHINFO_FILENAME).".png"); ?>" alt="<?php echo pathinfo($filename, PATHINFO_FILENAME); ?>" title="<?php echo pathinfo($filename, PATHINFO_FILENAME); ?>" /><br /><a href="javascript:%20return%20false;" onclick="toggletag('Show<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>'); toggletag('Hide<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>');">Show</a></div>
<?php
echo "[<a href=\"".$_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/index.php?act=delete&amp;filename=".rawurlencode($filename)."\">Delete</a>] <a href=\"".$_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/uploads/".rawurlencode($filename)."\" title=\"".$filename."\">".$filename."</a>\n"; 
?>[<a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>">INFO:CTIME</a>] <a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", filectime($filename)); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>">INFO:ATIME</a>] <a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", fileatime($filename)); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>">INFO:SIZE</a>] <a href="javascript:<?php echo rawurlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>"><?php echo filesize($filename)." Bytes =&gt; "._format_bytes(filesize($filename)); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('Resolution: ".$vidarray['resolution'].",Duration: ".$vidarray['duration']."');"); ?>">INFO:VIDEO</a>] <a href="javascript:<?php echo rawurlencode("alert('Resolution: ".$vidarray['resolution'].",Duration: ".$vidarray['duration']."');"); ?>">Resolution: <?php echo $vidarray['resolution']; ?>,Duration: <?php echo $vidarray['duration']; ?></a><?php echo "\n";
if($_GET['act']=="debug"||$_GET['act']=="info") {
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file('crc32',$filename,FALSE)."');"); 
?>">INFO:CRC</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file('crc32',$filename,FALSE)."');"); ?>"><?php echo hash_file('crc32',$filename,FALSE); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file('crc32b',$filename,FALSE)."');"); ?>">INFO:CRCB</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file('crc32b',$filename,FALSE)."');"); ?>"><?php echo hash_file('crc32b',$filename,FALSE); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("md2", $filename)."');"); ?>">INFO:MD2</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("md2", $filename)."');"); ?>"><?php echo hash_file("md2", $filename); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("md4", $filename)."');"); ?>">INFO:MD4</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("md4", $filename)."');"); ?>"><?php echo hash_file("md4", $filename); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("md5", $filename)."');"); ?>">INFO:MD5</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("md5", $filename)."');"); ?>"><?php echo hash_file("md5", $filename); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha1", $filename)."');"); ?>">INFO:SHA1</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha1", $filename)."');"); ?>"><?php echo hash_file("sha1", $filename); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha224", $filename)."');"); ?>">INFO:SHA224</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha224", $filename)."');"); ?>"><?php echo hash_file("sha224", $filename); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha256", $filename)."');"); ?>">INFO:SHA256</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha256", $filename)."');"); ?>"><?php echo hash_file("sha256", $filename); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha384", $filename)."');"); ?>">INFO:SHA384</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha384", $filename)."');"); ?>"><?php echo hash_file("sha384", $filename); ?></a><?php echo "\n";
?>[<a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha512", $filename)."');"); ?>">INFO:SHA512</a>] <a href="javascript:<?php echo rawurlencode("alert('".hash_file("sha512", $filename)."');"); ?>"><?php echo hash_file("sha512", $filename); ?></a><?php } echo "\n<hr />\n"; ?>
<?php }
echo "</div>\n";
?>
 </body>
</html>
