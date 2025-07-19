<?php
session_start();
include 'config.php';

// Fetch users
$users = $conn->query("SELECT * FROM residents");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">VDM Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="manage_users.php">User Management</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_volunteers.php">Volunteer Management</a></li>
                <li class="nav-item"><a class="nav-link" href="problem_reports.php">Problem Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resource Management</a></li>
                <li class="nav-item"><a class="nav-link" href="announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="generate_reports.php">Reports & Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Manage Users -->
<div class="container mt-4">
    <h2 class="mb-4">Manage Users</h2>
    
    <!-- Add User Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fa fa-user-plus"></i> Add User</button>
    
    <!-- User Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Occupation</th>
                <th>Household Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTable">
            <?php while ($row = $users->fetch_assoc()) { ?>
                <tr id="row_<?php echo $row['resident_id']; ?>">
                    <td><?php echo $row['resident_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['address']; ?></td>
                    <td><?php echo $row['occupation']; ?></td>
                    <td><?php echo $row['household_size']; ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $row['resident_id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <input type="text" id="name" class="form-control mb-2" placeholder="Name" required>
                    <input type="email" id="email" class="form-control mb-2" placeholder="Email" required>
                    <input type="text" id="phone" class="form-control mb-2" placeholder="Phone">
                    <textarea id="address" class="form-control mb-2" placeholder="Address" required></textarea>
                    <input type="text" id="occupation" class="form-control mb-2" placeholder="Occupation">
                    <input type="number" id="household_size" class="form-control mb-2" placeholder="Household Size" min="1">
                    <input type="password" id="password" class="form-control mb-2" placeholder="Password" required>
                    <button type="submit" class="btn btn-primary w-100">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AJAX Script -->
<script>
$(document).ready(function () {
    // Add User
    $("#addUserForm").submit(function (e) {
        e.preventDefault();
        $.post("user_actions.php", {
            action: "add",
            name: $("#name").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            address: $("#address").val(),
            occupation: $("#occupation").val(),
            household_size: $("#household_size").val(),
            password: $("#password").val()
        }, function (response) {
            alert(response); // Show response message
            location.reload();
        });
    });

    // Delete User
    $(".deleteBtn").click(function () {
        let userId = $(this).data("id");
        if (confirm("Are you sure you want to delete this user?")) {
            $.post("user_actions.php", { action: "delete", id: userId }, function (response) {
                alert(response); // Show response message
                $("#row_" + userId).remove();
            });
        }
    });
});

</script>

</body>
</html>
