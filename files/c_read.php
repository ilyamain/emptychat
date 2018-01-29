<?require_once($_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'start.php');?>
<?
// If chat was deleted or it wasn't created
$result = array 
(
	'last_message' => 0, 
	'message' => 'Wrong chat key', 
);
if (!defined('START_TIME')) die(json_encode($result)); // Prevent errors if chat wasn't started
// Read messages after unreaded ID
$result['message'] = '';
if (((!empty($_POST['last_message']))||($_POST['last_message'] == 0))&&(hash('sha256', $_POST['passkey']) == CHAT_KEY)) 
{
	$messages = get_messages($_POST['last_message']);
	$id_list = array();
	if (!empty($messages)) foreach ($messages as $msg) 
	{
		$result['message'] = '<div class="chat-line">'.$msg['sender'].': '.$msg['text'].'</div>'.$result['message'];
		array_push($id_list, $msg['id']);
	}
	if (!empty($id_list)) $result['last_message'] = max($id_list);
}
die(json_encode($result));
?>