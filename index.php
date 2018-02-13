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
    $_SESSION["ffmpeg_directory"] = str_replace(" ", "", $_POST['ffmpeg_dir']);

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
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes

        $command = escapeshellcmd('C:\Python27\python.exe C:\Python27\scripts\autosub_app.py -S en -D en videos\acer.mp4');
        $output = shell_exec($command);

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
                            <input name="vid_file" type="file">
                        </div>
                    </div>
                    <button name="vidUpBut" class="btn btn-info">Upload <i class="fa fa-upload" aria-hidden="true"></i>
                    </button>
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

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-8 mb-4 offset-md-2">

                <div class="card">
                    <div class="card-block p-3">
                        <!--Title-->
                        <h3 class="text-center font-up font-bold indigo-text py-2 mb-3"><strong>uploaded files
                            </strong> <span id="genspin"></span></h3>

                        <div class="list-group">
                            <?php uploadedFilesHTML() ?>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Grid column -->
        </div>
    </div>
    <!--MDB Video-->

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
</main>
</body>
<div id="callModal"></div>
</html>

<script>
    function main() {
        var ffmpeg_dir = $('input[name="ffmpeg_dir"').val();

        $('.thumbLink').on("click", function () {
            var filename = $(this).data('id').replace(".mp4", "");

            $.post("thumbnail_modal.php", {filename: filename},
                function (data, status) {
                    $('#callModal').html(data);
                    $('#thumbnails_modal').modal('show');
                });
        });

        $('#thumbnails_modal').on('hidden.bs.modal', function () {
            $('#callModal').html('<div id="callModal"></div>');
        });

        $('.list-group > li > a:nth-child(4)').click(function () {
            var filename = $(this).attr("name");

            $.post("vendor/ajax/index-ajax.php", {remove: filename}).done(function (data) {
                alert("Remove status: " + data);
                window.location.reload();
            });
        });

        $('button[name="gensub"]').click(function () {
            var filename = $(this).attr("id");
            document.getElementById('genspin').innerHTML = "<i class='fa fa-circle-o-notch fa-spin'></i>";

            $.post("vendor/ajax/index-ajax.php", {gensub: filename}).done(function (data) {
                alert("Gen status code: " + data + "(0 = no error)");
                window.location.reload();
            });
        });

        $('button[name="makethumb"]').click(function () {
            if (ffmpeg_dir === "") {
                alert('Please specify FFMPEG path');
                return false;
            }

            var filename = $(this).attr("id");
            document.getElementById('genspin').innerHTML = "<i class='fa fa-circle-o-notch fa-spin'></i>";
            $.post("vendor/ajax/index-ajax.php", {ffmpeg_dir: ffmpeg_dir, filename: filename},
                function (data, status) {
                    if (data !== "Thumbnails already generated")
                        alert(data + status);
                    else
                        alert(data);
                    window.location.reload();
                });
        });
    }

    $(document).ready(main());
</script>