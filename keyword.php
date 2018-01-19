<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
include_once('functions.php');
use Benlipp\SrtParser\Parser;

	$parser = new Parser();
    $parser->loadFile('./videos/the cast.srt');
    $captions = $parser->parse();

    $search = $_GET['search'];

    echo "Keyword: ".$search;
    echo nl2br("\n\n");

    $path = glob("./videos/*.mp4"); // This will work properly if only one mp4 video is in the path
	$vid_path = $path[0];
	$vid_fileName = basename($vid_path,".mp4"); // get file name without extension

    $i = 0; // Index the image file.
	foreach ($captions as $caption) {
		if (strpos($caption->text, $search) !== false) {
			echo "Start Time: " . $caption->startTime;
	        echo nl2br("\nEnd Time: ") . $caption->endTime;
			echo nl2br("\nText: ") . $caption->text;
	        echo nl2br("\n");
	        echo '<img src="./thumbs/'.$vid_fileName.'/'.$i.'.png" width="200" height="150">';
	        echo nl2br("\n\n");
		}
		else {
			$i = $i + 1; // Skip the image index synchonize with caption index.
		}
	}
?>