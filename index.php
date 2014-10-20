<?php

/*

The MIT License (MIT)

Copyright (c) 2014 Алексей

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

	$vip_host		= "localhost";	//Хост
	$vip_db			= "";		//База Данных
	$vip_username	= "";			//Mysql Логин
	$vip_password	= "";		//Mysql Пароль
	$vip_db_table	= "vip_tab";		//Таблица в которой есть випы. По умолчанию: vip_tab

	//Page Settings
	$PageTitle		= "Список VIP игроков";
	$Title			= "Список VIP";
	$PColor			= "primary";		//default, primary, success
	
	//What elements active?
	$VIPShowSteamID = true;				//Показывать SteamID випов? (true - да|false -нет)
	$VIPShowGroups = true;				//Показывать группы випов? (true - да|false -нет)

/*Do not edit above this comment*/
try
{
	$connect_db = new PDO('mysql:host='.$vip_host.';dbname='.$vip_db, $vip_username, $vip_password);
}
catch(PDOException $e)
{
	die("Failed to connect to database! Please check the database settings.");
}
/*Не реализовано.

$monthes = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля',
5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа',
9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
*/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $PageTitle; ?></title>
		
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/widget.bootstrap.css">
		<link href='http://fonts.googleapis.com/css?family=Alegreya+Sans+SC:400,500,800' rel='stylesheet' type='text/css'>
		
		<!-- Javascript -->
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
    	<script src="js/bootstrap.js" type="text/javascript"></script>
        <script src="js/jquery.tablesorter.js" type="text/javascript"></script>
	</head>
	<body style="background-image:url(img/shattered.jpg);">
	<div class="contrainer">
		<center>
        	<h1 style="font-family: 'Alegreya Sans SC', sans-serif;">
            	<span class="label label-<?php echo $PColor; ?>" style="padding:.2em .6em .2em;"><b><?php echo $Title; ?></b></span>
            </h1>
		</center><br/>
		<div class="panel panel-<?php echo $PColor; ?>">
		<table class="table table-striped table-vips">
			<thead class="table-vips">
				<tr>
					<th>Ник</td>
					<?php
					if ($VIPShowSteamID)
					{
						echo "<td>SteamID</td>";
					}?>
					<?php
					if ($VIPShowGroups)
					{
						echo "<td>Группа</td>";
					}?>
					<th>Истекает</td>
				</tr>
			</thead>
			<tbody>
<?php
	$SelectPlayers = $connect_db->query("SELECT * FROM ".$vip_db_table);
	foreach($SelectPlayers as $PrintPlayers)
	{
		echo '<tr>';
		echo '<td>'.$PrintPlayers['name'].'</td>';
		if ($VIPShowSteamID)
		{
			echo '<td>'.$PrintPlayers['steamid'].'</td>';
		}
		if ($VIPShowGroups)
		{
			echo '<td>'.$PrintPlayers['group'].'</td>';
		}
		if ($PrintPlayers['time'] == '0'){
			echo "<td>Никогда</td>";
		}
		else{
			echo '<td>'.date("d-m-Y H:i", $PrintPlayers['time']).'</td>';
		}
	}
?>
			</tbody>
		</table>
	</div>
	</body>
	<footer>
		Autor <a href="https://github.com/TiBarification">TiBarification</a><br/>
		Made with <a href="http://getbootstrap.com/">Bootstrap</a>.
	</footer>
</html>