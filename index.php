<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
include_once('functions.php');
//

use Benlipp\SrtParser\Parser;

session_start();
//Update FFMPEG dir variable
if (isset($_POST['ffmpeg_dir']))
    $_SESSION["ffmpeg_directory"] = $_POST['ffmpeg_dir'];

if (glob("./videos/*.mp4") == true) {
    $video = get_path(0);

    $parser = new Parser();
    $parser->loadFile(get_path(1));
    $captions = $parser->parse();
    $numFrames = count($captions);

    $frame_count = $numFrames; //Amount of frames to render from video

    $video_path = pathinfo($video);

    //Sutitle function
    if (isset($_GET['loadsrt'])) {
        $parser = new Parser();
        $parser->loadFileget_path(get_path(1));
        $captions = $parser->parse();

        foreach ($captions as $caption) {
            echo "Start Time: " . $caption->startTime;
            echo nl2br("\nEnd Time: ") . $caption->endTime;
            echo nl2br("\nText: ") . $caption->text;
            echo nl2br("\n\n");
        }
    }
}
?>

<html>

<head>
    <?php require_once('bootstrap.php') ?>
    <style>
        @import url(https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css);
        @import url(https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/css/mdb.min.css);

        .hm-gradient {
            background-color: #eee;
        }

        .darken-grey-text {
            color: #2E2E2E;
        }
    </style>
    <title>Video Thumbnailer Demo Using FFMPEG</title>
    <script type="text/javascript" src="frame_rotator.js"></script>
    <script type="text/javascript">
        /* how many frames to loop */
        frameRotator.frames = <?php echo $frame_count ?>;
    </script>
</head>

<body class="hm-gradient">

<main>
    <div class="container mt-4">
        <?php if (glob("./videos/*.MP4") == false) { ?>
            <div class="text-center darken-grey-text mb-4">
                <form action="vendor/ajax/index-ajax.php" method="post" enctype="multipart/form-data">
                    <div class="file-field">
                        <div class="btn btn-default btn-sm">
                            <span>Choose file</span>
                            <input name="vid_file" type="file">
                        </div>
                    </div>
                    <button name="vidUpBut" class="btn btn-info">Upload</button>
                </form>
            </div>
        <?php } ?>

        <div class="text-center darken-grey-text mb-4">
            <form method="post">
                <h1 class="font-bold mt-4 mb-3 h5">Specify FFMPEG directory</h1>
                <div class="form-group">
                    <div class="col-md-8 offset-md-2">
                        <input type="text" name="ffmpeg_dir" placeholder="Your FFMPEG directory"
                               value="<?php if (isset($_SESSION["ffmpeg_directory"])) echo $_SESSION["ffmpeg_directory"] ?>">
                        <label>For example: C:/ffmpeg/bin</label>
                    </div>
                    <button class="btn btn-primary">Submit FFMPEG directory <i class="fa fa-check-circle-o"></i>
                    </button>
                </div>
            </form>
        </div>

        <!--Search keyword section-->
        <div class="text-center darken-grey-text mb-4">
            <!-- go to keyword -->
            <form action="keyword.php" method="get">
                <h1 class="font-bold mt-4 mb-3 h5">Search keyword</h1>
                <div class="form-group">
                    <div class="col-md-4 offset-md-4">
                        <input type="text" name="search" placeholder="Enter keyword">
                    </div>
                </div>
                <button class="btn btn-success">Search <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-8 mb-4 offset-md-2">

                <div class="card">
                    <div class="card-block p-3">
                        <!--Title-->
                        <h3 class="text-center font-up font-bold indigo-text py-2 mb-3"><strong>Responsive
                                image</strong></h3>

                        <?php
                        if (glob("./videos/*.mp4") == true) {
                            if (!file_exists('./thumbs/' . $video_path['filename'] . '/0.png')): ?>
                                <p>
		<span id="notice">Video not yet processed,
            <button name="makeThumbnails" class="btn btn-danger btn-md">Click here</button>
            <!--			<a href="?make_thumbs" onClick="document.getElementById('notice').innerHTML='Processing please wait...';">click here</a>-->
		</span>
                                </p>
                            <?php else: ?>
                                <p>Roll over the image and wait for a few seconds.</p>

                                <img src="./thumbs/<?php echo $video_path['filename'] ?>/0.png" width="700" height="400"
                                     onmouseover="frameRotator.start(this)"
                                     onmouseout="frameRotator.end(this)"/>

                            <?php endif;
                        } ?>
                        <a href="?loadsrt">Show subtitle file</a>
                    </div>
                </div>

            </div>
            <!-- Grid column -->

        </div>
    </div>
    <!--MDB Video-->

</main>

</body>

</html>

<script>
    function main() {
        var ffmpeg_dir = $('input[name="ffmpeg_dir"').val();

        $('button[name="makeThumbnails"]').click(function () {
            if (ffmpeg_dir === "") {
                document.getElementById('notice').innerHTML = 'Please specify FFMPEG path';
                return false;
            }

            document.getElementById('notice').innerHTML = 'Processing...';
            $.post("vendor/ajax/index-ajax.php", {
                getThumbs: "Generating",
                ffmpeg_dir: ffmpeg_dir
            }, function (data, status) {
                // console.log(data + "" + status);
                window.location.reload();
            });
        });
    }

    $(document).ready(main());
</script>