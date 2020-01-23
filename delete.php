<?
require_once 'jwt-check.php';
if (isset($_POST['name'])) {
	$json = unlink('data/'.$_POST['name'].'.json');
	$media = unlink('media/'.$_POST['name']);
	if ($json || $media) {
		echo '{"action": "task-done"}';
	} else {
		echo '{"action": "task-failed"}';
	}
	unset($json, $media);
}
?>