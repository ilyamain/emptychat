<?require_once($_SERVER[DOCUMENT_ROOT].DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'start.php');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="/favicon.ico" type="image/ico">
		<title><?=PROGRAM_NAME;?></title>
		<link href="/files/css/styles.css" type="text/css" rel="stylesheet">
		<script src="/files/js/jquery-3.1.1.min.js"></script>
		<script src="/files/js/jquery.cookie.js"></script>
		<script src="/files/js/scripts.js"></script>
	</head>
	<body>
		<div class="message">
			<?
			if (!defined('START_TIME')) 
			{
				?>
				<div class="create">
					<input name="pass" placeholder="enter chat key">
					<span>Start chat</span>
				</div>
				<?
			}
			else 
			{
				?>
				<div class="send-message">
					<div class="sender"><input name="sender" placeholder="username" value="<?=abra();?>"></div>
					<div class="txt"><input name="txt" placeholder="message"></div>
					<div class="send-button">Send</div>
					<div class="stop-chat">Stop chat</div>
				</div>
				<?
			}
			?>
		</div>
		<div id="chat" class="chat"></div>
	</body>
</html>