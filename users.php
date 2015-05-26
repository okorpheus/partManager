<?php
require_once('includes.php');

if (!$currentUser->isAdmin()) {
	UserMessageQueue::addMessage('danger', 'User management requires admin privileges');
	redirect('index.php');
}

View::setTitle("Manage Users");
View::addContent('<a href="editUser.php?id=new">Add One User</a>');
View::addContent('<br>');
View::addContent('<a href="bulkAddUsers.php">Add Multiple Users</a>');
showUserTable();
View::sendPage();

function showUserTable() {
	$users = UserFactory::getAllUsers();
	
	$content = <<< TABLEHEAD
	<table class='table table-hover'>
		<thead>
			<tr>
				<th>ID</th>
				<th></th>
				<th>Username</th>
				<th>Real Name</th>
				<th>Email</th>
				<th>Admin</th>
			</tr>
		</thead>
		<tbody>
TABLEHEAD;
	
	foreach ($users as $user) {
		$content .= $user->getFullRow();
	}
	
	$content .= <<< TABLEFOOT
		</tbody>
	</table>
TABLEFOOT;
	View::addContent($content);
}