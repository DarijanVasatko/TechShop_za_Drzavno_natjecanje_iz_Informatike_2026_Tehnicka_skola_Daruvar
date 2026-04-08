<?php
include ("../includes/db_conn.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT Email FROM kupac WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo 'postoji';
    } else {
        echo 'ne postoji';
    }

    $stmt->close();
    $conn->close();
}
?>
