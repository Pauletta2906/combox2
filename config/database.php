<?php
//local connection
$local_host       = "localhost";
$local_port       = "5432";
$local_user       = "postgres";
$local_dbname     = "combox";
$local_password   = "unicesmag";
//local connection


//supa conecction
$supa_host     = "aws-0-ca-central-1.pooler.supabase.com";
$supa_port     = "6543";
$supa_user     = "postgres.wgtmiojqpplblcevumhe";
$supa_dbname   = "postgres";
$supa_password = "unicesmag@@";



$local_conn =pg_connect("
host=$local_host
port=$local_port  
user=$local_user 
dbname=$local_dbname 
password=$local_password 
");

$supa_conn =pg_connect("
host=$supa_host
port=$supa_port  
user=$supa_user 
dbname=$supa_dbname 
password=$supa_password 
");

if (!$local_conn){
    die("local connection error connection.");

} else{
    echo"<br>local connection success!!!";
}

if (!$supa_conn){
    die("supa  error connection.");

} else{
    echo"<br>supa connection success!!!";
}

?>