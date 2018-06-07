<?php require_once('Connections/link.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	$pic_name= "images/". date("YmdHis",strtotime("now")).'-'.$_FILES["pic"]["name"];		//定義圖片名稱
	$pic_url = $_FILES["pic"]["tmp_name"];	//定義圖片路徑

  $insertSQL = sprintf("INSERT INTO product (name, price, detail, vendor, picture) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['price'], "int"),
                       GetSQLValueString($_POST['detail'], "text"),
                       GetSQLValueString($_POST['vendorlist'], "text"),
                       GetSQLValueString($pic_name, "text"));

  mysql_select_db($database_link, $link);  
  $Result1 = mysql_query($insertSQL, $link) or die(mysql_error());   	
 copy($pic_url,$pic_name);						//上傳圖檔
  $insertGoTo = "product_add.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_link, $link);
$query_rsVendor = "SELECT * FROM vendor";
$rsVendor = mysql_query($query_rsVendor, $link) or die(mysql_error());
$row_rsVendor = mysql_fetch_assoc($rsVendor);
$totalRows_rsVendor = mysql_num_rows($rsVendor);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1">
  <p>Name
    <label for="name"></label>
  <input type="text" name="name" id="name" />
  </p>
  <p>Pice  
    <label for="price"></label>
    <input type="text" name="price" id="price" />
  </p>
  <p>Detail
    <label for="detail"></label>
    <input type="text" name="detail" id="detail" />
  </p>
  <p>Vendor 
    <label for="vendorlist"></label>
    <select name="vendorlist" id="vendorlist">
      <?php
do {  
?>
      <option value="<?php echo $row_rsVendor['v_seq']?>"><?php echo $row_rsVendor['name']?></option>
      <?php
} while ($row_rsVendor = mysql_fetch_assoc($rsVendor));
  $rows = mysql_num_rows($rsVendor);
  if($rows > 0) {
      mysql_data_seek($rsVendor, 0);
	  $row_rsVendor = mysql_fetch_assoc($rsVendor);
  }
?>
    </select>
  </p>
  <p>Picture   
    <label for="pic"></label>
    <input type="file" name="pic" id="pic" />
  </p>
  <p>
    <input type="submit" name="add" id="add" value="送出" />
  </p>
  <input type="hidden" name="MM_insert" value="form1" />
</form>

<a href="index.php">回首頁</a>
</body>
</html>
<?php
mysql_free_result($rsVendor);
?>
