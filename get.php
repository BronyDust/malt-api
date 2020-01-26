<?
header("Access-Control-Allow-Origin: *");

if (isset($_GET['mode'])) {
	switch ($_GET['mode']) {
		case 'list':
			$list = json_encode(getAllAsList());
			$json = array(
				'action' => 'task-done',
				'list' => $list
			);
			echo json_encode($json);
			break;
		case 'get':
			if (isset($_GET['type'])) {
				$elements = getCount($_GET['type']);
				if (isset($_GET['id'])) {
					echo getOne($elements[$_GET['id']]);
				} else {
					$json = array(
						'action' => 'task-done',
						'count' => count($elements)
					);
					echo json_encode($json);
				}
			}
			break;
		case 'one':
			if (isset($_GET['name'])) echo getOne($_GET['name']);
			break;
		case 'pic':
			if (isset($_GET['name'])) echo getPic($_GET['name']);
		default:
			echo '{"action": "not-found"}';
			break;
	}
}

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
function cutExt($el) {return preg_replace('/\\.[^.\\s]{3,4}$/', '', $el);}

function getAllAsList() {
	$dir = scandir('data/', SCANDIR_SORT_DESCENDING);
	unset($dir[count($dir) - 1], $dir[count($dir) - 1]);
	$dir = array_values($dir);
	$dir = array_map('cutExt', $dir);
	return $dir;
};

function getOne($id) {
	if (file_exists('data/'.$id.'.json')) {
		$json = json_decode(file_get_contents('data/'.$id.'.json'), TRUE);
		$json['action'] = 'task-done';
		return json_encode($json);
	} else {
		return '{"action": "not-found"}';
	}; 
}

function getPic($id) {
	if (file_exists('media/'.$id)) {
		return file_get_contents('media/'.$id);
	} else {
		return '{"action": "not-found"}';
	}
}

function getCount($type) {
	$dir = getAllAsList();
	function typeFilter($type, $name) {
		$content = json_decode(file_get_contents('data/'.$name.'.json'), TRUE);
		if (isset($content['type']) && $content['type'] === $type) {
			return $name;
		}
	}

	switch ($type) {
		case 'both':
			return array_filter($dir, function ($el) {return typeFilter('both', $el);});
			break;
		case 'feed':
			$feed = array_filter($dir, function ($el) {return typeFilter('feed', $el);});
			$both = array_filter($dir, function ($el) {return typeFilter('both', $el);});
			return array_merge($both, $feed);
			break;
		case 'post':
			$post = array_filter($dir, function ($el) {return typeFilter('post', $el);});
			$both = array_filter($dir, function ($el) {return typeFilter('both', $el);});
			return array_merge($both, $post);
			break;
		default:
			break;
	}
}

?>