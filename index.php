<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
use Benlipp\SrtParser\Parser;

$video = "./videos/The Cast of Star Wars.mp4"; //path to video
$frame_count = 10; //Amount of frames to render from video

$video_path = pathinfo($video);

// For simplicity, Generate frames from the video using ffmpeg upon request
if (isset($_GET['make_thumbs'])) {
    // init ffmpeg helper class
    include('ffmpeg.php');
    $ffmpeg = new ffmpeg();
    $ffmpeg->ffmpeg_screens($video, $video_path['filename'], $frame_count);
    exit(header('Location: ./'));
}

//Sutitle function
if (isset($_GET['loadsrt'])) {
    $parser = new Parser();
    $parser->loadFile('./videos/the cast.srt');
    $captions = $parser->parse();

    foreach ($captions as $caption) {
        echo "Start Time: " . $caption->startTime;
        echo nl2br("\nEnd Time: ") . $caption->endTime;
		echo nl2br("\nText: ") . $caption->text;
        echo nl2br("\n\n");
    }
}
?>

<!-- go to keyword -->
<form action="keyword.php" method="get">
	<input type="text" name="search" placeholder="Search">
</form>

<html>

<head>
	<title>Video Thumbnailer Demo Using FFMPEG</title>
	<script type="text/javascript" src="frame_rotator.js"></script>
	<script type="text/javascript">
		/* how many frames to loop */
		frameRotator.frames = <?php echo $frame_count ?>;
	</script>
</head>

<body>
	<h2>Video Thumbnailer Demo Using FFMPEG</h2>
	<?php if (!file_exists('./thumbs/'.$video_path['filename'].'/0.png')): ?>
	<p>
		<span id="notice">Video not yet processed,
			<a href="?make_thumbs" onClick="document.getElementById('notice').innerHTML='Processing please wait...';">click here</a>
		</span>
	</p>
	<?php else: ?>
	<p>Roll over the image and wait for a few seconds.</p>

	<img src="./thumbs/<?php echo $video_path['filename'] ?>/0.png" width="200" height="150" onmouseover="frameRotator.start(this)"
	onmouseout="frameRotator.end(this)" />

	<?php endif ?>

	<a href="?loadsrt">Load SRT file</a>
</body>

</html>