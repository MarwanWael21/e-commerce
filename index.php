<?php
session_start();
$pagetitle = 'Home';
include 'init.php'; ?>
    <div class="container">
        <div class="row"></div>
            <?php
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
            ?>
        </div>
    </div>
<?php
include $tpl . 'footer.php';

?>