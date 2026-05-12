<?php
include("../includes/db.php");

$sql = "SELECT * FROM members";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>
<head>

<title>View Members</title>

<link rel="stylesheet" href="../assets/css/css/bootstrap.min.css">

</head>

<body>

<div class="container mt-5">

<h2>All Members</h2>

<table class="table table-bordered">

<tr>
    <th>Member ID</th>
    <th>Firstname</th>
    <th>Lastname</th>
    <th>Birthday</th>
    <th>Email</th>
    <th>Actions</th>
</tr>

<?php

while($row = mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo $row['member_id']; ?></td>
<td><?php echo $row['firstname']; ?></td>
<td><?php echo $row['lastname']; ?></td>
<td><?php echo $row['birthday']; ?></td>
<td><?php echo $row['email']; ?></td>

<td>

<a href="edit_member.php?id=<?php echo $row['member_id']; ?>" class="btn btn-warning">
Edit
</a>

<a href="delete_member.php?id=<?php echo $row['member_id']; ?>" class="btn btn-danger">
Delete
</a>

</td>

</tr>

<?php
}
?>

</table>

</div>

</body>
</html>