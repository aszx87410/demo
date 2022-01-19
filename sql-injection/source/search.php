<?php

@$_GET["source"] == 1 && die(highlight_file(__FILE__));

/*
  You can find flag at `flag` table with column name `content`
*/

require_once "conn.php";

$tag = $_GET["tag"] ?? "";

$sql = "SELECT id, name, tags from home ";

if (strpos(strtolower($tag), "sleep") !== false) {
  die("QQ");
}

if(!empty($tag) && is_string($tag)) {
  $tag_arr = explode(',', $tag);
  $sql_tag = [];
  foreach ($tag_arr as $k => $v) {
      array_push($sql_tag, "( FIND_IN_SET({$v}, tags) )");
  }
  if (!empty($sql_tag)) {
      $sql.= "where (" . implode(' OR ', $sql_tag) . ")";
  }
}

$result = $conn->query($sql);
$arr = [];
if ($result) {
  while($row = $result->fetch_assoc()) {
    array_push($arr, $row);
  }
  header('Content-Type: application/json');
  echo json_encode($arr, JSON_PRETTY_PRINT);
} else {
  die($sql);
}

?>