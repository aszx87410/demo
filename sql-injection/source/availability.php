<?php

@$_GET["source"] == 1 && die(highlight_file(__FILE__));

require_once "conn.php";

date_default_timezone_set('Asia/Taipei');

if (empty($_GET["start_time"]) || empty($_GET["end_time"]) || empty($_GET["id"])) {
  die("start_time, end_time and id are required.");
}

$startTime = strtotime($_GET["start_time"]);
$endTime = strtotime($_GET["end_time"]);
$id = $_GET["id"];

if (strpos(strtolower($id), "sleep") !== false) {
  die("QQ");
}

$priceItems = getPriceItems($id, $startTime, $endTime);

for ($i = $startTime; $i <= $endTime; $i = strtotime('+1 day', $i)) {
    $found = false;
    foreach ($priceItems['results'] as $range) {
        if ($i == $range["start_time"] && $i <= $range["end_time"]) {
            $data = $range;
            $found = true;
            break;
        }
    }

    if ($found) {
      $events['events'][] = [
          'start' => date('Y-m-d', $data["start_time"]),
          'end' => date('Y-m-d', $data["end_time"]),
          'status' => "Available",
      ];
    } else {
      $events['events'][] = [
          'start' => date('Y-m-d', $i),
          'end' => date('Y-m-d', $i),
          'status' => "Unavailable",
      ];
    }   
}

header('Content-Type: application/json');
echo json_encode($events, JSON_PRETTY_PRINT);

function esc_sql($str) {
  global $conn;
  return $conn->real_escape_string($str);
}

function getPriceItems($id, $start, $end) {
    global $conn;

    $start = esc_sql($start);
    $end = esc_sql($end);
    $sql = "select * from price where ((price.start_time >= {$start} AND price.end_time <= {$end}) OR (price.start_time <= {$start} AND price.end_time >= {$start}) OR (price.start_time <= {$end} AND price.end_time >= {$end})) AND price.home_id = {$id}";
    
    $result = $conn->query($sql);
    $arr = [];
    if ($result) {
      while($row = $result->fetch_assoc()) {
        array_push($arr, $row);
      }
    } else {
      die($sql);
    }

    return [
        'results' => $arr
    ];
}
?>