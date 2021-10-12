<?php

    /* Page Title */
    function getTitle() {
        global $pagetitle;
        if (isset($pagetitle)) {
            echo $pagetitle;
        } else {
            echo "Default";
        }
    }

/* Redirect Function */
function redirectHome($theMsg, $url = null, $seconds = 3)
{

    if ($url === null) {
        $url = 'index.php';
        $link = 'Homepage';
    } else {

        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        } else {
            $url = 'index.php';
            $link = 'Homepage';
        }
    }
    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
    exit();
}

    /* Check Items In Database */
    function checkItem($select, $from, $value) {
        global $con;
        $statment = $con -> prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment -> execute(array($value));
        $count = $statment -> rowCount();
        return $count;
    }

    /* Count Items */
    function countItems ($item, $table) {
        global $con;
        $stmt2 = $con -> prepare("SELECT COUNT($item) FROM $table");
		$stmt2->execute();
		return $stmt2 -> fetchColumn();
    }

    /*  Get Latest Items */
    function getLatest($select, $table,$order, $limit = 5) {
        global $con;
        $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
        $getStmt->execute();
        return $getStmt->fetchAll();
    }

    /*  Get Latest Cats */
    function getAllFrom($feild = NULL, $table = NULL, $where = NULL, $and = NULL, $order = NULL, $ordering = "DESC") {
        global $con;
        $getAll = $con->prepare("SELECT $feild FROM $table $where $and ORDER BY $order $ordering");
        $getAll->execute();
        return $getAll->fetchAll();

    }
    /* Check User Activate */
    function checkUserStatus($user) {
        global $con;
        $stmtx = $con->prepare("SELECT 
									Username, RegStatus
								FROM 
									users 
								WHERE 
									Username = ? 
								AND 
									RegStatus = 0 ");

        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();

        return $status;
    }