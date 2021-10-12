<?php
ob_start();
session_start();
if (isset($_SESSION['Username'])) {
	$pagetitle = 'Dashboard';
	include 'init.php';
	countItems('UserID', 'users');
	$numItems = 5;
	$numUsers = 5;
	$numComments = 5;
	$latestUsers = getLatest("*", 'users', 'UserID', $numUsers);
	$latestItems = getLatest("*", 'items', 'Item_ID', $numUsers);
?>
	<div class="container home-stats text-center">
			<h1>Dashboard</h1>
				<div class="row">
					<div class="col-md-3">
						<div class="stat st-members">
							<i class="fa fa-users"></i>
							<div class="info">
								Total Members
								<span>
									<a href="members.php"><?php echo countItems('UserID', 'users') ?></a>
								</span>
							</div>
						</div>
					</div>
			<div class="col-md-3">
						<div class="stat st-pending">
							<i class="fa fa-user-plus"></i>
							<div class="info">
								Pending Members
								<span>
									<a href="members.php?do=Manage&page=Pending">
										<?php echo checkItem("RegStatus", "users", 0) ?>
									</a>
								</span>
							</div>
						</div>
					</div>
			<div class="col-md-3">
				<div class="stat st-items">
					<div class="info">
						<i class="fa fa-tag"></i>
						Total Items <span><a href="items.php" title="Click Here To Veiw Members"><span><?php echo countItems('Item_ID', 'items'); ?></span></a></span>
					</div>
				</div>
			</div>
			<div class="col-md-3">
						<div class="stat st-comments">
							<i class="fa fa-comments"></i>
							<div class="info">
								Total Comments
								<span>
									<a href="comments.php"><?php echo countItems('c_id', 'comments') ?></a>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="container latest">
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php $numUsers = 5; ?>
						<i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Register Users
						<span class="toggle-info pull-right">
							<i class="fa fa-plus fa-lg"></i>
						</span>
					</div>
					<div class="panel-body">
						<ul class="list-unstyled latest-users">
							<?php
							
							foreach ($latestUsers as $user) {
								echo "<li>";
									echo $user['Username'];
									echo "<span class = 'btn btn-success pull-right'>";
									echo "<a href='members.php?do=Edit&userid=" . $user['UserID'] . "' class='btn btn-success pull-right'><i class='fa fa-edit'></i>Edit</a>";
									if ($user['RegStatus'] == 0) {
										echo "<a href='members.php?do=Activate&userid=" . $user['UserID'] . "' class='btn btn-info activate pull-right'> <i class='fa fa-check'></i> Activate</a>";
									}
									echo "</span>";
								echo "</li>";
							}
							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-tag"></i> Latest <?php echo $numItems?> Items
						<span class="toggle-info pull-right">
							<i class="fa fa-plus fa-lg"></i>
						</span>
					</div>
					<div class="panel-body">
						<ul class="list-unstyled latest-users">
							<?php
							
							foreach ($latestItems as $item) {
								echo '<li>';
											echo $item['Name'];
												echo '<a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '">';
													echo '<span class="btn btn-success pull-right">';
														echo '<i class="fa fa-edit"></i> Edit';
															if ($item['Approve'] == 0) {
																echo "<a 
																	href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' 
																	class='btn btn-info pull-right activate'>
																	<i class='fa fa-check'></i> Approve</a>";
															}
													echo '</span>';
												echo '</a>';
											echo '</li>';
							}
							?>
						</ul>						
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php $numUsers = 5; ?>
						<i class="fa fa-comments"></i> Latest <?php echo $numComments?> Comments
						<span class="toggle-info pull-right">
							<i class="fa fa-plus fa-lg"></i>
						</span>
					</div>
					<div class="panel-body">
						<?php 
						if (!empty($latestItems)) {
						$stmt = $con->prepare("SELECT comments.*, users.Username AS User_Name FROM comments INNER JOIN users ON users.UserID = comments.user_id ORDER BY c_id DESC LIMIT $numComments");
						$stmt->execute();
						$rows = $stmt->fetchAll();
						foreach ($rows as $row) {
							echo '<div class = "comment-box">';
								echo '<span class = "member-n">' . $row['User_Name'] . '</span>';
								echo '<p class = "member-c">' . $row['comment'] . '</p>';
							echo "</div>";
						}
					} else {
						echo "There's No Comments";
					}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php include $tpl . 'footer.php';
} else {
	header("Location: index.php");
	exit();
}
ob_end_flush();
?>
