<?php

require('config/database.php');

$f_name = $_POST['fname'];
$l_name = $_POST['lname'];
$m_phone = $_POST['mphone'];
$e_email = $_POST['email'];
$p_assword = $_POST['pswd'];
$pss_enc   = md5($p_assword);


$sql = "
INSERT INTO users (
    firstname,
    lastname,
    mobile_phone,
    email,
    password
)
VALUES (
    '$f_name',
    '$l_name',
    '$m_phone',
    '$e_email',
    '$pss_enc'
)
";

$local_res = pg_query($local_conn, $sql);
$supa_res = pg_query($supa_conn, $sql);

if ($local_res) {
    echo "User has been created successfully into local database!!!<br>";
} else {
    echo "User hasn't been created into local database!!!<br>";
}

if ($supa_res) {
    echo "User has been created successfully into Supabase!!!";
} else {
    echo "User hasn't been created into Supabase!!!";
}

echo "<script>alert('user has been created successfully :::')</script>";
header('refresh:0;url=signin.html');


?>
