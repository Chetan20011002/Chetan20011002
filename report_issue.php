<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_type = $_POST['issue_type'];
    $description = $_POST['description'];
    $location = $_POST['location'];
$stmt = $conn->prepare("INSERT INTO issues (resident_id, issue_type, description, location, status) VALUES (?, ?, ?, ?, 'Pending')");
$stmt->bind_param("isss", $user_id, $issue_type, $description, $location);



    
    if ($stmt->execute()) {
        $success_message = "Issue reported successfully!";
    } else {
        $error_message = "Error reporting issue. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Issue</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Google Maps API (replace `YOUR_API_KEY` with your actual key) -->
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7KZI4jZkAPvVeaxEKvYF62Kf3fFQg44Q&libraries=places"></script> -->
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD3TnLxU0fd-E1UVGWeCbjWxJYlURb8Yc4&libraries=places"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCN18xtOCVOtiPecagxJknzB977gJ4fJCw&libraries=places&callback=initMap" async defer></script>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">VDM User</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="user_dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="report_issue.php">Report Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="track_issue.php">Track Issue</a></li>
                <li class="nav-item"><a class="nav-link" href="volunteer_register.php">Volunteer Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="view_announcements.php">Announcements</a></li>
                <li class="nav-item"><a class="nav-link" href="user_profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="user_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Report Issue Form -->
<div class="container mt-4">
    <h2 class="mb-4">Report an Issue</h2>
    <?php if (!empty($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; } ?>
    <?php if (!empty($success_message)) { echo "<div class='alert alert-success'>$success_message</div>"; } ?>
    
    <form method="POST">
        <div class="mb-3">
            <label for="issue_type" class="form-label">Issue Type</label>
            <select class="form-control" name="issue_type" required>
                <option value="Road Repair">Road Repair</option>
                <option value="Water Shortage">Water Shortage</option>
                <option value="Electricity Issue">Electricity Issue</option>
                <option value="Sanitation Problem">Sanitation Problem</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4" required></textarea>
        </div>
        <div class="mb-3">
    <label for="location" class="form-label">Location</label>
    <input id="location-input" name="location" class="form-control" placeholder="Search for a location" required>
</div>
<div id="map" style="height: 300px; margin-bottom: 20px;"></div>

        <button type="submit" class="btn btn-primary w-100">Submit Report</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let map;
    let marker;
    function initMap() {
        const defaultCenter = { lat: 12.9716, lng: 77.5946 }; // Default to Bangalore

        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultCenter,
            zoom: 13,
        });

        const input = document.getElementById("location-input");
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo("bounds", map);

        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                alert("No details available for input: '" + place.name + "'");
                return;
            }

            // Center map and add marker
            map.setCenter(place.geometry.location);
            map.setZoom(17);

            if (marker) marker.setMap(null);
            marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location,
            });
        });
    }

    window.initMap = initMap;
</script>

<script>
    window.onload = initMap;
</script>

</body>
</html>
