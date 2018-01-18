<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
use Benlipp\SrtParser\Parser;

$parser = new Parser();
    $parser->loadFile('./videos/10 ways.srt');
    $captions = $parser->parse();

    $search = $_GET['search'];

    echo $search."\n";

	foreach ($captions as $caption) {
		if (strpos($caption->text, $search) !== false) {
			echo "Start Time: " . $caption->startTime;
	        echo nl2br("\nEnd Time: ") . $caption->endTime;
			echo nl2br("\nText: ") . $caption->text;
	        echo nl2br("\n\n");
			}
	}
?>