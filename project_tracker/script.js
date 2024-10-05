let currentEditId = null;
let currentEditType = null;

document.getElementById('form').addEventListener('submit', function(event) {
    event.preventDefault();
    saveItem();
});

function toggleForm(action, id = null) {
    const formPanel = document.getElementById('formPanel');
    const formTitle = document.getElementById('formTitle');
    const projectField = document.getElementById('projectField');
    const weightField = document.getElementById('weightField');

    if (action === 'addProject') {
        formTitle.innerText = 'Add Project';
        projectField.style.display = 'none';
        weightField.style.display = 'none';
        currentEditType = 'project';
    } else if (action === 'addTask') {
        formTitle.innerText = 'Add Task';
        projectField.style.display = 'block';
        weightField.style.display = 'block';
        populateProjectOptions();
        currentEditType = 'task';
    } else if (action === 'editProject') {
        formTitle.innerText = 'Edit Project';
        projectField.style.display = 'none';
        weightField.style.display = 'none';
        currentEditType = 'project';
        populateForm(id);
    } else if (action === 'editTask') {
        formTitle.innerText = 'Edit Task';
        projectField.style.display = 'block';
        weightField.style.display = 'block';
        populateProjectOptions();
        currentEditType = 'task';
        populateForm(id);
    }

    currentEditId = id;
    formPanel.style.display = 'block';
}

function populateProjectOptions() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {populate_project_options: true},
        success: function(data) {
            const projectSelect = document.getElementById('project');
            projectSelect.innerHTML = '';
            data.forEach(project => {
                const option = document.createElement('option');
                option.value = project.id;
                option.text = project.name;
                projectSelect.appendChild(option);
            });
        }
    });
}

function populateForm(id) {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {populate_form: true, id: id},
        success: function(data) {
            document.getElementById('name').value = data.name;
            document.getElementById('status').value = data.status;
            if (currentEditType === 'task') {
                document.getElementById('project').value = data.projectId;
                document.getElementById('weight').value = data.weight;
            }
        }
    });
}

function saveItem() {
    const name = document.getElementById('name').value;
    const status = document.getElementById('status').value;

    if (currentEditType === 'project') {
        if (currentEditId) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {action: 'edit_project', id: currentEditId, name: name, status: status},
                success: function(data) {
                    renderProjects();
                    document.getElementById('formPanel').style.display = 'none';
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {action: 'tambah_project', name: name, status: status},
                success: function(data) {
                    renderProjects();
                    document.getElementById('formPanel').style.display = 'none';
                }
            });
        }
    } else if (currentEditType === 'task') {
        const projectId = document.getElementById('project').value;
        const weight = document.getElementById('weight').value;

        if (currentEditId) {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {action: 'edit_task', id: currentEditId, name: name, status: status, projectId: projectId, weight: weight},
                success: function(data) {
                    renderProjects();
                    document.getElementById('formPanel').style.display = 'none';
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: "index.php",
                data: {action: 'tambah_task', name: name, status: status, projectId: projectId, weight: weight},
                success: function(data) {
                    renderProjects();
                    document.getElementById('formPanel').style.display = 'none';
                }
            });
        }
    }
}

function deleteItem() {
    if (currentEditType === 'project') {
        $.ajax({
            type: "POST",
            url: "index.php",
            data: {action: 'hapus_project', id: currentEditId},
            success: function(data) {
                renderProjects();
                document.getElementById('formPanel').style.display = 'none';
            }
        });
    } else if (currentEditType === 'task') {
        $.ajax({
            type: "POST",
            url: "index.php",
 data: {action: 'hapus_task', id: currentEditId},
            success: function(data) {
                renderProjects();
                document.getElementById('formPanel').style.display = 'none';
            }
        });
    }
}

function renderProjects() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {render_projects: true},
        success: function(data) {
            const projectsContainer = document.getElementById('projects');
            projectsContainer.innerHTML = '';

            data.forEach(project => {
                const projectElement = document.createElement('div');
                projectElement.className = 'project';
                projectElement.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${project.name}</strong> (${project.status}) - ${project.progress.toFixed(2)}%
                        </div>
                        <div>
                            <button class="btn btn-sm btn-info" onclick="toggleForm('editProject', ${project.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="toggleForm('deleteProject', ${project.id})">Delete</button>
                        </div>
                    </div>
                    <div class="ml-3">
                        ${project.tasks.map(task => `
                            <div class="task d-flex justify-content-between align-items-center">
                                <div>${task.name} (${task.status}) - Bobot: ${task.weight}</div>
                                <div>
                                    <button class="btn btn-sm btn-info" onclick="toggleForm('editTask', ${task.id})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="toggleForm('deleteTask', ${task.id})">Delete</button>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
                projectsContainer.appendChild(projectElement);
            });
        }
    });
}