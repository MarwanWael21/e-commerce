<?php
ob_start();
session_start();
if (isset($_SESSION['Username'])) {
    $pagetitle = 'Categories';
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'manage') {
        $sort = 'asc';
        $sort_array = ['asc', 'desc'];
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $stmt2 = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll(); ?>

			<h1 class="text-center">Manage Categories</h1>
			<div class="container categories">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-edit"></i> Manage Categories
						<div class="option pull-right">
							<i class="fa fa-sort"></i> Ordering: [
							<a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">Asc</a> | 
							<a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">Desc</a> ]
							<i class="fa fa-eye"></i> View: [
							<span class="active" data-view="full">Full</span> |
							<span data-view="classic">Classic</span> ]
						</div>
					</div>
					<div class="panel-body">
						<?php
							foreach($cats as $cat) {
								echo "<div class='cat'>";
									echo "<div class='hidden-buttons'>";
										echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
										echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
									echo "</div>";
									echo "<h3>" . $cat['Name'] . '</h3>';
									echo "<div class='full-view'>";
										echo "<p>"; if($cat['Description'] == '') { echo 'This category has no description'; } else { echo $cat['Description']; } echo "</p>";
										if($cat['Visibility'] == 1) { echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> Hidden</span>'; } 
										if($cat['Allow_Comment'] == 1) { echo '<span class="commenting cat-span"><i class="fa fa-close"></i> Comment Disabled</span>'; }
										if($cat['Allow_Ads'] == 1) { echo '<span class="advertises cat-span"><i class="fa fa-close"></i> Ads Disabled</span>'; }  
									echo "</div>";

									// Get Child Categories
							$childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID", "ASC");
							if (! empty($childCats)) {
								echo "<h4 class='child-head'>Child Categories</h4>";
								echo "<ul class='list-unstyled child-cats'>";
										foreach ($childCats as $c) {
											echo "<li class='child-link'>
												<a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
												<a href='categories.php?do=Delete&catid=" . $c['ID'] . "' class='show-delete confirm'> Delete</a>
											</li>";
										}
										echo "</ul>";
									}
								echo "</div>";
								echo "<hr>";
							}
						?>
					</div>
				</div>
				<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
			</div>

    <?php } elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=insert" method="POST">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" autocomplete="off" required='required' placeholder="Name Of Category">
                    </div>
                </div>
                <!-- Description Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="password form-control" placeholder="Decripe The Category">
                    </div>
                </div>
                <!-- Order Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Oredring</label>
                    <div class="col-sm-10">
                        <input type="name" name="ordering" class="form-control" placeholder="Arrangment">
                    </div>
                </div>
                <!-- Parent Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category Type</label>
                    <div class="col-sm-10">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php $allCats = getAllFrom("*", "categories", "WHERE Parent = 0", "", "ID", "DESC");
                                foreach ($allCats as $cat) {
                                    echo "<option value='". $cat['ID'] ."'>". $cat['Name'] ."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- Visbialty Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visibale</label>
                    <div class="col-sm-10">
                        <div>
                            <input type="radio" name="visibility" value="0" checked id="0">
                            <label for="0">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="visibility" value="1" id="1">
                            <label for="1">No</label>
                        </div>
                    </div>
                </div>
                <!-- Commented Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Comments</label>
                    <div class="col-sm-10">
                        <div>
                            <input type="radio" name="commenting" value="0" checked id="00">
                            <label for="0">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="commenting" value="1" id="11">
                            <label for="1">No</label>
                        </div>
                    </div>
                </div>
                <!-- Ads Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10">
                        <div>
                            <input type="radio" name="ads" value="0" checked id="000">
                            <label for="0">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="ads" value="1" id="111">
                            <label for="1">No</label>
                        </div>
                    </div>
                </div>
                <!-- Submit -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Category" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>
<?php } elseif ($do == 'insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo "<h1 class = text-center>Update Member</h1>";
            echo "<div class = 'container'>";

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $parent  = $_POST['parent'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];

            $check = checkItem("Name", "categories", $name);
            if ($check == 1) {
                echo "<div class = 'container'>";
                $theMsg = "<div class = 'alert alert-success'> Category Is Already Exisit, Please Try Again </div>";
                redirectHome($theMsg, 'back');
                echo "</div>";
            } else {
                $stmt = $con->prepare("INSERT INTO categories (Name, Description, Parent, Ordering, Visibility, Allow_Comment, Allow_Ads) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute(array($name, $desc, $parent, $order, $visible, $comment, $ads));
                $theMsg =  '<div class = "alert alert-success">"Added"</div>';
                redirectHome($theMsg, 'back');
            }
        } else {
            echo "<div class = 'container'>";
            $theMsg = "<div class = 'alert alert-success'> Can't Accses This page Directly </div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
        echo '</div>';
    } elseif ($do == 'Edit') {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $stmt = $con->prepare("SELECT * FROM `categories` WHERE ID = ?");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            
        <h1 class="text-center">Edit Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=update" method="POST">
                <input type="hidden" name="catid" value = "<?php echo $catid;?>">
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" autocomplete="off" required='required' placeholder="Name Of Category" value = "<?php echo $cat['Name'];?>">
                    </div>
                </div>
                <!-- Description Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10">
                        <input type="text" name="description" class="password form-control" placeholder="Decripe The Category" value = "<?php echo $cat['Description'];?>">
                    </div>
                </div>
                <!-- Order Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Oredring</label>
                    <div class="col-sm-10">
                        <input type="name" name="ordering" class="form-control" placeholder="Arrangment" value = "<?php echo $cat['Ordering'];?>">
                    </div>
                </div>
                <!-- Parent Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category Type</label>
                    <div class="col-sm-10">
                        <select name="parent">
                            <option value="0">None</option>
                            <?php 
										$allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
										foreach($allCats as $c) {
											echo "<option value='" . $c['ID'] . "'";
											if ($cat['Parent'] == $c['ID']) { echo ' selected'; }
											echo ">" . $c['Name'] . "</option>";
										}
									?>
                        </select>
                    </div>
                </div>
                <!-- Visbialty Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Visibale</label>
                    <div class="col-sm-10">
                        <div>
                            <input type="radio" name="visibility" value="0" id="0" <?php if ($cat['Visibility'] == 0) echo "checked";?>>
                            <label for="0">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="visibility" value="1" id="1" <?php if ($cat['Visibility'] == 1) echo "checked";?>>
                            <label for="1">No</label>
                        </div>
                    </div>
                </div>
                <!-- Commented Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Comments</label>
                    <div class="col-sm-10">
                        <div>
                            <input type="radio" name="commenting" value="0" id="0" <?php if ($cat['Allow_Comment'] == 0) echo "checked";?>>
                            <label for="0">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="commenting" value="1" id="1" <?php if ($cat['Allow_Comment'] == 1) echo "checked";?> >
                            <label for="1">No</label>
                        </div>
                    </div>
                </div>
                <!-- Ads Feild -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Allow Ads</label>
                    <div class="col-sm-10">
                        <div>
                            <input type="radio" name="ads" value="0" id="0" <?php if ($cat['Allow_Ads'] == 0) echo "checked";?>>
                            <label for="0">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="ads" value="1" id="1" <?php if ($cat['Allow_Ads'] == 1) echo "checked";?>>
                            <label for="1">No</label>
                        </div>
                    </div>
                </div>
                <!-- Submit -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Update" class="btn btn-primary btn-lg">
                    </div>
                </div>
            </form>
        </div>
<?php
        } else {
            echo "<div class = 'container'>";
            $theMsg = "<div class = 'alert alert-danger'>Id Not Found</div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
    } elseif ($do == 'update') {
        echo "<h1 class='text-center'>Update Category</h1>";
			echo "<div class='container'>";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
                $id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];
				$order 		= $_POST['ordering'];
				$parent 	= $_POST['parent'];
				$visible 	= $_POST['visibility'];
				$comment 	= $_POST['commenting'];
				$ads 		= $_POST['ads'];

				$stmt = $con->prepare("UPDATE 
											`categories`
										SET 
											Name = ?, 
											Description = ?, 
											Ordering = ?, 
                                            Parent = ?,
											Visibility = ?,
											Allow_Comment = ?,
											Allow_Ads = ? 
										WHERE 
											ID = ?");
                                            
            $stmt->execute(array($name, $desc, $order, $parent,$visible, $comment, $ads, $id));
                echo "<div class = 'container'>";
                $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';
				redirectHome($theMsg, 'back');       
                echo "</div>";
            
        } else {
            echo "<div class = 'container'>";
            $theMsg = "<div class = 'alert alert-danger'>Can't Access This Page Directly</div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
        echo '</div>';
    }elseif ($do = 'delete') {

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $check = checkItem('ID', 'categories', $catid);

        if ($check > 0) {
            echo "<h1 class = text-center>Delete Member</h1>";
            echo "<div class = 'container'>";
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = ?");
            $stmt->execute(array($catid));
            echo "<div class = 'container'>";
            $theMsg = "<div class = 'alert alert-danger'>Deleted</div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
            echo '</div>';
        } else {
            echo "<div class = 'container'>";
            $theMsg = "<div class = 'alert alert-danger'>Id Not Found</div>";
            redirectHome($theMsg, 'back');
            echo "</div>";
        }
    }
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
