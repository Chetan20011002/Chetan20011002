<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// --- Fast2SMS function ---
function sendSMS($mobile, $message) {
    $fields = array(
        "sender_id" => "FSTSMS",
        "message" => $message,
        "language" => "english",
        "route" => "p", // Use 't' for transactional after DLT approval
        "numbers" => $mobile,
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: yCLTNPQxawd0O5hiWbRBV6pMHI8f2tZJlcSKY1mXog7DUkuEGvGbjTvz27oRaUSQAek0gxF9uXVKiyZq", // Replace with actual API key
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// Fetch problem reports
$reports = $conn->query("SELECT p.issue_id, r.name AS resident_name, p.issue_type, p.description, p.status, p.location, p.created_at, p.assigned_to FROM issues p JOIN residents r ON p.resident_id = r.resident_id ORDER BY p.created_at DESC");
if (!$reports) {
    die("Problem Reports Query Failed: " . $conn->error);
}

// Fetch active volunteers
$volunteers = $conn->query("SELECT v.volunteer_id, r.name FROM volunteers v JOIN residents r ON v.resident_id = r.resident_id WHERE v.status = 'Active'");
if (!$volunteers) {
    die("Volunteer Query Failed: " . $conn->error);
}

$volunteer_list = [];
while ($vol = $volunteers->fetch_assoc()) {
    $volunteer_list[] = $vol;
}

// Assign volunteer to issue and insert task
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_volunteer'])) {
    $issue_id = $_POST['issue_id'];
    $volunteer_id = $_POST['volunteer_id'];

    // Update issues table
    $stmt = $conn->prepare("UPDATE issues SET assigned_to = ? WHERE issue_id = ?");
    $stmt->bind_param("ii", $volunteer_id, $issue_id);
    $stmt->execute();

    // Insert into tasks table
    $task_description = "Resolve the issue #" . $issue_id;
    $task_status = 'Pending';
    $insert_task = $conn->prepare("INSERT INTO tasks (volunteer_id, issue_id, task_description, task_status) VALUES (?, ?, ?, ?)");
    $insert_task->bind_param("iiss", $volunteer_id, $issue_id, $task_description, $task_status);
    $insert_task->execute();

    // Send SMS to admin
    $adminPhone = "9876543210"; // Replace with your real number
    $smsMessage = "Volunteer assigned to Issue ID #$issue_id. Please check the admin panel.";
    sendSMS($adminPhone, $smsMessage);

    header("Location: problem_reports.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Problem Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                <li class="nav-item"><a class="nav-link" href="manage_users.php">User Management</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_volunteers.php">Volunteer Management</a></li>
                <li class="nav-item"><a class="nav-link active" href="problem_reports.php">Problem Reports</a></li>
                <li class="nav-item"><a class="nav-link" href="manage_resources.php">Resource Management</a></li>
                <li class="nav-item"><a class="nav-link" href="announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="generate_reports.php">Reports & Analytics</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Problem Reports Table -->
<div class="container mt-4">
    <h2 class="mb-4">Problem Reports</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Resident</th>
                <th>Issue Type</th>
                <th>Description</th>
                <th>Location</th>
                <th>Status</th>
                <th>Reported Date</th>
                <th>Assign Volunteer</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $reports->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['issue_id']; ?></td>
                    <td><?php echo $row['resident_name']; ?></td>
                    <td><?php echo $row['issue_type']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <?php echo $row['location']; ?><br>
                        <?php if (!empty($row['location'])) { ?>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($row['location']); ?>" target="_blank">View Map</a>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $row['status']; ?>
                    </td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <?php if (empty($row['assigned_to'])) { ?>
                            <form method="post">
                                <input type="hidden" name="issue_id" value="<?php echo $row['issue_id']; ?>">
                                <div class="input-group">
                                    <select name="volunteer_id" class="form-select" required>
                                        <option value="">Select Volunteer</option>
                                        <?php foreach ($volunteer_list as $vol) { ?>
                                            <option value="<?php echo $vol['volunteer_id']; ?>"><?php echo $vol['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" name="assign_volunteer" class="btn btn-primary">Assign</button>
                                </div>
                            </form>
                        <?php } else { ?>
                            Assigned
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
