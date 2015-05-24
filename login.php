<?php
require_once('includes.php');


if (!function_exists('checkSubmittedForm')) {
	function checkSubmittedForm($username, $password) {
		$checkUser = UserFactory::findByUsername($username);

		// show login form if user does note exist
		if(is_null($checkUser)) {
			UserMessageQueue::addMessage('danger', "No Such User");
			showLoginForm();
			return;
		}

		// OK, the user exists, verify their password
		if ($checkUser->verifyPassword($password)) {
			$_SESSION['currentUserID'] = $checkUser->getID();
			UserMessageQueue::addMessage("success", "Login Successful");
			redirect('index.php');
			return;
		}
		UserMessageQueue::addMessage('danger', "Incorrect Password");
		showLoginForm();
	}
}

if (!function_exists('showLoginForm')) {
	function showLoginForm() {
		View::setTitle("PartManager Login");


		$addContent = <<< LOGINFORM
	<div class='row'>
		<div class='col-sm-4 hidden-xs'></div>
		<div class='col-sm-4' col-xs-12'>
			<form method='post' action =''>
				<div class='form-group'>
					<label for='username'>Username</label>
					<input type='text' name='username' id='username' placeholder='username' class='form-control autofocus>
				</div>
				<div class='form-group'>
					<label for='password'>Password</label>
					<input type='password' name='password' id='password' placeholder='password' class='form-control'>
				</div>
				<button type="submit" class="btn btn-primary">Login</button>
			</form>
		</div>
		<div class='col-sm-4 hidden-xs'></div>
	</div>
LOGINFORM;


		View::addContent($addContent);
		View::sendPage();
	}
}


if(isset($_POST['username'])) checkSubmittedForm($_POST['username'],$_POST['password']);
else showLoginForm();

