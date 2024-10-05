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
    } elseif ($action == 'getProject') {
        $id = $_GET['id'];
        $project = get_project($id);
        echo json_encode($project);
    } elseif ($action == 'getTask') {
        $id = $_GET['id'];
        $task = get_task($id);
        echo json_encode($task);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Project Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f7f7;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            height: 80%;
            border: 1px solid #ccc;
            border-radius: 10px;
            display: flex;
            padding: 20px;
            box-sizing: border-box;
            background-color: #e0ebeb;
        }

        .left-panel,
        .right-panel {
            width: 50%;
            padding: 20px;
            box-sizing: border-box;
        }

        .left-panel {
            border-right: 1px solid #ccc;
        }

        .right-panel {
            padding-left: 40px;
            display: none; /* Initially hidden */
            position: relative; /* For slide animation */
            transition: left 0.3s ease-in-out; /* Add transition effect */
         }

        .button {
            background-color: #e0ebeb;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px 20px;
            margin-right: 10px;
            cursor: pointer;
        }

        .project,
        .task {
            margin: 10px 0;
        }

        .project {
            font-weight: bold;
        }

        .task {
            margin-left: 20px;
        }

        .add-icon,
        .crud-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 1px solid #ccc;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            cursor: pointer;
            margin-left: 10px;
        }

        .form-group {
            margin: 20px 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
        }

        .form-actions .button {
            width: 45%;
        }

        .close-button {
            background-color: #ff6666;
            border: none;
            border-radius: 5px;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
            position: absolute; /* Positioning relative to form panel */
            top: 10px; /* Adjust as needed */
            right: 10px; /* Adjust as needed */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <button class="button" onclick="toggleForm('addProject')">Add Project</button>
            <button class="button" onclick="toggleForm('addTask')">Add Task</button>
            <?php foreach ($projects as $project) { ?>
                <div class="project" onclick="toggleForm('editProject', <?php echo $project['id']; ?>)">
                    <?php echo $project['name']; ?>
                    <div class="add-icon" onclick="toggleForm('addTask', <?php echo $project['id']; ?>)">+</div>
                    <div class="crud-icon" onclick="hapusProject(<?php echo $project['id']; ?>)">X</div>
                    <?php foreach ($tasks[$project['id']] as $task) { ?>
                        <div class="task" onclick="toggleForm('editTask', <?php echo $task['id']; ?>)">
                            <?php echo $task['name']; ?>
                            <div class="crud-icon" onclick="hapusTask(<?php echo $task['id']; ?>)">X</div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="right-panel" id="formPanel">
            <button class="close-button" onclick="closePanel()">X</button>
            <h2 id="formTitle">Add Project/Task</h2>
            <form id="form-data">
                <input type="hidden" id="id" name="id">
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" id="name" name="name">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <input type="text" id="status" name="status">
                </div>
                <div class="form-group" id="project-group">
                    <label for="project_id">Project</label>
                    <select class="form-control" id="project_id" name="project_id">
                        <?php foreach ($projects as $project) { ?>
                            <option value="<?php echo $project['id']; ?>"><?php echo $project['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group" id="weight-group">
                    <label for="weight">Bobot</label>
                    <input type="number" id="weight" name="weight">
                </div>
                <div class="form-actions">
                    <button type="button" class="button" onclick="deleteItem()">Hapus</button>
                    <button type="button" class="button" onclick="saveItem()">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function toggleForm(action, id = null) {
            var formPanel = document.getElementById('formPanel');
            var formTitle = document.getElementById('formTitle');
            var projectGroup = document.getElementById('project-group');
            var weightGroup = document.getElementById('weight-group');
            var idInput = document.getElementById('id');
            var nameInput = document.getElementById('name');
            var statusInput = document.getElementById('status');
            formPanel.style.display = 'block';

            if (action === 'addProject') {
                formTitle.innerText = 'Add Project';
                nameInput.value = '';
                statusInput.value = '';
                idInput.value = '';
                projectGroup.style.display = 'none'; // Hide project selection
                weightGroup.style.display = 'none'; // Hide weight selection
            } else if (action === 'addTask') {
                formTitle.innerText = 'Add Task';
                nameInput.value = '';
                statusInput.value = '';
                idInput.value = '';
                projectGroup.style.display = 'block'; // Show project selection
                weightGroup.style.display = 'block'; // Show weight selection
            } else if (action === 'editProject') {
                formTitle.innerText = 'Edit Project';
                idInput.value = id;
                $.get('index.php?action=getProject&id=' + id, function(data) {
                    var project = JSON.parse(data);
                    nameInput.value = project.name;
                    statusInput.value = project.status;
                    projectGroup.style.display = 'none'; // Hide project selection
                    weightGroup.style.display = 'none'; // Hide weight selection
                });
            } else if (action === 'editTask') {
                formTitle.innerText = 'Edit Task';
                idInput.value = id;
                $.get('index.php?action=getTask&id=' + id, function(data) {
                    var task = JSON.parse(data);
                    nameInput.value = task.name;
                    statusInput.value = task.status;
                    projectGroup.style.display = 'block'; // Show project selection
                    weightGroup.style.display = 'block'; // Show weight selection
                });
            }
        }

        function closePanel() {
            var formPanel = document.getElementById('formPanel');
            formPanel.style.display = 'none'; // Hide the form panel
        }

        function saveItem() {
            var id = document.getElementById('id').value;
            var name = document.getElementById('name').value;
            var status = document.getElementById('status').value;
            var projectId = document.getElementById('project_id').value;
            var weight = document.getElementById('weight').value;
            var action = id ? 'edit_task' : 'tambah_task'; // Determine action based on presence of ID

            $.post('index.php', {
                action: action,
                id: id,
                name: name,
                status: status,
                project_id: projectId,
                weight: weight
            }, function(response) {
                alert(response);
                location.reload(); // Reload page to show updated data
            });
        }

        function deleteItem() {
            var id = document.getElementById('id').value;
            var action = id.includes('task') ? 'hapus_task' : 'hapus_project';

            $.post('index.php', {
                action: action,
                id: id
            }, function(response) {
                alert(response);
                location.reload(); // Reload page to show updated data
            });
        }

        function hapusProject(id) {
            if (confirm("Yakin ingin menghapus project ini?")) {
                $.post('index.php', { action: 'hapus_project', id: id }, function(response) {
                    alert(response);
                    location.reload();
                });
            }
        }

        function hapusTask(id) {
            if (confirm("Yakin ingin menghapus task ini?")) {
                $.post('index.php', { action: 'hapus_task', id: id }, function(response) {
                    alert(response);
                    location.reload();
                });
            }
        }
    </script>
</body>
</html>
