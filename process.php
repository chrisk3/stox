<?php

	if (!empty($_POST['company_name1']) AND !empty($_POST['company_name2']))
	{
		$ticker1 = strtoupper($_POST['company_name1']);
		$ticker2 = strtoupper($_POST['company_name2']);

		create_url($ticker1, $ticker2);
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

		$data = array(array("name" => $ticker1, "close" => $close1), array("name" => $ticker2, "close" => $close2));
		echo json_encode($data);
	}

?>
