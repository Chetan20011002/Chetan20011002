<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = "";

// Check if the user is already a volunteer
$check_volunteer = $conn->query("SELECT * FROM volunteers WHERE resident_id = $user_id");
$is_volunteer = $check_volunteer->num_rows > 0;
$skills_value = "";
$availability_value = "";

if ($is_volunteer) {
    $vol_data_query = $conn->query("SELECT skills, availability FROM volunteers WHERE resident_id = $user_id");
    if ($vol_data_query && $vol_data_query->num_rows > 0) {
        $vol_data = $vol_data_query->fetch_assoc();
        $skills_value = htmlspecialchars($vol_data['skills']);
        $availability_value = htmlspecialchars($vol_data['availability']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$is_volunteer) {
    $skills = $_POST['skills'];
    $availability = $_POST['availability'];

    $stmt = $conn->prepare("INSERT INTO volunteers (resident_id, skills, availability, status) VALUES (?, ?, ?, 'Active')");
    $stmt->bind_param("iss", $user_id, $skills, $availability);

    if ($stmt->execute()) {
        $success_message = "You have successfully registered as a volunteer!";
        $is_volunteer = true;
    } else {
        $error_message = "Error in registration. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
        .container { max-width: 800px; margin-top: 40px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">VDM User</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="report_issue.php">Report Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="track_issue.php">Track Issue</a></li>
                <li class="nav-item"><a class="nav-link active" href="volunteer_register.php">Volunteer Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="view_announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="user_profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="user_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4 text-center">Volunteer Registration</h2>
    <?php if (!empty($error_message)) echo "<div class='alert alert-danger'>$error_message</div>"; ?>
    <?php if (!empty($success_message)) echo "<div class='alert alert-success'>$success_message</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="skills" class="form-label">Skills</label>
            <input type="text" class="form-control" name="skills" value="<?php echo $skills_value; ?>" <?php echo $is_volunteer ? 'disabled' : 'required'; ?>>
        </div>
        <div class="mb-3">
            <label for="availability" class="form-label">Availability</label>
            <select class="form-control" name="availability" <?php echo $is_volunteer ? 'disabled' : 'required'; ?>>
                <option value="Weekdays" <?php echo ($availability_value == 'Weekdays') ? 'selected' : ''; ?>>Weekdays</option>
                <option value="Weekends" <?php echo ($availability_value == 'Weekends') ? 'selected' : ''; ?>>Weekends</option>
                <option value="Flexible" <?php echo ($availability_value == 'Flexible') ? 'selected' : ''; ?>>Flexible</option>
            </select>
        </div>
        <?php if (!$is_volunteer) { ?>
            <button type="submit" class="btn btn-primary w-100">Register as Volunteer</button>
        <?php } else { ?>
            <button class="btn btn-secondary w-100" disabled>You are already a Volunteer</button>
        <?php } ?>
    </form>

    <?php if ($is_volunteer): ?>
        <hr>
        <h4 class="mb-3">Assigned Issues</h4>
        <?php
        $volunteer_id = $conn->query("SELECT volunteer_id FROM volunteers WHERE resident_id = $user_id")->fetch_assoc()['volunteer_id'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
            $issue_id = $_POST['issue_id'];
            $status = $_POST['status'];
            $stmt = $conn->prepare("UPDATE issues SET status = ? WHERE issue_id = ? AND assigned_to = ?");
            $stmt->bind_param("sii", $status, $issue_id, $volunteer_id);
            $stmt->execute();
            echo "<script>window.location.href='volunteer_register.php';</script>";
            exit();
        }

        $assigned_issues = $conn->prepare("SELECT i.issue_id, r.name AS resident_name, i.issue_type, i.description, i.status, i.location, i.created_at FROM issues i JOIN residents r ON i.resident_id = r.resident_id WHERE i.assigned_to = ?");
        $assigned_issues->bind_param("i", $volunteer_id);
        $assigned_issues->execute();
        $issue_result = $assigned_issues->get_result();

        if ($issue_result->num_rows > 0):
        ?>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Resident</th>
                    <th>Issue Type</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Reported On</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $issue_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['issue_id'] ?></td>
                        <td><?= $row['resident_name'] ?></td>
                        <td><?= $row['issue_type'] ?></td>
                        <td><?= $row['description'] ?></td>
                        <td><?= $row['location'] ?></td>
                        <td>
                            <span class="badge <?= ($row['status'] == 'Resolved') ? 'bg-success' : (($row['status'] == 'In Progress') ? 'bg-warning' : 'bg-danger') ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="issue_id" value="<?= $row['issue_id'] ?>">
                                <select name="status" class="form-select">
                                    <option value="Pending" <?= ($row['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                    <option value="In Progress" <?= ($row['status'] == 'In Progress') ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Resolved" <?= ($row['status'] == 'Resolved') ? 'selected' : '' ?>>Resolved</option>
                                </select>
                                <button name="update_status" class="btn btn-primary btn-sm mt-1">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No issues assigned to you currently.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>