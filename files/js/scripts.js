$(document).ready(function () 
{
	// Start chat
	$(document).on('click', '.create span', function () {chat_start (this);});
	$(document).on('keypress', '[name="pass"]', function (e) 
	{
		if (e.which == 13) $(this).closest('.create').find('span').first().click();
	});
	// Remove chat
	$(document).on('click', '.stop-chat', chat_stop);
	// Send message
	$(document).on('click', '.send-button', function () {msg_send (this);});
	$(document).on('keypress', '.send-message .txt input', function (e) 
	{
		if (e.which == 13) $(this).closest('.send-message').find('.send-button').first().click();
	});
	// Read messages
	messages.start();
});

// Chat messages update
var messages = 
{
	last_message: 0, 
	file: '/files/c_read.php', 
	passkey: '', 
	pause: false, 
	reading: false, 
	start: function () 
	{
		let get_parameters = new Object();
		location.search.substr(1).split("&").forEach(function (item) {get_parameters[item.split("=")[0]] = item.split("=")[1]});
		if (get_parameters['passkey'] != undefined) 
		{
			messages.passkey = get_parameters['passkey'];
			messages.reading = setInterval(messages.update, 1000);
		}
	}, 
	update: function () 
	{
		if (!messages.pause) 
		{
			messages.pause = true;
			$.post(messages.file, {passkey: messages.passkey, last_message: messages.last_message}).done(function (data) 
			{
				let results = $.parseJSON(data);
				if (results.last_message !== 0) 
				{
					add_message(results.message);
					messages.last_message = results.last_message;
				}
				messages.pause = false;
			});
		}
	}, 
}

// Initiate chat
function chat_start (caller) 
{
	let ajax_file = '/files/c_start.php';
	let passkey = clean_words($(caller).parent().find('input').first().val());
	$.post(ajax_file, {passkey: passkey}).done(function (data) 
	{
		let results = $.parseJSON(data);
		add_message(results.message, true);
		if (results.created) window.location.href = '/?passkey=' + passkey;
	});
}

// Delete chat
function chat_stop () 
{
	let ajax_file = '/files/c_stop.php';
	let get_parameters = new Object();
	location.search.substr(1).split("&").forEach(function (item) {get_parameters[item.split("=")[0]] = item.split("=")[1]});
	let passkey = get_parameters['passkey'];
	$.post(ajax_file, {passkey: passkey}).done(function (data) 
	{
		let results = $.parseJSON(data);
		add_message(results.message, true);
		if (results.deleted) window.location.href = '/';
	});
}

// Sending message
function msg_send (caller) 
{
	let ajax_file = '/files/c_write.php';
	let get_parameters = new Object();
	location.search.substr(1).split("&").forEach(function (item) {get_parameters[item.split("=")[0]] = item.split("=")[1]});
	let passkey = get_parameters['passkey'];
	let sender = clean_words($(caller).closest('.send-message').find('.sender input').first().val());
	let txt = clean_words($(caller).closest('.send-message').find('.txt input').first().val());
	$.post(ajax_file, {passkey: passkey, sender: sender, txt: txt}).done(function () 
	{
		$(caller).closest('.send-message').find('.txt input').first().val('');
	});
}

// System functions
function add_message (txt, clean = false) 
{
	let line = (clean) ? '<div class="chat-line">' + txt + '</div>' : txt;
	$('#chat').prepend(line);
}

function clean_words (input) 
{
	let html_list = 
	{
		'&': '&amp;', 
		'<': '&lt;', 
		'>': '&gt;', 
		'"': '&quot;', 
		"'": '&#39;', 
		'/': '&#x2F;', 
		'`': '&#x60;', 
		'=': '&#x3D;', 
	};
	return String(input).replace(/[&<>"'`=\/]/g, function (item) 
	{
		return html_list[item];
	});
}