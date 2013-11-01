<?php

	session_start();

	include_once("includes/connection.php");

	if (!empty($_POST['company_name1']) AND !empty($_POST['company_name2']))
	{
		$ticker1 = strtoupper($_POST['company_name1']);
		$ticker2 = strtoupper($_POST['company_name2']);

		create_url($ticker1, $ticker2);

		// header('location: index.php');
	}

	function create_url($ticker1, $ticker2)
	{
		$month = date('n') - 1;
		$day = date("j", time() - 60 * 60 * 24);
		$year = date("Y");

		$url1 = "http://ichart.finance.yahoo.com/table.csv?s={$ticker1}&a={$month}&b={$day}&c={$year}&d={$month}&e={$day}&f={$year}&g=d&ignore=.csv";
		$url2 = "http://ichart.finance.yahoo.com/table.csv?s={$ticker2}&a={$month}&b={$day}&c={$year}&d={$month}&e={$day}&f={$year}&g=d&ignore=.csv";

		$content1 = file_get_contents($url1);
		$content1 = str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", $content1);
		$content1 = trim($content1);
		$explode1 = explode(",", $content1);
		$date1 = $explode1[0];
		$close1 = $explode1[4];

		$content2 = file_get_contents($url2);
		$content2 = str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", $content2);
		$content2 = trim($content2);
		$explode2 = explode(",", $content2);
		$date2 = $explode2[0];
		$close2 = $explode2[4];

		$data = array(["name" => $ticker1, "close" => $close1], ["name" => $ticker2, "close" => $close2]);
		echo json_encode($data);
	}

	// function get_csv_file($url, $output_file)
	// {
	// 	$content = file_get_contents($url);
	// 	$content = str_replace("Date,Open,High,Low,Close,Volume,Adj Close", "", $content);
	// 	$content = trim($content);
	// 	$content_explode = explode(",", $content);

	// 	$date = $content_explode[0];
	// 	$close = $content_explode[4];

	// 	file_put_contents($output_file, $content);
	// }

	// function file_to_database($text_file, $table_name)
	// {
	// 	$file = fopen($text_file, "r");

	// 	// Probably should add conditional here that checks if record for compmany already exists. If it does, do something, if it doesn't, do the whole while loop that populates a table with the last years worth of data.
	// 	// Also should check for how recent records are on table, and update them if necessary

	// 	while (!feof($file))
	// 	{
	// 		$line = fgets($file);
	// 		$pieces = explode(",", $line);

	// 		$date = $pieces[0];
	// 		$open = $pieces[1];
	// 		$high = $pieces[2];
	// 		$low = $pieces[3];
	// 		$close = $pieces[4];
	// 		$volume = $pieces[5];
	// 		$amount_change = $close - $open;
	// 		$percent_change = ($amount_change / $open) * 100;

	// 		$query_check_if_exists = "SELECT * FROM {$table_name}";
	// 		$result = mysql_query($query_check_if_exists);

	// 		if (empty($result))
	// 		{
	// 			$query_create_new = "CREATE TABLE {$table_name} (date DATE, PRIMARY KEY (date), open FLOAT, high FLOAT, low FLOAT, close FLOAT, volume INT, amount_change FLOAT, percent_change FLOAT)";
	// 			mysql_query($query_create_new);
	// 		}

	// 		$query_insert_data = "INSERT INTO {$table_name} (date, open, high, low, close, volume, amount_change, percent_change) VALUES ('{$date}', '{$open}', '{$high}', {$low}, '{$close}', '{$volume}', '{$amount_change}', '{$percent_change}')";
	// 		mysql_query($query_insert_data);
	// 	}

	// 	fclose($file);
	// }

	// function main($company, $company2)
	// {
	// 	$file_url = create_url($company);
	// 	$company_text_file = "text_files/" . $company . ".txt";

	// 	get_csv_file($file_url, $company_text_file);
	// 	file_to_database($company_text_file, $company);

	// 	$query_get_data1 = "SELECT close, percent_change FROM {$company1} ORDER BY date DESC LIMIT 1";
	// 	$query_get_data2 = "SELECT close, percent_change FROM {$company2} ORDER BY date DESC LIMIT 1";
	// }

?>