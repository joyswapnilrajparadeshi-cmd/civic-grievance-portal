<?php
include('../db.php');
$categories = ['Infrastructure', 'Public Safety', 'Utilities', 'Environmental'];
$data = [];
foreach ($categories as $cat) {
    $count = $conn->query("SELECT COUNT(*) AS c FROM complaints WHERE category='$cat'")->fetch_assoc()['c'];
    $data['categories'][] = $cat;
    $data['counts'][] = $count;
}
echo json_encode($data);
?>