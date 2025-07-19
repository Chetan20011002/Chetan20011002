<?php
session_start();
include 'config.php';

// Fetch volunteers
$volunteers = $conn->query("SELECT v.volunteer_id, r.name, r.email, r.phone, v.skills, v.availability, v.status 
                            FROM volunteers v 
                            JOIN residents r ON v.resident_id = r.resident_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Volunteers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            transition: 0.3s;
        }
        .dashboard-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">VDM Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_users.php">User Management</a></li>
                <li class="nav-item"><a class="nav-link active" href="manage_volunteers.php">Volunteer Management</a></li>
                <li class="nav-item"><a class="nav-link" href="problem_reports.php">Problem Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resource Management</a></li>
                <li class="nav-item"><a class="nav-link" href="announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="generate_reports.php">Reports & Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Manage Volunteers -->
<div class="container mt-4">
    <h2 class="mb-4">Manage Volunteers</h2>
    
    <!-- Add Volunteer Button -->
    
    <!-- Volunteer Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Skills</th>
                <th>Availability</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="volunteerTable">
            <?php while ($row = $volunteers->fetch_assoc()) { ?>
                <tr id="row_<?php echo $row['volunteer_id']; ?>">
                    <td><?php echo $row['volunteer_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['skills']; ?></td>
                    <td><?php echo $row['availability']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo $row['volunteer_id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AJAX Script -->
<script>


    // Delete Volunteer
    $(".deleteBtn").click(function () {
        let volunteerId = $(this).data("id");
        if (confirm("Are you sure you want to delete this volunteer?")) {
            $.post("volunteer_actions.php", { action: "delete", id: volunteerId }, function (response) {
                $("#row_" + volunteerId).remove();
            });
        }
    });
</script>

</body>
</html>
