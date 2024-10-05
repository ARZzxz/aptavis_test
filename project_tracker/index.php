<?php
require_once 'koneksi.php';
require_once 'project.php';

// Tampilkan project
$projects = tampilkan_project();

// Tampilkan task
$tasks = array();
foreach ($projects as $project) {
    $tasks[$project['id']] = tampilkan_task($project['id']);
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'tambah_project') {
        $name = $_POST['name'];
        $status = $_POST['status'];
        if (tambah_project($name, $status)) {
            echo "Project berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan project!";
        }
    } elseif ($action == 'tambah_task') {
        $name = $_POST['name'];
        $status = $_POST['status'];
        $project_id = $_POST['project_id'];
        $weight = $_POST['weight'];
        if (tambah_task($name, $status, $project_id, $weight)) {
            echo "Task berhasil ditambahkan!";
        } else {
            echo "Gagal menambahkan task!";
        }
    } elseif ($action == 'edit_project') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $status = $_POST['status'];
        if (edit_project($id, $name, $status)) {
            echo "Project berhasil diedit!";
        } else {
            echo "Gagal mengedit project!";
        }
    } elseif ($action == 'edit_task') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $status = $_POST['status'];
        $project_id = $_POST['project_id'];
        $weight = $_POST['weight'];
        if (edit_task($id, $name, $status, $project_id, $weight)) {
            echo "Task berhasil diedit!";
        } else {
            echo "Gagal mengedit task!";
        }
    } elseif ($action == 'hapus_project') {
        $id = $_POST['id'];
        if (hapus_project($id)) {
            echo "Project berhasil dihapus!";
        } else {
            echo "Gagal menghapus project!";
        }
    } elseif ($action == 'hapus_task') {
        $id = $_POST['id'];
        if (hapus_task($id)) {
            echo "Task berhasil dihapus!";
        } else {
            echo "Gagal menghapus task!";
        }
    }
}

?>

<html>
<head>
    <title>Project Tracker</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f7f7;
        }
        .container {
            margin-top: 50px;
        }
        .right-panel {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Project</h2>
                <ul id="project-list">
                    <?php foreach ($projects as $project) { ?>
                        <li><a href="#" onclick="toggleForm('editProject', <?php echo $project['id']; ?>)" data-id="<?php echo $project['id']; ?>"><?php echo $project['name']; ?></a></li>
                    <?php } ?>
                </ul>
                <button class="btn btn-success" onclick="toggleForm('addProject')">Tambah Project</button>
            </div>
            <div class="col-md-6">
                <h2>Task</h2>
                <ul id="task-list">
                    <?php foreach ($tasks as $project_id => $project_tasks) { ?>
                        <?php foreach ($project_tasks as $task) { ?>
                            <li><a href="#" onclick="toggleForm('editTask', <?php echo $task['id']; ?>)" data-id="<?php echo $task['id']; ?>"><?php echo $task['name']; ?></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <button class="btn btn-success" onclick="toggleForm('addTask')">Tambah Task</button>
            </div>
        </div>
    </div>

    <!-- Form untuk tambah/edit project -->
    <div id="project-form" style="display: none;">
        <h2>Tambah/Edit Project</h2>
        <form id="project-form-data">
            <input type="hidden" id="project-id" name="id">
            <div class="form-group">
                <label for="project-name">Nama Project</label>
                <input type="text" class="form-control" id="project-name" name="name">
            </div>
            <div class="form-group">
                <label for="project-status">Status</label>
                <select class="form-control" id="project-status" name="status">
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm()">Batal</button>
        </form>
    </div>

    <!-- Form untuk tambah/edit task -->
    <div id="task-form" style="display: none;">
        <h2>Tambah/Edit Task</h2>
        <form id="task-form-data">
            <input type="hidden" id="task-id" name="id">
            <div class="form-group">
                <label for="task-name">Nama Task</label>
                <input type="text" class="form-control" id="task-name" name="name">
            </div>
            <div class="form-group">
                <label for="task-status">Status</label>
                <select class="form-control" id="task-status" name="status">
                    <option value="aktif">Aktif</option>
                    <option value="tidak aktif">Tidak Aktif</option>
                </select>
            </div>
            <div class="form-group">
                <label for="task-project">Project</label>
                <select class="form-control" id="task-project" name="project_id">
                    <?php foreach ($projects as $project) { ?>
                        <option value="<?php echo $project['id']; ?>"><?php echo $project['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="task-weight">Bobot</label>
                <input type="number" class="form-control" id="task-weight" name="weight">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="toggleForm()">Batal</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function toggleForm(action, id = null) {
            if (action === 'addProject') {
                $('#project-form').show();
                $('#project-id').val('');
                $('#project-name').val('');
                $('#project-status').val('aktif');
            } else if (action === 'editProject') {
                $('#project-form').show();
                $('#project-id').val(id);
                $.ajax({
                    type: "GET",
                    url: "index.php",
                    data: {action: 'getProject', id: id},
                    success: function(data) {
                        const project = JSON.parse(data);
                        $('#project-name').val(project.name);
                        $('#project-status').val(project.status);
                    }
                });
            } else if (action === 'addTask') {
                $('#task-form').show();
                $('#task-id').val('');
                $('#task-name').val('');
                $('#task-status').val('aktif');
                $('#task-project').val('');
                $('#task-weight').val('');
            } else if (action === 'editTask') {
                $('#task-form').show();
                $('#task-id').val(id);
                $.ajax({
                    type: "GET",
                    url: "index.php",
                    data: {action: 'getTask', id: id},
                    success: function(data) {
                        const task = JSON.parse(data);
                        $('#task-name').val(task.name);
                        $('#task-status').val(task.status);
                        $('#task-project').val(task.project_id);
                        $('#task-weight').val(task.weight);
                    }
                });
            } else {
                $('#project-form').hide();
                $('#task-form').hide();
            }
        }

        function hapusProject(id) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {action: 'hapus_project', id: id},
                success: function(data) {
                    if (data === 'Project berhasil dihapus!") {
                        location.reload();
                    } else {
                        alert(data);
                    }
                }
            });
        }

        function hapusTask(id) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {action: 'hapus_task', id: id},
                success: function(data) {
                    if (data === 'Task berhasil dihapus!") {
                        location.reload();
                    } else {
                        alert(data);
                    }
                }
            });
        }

        $('#project-form-data').submit(function(event) {
            event.preventDefault();
            const id = $('#project-id').val();
            const name = $('#project-name').val();
            const status = $('#project-status').val();
            if (id === '') {
                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: {action: 'tambah_project', name: name, status: status},
                    success: function(data) {
                        if (data === 'Project berhasil ditambahkan!") {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: {action: 'edit_project', id: id, name: name, status: status},
                    success: function(data) {
                        if (data === 'Project berhasil diedit!") {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            }
        });

        $('#task-form-data').submit(function(event) {
            event.preventDefault();
            const id = $('#task-id').val();
            const name = $('#task-name').val();
            const status = $('#task-status').val();
            const project_id = $('#task-project').val();
            const weight = $('#task-weight').val();
            if (id === '') {
                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: {action: 'tambah_task', name: name, status: status, project_id: project_id, weight: weight},
                    success: function(data) {
                        if (data === 'Task berhasil ditambahkan!") {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "index.php",
                    data: {action: 'edit_task', id: id, name: name, status: status, project_id: project_id, weight: weight},
                    success: function(data) {
                        if (data === 'Task berhasil diedit!") {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>