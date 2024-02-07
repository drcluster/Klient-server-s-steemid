<?php
include 'includes/db.php';

if($_GET["action"] == "find_city") {
    $sql = "SELECT stop_area FROM stops WHERE stop_area LIKE '%" . $_GET["search"] . "%'";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row['stop_area'];
        }
        echo json_encode(array_unique($data), JSON_UNESCAPED_UNICODE);
    }
    $conn->close();
} elseif ($_GET["action"] == "find_stop") {
    $sql = "SELECT stop_name FROM stops WHERE stop_name LIKE '%" . $_GET['search'] . "%' AND stop_area = '" . $_GET['city'] . "'";
    $result = $conn->query($sql);
    $data = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row['stop_name'];
        }
        echo json_encode(array_unique($data), JSON_UNESCAPED_UNICODE);
    }
    $conn->close();
} elseif($_GET["action"] == "find_bus") {
    $sql = "SELECT stop_id FROM stops WHERE stop_name LIKE '%" . $_GET['stop_name'] . "%'";
    $result = $conn->query($sql);
    $trip_ids = [];
    $route_ids = [];
    $route_names = [];
    $data = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $stop_id = $row['stop_id'];
        }
    }

    $sql = "SELECT trip_id FROM stop_times WHERE stop_id = '" . $stop_id . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $trip_ids[] = $row['trip_id'];
        }
    }

    $trip_ids_mysql = implode("','",$trip_ids);
    $sql = "SELECT route_id FROM trips WHERE trip_id IN ('".$trip_ids_mysql."')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $route_ids[] = $row['route_id'];
        }
    }

    $route_ids_mysql = implode("','",$route_ids);
    $sql = "SELECT route_short_name FROM routes WHERE route_id IN ('".$route_ids_mysql."')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $route_names[] = $row['route_short_name'];
        }
    }

    echo json_encode(array_unique($route_names), JSON_UNESCAPED_UNICODE);
} elseif($_GET["action"] == "find_times") {
    $arrival_times = [];
    $trip_ids = [];

    $sql = "SELECT route_id FROM routes WHERE route_short_name = '" . $_GET['bus_name'] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $route_id = $row['route_id'];
        }
    }

    $sql = "SELECT trip_id FROM trips WHERE route_id = '" . $route_id . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $trip_ids[] = $row['trip_id'];
        }
    }

    $trip_ids_mysql = implode("','",$trip_ids);
    $sql = "SELECT arrival_time FROM stop_times WHERE trip_id IN ('".$trip_ids_mysql."')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $arrival_times[] = $row['arrival_time'];
        }
    }

    date_default_timezone_set('Europe/Tallinn');


    function findClosestTimes($times, $myTime) {
        $myTimeSeconds = strtotime($myTime) - strtotime('today');

        $differences = array_map(function($time) use ($myTimeSeconds) {
            $timeSeconds = strtotime($time) - strtotime('today');
            return abs($myTimeSeconds - $timeSeconds);
        }, $times);

        $timesWithDifferences = array_combine($times, $differences);

        asort($timesWithDifferences);

        $sortedTimes = array_keys($timesWithDifferences);

        return array_slice($sortedTimes, 0, 3);
    }

    $myTime = date("h:i:sa");
    $closestTimes = findClosestTimes($arrival_times, $myTime);

    echo json_encode(array_unique($closestTimes), JSON_UNESCAPED_UNICODE);
} elseif($_GET["action"] == "find_closest") {
    $data = [];
    $sql = "SELECT stop_lat, stop_lon FROM stops WHERE stop_name = '" . $_GET["stop_name"] . "' AND stop_area = '" . $_GET["stop_area"] . "' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lat1 = $row['stop_lat'];
        $lon1 = $row['stop_lon'];

        $sql = "SELECT stop_name, stop_area, ( 6371 * acos( cos( radians($lat1) ) * cos( radians( stop_lat ) ) * cos( radians( stop_lon ) - radians($lon1) ) + sin( radians($lat1) ) * sin( radians( stop_lat ) ) ) ) AS distance FROM stops HAVING distance < 10 AND stop_name != '" . $_GET["stop_name"] . "' ORDER BY distance LIMIT 1";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $data[] = "Lähim peatus " . $row['stop_name'] . " " . $row['stop_area'];
        }
    }

    echo json_encode(array_unique($data), JSON_UNESCAPED_UNICODE);
}