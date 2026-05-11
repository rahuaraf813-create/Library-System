<?php

include('../config/db.php');

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM member WHERE member_id=?");
$stmt->bind_param("s", $id);
if($stmt->execute()){
    header("Location: members.php");
}

?>