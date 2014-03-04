<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Simple Task Manager using Bootstrap and JQuery">
<meta name="keywords" content="HTML, CSS, JS, JavaScript, jQuery, bootstrap">
<meta name="author" content="Aaron Jay Lev">

<title>Sage Task - Simple Task Manager</title>

<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/bootstrap-theme.css" rel="stylesheet">
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<link href="/css/custom.css" rel="stylesheet">

</head>
<body>


<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="navbar-inner">
    	<div class="container">
        	<a class="brand" href="#"><img src="/images/sagetask.png"></a>
			<ul id="RightButtons" class="nav navbar-nav navbar-right">
				<li id="SignIn"><a href="#" onClick="SignIn();">Sign In</a></li>
				<li id="Register"><a href="#" onClick="Register();">Register</a></li>

				<li id="Profile" class="hidden"><a href="#" onClick="Profile();">Profile</a></li>
				<li id="Logout" class="hidden"><a href="#" onClick="Logout();">Logout</a></li>
			</ul>
		</div>
	</div>
</nav> 

<div class="container">
	<div class="page-header">
	  <h1>Sage Task - Simple Task Manager</h1>
	</div>
	<button id="AddTask" type="button" class="btn btn-default btn-lg pull-right hidden" onClick="ShowTask('Add');"> 
  		<span class="glyphicon glyphicon-plus"></span> Add New Task
	</button>
</div>


<div class="container" style="padding-top: 20px;">	
	<div id="TaskTable"></div>
</div>

<nav class="navbar navbar-inverse navbar-fixed-bottom" role="navigation">
	<div class="container">
		<div class="navbar-footer">
			<p class="navbar-brand">&copy; <?php echo date("Y"); ?> Sage Task</p>
		</div>
	</div>
</nav>

<div id="TaskDialog" title="Add Task">
	<form>
		<input type="hidden" id="TaskID" value="">
		<table class="table">
			<tr><td>Title:</td><td><input maxlength="100" size="70" id="Title" value=""></td></tr>
			<tr><td>Due Date:</td><td><input id="DueDate" value="" maxlength="20"></td></tr>
			<tr><td>Priority:</td><td><select id="Priority"></select></td></tr>
			<tr><td>Description:</td><td><textarea id="Description" cols="50" rows="6"></textarea></tr>
		</table>
	</form>
</div>

<div id="Register" title="Register for Sage Task">
	<form>
		<table class="table">
			<tr><td>Your Name:</td><td><input maxlength="50" size="40" id="Name" value=""></td></tr>
			<tr><td>Email to Register:</td><td><input maxlength="100" size="40" id="Email" value=""></td></tr>
		</table>
	</form>
</div>

<div id="Register-Message" title="Email Sent">
	<p><span class="ui-icon ui-icon-circle-check" style="float:left; margin:0 7px 50px 0;"></span>
	The password has been sent to your email.</p>
</div>

<div id="Error-Message" title="Error Message">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
	<span id="ErrorMsg"></span></p>
</div>

<div id="SignIn" title="Sign In for Sage Task">
	<form>
		<table class="table">
			<tr><td>Your Email:</td><td><input maxlength="100" size="40" id="Email" value=""></td></tr>
			<tr><td>Password:</td><td><input type="password" maxlength="20" size="20" id="Password" value=""></td></tr>
		</table>
	</form>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="/js/jquery.cookie.js"></script>
<script src="/js/tasks.js"></script>

</body>
</html>
