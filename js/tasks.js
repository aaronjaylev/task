$(function() {
	$("#TaskDialog").dialog({
		autoOpen: false,
		height: 500,
		width: 800,
		modal: true,
		zIndex: 1500,
		buttons: {
			"Save Task": function() {
				SaveTask();
				$(this).dialog("close");
				getTaskTable();
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("div#Register").dialog({
		autoOpen: false,
		height: 250,
		width: 600,
		modal: true,
		zIndex: 1500,
		buttons: {
			"Register": function() {
				RegisterEmail();
				$(this).dialog("close");
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		}
	});

	$("#Register-Message, #Error-Message").dialog({
		autoOpen: false,
		height: 200,
		width: 600,
		modal: true,
		zIndex: 1500,
		buttons: {
			Ok: function() {
				$(this).dialog("close");
			}
		}
	});
	
	$("div#SignIn").dialog({
		autoOpen: false,
		height: 250,
		width: 600,
		modal: true,
		zIndex: 1500,
		buttons: {
			"Sign In": function() {
				SignInEmail();
				$(this).dialog("close");
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		}
	});
	
	LoginCheck();
});

function ShowTask(TaskID) {
	if (TaskID == 'Add') {
		$('span#ui-id-1').html('Add Task');
	} else {
		$('span#ui-id-1').html('Update Task');
	}
	$('td#ShowTaskID').html('<b>' + TaskID + '</b>');
	$.getJSON("/js/task-info.php", {"TaskID": TaskID}, function(data) {
		$('div#TaskDialog input#TaskID')[0].value = TaskID;
		$('div#TaskDialog input#Title')[0].value = data.Title;
		$('div#TaskDialog input#DueDate')[0].value = data.DueDate;
		$('div#TaskDialog input#DueDate').datepicker();
		$('div#TaskDialog textarea#Description')[0].value = data.Description;

		$('div#TaskDialog select#Priority').html('');
		$.each(data["PriorityValues"], function(key, value) {
			var Selected = (value == data["Priority"] ? " selected" : ""); 
			$('div#TaskDialog select#Priority').append('<option' + Selected + '>' + value + '</option>');
		});
		$("#TaskDialog").dialog("open");
	});
}

function SaveTask() {
	info = new Object();
	info['TaskID'] = $('div#TaskDialog input#TaskID')[0].value;
	info['Title'] = $('div#TaskDialog input#Title')[0].value;
	info['Description'] = $('div#TaskDialog textarea#Description')[0].value;
	info['DueDate'] = $('div#TaskDialog input#DueDate')[0].value;
	info['Priority'] = $('div#TaskDialog select#Priority')[0].value;
	$.ajax({
		type: 'POST',
		url: '/js/task-save.php',
		data: info,
		async: false
	});
}

function TaskStatus(TaskID, NewStatus) {
	info = new Object();
	info['TaskID'] = TaskID;
	info['Status'] = NewStatus;
	$.ajax({
		type: 'POST',
		url: '/js/task-status.php',
		data: info,
		async: false,
	}).done(getTaskTable());
}

function Register() {
	$("#Register").dialog("open");
}

function RegisterEmail() {
	info = new Object();
	info['Action'] = 'Register';
	info['Name'] = $('div#Register input#Name')[0].value;
	info['Email'] = $('div#Register input#Email')[0].value;
	$.ajax({
		type: 'POST',
		url: '/api/sagetask-api.php',
		async: false,
		data: JSON.stringify(info),
		dataType: 'json',
		processData: false,
        contentType: "application/json",
        success: function(data) {
        	if (data.ErrorMsg == "") {
        		$("#Register-Message").dialog("open");
        	} else {
        		$('div#Error-Message span#ErrorMsg').html(data.ErrorMsg);
        		$("#Register-Error").dialog("open");
			}
		}
	});
}

function SignInEmail() {
	info = new Object();
	info['Action'] = 'SignIn';
	info['Email'] = $('div#SignIn input#Email')[0].value;
	info['Password'] = $('div#SignIn input#Password')[0].value;
	$.ajax({
		type: 'POST',
		url: '/api/sagetask-api.php',
		async: false,
		data: JSON.stringify(info),
		dataType: 'json',
		processData: false,
        contentType: "application/json",
        success: function(data) {
        	if (data.ErrorMsg == "") {
        		$.cookie("Email", info['Email'], { path: '/' });
        		$.cookie("Password", info['Password'], { path: '/' });
        		alert('success');
        	} else {
        		$('div#Error-Message span#ErrorMsg').html('Invalid Email / Password Combination');
        		$("#Error-Message").dialog("open");
			}
		}
	});
}


function SignInCheck() {
	info = new Object();
	info['Action'] = 'SignIn';
	info['Email'] = $('div#SignIn input#Email')[0].value;
	info['Password'] = $('div#SignIn input#Password')[0].value;
	$.ajax({
		type: 'POST',
		url: '/api/sagetask-api.php',
		async: false,
		data: JSON.stringify(info),
		dataType: 'json',
		processData: false,
        contentType: "application/json",
        success: function(data) {
        	if (data.ErrorMsg == "") {
        		$.cookie("Email", info['Email'], { path: '/' });
        		$.cookie("Password", info['Password'], { path: '/' });
        		LoginShow(data.Data.Name);
        	} else {
        		$('div#Error-Message span#ErrorMsg').html('Invalid Email / Password Combination');
        		$("#Error-Message").dialog("open");
			}
		}
	});
}

function SignIn() {
	$("div#SignIn").dialog("open");
}

function LoginShow(Name) {
	$('li#SignIn').addClass("hidden");
	$('li#Register').addClass("hidden");

	$('li#Profile').removeClass("hidden");
	if (Name == '') {
		$('li#Profile a').html('Profile');
	} else {
		$('li#Profile a').html(Name);
	}
	$('li#Logout').removeClass("hidden");
	$('button#AddTask').removeClass("hidden");
	$('div#TaskTable').load(getTaskTable());
}


function LoginCheck() {
	var Email = $.cookie("Email");
	var Password = $.cookie("Password");
	if (Email != '' && Password != '') {
		info = new Object();
		info['Action'] = 'SignIn';
		info['Email'] = Email;
		info['Password'] = Password;
		$.ajax({
			type: 'POST',
			url: '/api/sagetask-api.php',
			async: false,
			data: JSON.stringify(info),
			dataType: 'json',
			processData: false,
			contentType: "application/json", 
			success: function(data) {
				if (data.ErrorMsg == "") {
					LoginShow(data.Data.Name);
				}
			}
		});
	}
}

function Logout() {
	$.cookie("Email", "", { path: '/' });
    $.cookie("Password", "", { path: '/' });
	$('li#SignIn').removeClass("hidden");
	$('li#Register').removeClass("hidden");

	$('li#Profile').addClass("hidden");
	$('li#Logout').addClass("hidden");
	$('button#AddTask').addClass("hidden");
	$('div#TaskTable').html('');
}

function formatTable(json) {
	var html = '';
	html = '<div class="panel panel-default"><table class="table">' + "\n";
	html += '<tr><th><a href="#" onClick="Sort(\'Title\');">Title</a></th>';
	html += '<th><a href="#" onClick="Sort(\'Priority\');">Priority</a></th>';
	html += '<th><a href="#" onClick="Sort(\'DueDate\');">Due Date</a></th>';
	html += '<th class="text-center"><a href="#" onClick="Sort(\'Completed\');">Completed</a></th></tr>' + "\n";
	for (var i=0; i < json.Data.length; i++) {
		var info = json.Data[i];
		var Title = (info.Title == '' ? '<i>Untitled</i>' : info.Title);
		var Completed = (info.Status == 'Completed' ? 1 : 0);
		html += '<tr><td' + (Completed ? ' class="completed-task"' : '') + '><a href="#" onClick="ShowTask(\'' + info.TaskID + '\',\'Completed\');">' + Title + '</a></td>';
		html += '<td' + (Completed ? ' class="completed-task"' : '') + '>' + info.Priority + '</td>';
		html += '<td' + (Completed ? ' class="completed-task"' : '') + '>' + info.DueDate + '</td>';
		html += '<td class="text-center"><input type="checkbox" onClick="TaskStatus(\'' + + info.TaskID + "','" + (Completed ? 'Active' : 'Completed') + '\');"' + (Completed ? ' checked' : '') + '></td></tr>' + "\n";
	}
	html += '</table></div>';
	return html;
}

function getTaskTable() {
	info = new Object();
	info['Action'] = 'GetTaskList';
	info['Email'] = $.cookie("Email");
	info['Password'] = $.cookie("Password");
	$.ajax({
		type: 'POST',
		url: '/api/sagetask-api.php',
		async: false,
		data: JSON.stringify(info),
		dataType: 'json',
		processData: false,
		contentType: "application/json", 
		success: function(data) {
			if (data.ErrorMsg == "") {
				$('div#TaskTable').html(formatTable(data));
			}
		}
	});
}

function Sort(SortBy) {
	info = new Object();
	info['Action'] = 'Sort';
	info['Email'] = $.cookie("Email");
	info['Password'] = $.cookie("Password");
	info['SortBy'] = SortBy;
	$.ajax({
		type: 'POST',
		url: '/api/sagetask-api.php',
		async: false,
		data: JSON.stringify(info),
		dataType: 'json',
		processData: false,
		contentType: "application/json", 
		success: function(data) {
			if (data.ErrorMsg == "") {
				$('div#TaskTable').html(formatTable(data));
			}
		}
	});
}







