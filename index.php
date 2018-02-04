<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
include_once('functions.php');

use Benlipp\SrtParser\Parser;

session_start();
//Update FFMPEG dir variable
if (isset($_POST['ffmpeg_dir']))
    $_SESSION["ffmpeg_directory"] = $_POST['ffmpeg_dir'];

if (glob("./videos/*.MP4") == true) {
    $video = "./videos/Acer Nitro 5.mp4"; //path to video

    $parser = new Parser();
    $parser->loadFile('./videos/Acer Nitro 5 sub.srt');
    $captions = $parser->parse();
    $numFrames = count($captions);

    $frame_count = $numFrames; //Amount of frames to render from video

    $video_path = pathinfo($video);

    // For simplicity, Generate frames from the video using ffmpeg upon request
    if (isset($_GET['make_thumbs'])) {
        // init ffmpeg helper class
        include('ffmpeg.php');
//        $my_directory = 'C:/ffmpeg/bin/';
        $ffmpeg = new ffmpeg($_SESSION["ffmpeg_directory"]);
        $ffmpeg->ffmpeg_screens($video, $video_path['filename'], $frame_count);
        //exit(header('Location: ./'));
    }

    //Sutitle function
    if (isset($_GET['loadsrt'])) {
        $parser = new Parser();
        $parser->loadFile('./videos/Acer Nitro 5 sub.srt');
        $captions = $parser->parse();

        foreach ($captions as $caption) {
            echo "Start Time: " . $caption->startTime;
            echo nl2br("\nEnd Time: ") . $caption->endTime;
            echo nl2br("\nText: ") . $caption->text;
            echo nl2br("\n\n");
        }
    }
} else { ?>
    <form action="temp.php" method="post" enctype="multipart/form-data">
        <input type="file" name="vid_file">
        <button type="submit" name="submit">Submit</button>
    </form>
<?php } ?>
<html>

<head>
    <?php require_once('bootstrap.php') ?>
    <title>Video Thumbnailer Demo Using FFMPEG</title>
    <script type="text/javascript" src="frame_rotator.js"></script>
    <script type="text/javascript">
        /* how many frames to loop */
        frameRotator.frames = <?php echo $frame_count ?>;
    </script>
</head>

<body>
<form method="post">
    <input type="text" name="ffmpeg_dir" placeholder="Your FFMPEG directory"
           value="<?php if (isset($_SESSION["ffmpeg_directory"])) echo $_SESSION["ffmpeg_directory"] ?>">
    For example: C:/ffmpeg/bin/
    <button>Submit FFMPEG directory</button>
</form>

<!-- go to keyword -->
<form action="keyword.php" method="get">
    <input type="text" name="search" placeholder="Search">
    <button>Search</button>
</form>

<h2>Video Thumbnailer Demo Using FFMPEG</h2>
<?php if (!file_exists('./thumbs/' . $video_path['filename'] . '/0.png')): ?>
    <p>
		<span id="notice">Video not yet processed,
			<a href="?make_thumbs" onClick="document.getElementById('notice').innerHTML='Processing please wait...';">click here</a>
		</span>
    </p>
<?php else: ?>
    <p>Roll over the image and wait for a few seconds.</p>

    <img src="./thumbs/<?php echo $video_path['filename'] ?>/0.png" width="200" height="150"
         onmouseover="frameRotator.start(this)"
         onmouseout="frameRotator.end(this)"/>

<?php endif ?>

<a href="?loadsrt">Show subtitle file</a>
</body>

</html>