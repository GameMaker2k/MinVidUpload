<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2011 iDB Support - http://idb.berlios.de/
    Copyright 2011 Game Maker 2k - http://gamemaker2k.org/

    $FileInfo: index.php - Last Update: 12/07/2012 Version: 0.2.5 - Author: cooldude2k $
*/
// ffmpeg write progress to file
// http://stackoverflow.com/questions/747982/can-ffmpeg-show-a-progress-bar
// http://stackoverflow.com/questions/6481210/getting-progress-for-multiple-exec-processes-realtime
ob_start("ob_gzhandler");
$shell = "/bin/sh";
@ob_start("ob_gzhandler");
@ini_set("html_errors", false);
@ini_set("track_errors", false);
@ini_set("display_errors", false);
@ini_set("report_memleaks", false);
@ini_set("display_startup_errors", false);
@ini_set("docref_ext", "");
@ini_set("docref_root", "http://php.net/");
if (!defined("E_DEPRECATED")) {
    define("E_DEPRECATED", 0);
}
@error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
@ini_set("ignore_user_abort", 1);
@set_time_limit(0);
@ignore_user_abort(true);
@ini_set('zend.ze1_compatibility_mode', 0);
@ini_set("date.timezone", "UTC");
@date_default_timezone_set("UTC");
$default_opts = array(
  'http' => array(
    'method' => "GET",
    'header' => "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n".
              "Accept: ".$_SERVER['HTTP_ACCEPT']."\r\n",
              "Accept-Language: ".$_SERVER['HTTP_ACCEPT_ENCODING']."\r\n",
              "Accept-Encoding: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n",
              "Accept-Charset: ".$_SERVER['HTTP_ACCEPT_CHARSET']."\r\n"
  )
);
$default = stream_context_set_default($default_opts);
if (!isset($_SERVER['HTTP_USER_AGENT'])) {
    $_SERVER['HTTP_USER_AGENT'] = "";
}
if (strpos($_SERVER['HTTP_USER_AGENT'], "msie") &&
    !strpos($_SERVER['HTTP_USER_AGENT'], "opera")) {
    header("X-UA-Compatible: IE=Edge");
}
if (strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) {
    header("X-UA-Compatible: IE=Edge,chrome=1");
}
header("Content-Language: en");
header("Content-Type: text/html; charset=UTF-8");
session_cache_limiter("private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Cache-Control: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Pragma: private, no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("P3P: CP=\"IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\"");
header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
output_reset_rewrite_vars();
@clearstatcache();
if (!isset($_SERVER["REQUEST_SCHEME"])) {
    if (!isset($_SERVER['HTTPS'])) {
        $_SERVER["REQUEST_SCHEME"] = "http";
    }
    if (isset($_SERVER['HTTPS'])) {
        $_SERVER["REQUEST_SCHEME"] = "https";
    }
}
$website_info['flash_player'] = "jwplayer";
$website_info['sname'] = "MinVidUpload";
$website_info['lname'] = "Minimalist Video Uploader";
$website_info['author'] = "Kazuki Przyborowski";
$website_info['keywords'] = "MinUpload,MinVidUpload,Minimalist Video Upload,Minimalist,Video Upload,Video,Upload";
$website_info['description'] = "MinVidUpload ( Minimalist Video Upload ) by Kazuki Przyborowski";
$website_info['fname'] = " ".$website_info['sname']." ( ".$website_info['lname']." ) ";
$website_info['main_url'] = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);
$website_info['jwplayer_url'] = $website_info['main_url']."/jwplayer";
$website_info['jquery_url'] = $website_info['main_url']."/jquery";
$website_info['flowplayer_url'] = $website_info['main_url']."/flowplayer";
$website_info['thumbnail_url'] = $website_info['main_url']."/thumbnail";
$website_info['upload_url'] = $website_info['main_url']."/uploads";
$website_info['charset'] = "UTF-8";
$website_info['language'] = "en";
$website_info['main_dir'] = getcwd();
$website_info['jwplayer_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."jwplayer";
$website_info['jquery_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."jquery";
$website_info['flowplayer_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."flowplayer";
$website_info['shell_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."shell";
$website_info['thumbnail_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."thumbnail";
$website_info['upload_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."uploads";
$website_info['vidlog_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."vidlogs";
$website_info['vidtmp_dir'] = $website_info['main_dir'].DIRECTORY_SEPARATOR."vidtmp";
$website_info['main_file'] = "index.php";
if (!file_exists($website_info['main_dir'].DIRECTORY_SEPARATOR."thumbnail")) {
    mkdir($website_info['main_dir'].DIRECTORY_SEPARATOR."thumbnail");
}
if (!file_exists($website_info['main_dir'].DIRECTORY_SEPARATOR."uploads")) {
    mkdir($website_info['main_dir'].DIRECTORY_SEPARATOR."uploads");
}
if (!file_exists($website_info['main_dir'].DIRECTORY_SEPARATOR."vidlogs")) {
    mkdir($website_info['main_dir'].DIRECTORY_SEPARATOR."vidlogs");
}
if (!file_exists($website_info['main_dir'].DIRECTORY_SEPARATOR."vidtmp")) {
    mkdir($website_info['main_dir'].DIRECTORY_SEPARATOR."vidtmp");
}
if (!isset($_GET['act']) && isset($_GET['id'])) {
    $_GET['act'] = "view";
}
if (!isset($_GET['act'])) {
    $_GET['act'] = "upload";
}
if ($_GET['act'] == "view" && !isset($_GET['id'])) {
    $_GET['act'] = "upload";
}
if ($_GET['act'] == "delete" && !isset($_GET['filename']) && isset($_GET['id'])) {
    $_GET['filename'] = base64_decode(str_replace(":", "=", $_GET['id']));
}
if ($_GET['act'] == "delete" && isset($_GET['filename'])) {
    if (file_exists($website_info['upload_dir'].DIRECTORY_SEPARATOR.$_GET['filename'])) {
        unlink($website_info['upload_dir'].DIRECTORY_SEPARATOR.$_GET['filename']);
    }
    if (file_exists($website_info['thumbnail_dir'].DIRECTORY_SEPARATOR.pathinfo($_GET['filename'], PATHINFO_FILENAME).".png")) {
        unlink($website_info['thumbnail_dir'].DIRECTORY_SEPARATOR.pathinfo($_GET['filename'], PATHINFO_FILENAME).".png");
    }
    $_GET['act'] = "upload";
}
if ($_GET['act'] == "delete" && !isset($_GET['filename'])) {
    $_GET['act'] = "upload";
}
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
  <title><?php echo $website_info['fname']; ?></title>
  <meta name="generator" content="<?php echo $website_info['lname']; ?>" />
  <meta name="author" content="<?php echo $website_info['author']; ?>" />
  <meta name="keywords" content="<?php echo $website_info['keywords']; ?>" />
  <meta name="description" content="<?php echo $website_info['description']; ?>" />
  <meta name="ROBOTS" content="Index, FOLLOW" />
  <meta name="revisit-after" content="1 days" />
  <meta name="GOOGLEBOT" content="Index, FOLLOW" />
  <meta name="resource-type" content="document" />
  <meta name="distribution" content="global" />
  <?php
  if (!isset($_SERVER['HTTP_USER_AGENT'])) {
      $_SERVER['HTTP_USER_AGENT'] = "";
  }
if (strpos($_SERVER['HTTP_USER_AGENT'], "msie") &&
  !strpos($_SERVER['HTTP_USER_AGENT'], "opera")) { ?>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
  <?php } if (strpos($_SERVER['HTTP_USER_AGENT'], "chromeframe")) { ?>
  <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
  <?php } ?>
  <meta http-equiv="Content-Language" content="en" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <meta http-equiv="Cache-Control" content="private, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0" />
  <meta http-equiv="Pragma" content="private, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0" />
  <meta http-equiv="Expires" content="<?php echo gmdate("D, d M Y H:i:s")." GMT"; ?>" />
  <meta http-equiv="P3P" content='CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"' /> 
  <meta http-equiv="P3P" name="CP" content="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT" />
  <meta http-equiv="Expires" content="<?php echo gmdate("D, d M Y H:i:s")." GMT"; ?>" />
  <base href="<?php echo $website_info['main_url']; ?>/" />
  <script type="text/javascript" src="<?php echo $website_info['jquery_url']; ?>/jquery.js"></script>
  <?php if ($website_info['flash_player'] == "jwplayer") { ?>
  <script type="text/javascript" src="<?php echo $website_info['jwplayer_url']; ?>/jwplayer.js"></script>
  <script type="text/javascript" src="<?php echo $website_info['jwplayer_url']; ?>/swfobject.js"></script>
  <?php } if ($website_info['flash_player'] == "flowplayer") { ?>
  <script type="text/javascript" src="<?php echo $website_info['flowplayer_url']; ?>/flowplayer.js"></script>
  <?php } ?>
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

<?php if ($_GET['act'] == "upload") { ?>
<form name="file_upload" id="file_upload" action="<?php echo $website_info['main_file']; ?>?act=upload" method="post" enctype="multipart/form-data">
<input type="submit" name="submit" value="Submit" /> <button type="button" onclick="if(typeof uploadid === 'undefined') { uploadid = 1; }; var input0 = document.createElement('input'); input0.type = 'file'; input0.name = 'file[]'; input0.id = 'file'+uploadid; document.getElementById('file_upload').appendChild(input0); input1 = document.createElement('select'); input1.setAttribute('name', 'convert[]'); input1.setAttribute('id', 'convert'+uploadid); input1opt1 = document.createElement('Option'); input1opt1.text = 'Dont Convert'; input1opt1.value = 'off'; input1.add(input1opt1); input1opt2 = document.createElement('Option'); input1opt2.text = 'Convert'; input1opt2.value = 'on'; input1.add(input1opt2); document.getElementById('file_upload').appendChild(input1); input2 = document.createElement('select'); input2.setAttribute('name', 'convertype[]'); input2.setAttribute('id', 'convertype'+uploadid); input2opt1 = document.createElement('Option'); input2opt1.text = 'MP4 File'; input2opt1.value = 'mp4'; input2.add(input2opt1); input2opt2 = document.createElement('Option'); input2opt2.text = 'FLV File'; input2opt2.value = 'flv'; input2.add(input2opt2); document.getElementById('file_upload').appendChild(input2); var brline = document.createElement('br'); document.getElementById('file_upload').appendChild(brline); uploadid=++uploadid;">Add More</button><br />
<label for="file0">Filename:</label><br />
<input type="file" name="file[]" id="file0" /><select name="convert[]" id="convert0"><option value="off">Dont Convert</option><option value="on">Convert</option></select><select name="convertype[]" id="convertype0"><option value="mp4">MP4 File</option><option value="flv">FLV File</option></select><br />
</form>

<form name="file_download" id="file_download" action="<?php echo $website_info['main_file']; ?>?act=upload" method="post">
<input type="submit" name="submit" value="Submit" /> <button type="button" onclick="if(typeof uploadid === 'undefined') { downloadid = 1; }; var input0 = document.createElement('input'); input0.type = 'text'; input0.name = 'getvidurl[]'; input0.id = 'getvidurl'+downloadid; document.getElementById('file_download').appendChild(input0);  var input1 = document.createElement('input'); input1.type = 'text'; input1.name = 'getvidfname[]'; input1.id = 'getvidfname'+downloadid; document.getElementById('file_download').appendChild(input1); input2 = document.createElement('select'); input2.setAttribute('name', 'getvidconvert[]'); input2.setAttribute('id', 'getvidconvert'+downloadid); input2opt1 = document.createElement('Option'); input2opt1.text = 'Dont Convert'; input2opt1.value = 'off'; input2.add(input2opt1); input2opt2 = document.createElement('Option'); input2opt2.text = 'Convert'; input2opt2.value = 'on'; input2.add(input2opt2); document.getElementById('file_download').appendChild(input2); input3 = document.createElement('select'); input3.setAttribute('name', 'getvidconvertype[]'); input3.setAttribute('id', 'getvidconvertype'+downloadid); input3opt1 = document.createElement('Option'); input3opt1.text = 'MP4 File'; input3opt1.value = 'mp4'; input3.add(input3opt1); input3opt2 = document.createElement('Option'); input3opt2.text = 'FLV File'; input3opt2.value = 'flv'; input3.add(input3opt2); document.getElementById('file_download').appendChild(input3); var brline = document.createElement('br'); document.getElementById('file_download').appendChild(brline); downloadid=++downloadid;">Add More</button><br />
<label for="file0">Download URL:</label><br />
<input type="text" name="getvidurl[]" id="getvidurl0" value="http://" /><input type="text" name="getvidfname[]" id="getvidfname0" value="test.flv" /><select name="getvidconvert[]" id="getvidconvert0"><option value="off">Dont Convert</option><option value="on">Convert</option></select><select name="getvidconvertype[]" id="getvidconvertype0"><option value="mp4">MP4 File</option><option value="flv">FLV File</option></select><br />
</form>
 
<?php
$il = 0;
    $maxl = count($_POST['getvidurl'][$il]);
    while ($il <= $maxl) {
        if (isset($_POST['getvidurl'][$il]) && ($_POST['getvidurl'][$il] != null && $_POST['getvidurl'][$il] != "" && $_POST['getvidurl'][$il] != "http://" && $_POST['getvidurl'][$il] != "https://")) {
            $getvidheaders = get_headers($_POST['getvidurl'][$il], 1);
            while (isset($getvidheaders['Location'])) {
                $_POST['getvidurl'][$il] = $getvidheaders['Location'];
                $getvidheaders = get_headers($_POST['getvidurl'][$il], 1);
            }
            $urlcheck = parse_url($_POST['getvidurl'][$il]);
            var_dump($urlcheck);
            if (isset($urlcheck['port'])) {
                echo $urlcheck['port'];
                $getvidfp = fsockopen($urlcheck['host'], $urlcheck['port'], $getviderrno, $getviderrstr, 30);
            }
            if ($urlcheck['scheme'] == "http" && !isset($urlcheck['port'])) {
                echo $urlcheck['scheme'];
                $getvidfp = fsockopen($urlcheck['host'], 80, $getviderrno, $getviderrstr, 30);
            }
            if ($urlcheck['scheme'] == "https" && !isset($urlcheck['port'])) {
                echo $urlcheck['scheme'];
                $getvidfp = fsockopen($urlcheck['host'], 443, $getviderrno, $getviderrstr, 30);
            }
            if (!$getvidfp) {
                echo $errstr." (".$errno.")<br />\n";
            } else {
                if (isset($urlcheck['query'])) {
                    $getvidout = "GET ".$urlcheck['path']."?".$urlcheck['query']." HTTP/1.0\r\n";
                }
                if (!isset($urlcheck['query'])) {
                    $getvidout = "GET ".$urlcheck['path']." HTTP/1.0\r\n";
                }
                if (isset($urlcheck['port'])) {
                    $getvidout .= "Host: ".$urlcheck['host'].":".$urlcheck['port']."\r\n";
                }
                if (!isset($urlcheck['port'])) {
                    $getvidout .= "Host: ".$urlcheck['host']."\r\n";
                }
                $getvidout .= "User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n";
                $getvidout .= "Accept: ".$_SERVER['HTTP_ACCEPT']."\r\n";
                $getvidout .= "Accept-Language: ".$_SERVER['HTTP_ACCEPT_ENCODING']."\r\n";
                $getvidout .= "Accept-Encoding: ".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\r\n";
                $getvidout .= "Accept-Charset: ".$_SERVER['HTTP_ACCEPT_CHARSET']."\r\n";
                $getvidout .= "Connection: Close\r\n\r\n";
                fwrite($getvidfp, $getvidout);
                $getvidhdle = fopen($website_info['upload_dir'].DIRECTORY_SEPARATOR.$_POST['getvidfname'][$il], "wb+");
                $getvidcontent = null;
                while (!feof($getvidfp)) {
                    $getvidcontent .= fgets($getvidfp, 128);
                }
                $getvidcontent = explode("\r\n\r\n", $getvidcontent, 2);
                fwrite($getvidhdle, $getvidcontent[1], strlen($getvidcontent[1]));
                fclose($getvidhdle);
                fclose($getvidfp);
            }
            if ($_POST['getvidconvert'][$i] == "on") {
                shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."convert.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]."\" \"".$_POST['getvidconvertype'][$i]."\"");
            }
        }
        ++$il;
    }
    $i = 0;
    $max = count($_FILES['file']['error']) - 1;
    while ($i <= $max) {
        if ($_FILES['file']['error'][$i] === 0) {
            echo "<hr />";
            echo $_FILES['file']['tmp_name'][$i]." => ".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]."<br />\n";
            echo "<a href=\"".$website_info['upload_url']."/".$_FILES['file']['name'][$i]."\" title=\"".$_FILES['file']['name'][$i]."\">".$website_info['upload_url']."/".$_FILES['file']['name'][$i]."</a><br />\n";
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
            move_uploaded_file($_FILES['file']['tmp_name'][$i], $website_info['upload_dir'].DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]);
            if ($_POST['convert'][$i] == "on") {
                shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."convert.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$_FILES['file']['name'][$i]."\" \"".$_POST['convertype'][$i]."\"");
            }
            @clearstatcache();
        }
        ++$i;
    }
    chdir($website_info['upload_dir'].DIRECTORY_SEPARATOR);
    foreach (glob("*") as $filename) {
        $fileinfo = pathinfo($filename);
        if (preg_match("/(mp4|flv)/i", $fileinfo['extension']) &&
            ($fileinfo['extension'] != "mp4" && $fileinfo['extension'] != "flv")) {
            $newfilename = $fileinfo['filename'].".".strtolower($fileinfo['extension']);
            rename($filename, $newfilename);
        }
    }
    if (count(glob("{*.flv,*.mp4}", GLOB_BRACE)) > 0 && glob("{*.flv,*.mp4}", GLOB_BRACE) == !false) {
        echo "\n<hr />\n";
    }
    echo "\n<div style=\"white-space: pre-wrap;\">";
    foreach (glob("{*.flv,*.mp4}", GLOB_BRACE) as $filename) {
        $vidarray['width'] = trim(shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."getwidth.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\""));
        $vidarray['height'] = trim(shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."getheight.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\""));
        $vidarray['resolution'] = $vidarray['width']."x".$vidarray['height'];
        $vidarray['mininfo'] = trim(shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."getmininfo.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\""));
        preg_match('/.*Duration: ([0-9:]+).*/', $vidarray['mininfo'], $tmp_duration);
        $vidarray['duration'] = $tmp_duration[1];
        preg_match('/([0-9]{2}):([0-9]{2}):([0-9]{2})/', $vidarray['duration'], $gettimestamp);
        $vidarray['timestamp'] = ($gettimestamp[1] * 3600) + ($gettimestamp[2] * 60) + ($gettimestamp[3] * 1);
        if (!file_exists($website_info['thumbnail_dir'].DIRECTORY_SEPARATOR.pathinfo($filename, PATHINFO_FILENAME).".png")) {
            shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."thumbnail.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\" \"".round($vidarray['timestamp'] / rand(4, 24), 2)."\"");
        }
        echo "<a href=\"".$website_info['main_url']."/".$website_info['main_file']."?id=".urlencode(str_replace("=", ":", base64_encode($filename)))."\">View ".htmlentities($filename, ENT_COMPAT | ENT_HTML401, "UTF-8", false)."</a>\n";
        if ($website_info['flash_player'] == "jwplayer") { ?>
<div id="<?php echo str_replace("=", ":", base64_encode($filename)); ?>">Loading the player...</div>
<script type="text/javascript">
    <!--
    jwplayer("<?php echo str_replace("=", ":", base64_encode($filename)); ?>").setup({
        flashplayer: "<?php echo $website_info['jwplayer_url']; ?>/jwplayer.swf",
        file: "<?php echo $website_info['upload_url']."/".$filename; ?>",
        image: "<?php echo $website_info['thumbnail_url']."/".pathinfo($filename, PATHINFO_FILENAME).".png" ?>",
        height: <?php echo $vidarray['height']; ?>,
        width: <?php echo $vidarray['width']."\n"; ?>
    });
    //-->
</script>
<?php } if ($website_info['flash_player'] == "flowplayer") { ?>
<div id="<?php echo str_replace("=", ":", base64_encode($filename)); ?>" class="<?php echo str_replace("=", ":", base64_encode($filename)); ?>" style="width: <?php echo $vidarray['width']; ?>px; height: <?php echo $vidarray['height']; ?>px;"></div>
<script type="text/javascript">
    <!--
  // Flowplayer installation with Flashembed parameters
    flowplayer("<?php echo str_replace("=", ":", base64_encode($filename)); ?>", {
 
        // our Flash component
        src: "<?php echo $website_info['flowplayer_url']; ?>/flowplayer.swf",
 
        // Flowplayer requires at least this version
        version: [10, 1],
 
        // older versions will see a custom message
        onFail: function ()  {
            document.getElementById("info").innerHTML =
                "You need at least Flash version 10.1 to play the movie. " +
                "Your version is " + this.getVersion();
        }
    },
    // here is our third argument which is the Flowplayer configuration
    {
        playlist: [
		"<?php echo $website_info['thumbnail_url']."/".pathinfo($filename, PATHINFO_FILENAME).".png" ?>",
        {
	       autoPlay: false,
		   scaling: 'fit',
		   autoBuffering: false,
	       "url": "<?php echo $website_info['upload_url']."/".$filename; ?>"
        }]
    });
    //-->
</script>
<?php }
echo "[<a href=\"".$website_info['main_url']."/".$website_info['main_file']."?act=delete&amp;id=".urlencode(str_replace("=", ":", base64_encode($filename)))."\">Delete</a>] <a href=\"".$website_info['upload_url']."/".rawurlencode($filename)."\" title=\"".$filename."\">".$filename."</a>\n";
        ?>[<a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>">INFO:CTIME</a>] <a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", filectime($filename)); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>">INFO:ATIME</a>] <a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", fileatime($filename)); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo rawurlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>">INFO:SIZE</a>] <a href="javascript:<?php echo rawurlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>"><?php echo filesize($filename)." Bytes =&gt; "._format_bytes(filesize($filename)); ?></a><?php echo "\n";
        ?>[<a href="javascript:<?php echo rawurlencode("alert('Resolution: ".$vidarray['resolution'].",Duration: ".$vidarray['duration']."');"); ?>">INFO:VIDEO</a>] <a href="javascript:<?php echo rawurlencode("alert('Resolution: ".$vidarray['resolution'].",Duration: ".$vidarray['duration']."');"); ?>">Resolution: <?php echo $vidarray['resolution']; ?>,Duration: <?php echo $vidarray['duration']; ?></a><?php echo "\n<hr />\n"; ?>
<?php } echo "</div>\n";
} if ($_GET['act'] == "view" && isset($_GET['id'])) {
    echo "\n<div style=\"width: 100%; height: 100%; vertical-align: middle; white-space: pre-wrap;\">";
    $filename = base64_decode(str_replace(":", "=", $_GET['id']));
    chdir($website_info['upload_dir'].DIRECTORY_SEPARATOR);
    $vidarray['width'] = trim(shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."getwidth.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\""));
    $vidarray['height'] = trim(shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."getheight.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\""));
    $vidarray['resolution'] = $vidarray['width']."x".$vidarray['height'];
    $vidarray['mininfo'] = trim(shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."getmininfo.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\""));
    preg_match('/.*Duration: ([0-9:]+).*/', $vidarray['mininfo'], $tmp_duration);
    $vidarray['duration'] = $tmp_duration[1];
    preg_match('/([0-9]{2}):([0-9]{2}):([0-9]{2})/', $vidarray['duration'], $gettimestamp);
    $vidarray['timestamp'] = ($gettimestamp[1] * 3600) + ($gettimestamp[2] * 60) + ($gettimestamp[3] * 1);
    if (!file_exists($website_info['thumbnail_dir'].DIRECTORY_SEPARATOR.pathinfo($filename, PATHINFO_FILENAME).".png")) {
        shell_exec($shell." \"".$website_info['shell_dir'].DIRECTORY_SEPARATOR."thumbnail.sh\" \"".$website_info['upload_dir'].DIRECTORY_SEPARATOR.$filename."\" \"".round($vidarray['timestamp'] / rand(4, 24), 2)."\"");
    }
    if ($website_info['flash_player'] == "jwplayer") { ?>
<div id="<?php echo str_replace("=", ":", base64_encode($filename)); ?>">Loading the player...</div>
<script type="text/javascript">
    <!--
    jwplayer("<?php echo str_replace("=", ":", base64_encode($filename)); ?>").setup({
        flashplayer: "<?php echo $website_info['jwplayer_url']; ?>/jwplayer.swf",
        file: "<?php echo $website_info['upload_url']."/".$filename; ?>",
        image: "<?php echo $website_info['thumbnail_url']."/".pathinfo($filename, PATHINFO_FILENAME).".png" ?>",
        height: <?php echo $vidarray['height']; ?>,
        width: <?php echo $vidarray['width']."\n"; ?>
    });
    //-->
</script>
<?php } if ($website_info['flash_player'] == "flowplayer") { ?>
<div id="<?php echo str_replace("=", ":", base64_encode($filename)); ?>" class="<?php echo str_replace("=", ":", base64_encode($filename)); ?>" style="width: <?php echo $vidarray['width']; ?>px; height: <?php echo $vidarray['height']; ?>px;"></div>
<script type="text/javascript">
    <!--
  // Flowplayer installation with Flashembed parameters
    flowplayer("<?php echo str_replace("=", ":", base64_encode($filename)); ?>", {
 
        // our Flash component
        src: "<?php echo $website_info['flowplayer_url']; ?>/flowplayer.swf",
 
        // Flowplayer requires at least this version
        version: [10, 1],
 
        // older versions will see a custom message
        onFail: function ()  {
            document.getElementById("info").innerHTML =
                "You need at least Flash version 10.1 to play the movie. " +
                "Your version is " + this.getVersion();
        }
    },
    // here is our third argument which is the Flowplayer configuration
    {
        playlist: [
		"<?php echo $website_info['thumbnail_url']."/".pathinfo($filename, PATHINFO_FILENAME).".png" ?>",
        {
	       autoPlay: false,
		   scaling: 'fit',
		   autoBuffering: false,
	       "url": "<?php echo $website_info['upload_url']."/".$filename; ?>"
        }]
    });
    //-->
</script>
<?php }
echo "[<a href=\"".$website_info['main_url']."/".$website_info['main_file']."?act=delete&amp;filename=".rawurlencode($filename)."\">Delete</a>] <a href=\"".$website_info['upload_url']."/".rawurlencode($filename)."\" title=\"".$filename."\">".$filename."</a>\n";
    ?>[<a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>">INFO:CTIME</a>] <a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", filectime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", filectime($filename)); ?></a><?php echo "\n";
    ?>[<a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>">INFO:ATIME</a>] <a href="javascript:<?php echo rawurlencode("alert('".gmdate("F d Y H:i:s", fileatime($filename))."');"); ?>"><?php echo gmdate("F d Y H:i:s", fileatime($filename)); ?></a><?php echo "\n";
    ?>[<a href="javascript:<?php echo rawurlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>">INFO:SIZE</a>] <a href="javascript:<?php echo rawurlencode("alert('".filesize($filename)." Bytes => "._format_bytes(filesize($filename))."');"); ?>"><?php echo filesize($filename)." Bytes =&gt; "._format_bytes(filesize($filename)); ?></a><?php echo "\n";
    ?>[<a href="javascript:<?php echo rawurlencode("alert('Resolution: ".$vidarray['resolution'].",Duration: ".$vidarray['duration']."');"); ?>">INFO:VIDEO</a>] <a href="javascript:<?php echo rawurlencode("alert('Resolution: ".$vidarray['resolution'].",Duration: ".$vidarray['duration']."');"); ?>">Resolution: <?php echo $vidarray['resolution']; ?>,Duration: <?php echo $vidarray['duration']; ?></a><?php echo "\n"; ?>
<?php echo "</div>\n";
} ?>
 </body>
</html>
