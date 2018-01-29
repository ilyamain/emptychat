<?require_once($_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'start.php');?>
<?
// If chat was deleted or it wasn't created
$result = array 
(
	'message' => 'Wrong chat key', 
);
if (!defined('START_TIME')) die(json_encode($result)); // Prevent errors if chat wasn't started
// Send message to DB and write it
$server_time = gmdate('U');
if ((!empty($_POST['txt']))&&(!empty($_POST['sender']))&&(hash('sha256', $_POST['passkey']) == CHAT_KEY)) 
{
	add_message(strval($server_time), $_POST['sender'], $_POST['txt']);
	$result['message'] = $_POST['sender'].': '.$_POST['txt'];
}
else 
{
	$result['message'] = '';
}
die(json_encode($result));
?>