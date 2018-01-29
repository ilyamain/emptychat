<?require_once($_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'start.php');?>
<?
// If chat was already deleted or it wasn't created
$result = array 
(
	'deleted' => false, 
	'message' => 'Chat was already deleted or it wasn\'t created', 
);
if (!defined('START_TIME')) die(json_encode($result));
// Stop if chat exist
if ((!empty($_POST['passkey']))&&(hash('sha256', $_POST['passkey']) == CHAT_KEY)) 
{
	// Drop DB table
	$sql = 'DROP TABLE IF EXISTS msg;';
	query ($sql);
	// Delete chat-file
	$chat_file = $_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'chat.php';
	unlink($chat_file);
	// Update results
	$result['deleted'] = true;
	$result['message'] = 'Chat was deleted';
}
else 
{
	$result['message'] = 'Wrong chat key';
}
die(json_encode($result));
?>