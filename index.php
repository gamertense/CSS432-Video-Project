<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
include_once('functions.php');

use Benlipp\SrtParser\Parser;

session_start();
//Update FFMPEG dir variable
if (isset($_POST['ffmpeg_dir']))
    $_SESSION["ffmpeg_directory"] = str_replace(" ", "", $_POST['ffmpeg_dir']);
//Update Python path variable
if (isset($_POST['python_path']))
    $_SESSION["python_path"] = str_replace(" ", "", $_POST['python_path']);
//Remove all thumbnails of this video
if (isset($_GET['rmthumb'])) {
    deleteDirectory('thumbs/' . $_GET['rmthumb']);
    header('Location: index.php');
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
    <script type="text/javascript"
            data-pace-options='{ "elements": { "selectors": [".selector"] }, "startOnPageLoad": false }'
            src="vendor/pace/pace.min.js"></script>
    <link rel="stylesheet" href="vendor/pace/pace-theme-big-counter.tmpl.css">
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

        <div class="text-center darken-grey-text mb-4">
            <form method="post">
                <h1 class="font-bold mt-4 mb-3 h5">Specify Python path</h1>
                <div class="form-group">
                    <div class="col-md-8 offset-md-2">
                        <input type="text" name="python_path" placeholder="Your Python path"
                               value="<?php if (isset($_SESSION["python_path"])) echo $_SESSION["python_path"] ?>">
                        <label>For example: C:\Python27\</label>
                    </div>
                    <button class="btn btn-primary">Submit Python path <i class="fa fa-check-circle-o"></i>
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

<script type="text/javascript" src="vendor/bootstrap-4.0.0-dist/index.js"></script>