<?require_once($_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'start.php');?>
<?
// If chat was already created
$result = array 
(
	'created' => false, 
	'message' => 'Chat was already created', 
);
if (defined('START_TIME')) die(json_encode($result));
// If chat wasn't created
if (!empty($_POST['passkey'])) 
{
	// Create DB table
	$sql = 'CREATE TABLE IF NOT EXISTS msg (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, time tinytext NOT NULL, sender tinytext NOT NULL, text text NOT NULL);';
	query ($sql);
	// Create chat-file
	$server_time = gmdate('U');
	$chat_file = $_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'chat.php';
	$chat_file_content = '<?'.PHP_EOL;
	$chat_file_content .= 'if (!defined(\'PROGRAM_NAME\')) die(); // Protection against direct call script'.PHP_EOL;
	$chat_file_content .= 'define(\'CHAT_KEY\', \''.hash('sha256', $_POST['passkey']).'\');'.PHP_EOL;
	$chat_file_content .= 'define(\'START_TIME\', '.$server_time.');'.PHP_EOL;
	$chat_file_content .= '?>';
	file_put_contents($chat_file, $chat_file_content);
	add_message (strval($server_time), 'SYSTEM', 'Chat was started at '.date('h:i:s', $server_time));
	// Update results
	$result = array 
	(
		'created' => true, 
		'message' => 'Chat was started', 
	);
}
else 
{
	$result['message'] = 'Please enter chat key';
}
die(json_encode($result));
?>