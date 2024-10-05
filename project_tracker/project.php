<?php
require_once 'koneksi.php';

function tampilkan_project() {
    global $conn; // tambahkan baris ini
    $query = "SELECT * FROM project";
    $result = $conn->query($query);
    $projects = array();
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    return $projects;
}

function tambah_project($name, $status) {
    global $conn; // tambahkan baris ini
    $query = "INSERT INTO project (name, status) VALUES ('$name', '$status')";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function edit_project($id, $name, $status) {
    global $conn; // tambahkan baris ini
    $query = "UPDATE project SET name = '$name', status = '$status' WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function hapus_project($id) {
    global $conn; // tambahkan baris ini
    $query = "DELETE FROM project WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function tampilkan_task($project_id) {
    global $conn; // tambahkan baris ini
    $query = "SELECT * FROM task WHERE project_id = $project_id";
    $result = $conn->query($query);
    $tasks = array();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    return $tasks;
}

function tambah_task($name, $status, $project_id, $weight) {
    global $conn; // tambahkan baris ini
    $query = "INSERT INTO task (name, status, project_id, weight) VALUES ('$name', '$status', $project_id, $weight)";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function edit_task($id, $name, $status, $project_id, $weight) {
    global $conn; // tambahkan baris ini
    $query = "UPDATE task SET name = '$name', status = '$status', project_id = $project_id, weight = $weight WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function hapus_task($id) {
    global $conn; // tambahkan baris ini
    $query = "DELETE FROM task WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}
?>