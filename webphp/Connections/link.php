<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_link = "localhost";
$database_link = "myphp_2018_05";
$username_link = "joyce";
$password_link = "joyce";
$link = mysql_pconnect($hostname_link, $username_link, $password_link) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query('SET NAMES UTF8');
?>