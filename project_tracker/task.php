<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_tracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ambil semua project
    $result = $conn->query("SELECT * FROM projects");
    $projects = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($projects);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tambahkan project baru
    $name = $_POST['name'];
    $status = $_POST['status'];
    $conn->query("INSERT INTO projects (name, status) VALUES ('$name', '$status')");
    echo json_encode(['success' => true]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update project
    parse_str(file_get_contents("php://input"), $post_vars);
    $id = $post_vars['id'];
    $name = $post_vars['name'];
    $status = $post_vars['status'];
    $conn->query("UPDATE projects SET name='$name', status='$status' WHERE id=$id");
    echo json_encode(['success' => true]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Hapus project
    $id = $_GET['id'];
    $conn->query("DELETE FROM projects WHERE id=$id");
    echo json_encode(['success' => true]);
}
?>
