<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "challenge_test";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT ui.*, s.name AS sector_name FROM user_input ui 
            JOIN sectors s ON ui.sector_id = s.id 
            WHERE ui.id = :id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(array("message" => "No data found for the specified ID"));
        }
    } catch(PDOException $e) {
        echo json_encode(array("message" => "Failed to retrieve data: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("message" => "Missing 'id' parameter in the URL"));
}
?>
