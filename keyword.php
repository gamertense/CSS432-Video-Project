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
<head>
<?php require_once('bootstrap.php');  ?>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
</head>
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
			<tbody>
	<?php
	foreach ($captions as $caption) {
		$caption_str = explode(" ", $caption->text);
		if(in_array($search, $caption_str)) {
             ?>
				<tr>
					<td><?= $caption->startTime ?></td>
					<td><?= $caption->endTime ?></td>
					<td><?= $caption->text ?></td>
					<td><?= '<img src="./thumbs/'.$vid_fileName.'/'.$i.'.png" width="200" height="150">' ?></td>
				</tr>
		<?php }
		else {
			$i = $i + 1; // Skip the image index synchonize with caption index.
		}
	}
?>
			</tbody>
		</table>
	</div>
</body>
</html>

<script>
$(document).ready(function() {
    $('.table').DataTable({
		"searching": false
	});
} );
</script>