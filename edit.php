<?
require_once 'jwt-check.php';
function json_validate($string) {
	if ($string == 'null' || $string == 'undefined') return 'Broken data file';
	$result = json_decode($string, true);
	switch (json_last_error()) {
		case JSON_ERROR_NONE:
			$error = '';
			break;
		case JSON_ERROR_DEPTH:
			$error = 'The maximum stack depth has been exceeded.';
			break;
		case JSON_ERROR_STATE_MISMATCH:
			$error = 'Invalid or malformed JSON.';
			break;
		case JSON_ERROR_CTRL_CHAR:
			$error = 'Control character error, possibly incorrectly encoded.';
			break;
		case JSON_ERROR_SYNTAX:
			$error = 'Syntax error, malformed JSON.';
			break;
		case JSON_ERROR_UTF8:
			$error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
			break;
		case JSON_ERROR_RECURSION:
			$error = 'One or more recursive references in the value to be encoded.';
			break;
		case JSON_ERROR_INF_OR_NAN:
			$error = 'One or more NAN or INF values in the value to be encoded.';
			break;
		case JSON_ERROR_UNSUPPORTED_TYPE:
			$error = 'A value of a type that cannot be encoded was given.';
			break;
		default:
			$error = 'Unknown JSON error occured.';
			break;
	}
	if ($error !== '') return $error;
	return $result;
}

if (isset($_POST['name'])) {
	$d = !file_exists('data/'.$_POST['name'].'.json');
	$m = !file_exists('media/'.$_POST['name']);
	if ($d && $m) exit('{"action": "not-found"}');
	if ($d || $m) exit('{"action": "task-failed"}');
	$source = json_validate(file_get_contents('data/'.$_POST['name'].'.json'));
	if (is_string($source)) $source = array();
	$data = $_POST;
	unset($data['name']);
	file_put_contents("data/".$_POST['name'].".json", json_encode(array_merge($source, $data)));
	echo '{"action": "task-done"}';
}
?>