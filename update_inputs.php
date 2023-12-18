<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PATCH");
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

if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    $input_data = file_get_contents('php://input');
    
    $data = json_decode($input_data, true);

    $id = $data['id'] ?? '';
    $name = $data['name'] ?? '';
    $sector_id = $data['sector_id'] ?? '';
    $terms_agree = $data['terms_agree'] ?? '';
    $sql = "UPDATE user_input SET name = :name, sector_id = :sector_id, terms_agree = :terms_agree WHERE id = :id";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':sector_id', $sector_id);
        $stmt->bindParam(':terms_agree', $terms_agree);
        $stmt->execute();

        echo json_encode(array("message" => "Data updated successfully"));
    } catch(PDOException $e) {
        echo json_encode(array("message" => "Failed to update data: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("message" => "Invalid request method"));
}
?>
