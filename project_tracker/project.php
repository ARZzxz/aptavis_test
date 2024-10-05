<?php
require_once 'koneksi.php';

function tampilkan_project() {
    $query = "SELECT * FROM project";
    $result = $conn->query($query);
    $projects = array();
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    return $projects;
}

function tambah_project($name, $status) {
    $query = "INSERT INTO project (name, status) VALUES ('$name', '$status')";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function edit_project($id, $name, $status) {
    $query = "UPDATE project SET name = '$name', status = '$status' WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function hapus_project($id) {
    $query = "DELETE FROM project WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function tampilkan_task($project_id) {
    $query = "SELECT * FROM task WHERE project_id = $project_id";
    $result = $conn->query($query);
    $tasks = array();
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    return $tasks;
}

function tambah_task($name, $status, $project_id, $weight) {
    $query = "INSERT INTO task (name, status, project_id, weight) VALUES ('$name', '$status', $project_id, $weight)";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function edit_task($id, $name, $status, $project_id, $weight) {
    $query = "UPDATE task SET name = '$name', status = '$status', project_id = $project_id, weight = $weight WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}

function hapus_task($id) {
    $query = "DELETE FROM task WHERE id = $id";
    if ($conn->query($query) === TRUE) {
        return true;
    } else {
        return false;
    }
}
?>