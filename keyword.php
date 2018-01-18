<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
use Benlipp\SrtParser\Parser;

	$parser = new Parser();
    $parser->loadFile('./videos/the cast.srt');
    $captions = $parser->parse();

    $search = $_GET['search'];

    echo "Keyword: ".$search;
    echo nl2br("\n\n");

	foreach ($captions as $caption) {
		if (strpos($caption->text, $search) !== false) {
			echo "Start Time: " . $caption->startTime;
	        echo nl2br("\nEnd Time: ") . $caption->endTime;
			echo nl2br("\nText: ") . $caption->text;
	        echo nl2br("\n\n");
		}
	}
?>