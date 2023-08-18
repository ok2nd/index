<?php
if (!isset($_GET['dir'])) {
	exit;
}
?><!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=0.46, maximum-scale=1.0, user-scalable=yes">
<meta name="description" content="">
<title>PhotoアルバムHTML作成</title>
<link href="https://fonts.googleapis.com/css?family=Sawarabi+Mincho" rel="stylesheet">
<link href="css/vignette-thumbnail.css" rel="stylesheet">
<style>
.subtitle {
	font-size: 18px;
}
</style>
</head>
<body>
<h1>PhotoアルバムHTML作成　<span class="subtitle">サブタイトル</span></h1>
<!--
	http://localhost/github/index/photo/-thumbnail-create.php?dir=art1
-->
<div id="gallery">
<?php
	$cwd = getcwd();
	$dir = $_GET['dir'];
	$open_path = $cwd.'/'.$dir.'/small';
	if ($opendir = @opendir($open_path)) {		// @ : エラー制御演算子 (E_WARNINGを出さないため)
		$files = scandir($open_path);
		natcasesort($files);
		$order = 0;
		foreach ($files as $file) {
			if (!is_dir($open_path.'/'.$file)) {
?>
	<a href="photo.html?photo=<?= $dir ?>/big/<?= $file ?>">
		<div class="box-frame"><div class="vignette"><img src="<?= $dir ?>/small/<?= $file ?>"></div></div>
	</a>
<?php
			}
		}
		closedir($opendir);
	}
?>
</div>
<div style="clear:both;padding:10px 0 0 0;text-align:center;">
<a href="https://2ndart.hatenablog.com/" target="_blank">晴歩雨描</a>
</div>
</body>
</html>
