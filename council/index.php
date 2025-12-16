<?php

function callAPI($url) {
    $response = @file_get_contents($url);
    if ($response === false) {
        return null;
    }

    return json_decode($response, true);
}

// CALL APIs
$kompa = callAPI("http://localhost/gki/gkompa/api/attendance.php");
$korem = callAPI("http://localhost/gki/gkorem/api/attendance.php");

$allData = [];

// KOMPA DATA
if (isset($kompa["data"]) && is_array($kompa["data"])) {
    foreach ($kompa["data"] as $row) {
        $allData[] = [
            "source"           => "kompa",
            "id_day"           => $row["id_day"],
            "record_timestamp" => $row["record_timestamp"],
            "visitor_name"     => $row["visitor_name"],
            "name_type"        => $row["name_type"] ?? "N/A",
            "seat_name"        => $row["seat_name"],
            "isDone"           => $row["isDone"]
        ];
    }
}

// KOREM DATA
if (isset($korem["data"]) && is_array($korem["data"])) {
    foreach ($korem["data"] as $row) {
        $allData[] = [
            "source"           => "korem",
            "id_day"           => $row["id_day"],
            "record_timestamp" => $row["record_timestamp"],
            "visitor_name"     => $row["visitor_name"],
            "name_type"        => $row["name_type"] ?? "N/A",
            "seat_name"        => $row["seat_name"],
            "isDone"           => $row["isDone"]
        ];
    }
}

// OUTPUT TABLE (NO CSS)
echo "<table border='1' cellpadding='5'>";
echo "<tr>
        <th>Source</th>
        <th>ID</th>
        <th>Date</th>
        <th>Visitor</th>
        <th>Type</th>
        <th>Seat</th>
        <th>Status</th>
      </tr>";

foreach ($allData as $row) {
    echo "<tr>";
    echo "<td>{$row['source']}</td>";
    echo "<td>{$row['id_day']}</td>";
    echo "<td>{$row['record_timestamp']}</td>";
    echo "<td>{$row['visitor_name']}</td>";
    echo "<td>{$row['name_type']}</td>";
    echo "<td>{$row['seat_name']}</td>";
    echo "<td>" . ($row['isDone'] ? "Done" : "Not Yet") . "</td>";
    echo "</tr>";
}

echo "</table>";