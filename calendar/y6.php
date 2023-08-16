<?php /*
	calendar/?y=2019		// 2019年1月から12か月
	calendar/?y=2019&m=4		// 2019年4月から12か月
*/ ?>
<!DOCTYPE html><html lang="ja">
<head>
<meta charset="utf-8">
<meta name="author" content="ok.2nd">
<meta name="description" content="6か月印刷用カレンダー">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=0">
<link rel="shortcut icon" href="calendar1.ico" type="image/ico">
<link rel="icon" href="calendar1.ico" type="image/ico">
<title>6か月印刷用カレンダー</title>
<link rel="stylesheet" href="calendar-no-today.css?20180713">
<style type="text/css">
table.calendar {
	margin: 5px;
	display: inline-block;	/* 高さの異なるボックスを横に並べるならfloatよりinline-block */
	vertical-align: top;
	font-size: 16px;
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
hr {
	page-break-after: always;
	visibility: hidden;
}
.page {
	margin: auto;
	width: 470px;
}
.month {
	font-weight: bold;
	font-size: 20px;
}
h1 {
	text-align: center;
	margin: 0px;
	padding: 0px;
}
</style>
</head>
<body>
<?php
	if (empty($_GET['y'])) {
?>
<br>　　年(例：/calendar/y6.php?y=2019)を指定してください。
<?php
		exit;
	}
	$year = $_GET['y'];
	if (empty($_GET['m'])) {
		$month = 1;
	} else {
		$month = $_GET['m'];
	}
?>
<div class="page">
<h1><?= $year ?></h1>
<?php
	my_calendar($year, $month, 6);
?>
</div>
<hr>
<div class="page">
<?php
	if ($month >= 7) {
		$year+=1;
		$month-=6;
	} else {
		$month+=6;
	}
?>
<h1><?= $year ?></h1>
<?php
	my_calendar($year, $month, 6);
?>
</div>
</body>
</html>
<?php
function my_calendar($year, $month, $num) {	// $num: 月数
	$holidays = get_holidays($year);
	for ($i = 1; $i <= $num; $i++) {
		calendar($year, $month, $holidays);
		if ($month++ >= 12) {
			$year++;
			$month = 1;
		}
	}
}
function calendar($year, $month, $holidays) {
	$last_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
	$calendar = array();
	$j = 0;
	for ($i = 1; $i < $last_day + 1; $i++) {
		$week = date('w', mktime(0, 0, 0, $month, $i, $year));
		if ($i == 1) {
			for ($s = 1; $s <= $week; $s++) {
				$calendar[$j]['day'] = '';
				$j++;
			}
		}
		$calendar[$j]['day'] = $i;
		$j++;
		if ($i == $last_day) {
			for ($e = 1; $e <= 6 - $week; $e++) {
				$calendar[$j]['day'] = '';
				$j++;
			}
		}
	}
	if ($month == 12) {
		$next_year = $year + 1;
		$next_month = 1;
	} else {
		$next_year = $year;
		$next_month = $month + 1;
	}
	if ($month == 1) {
		$prev_year = $year - 1;
		$prev_month = 12;
	} else {
		$prev_year = $year;
		$prev_month = $month - 1;
	}
?>
<table class="calendar">
<caption><a class="prevnext" href="?y=<?= $prev_year ?>&m=<?= $prev_month ?>">＜＜</a><span class="month"><?= $month ?></span><a class="prevnext" href="?y=<?= $next_year ?>&m=<?= $next_month ?>">＞＞</a></caption>
	<tr>
		<th class="sunday">日</th>
		<th class="weekday">月</th>
		<th class="weekday">火</th>
		<th class="weekday">水</th>
		<th class="weekday">木</th>
		<th class="weekday">金</th>
		<th class="saturday">土</th>
	</tr>
	<tr>
<?php
	$cnt = 0;
	foreach ($calendar as $key => $value) {
		$ymd = $year.'-'.$month.'-'.$value['day'];
		if (isset($holidays[$ymd])) {
			$holiday_class = ' holiday';
			$holidays_name = '<p class="holidays_name">'.$holidays[$ymd].'</p>';
		} else {
			$holiday_class = '';
			$holidays_name = '';
		}
?>
		<td class="<?= ($cnt == 0) ? 'sunday' : '' ?><?= ($cnt == 6) ? 'saturday' : '' ?><?= $holiday_class ?><?= ((date('Y') == $year) && (date('m') == $month) && (date('d') == $value['day'])) ? ' today' : '' ?>"><?php $cnt++; ?><span><?= $value['day'] ?></span><?= $holidays_name ?></td>
<?php
		if ($cnt == 7) {
			$cnt = 0;
?>
	</tr>
	<tr>
<?php
		}
	}
?>
	</tr>
</table>
<?php
}
function get_holidays($year) {	// 祝日2年分取得 ($year ～ $year+1)
	$holidays = array();
	$google_api_key = 'AIzaSyDiyY1Cle9grt7KeLMLUikckt6WJewiZlI';	// Google API KEY
	$holidays_id = 'japanese__ja@holiday.calendar.google.com';	// Google 公式版日本語
	//$holidays_id = 'japanese@holiday.calendar.google.com';	// Google 公式版英語
	//$holidays_id = 'outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com';	// mozilla.org版
	$url = 'https://www.googleapis.com/calendar/v3/calendars/'.$holidays_id.'/events'
		.'?key='.$google_api_key
		.'&timeMin='.$year.'-01-01T00:00:00Z'
		.'&timeMax='.($year+1).'-12-31T00:00:00Z'
		.'&maxResults=100'
		.'&orderBy=startTime'
		.'&singleEvents=true';
	if ($results = file_get_contents($url, true)) {
		$results = json_decode($results);
		foreach ($results->items as $item ) {
			$date = strtotime((string) $item->start->date);
			$name = (string) $item->summary;
			$holidays[date('Y-n-j', $date)] = $name;
		}
	}
	return $holidays;
}
?>
