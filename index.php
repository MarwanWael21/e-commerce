<?php
session_start();
$pagetitle = 'Home';
include 'init.php'; ?>
    <div class="container mt-5">
        <h1 class = "text-center">Homepage</h1>
        <div class="row">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class = "text-center">
                <div class="search-form">
                    <input type="text" name = 'search' autocomplete = "off">
                    <input type="button" value="Search" class = "btn btn-primary">
                </div>
            </form> <br>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                $search = $_POST['search'];               
                $stmt = $con -> prepare('SELECT * FROM items WHERE Name LIKE ?');
                $stmt -> execute(["%$search%"]);
                while ($item = $stmt -> fetch()) {
                echo '<div class = "col-sm-6 col-md-3">';
                    echo '<div class = "thumbnail item-box">';
                        echo '<span class= "price-tag">'.$item['Price'].'</span>';
                            echo '<img src = "img.jpg" alt = "" class = "img-responsive"/>';
                            echo '<div class = "caption">';
                            echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
                            echo '<p>'.$item['Description'].'</</p>';
                                echo '<div class = "date">' .$item['Add_Date']. '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            } else {
                foreach (getAllFrom('*', 'items', 'WHERE Approve = 1', '', 'Item_ID') as $item) {
                    echo '<div class = "col-sm-6 col-md-3">';
                        echo '<div class = "thumbnail item-box">';
                        echo '<span class= "price-tag">'.$item['Price'].'</span>';
                            echo '<img src = "img.jpg" alt = "" class = "img-responsive"/>';
                            echo '<div class = "caption">';
                                echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
                                echo '<p>'.$item['Description'].'</</p>';
                                echo '<div class = "date">' .$item['Add_Date']. '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            }
            ?>
            </div>
        </div>
    </div>
<?php
include $tpl . 'footer.php';

?>