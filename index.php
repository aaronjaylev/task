<?php

/**
* SageTask.com
*
* SageTask.com is a Website, API, and SDK for Creating and Managing simple tasks.
*
* This code is visible on http://www.sagetask.com.  It is a "one page" website
* and uses JQuery, JQueryUI, Twitter Bootstrap to format the pages.  Connection
* to the MySQL database is done through an API which can also be called directly.
* I hope you enjoy viewing, using and learning from this code.  You may use it for 
* your own projects if you give me credit by leaving this license notification 
* in your files.  Happy Coding.  Aaron Jay
*
* @package SageTask
* @author Aaron Jay Lev <aaronjaylev@gmail.com>
* @copyright Copyright (c) 2014, Aaron Jay Lev
* @link http://www.sagetask.com
* @example http://www.sagetask.com/
* @license http://www.apache.org/licenses/LICENSE-2.0
*
* Copyright 2014 Aaron Jay Lev
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
* http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/

?>
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
	<div id="Intro">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <h3 class="panel-title">Welcome to SageTask.com</h3>
		  	</div>
			<div class="panel-body">
				<p>Welcome to SageTask.com.  Your simple, easy and free easy task manager.</p>
				<p>To get started, please click the &quot;Register&quot; button at the top of the screen.</p>
				<p>After you complete the Register screen, a password will be emailed to you.</p>
				<p>Then click the &quot;Login&quot; button to start keeping track of your tasks</p>
				<p>We will set a cookie on your computer, so you will not have to login again.  You will just
				have to return to this website, to see and manage your tasks</p>
				<p>Enjoy!</p>
			</div>
		</div>
	</div>
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

<div id="Info-Message" title="Info Message">
	<p>
		<span class="ui-icon ui-icon-info" style="float:left; margin:0 7px 50px 0;"></span>
		<span id="InfoMsg"></span>
	</p>
</div>

<div id="SignIn" title="Sign In for Sage Task">
	<form>
		<table class="table">
			<tr><td>Your Email:</td><td><input maxlength="100" size="40" id="Email" value=""></td></tr>
			<tr><td>Password:</td><td><input type="password" maxlength="20" size="20" id="Password" value=""></td></tr>
		</table>
		<p><span id="InfoMsg"></span></p>
	</form>
</div>

<div id="Forgot" title="Forgot my Password">
	<form>
		<table class="table">
			<tr><td colspan="2">Enter your Email to have a new password Emailed to you</td></tr>
			<tr><td>Your Email:</td><td><input maxlength="100" size="40" id="Email" value=""></td></tr>
			<tr><td colspan="2"><span id="ErrorMsg"></span></td></tr>
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
