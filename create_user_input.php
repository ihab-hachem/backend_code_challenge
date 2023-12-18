<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = file_get_contents("php://input");
    $postData=json_decode($postData,true);
    $name = $postData['name'] ?? '';
    $sector_id = $postData['sector_id'];
    $terms_agree = $postData['terms_agree'] ?? '';

    $sql = "INSERT INTO user_input (name, sector_id, terms_agree) VALUES (:name, :sector_id, :terms_agree)";
    
    

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':sector_id', $sector_id);
        $stmt->bindParam(':terms_agree', $terms_agree);
        
        $stmt->execute();
        
        $data_id=$conn->lastInsertId();

        echo json_encode(array("message" => "Data inserted successfully","id"=>$data_id));
    } catch(PDOException $e) {
        echo json_encode(array("message" => "Failed to insert data: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("message" => "Invalid request method"));
}
?>
