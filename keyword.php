<?php
include 'vendor/SrtParser/Caption.php';
include 'vendor/SrtParser/Parser.php';
include 'vendor/SrtParser/Time.php';
include_once('functions.php');
include_once('exclude_word.php');
session_start();

use Benlipp\SrtParser\Parser;

$search = $_GET['search'];
?>

<html>
<head>
    <?php require_once('bootstrap.php'); ?>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
<div class="container">
    <h4>Keyword: <?= $search ?></h4>
    <?php for ($vidIndex = 0; $vidIndex < count(getPathArray(0)); $vidIndex++) {
        $i = 0; // Index the image file.
        $vid_Idx = getPathArray(1)[$vidIndex];
        $parser = new Parser();
        $parser->loadFile($vid_Idx);
        $captions = $parser->parse();
        $vid_fileName = str_replace(".srt", "", $vid_Idx);
        $vid_fileName = str_replace("./videos/", "", $vid_fileName); ?>

        <font color="green"><h5>Video name: <?= $vid_fileName ?></h5></font>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Start Time (min)</th>
                <th>End Time (min)</th>
                <th>Text</th>
                <th>Screen</th>
            </tr>
            </thead>
            <tbody>
            <?php

            if (exclude_word($search, $All) == false) { //Options: All, Auxiliary, Preposition, Article, Conjunction, Pronoun
                echo "<b><font color=\"red\">Exclude Word!</font></b>";
            } else {
                foreach ($captions as $caption) {
                    //$caption_str = explode(" ", $caption->text);
                    if (strpos($caption->text, $search) !== false) {
                        ?>
                        <tr>
                            <td><?= round($caption->startTime / 60, 2) ?></td>
                            <td><?= round($caption->endTime / 60, 2) ?></td>
                            <td><?= $caption->text ?></td>
                            <td><?= '<img src="./thumbs/' . $vid_fileName . '/' . $i . '.png" width="200" height="150">' ?></td>
                        </tr>
                    <?php } else {
                        $i = $i + 1; // Skip the image index synchonize with caption index.
                    }
                }
            }
            ?>
            </tbody>
        </table>
        <hr><br>
    <?php } ?>
</div>
</body>
</html>

<script>
    $(document).ready(function () {
        $('.table').DataTable({
            "searching": false
        });
    });
</script>