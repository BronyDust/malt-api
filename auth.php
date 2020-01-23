<?
require './vendor/autoload.php';
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$_username = '******';
$_password = '******';
$_trusted_hash = hash('sha256', $_username . '.' . $_password);

$coming_data = file_get_contents("php://input");

if ($_trusted_hash == $coming_data) {
	$secret_key = "******";
	$issuer_claim = "SOL_AD_CMS";
	$audience_claim = "SOL_AD_APP";
	$issuedat_claim = time();
	$notbefore_claim = $issuedat_claim + 1;
	$expire_claim = $issuedat_claim + 500;
	$token = array(
		"iss" => $issuer_claim,
		"aud" => $audience_claim,
		"iat" => $issuedat_claim,
		"nbf" => $notbefore_claim,
		"exp" => $expire_claim,
		"data" => array("auth" => $coming_data)
	);

	$jwt = JWT::encode($token, $secret_key);
	echo json_encode(
		array(
			"action" => "access-allowed",
			"token" => $jwt,
		)
	);
} else {
	echo '{"action": "access-denied"}';
}