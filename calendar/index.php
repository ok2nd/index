<?php /*
	calendar/			// 省略：今月から12か月
	calendar/?y=2018		// 2018年1月から12か月
	calendar/?y=2018&m=4		// 2018年1月から4月から12か月
	calendar/?y=2018&m=4&t=24	// 2018年1月から4月から24か月
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
.holidays_name {
	display: none;
	position: absolute;
	margin: -1px 0 0 8px;
	padding: 5px;
	color: #f00;
	background: #fff;
	border: solid 1px #f00;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
td.holiday span:hover + p.holidays_name {
	display: block;
}
table.calendar .prevnext {
	display: none;
}
#comment {
	margin: 10px 5px;
	font-size: 12px;
}
@media print {
	p#comment {
		display: none;
	}
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
		if (empty($_GET['y'])) {
			$month = date('n');
		} else {
			$month = 1;
		}
	} else {
		$month = $_GET['m'];
	}
	if (empty($_GET['t'])) {
		$span = 12;
	} else {
		$span = $_GET['t'];
	}
	my_calendar($year, $month, $span);
?>
</div>
<p id="comment">祝日は今年の前後合わせて3年分しか表示されません。</p>
</body>
</html>
