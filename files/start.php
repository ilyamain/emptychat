<?
define('PROGRAM_NAME', 'Empty chat');
// Load common config
require_once($_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'config.php');
// Load chat config
$chat_file = $_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'chat.php';
if (is_file($chat_file)) require_once($chat_file);

// Random word generate
function abra ($length = 5) 
{
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$result = '';
	$alphabet_length = strlen($alphabet)-1;
	while (strlen($result) < $length) $result .= $alphabet[random_int(0, $alphabet_length)];
	return $result;
}

// Add new message to DB
function add_message ($time, $sender, $text) 
{
	$sql = 'INSERT INTO msg (time, sender, text) VALUES (\''.$time.'\',\''.$sender.'\',\''.$text.'\')';
	return (query($sql)) ? true : false;
}

// Read messages from DB
function get_messages ($id = 0) 
{
	$output = array();
	$sql = 'SELECT * FROM msg WHERE CAST(id AS UNSIGNED) > '.$id.';';
	$query = query($sql);
	if (!empty($query)) 
	{
		$messages = array();
		while ($msg = $query->fetch_assoc()) array_push($messages, $msg);
		if (!empty($messages)) $output = $messages;
	}
	return $output;
}

// Database query
function query ($sql) 
{
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($db->connect_error) 
	{
		$db->close();
		$result = false;
	}
	else 
	{
		if (is_array($sql)) foreach ($sql as $sql_row) $db->query($sql_row); else $result = $db->query($sql);
		if ($db->error) 
		{
			$query = '';
			if (is_array($sql)) foreach ($sql as $sql_row) $query .= $sql_row.PHP_EOL; else $query = $sql;
			$db->rollback();
			$db->close();
			$result = false;
		}
		else 
		{
			if (empty($result)) $result = false;
			$db->close();
		}
	}
	return $result;
}
?>