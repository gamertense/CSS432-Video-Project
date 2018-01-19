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

$path = glob("./videos/*.mp4"); // This will work properly if only one mp4 video is in the path
$vid_path = $path[0];
$vid_fileName = basename($vid_path,".mp4"); // get file name without extension

$i = 0; // Index the image file.
?>

<html>
<body>
	<div class="container">
		<h4>Keyword: <?= $search ?></h4>
		<table class="table table-hover">
			<thead>
			<tr>
				<th>Start Time</th>
				<th>End Time</th>
				<th>Text</th>
				<th>Screen</th>
			</tr>
			</thead>
	<?php
	foreach ($captions as $caption) {
		if (strpos($caption->text, $search) !== false) {
			require_once('bootstrap.php'); ?>
					<tbody>
					<tr>
						<td><?= $caption->startTime ?></td>
						<td><?= $caption->endTime ?></td>
						<td><?= $caption->text ?></td>
						<td><?= '<img src="./thumbs/'.$vid_fileName.'/'.$i.'.png" width="200" height="150">' ?></td>
					</tr>
					</tbody>
		<?php }
		else {
			$i = $i + 1; // Skip the image index synchonize with caption index.
		}
	}
?>
		</table>
	</div>
</body>
</html>