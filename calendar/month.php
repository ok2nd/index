<?php /*
	month.php		// 省略：今月
	month.php?m=4		// 今年4月
	month.php?y=2019&m=4	// 2019年4月
*/ ?>
<!DOCTYPE html><html lang="ja">
<head>
<meta charset="utf-8">
<meta name="author" content="ok.2nd">
<meta name="description" content="カレンダー">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0">
<link rel="shortcut icon" href="calendar1.ico" type="image/ico">
<link rel="icon" href="calendar1.ico" type="image/ico">
<title>カレンダー</title>
<link rel="stylesheet" href="calendar.css?20180713">
<style type="text/css">
table.calendar {
	margin: 5px;
	display: inline-block;	/* 高さの異なるボックスを横に並べるならfloatよりinline-block */
	vertical-align: top;
	font-size: 12px;
}
table.calendar td {
	width: 110px;
	height: 80px;
	padding: 2px;
	text-align: left;
	vertical-align: top;
}
table.calendar caption {
	font-size: 24px;
}
.holidays_name {
	margin: 0;
	padding: 0;
}
table.calendar caption {
	margin 0;
	padding 0;
}
table.calendar .title {
	padding 0 40px;
}
table.calendar a.prevnext {
	text-decoration: none;
	padding: 0;
	color: #0080e0;
}
table.calendar .title {
	padding: 0 20px;
}
#comment {
	margin: 10px 8px;
	font-size: 12px;
}
</style>
</head>
<body>
<div>
<?php
	require("__include-calendar.php");
	if (empty($_GET['y'])) {
		$year = date('Y');
	} else {
		$year = $_GET['y'];
	}
	if (empty($_GET['m'])) {
		$month = date('n');
	} else {
		$month = $_GET['m'];
	}
	my_calendar($year, $month, 1);
?>
</div>
<p id="comment">祝日は今年の前後合わせて3年分しか表示されません。</p>
</body>
</html>
