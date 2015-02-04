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
	$VIPOrderBy = 'group';				//Сортировать таблицу по (name - нику; steamid - SteamID; group - группе; time - истекает)
	$Per_Page = 5;						//Сколько элементов на страницу

/*Do not edit above this comment*/
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
Post($vip_host, $vip_username, $vip_password, $vip_db, $vip_db_table, $critery = null, $limit = $Per_Page, $VIPOrderBy, $VIPShowSteamID, $VIPShowGroups);
	
function Post($vip_host, $vip_username, $vip_password, $vip_db, $table, $critery = null, $limit = 5, $OrderBy = 'group', $ShowSteamID = true, $ShowGroups = true)
{
	try
	{
		$connect_db = new PDO('mysql:host='.$vip_host.';dbname='.$vip_db, $vip_username, $vip_password);
	}
	catch(PDOException $e)
	{
		die("Failed to connect to database! Please check the database settings.");
	}
	//Формирование критерий поиска
	$where = NULL;
	if (isset($critery))
	{
		list($at, $vl) = explode("=", $critery);
		$atribute = trim($at);
		$value = trim($vl);
		$where = "WHERE `$atribute` = '$value'";
	}
	//Отправная точка отсчета
	$begin = isset($_GET['page'])?intval($_GET['page']):1;
	//Запрос в БД
	$query = "SELECT * FROM `$table` $where ORDER BY `$OrderBy` DESC LIMIT ".$begin.", ".$limit;
	$result = $connect_db->query($query);
	//Сохраняем все значения в массив
	$row = $result->fetchAll(PDO::FETCH_ASSOC);
	//Перебираем массив и формируем визуализацию
	if(is_array($row))
	{
		foreach($row as $array)
		{
			echo '<tr>';
			echo '<td>'.substr($array['name'], 0, 400).'</td>';
			if ($ShowSteamID)
			{
				echo '<td>'.$array['steamid'].'</td>';
			}
			if ($ShowGroups)
			{
				echo '<td>'.$array['group'].'</td>';
			}
			if ($array['time'] == '0'){
				echo "<td>Никогда</td>";
			}
			else
			{
				echo '<td>'.date("d-m-Y H:i", $array['time']).'</td>';
			}
		}
	}
	$query = "SELECT COUNT(*) as `count` FROM `$table` ".$where;
	$result = $connect_db->query($query);
	//Подсчитываем сколько строк
	$count = $result->fetch(PDO::FETCH_OBJ)->count;
	//Вычисляем сколько будет страниц
	$pageCount = ceil($count/$limit);
	$navigation = NULL;

?>
			</tbody>
		</table>
	</div>
	<?php //Формируем навигацию
	for($i=1; $i<=$pageCount; $i++)
	{
		$navigation .= '<a class="btn btn-default" href="index.php?page='.($i).'">'.($i).'</a>';
	}
	echo '<div align="right" class="container-fluid">'.$navigation.'</div>';
	}
	?>
	</body>
	<footer>
		Autor <a href="https://github.com/TiBarification">TiBarification</a><br/>
		Made with <a href="http://getbootstrap.com/">Bootstrap</a>.
		<p class="text-center"><strong>Version: 1.2</strong></p>
	</footer>
</html>