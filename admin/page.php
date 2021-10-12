<?php
    $do = isset($_GET['do']) ?  $_GET['do'] : 'manage';

    // if (isset($_GET['do'])) {
    //     $do = $_GET['do'];
    // } else {
    //     $do = 'manage';
    // }

    if ($do == 'manage') {
        echo "Welcome You Are In Category";
    } elseif ($do == 'add') {
        echo "Add Page";
    }elseif($do == 'insert') {
        echo "Insert";
    } else {
        echo "Error";
    }