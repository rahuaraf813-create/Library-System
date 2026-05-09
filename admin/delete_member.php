<?php

include("../includes/db.php");

$id = $_GET['id'];

$sql = "DELETE FROM members WHERE member_id='$id'";

if(mysqli_query($conn,$sql)){
    header("Location:view_members.php");
}

?>