

<!-- ==================== BEGIN MENU BAR ==================== -->
<nav role="navigation" class="navbar navbar-inverse">
	<div class='navbar-header'>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
        	<span class="sr-only">Toggle navigation</span>
           	<span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
     	<a class="navbar-brand" href="index.php">partManger</a>
     </div>
     <div id='navbarCollapse' class='collapse navbar-collapse'>
     	<ul class='nav navbar-nav'>
     		<li><a href='index.php'>Home</a></li>
     		<?php if (is_object($currentUser) AND $currentUser->isAdmin()) { ?>
     			<li class='dropdown'>
     				<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
     					Admin Options
     					<b class='caret'></b>
     				</a>
     				<ul role='menu' class='dropdown-menu'>
     					<li><a href='users.php'>Manage Users</a></li>
     					<li><a href='maintenance.php?a=rescanDirectory'>Scan for new files</a></li>
     					<li><a href='maintenance.php?a=cleanDatabase'>Remove Entries for Missing Files</a></li>
 						<li><a href='maintenance.php?a=all'>Full Cleanup</a></li>
     				</ul>
     			</li>
     			
     		<?php } ?>
     		
     		<?php if (is_object($currentUser)) { ?>
     			<li><a href='editUser.php?id=<?php echo $currentUser->getID();?>'>My Profile</a></li>
     			<li><a href='logout.php'>Logout</a></li>
     		<?php } else { ?>
           		<li><a href="login.php">Login</a></li>
           	<?php } ?>
       	</ul>
     	
	</div>
</nav>	
<!-- ==================== END MENU BAR ==================== -->

