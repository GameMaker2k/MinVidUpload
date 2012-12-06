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
$default_opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n".
              "Accept: ".$_SERVER['HTTP_ACCEPT']."\r\n",
              "Accept-Language: ".$_SERVER['HTTP_ACCEPT_ENCODING']."\r\n",
              "Accept-Encoding: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n",
              "Accept-Charset: ".$_SERVER['HTTP_ACCEPT_CHARSET']."\r\n"
  )
);
$default = stream_context_set_default($default_opts);
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
  <script type="text/javascript" src="<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']); ?>/jwplayer/jwplayer.js"></script>
  <script type="text/javascript" src="<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']); ?>/jwplayer/swfobject.js"></script>
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
<input type="submit" name="submit" value="Submit" /> <button type="button" onclick="if(typeof uploadid === 'undefined') { uploadid = 1; }; var input0 = document.createElement('input'); input0.type = 'file'; input0.name = 'file[]'; input0.id = 'file'+uploadid; document.getElementById('file_upload').appendChild(input0); input1 = document.createElement('select'); input1.setAttribute('name', 'convert[]'); input1.setAttribute('id', 'convert'+uploadid); input1opt1 = document.createElement('Option'); input1opt1.text = 'Dont Convert'; input1opt1.value = 'off'; input1.add(input1opt1); input1opt2 = document.createElement('Option'); input1opt2.text = 'Convert'; input1opt2.value = 'on'; input1.add(input1opt2); document.getElementById('file_upload').appendChild(input1); input2 = document.createElement('select'); input2.setAttribute('name', 'convertype[]'); input2.setAttribute('id', 'convertype'+uploadid); input2opt1 = document.createElement('Option'); input2opt1.text = 'MP4 File'; input2opt1.value = 'mp4'; input2.add(input2opt1); input2opt2 = document.createElement('Option'); input2opt2.text = 'FLV File'; input2opt2.value = 'flv'; input2.add(input2opt2); document.getElementById('file_upload').appendChild(input2); var brline = document.createElement('br'); document.getElementById('file_upload').appendChild(brline); uploadid=++uploadid;">Add More</button><br />
<label for="file0">Filename:</label><br />
<input type="file" name="file[]" id="file0" /><select name="convert[]" id="convert0"><option value="off">Dont Convert</option><option value="on">Convert</option></select><select name="convertype[]" id="convertype0"><option value="mp4">MP4 File</option><option value="flv">FLV File</option></select><br />
</form>

<form name="file_download" id="file_download" action="index.php" method="post">
<input type="submit" name="submit" value="Submit" /> <button type="button" onclick="if(typeof uploadid === 'undefined') { downloadid = 1; }; var input0 = document.createElement('input'); input0.type = 'text'; input0.name = 'getvidurl[]'; input0.id = 'getvidurl'+downloadid; document.getElementById('file_download').appendChild(input0);  var input1 = document.createElement('input'); input1.type = 'text'; input1.name = 'getvidfname[]'; input1.id = 'getvidfname'+downloadid; document.getElementById('file_download').appendChild(input1); input2 = document.createElement('select'); input2.setAttribute('name', 'getvidconvert[]'); input2.setAttribute('id', 'getvidconvert'+downloadid); input2opt1 = document.createElement('Option'); input2opt1.text = 'Dont Convert'; input2opt1.value = 'off'; input2.add(input2opt1); input2opt2 = document.createElement('Option'); input2opt2.text = 'Convert'; input2opt2.value = 'on'; input2.add(input2opt2); document.getElementById('file_download').appendChild(input2); input3 = document.createElement('select'); input3.setAttribute('name', 'getvidconvertype[]'); input3.setAttribute('id', 'getvidconvertype'+downloadid); input3opt1 = document.createElement('Option'); input3opt1.text = 'MP4 File'; input3opt1.value = 'mp4'; input3.add(input3opt1); input3opt2 = document.createElement('Option'); input3opt2.text = 'FLV File'; input3opt2.value = 'flv'; input3.add(input3opt2); document.getElementById('file_download').appendChild(input3); var brline = document.createElement('br'); document.getElementById('file_download').appendChild(brline); downloadid=++downloadid;">Add More</button><br />
<label for="file0">Download URL:</label><br />
<input type="text" name="getvidurl[]" id="getvidurl0" value="http://" /><input type="text" name="getvidfname[]" id="getvidfname0" value="test.flv" /><select name="getvidconvert[]" id="getvidconvert0"><option value="off">Dont Convert</option><option value="on">Convert</option></select><select name="getvidconvertype[]" id="getvidconvertype0"><option value="mp4">MP4 File</option><option value="flv">FLV File</option></select><br />
</form>
 
<?php
$il=0;
$maxl=count($_POST['getvidurl'][$il]);
while($il<=$maxl) {
if(isset($_POST['getvidurl'][$il])&&($_POST['getvidurl'][$il]!=null&&$_POST['getvidurl'][$il]!=""&&$_POST['getvidurl'][$il]!="http://"&&$_POST['getvidurl'][$il]!="https://")) {
$getvidheaders=get_headers($_POST['getvidurl'][$il],1);
while(isset($getvidheaders['Location'])) {
$_POST['getvidurl'][$il]=$getvidheaders['Location'];
$getvidheaders=get_headers($_POST['getvidurl'][$il],1); }
$urlcheck=parse_url($_POST['getvidurl'][$il]); 
var_dump($urlcheck);
if(isset($urlcheck['port'])) { echo $urlcheck['port'];
$getvidfp = fsockopen($urlcheck['host'], $urlcheck['port'], $getviderrno, $getviderrstr, 30); }
if($urlcheck['scheme']=="http"&&!isset($urlcheck['port'])) { echo $urlcheck['scheme'];
$getvidfp = fsockopen($urlcheck['host'], 80, $getviderrno, $getviderrstr, 30); }
if($urlcheck['scheme']=="https"&&!isset($urlcheck['port'])) { echo $urlcheck['scheme'];
$getvidfp = fsockopen($urlcheck['host'], 443, $getviderrno, $getviderrstr, 30); }
if (!$getvidfp) {
    echo $errstr." (".$errno.")<br />\n";
} else {
    if(isset($urlcheck['query'])) { 
    $getvidout = "GET ".$urlcheck['path']."?".$urlcheck['query']." HTTP/1.0\r\n"; }
    if(!isset($urlcheck['query'])) { 
    $getvidout = "GET ".$urlcheck['path']." HTTP/1.0\r\n"; }
    if(isset($urlcheck['port'])) { 
    $getvidout .= "Host: ".$urlcheck['host'].":".$urlcheck['port']."\r\n"; }
    if(!isset($urlcheck['port'])) { 
    $getvidout .= "Host: ".$urlcheck['host']."\r\n"; }
    $getvidout .= "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n";
    $getvidout .= "Accept: ".$_SERVER['HTTP_ACCEPT']."\r\n";
    $getvidout .= "Accept-Language: ".$_SERVER['HTTP_ACCEPT_ENCODING']."\r\n";
    $getvidout .= "Accept-Encoding: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n";
    $getvidout .= "Accept-Charset: ".$_SERVER['HTTP_ACCEPT_CHARSET']."\r\n";
    $getvidout .= "Connection: Close\r\n\r\n";
    fwrite($getvidfp, $getvidout);
	$getvidhdle = fopen(getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$_POST['getvidfname'][$il], "wb+");
	$getvidcontent=null;
    while (!feof($getvidfp)) {
		$getvidcontent .= fgets($getvidfp, 128); }
	$getvidcontent = explode("\r\n\r\n", $getvidcontent, 2);
	fwrite($getvidhdle, $getvidcontent[1], strlen($getvidcontent[1]));
	fclose($getvidhdle);
    fclose($getvidfp); }
    if($_POST['getvidconvert'][$i]=="on") { 
    shell_exec($shell." \"".$basewd.DIRECTORY_SEPARATOR."shell".DIRECTORY_SEPARATOR."convert.sh\" \"".getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]."\" \"".$_POST['getvidconvertype'][$i]."\""); } }
++$il; }
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
if($_POST['convert'][$i]=="on") { 
shell_exec($shell." \"".$basewd.DIRECTORY_SEPARATOR."shell".DIRECTORY_SEPARATOR."convert.sh\" \"".getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]."\" \"".$_POST['convertype'][$i]."\""); }
@clearstatcache(); } 
++$i; }
chdir(getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR);
if(count(glob("{*.flv,*.mp4}", GLOB_BRACE))>0&&glob("{*.flv,*.mp4}", GLOB_BRACE)==!false) { 
	echo "\n<hr />\n"; }
echo "\n<div style=\"white-space: pre-wrap;\">";
foreach (glob("{*.flv,*.mp4}", GLOB_BRACE) as $filename) {
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
<div id="<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>">Loading the player...</div>
<script type="text/javascript">
    jwplayer("<?php echo str_replace("=", ":", base64_encode(pathinfo($filename, PATHINFO_FILENAME))); ?>").setup({
        flashplayer: "<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']); ?>/jwplayer/jwplayer.swf",
        file: "<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/uploads/".$filename; ?>",
        image: "<?php echo $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/thumbnail/".pathinfo($filename, PATHINFO_FILENAME).".png" ?>",
		title: "<?php echo pathinfo($filename, PATHINFO_FILENAME); ?>",
        height: <?php echo $vidarray['height']; ?>,
        width: <?php echo $vidarray['width']; ?>
    });
</script>
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
