<?php
$link = mysqli_connect("127.0.0.1", "mysql", "mysql", "EasySchoolApp");

$query = "CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `url` text NOT NULL,
  `name` text NOT NULL,
  `subject` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `group` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
REPLACE INTO `users` (`user_id`, `type`, `group`) VALUES
(138548631, 1, '33');
";
$test = mysqli_query($link, $query);

if ($_GET['type'] === "getPubConversations") {
  $query = "SELECT * FROM conversations";
  if (isset($_GET['subject'])) {
    $subject = $_GET['subject'];
    $query .= " WHERE subject = '$subject'";
  }
  $query .= " ORDER by id";
  $result = mysqli_query($link, $query);
  if ($result) {
    $result = mysqli_fetch_all($result);
    $response = [];
    for ($i = 0; $i < count($result); $i++) {
      array_push($response, [
        "status" => "ok",
        "id" => $result[$i][0],
        "user_id" => $result[$i][1],
        "url" => $result[$i][2],
        "name" => $result[$i][3],
        "subject" => $result[$i][4]
      ]);
    }
  }
}

if ($_GET['type'] === "getUserConversations" && isset($_GET['user_id'])) {
  $userId = $_GET['user_id'];
  $query = "SELECT * FROM conversations WHERE `creator_id` = $userId ORDER by id";
  $result = mysqli_query($link, $query);
  if ($result) {
    $result = mysqli_fetch_all($result);
    $response = [];
    if (count($result) === 0) {
      array_push($response, [
        "status" => "null"
      ]);
    } else {
      for ($i = 0; $i < count($result); $i++) {
        array_push($response, [
          "status" => "ok",
          "id" => $result[$i][0],
          "user_id" => $result[$i][1],
          "url" => $result[$i][2],
          "name" => $result[$i][3],
          "subject" => $result[$i][4]
        ]);
      }
    }
  }
}

if ($_GET['type'] === "getUsers" && !isset($_GET['user_id'])) {
  $query = "SELECT * FROM users";
  $result = mysqli_query($link, $query);
  if ($result) {
    $result = mysqli_fetch_all($result);
    $response = [];
    if (count($result) === 0) {
      array_push($response, [
        "status" => "null"
      ]);
    } else {
      for ($i = 0; $i < count($result); $i++) {
        array_push($response, [
          "status" => "ok",
          "user_id" => $result[$i][0],
          "type" => $result[$i][1],
          "group" => $result[$i][2],
        ]);
      }
    }
  }
}

if ($_GET['type'] === "getUsers" && isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];
  $query = "SELECT * FROM users WHERE `user_id` = $user_id";
  $result = mysqli_query($link, $query);
  if ($result) {
    $result = mysqli_fetch_all($result);
    $response = [];
    if (count($result) === 0) {
      array_push($response, [
        "status" => "null"
      ]);
    } else {
      for ($i = 0; $i < count($result); $i++) {
        array_push($response, [
          "status" => "ok",
          "user_id" => $result[$i][0],
          "type" => $result[$i][1],
          "group" => $result[$i][2],
        ]);
      }
    }
  }
}

if ($_GET['type'] === "delete" && isset($_GET['user_id'])) {
  $user_id = $_GET['user_id'];
  $query = "DELETE FROM `users` WHERE `users`.`user_id` = $user_id";
  $result = mysqli_query($link, $query);
  if ($result) {
    $response = [
      "status" => "ok"
    ];
  }
}

if ($_GET['type'] === "insert" && isset($_GET['user_id']) && isset($_GET['user_type']) && isset($_GET['group'])) {
  $group = $_GET['group'];
  $type = $_GET['user_type'];
  $user_id = $_GET['user_id'];
  $query = "REPLACE INTO `users` SET `user_id` = $user_id, `type` = $type, `group` = '$group'";
  $result = mysqli_query($link, $query);
  if ($result) {
    $response = [
      "status" => "ok"
    ];
  }
}

if ($_GET['type'] === "insert" && isset($_GET['creator_id']) && isset($_GET['name']) && isset($_GET['url']) && isset($_GET['subject'])) {
  $url = $_GET['url'];
  $name = $_GET['name'];
  $creator_id = $_GET['creator_id'];
  $subject = $_GET['subject'];
  $query = "INSERT INTO `conversations` (`creator_id`, `url`, `name`, `subject`) VALUES ($creator_id, '$url', '$name', '$subject')";
  $result = mysqli_query($link, $query);
  // print_r($query);
  if ($result) {
    $response = [
      "status" => "ok"
    ];
  }
}

if (!$link) {
  $response = [
    "status" => "error connect to db"
  ];
}

if (!isset($response)) {
  $response = [
    "status" => "null response"
  ];
}

header('Content-Type: application/json');
echo json_encode($response);
