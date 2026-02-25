<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$reporter_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Submit New Complaint</title>
    <link rel="stylesheet" href="assets/style.css" />
    <style>
         body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #4CAF50, #2E7D32);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }
        .form-container {
            max-width: 700px;
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            margin: 20px;
        }
        h2 {
            margin-bottom: 20px;
            color: #4CAF50;
            text-align: center;
            font-size: 1.8rem;
        }
        input[type=text], select, textarea, input[type=datetime-local], input[type=file] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            font-size: 1rem;
        }
        textarea {
            min-height: 120px;
        }
        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #555;
        }
        .char-count {
            font-size: 0.85rem;
            color: #777;
            text-align: right;
            margin-top: -16px;
            margin-bottom: 12px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #2E7D32;
        }
        .success-msg {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        .error-msg {
            color: red;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        #map {
            height: 300px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            border-radius: 8px;
        }
    </style>
    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet.js JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <div class="form-container">
        <h2>Submit a New Complaint</h2>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-msg">Complaint submitted successfully!</div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="error-msg"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="api/submit_complaint.php" method="POST" enctype="multipart/form-data" novalidate>
            <label for="title">Complaint Title <sup style="color:red">*</sup></label>
            <input type="text" id="title" name="title" maxlength="100" placeholder="Enter title" required>

            <label for="description">Description <sup style="color:red">*</sup></label>
            <textarea id="description" name="description" maxlength="1000" placeholder="Describe the issue..." required></textarea>
            <div class="char-count" id="desc-count">0 / 1000</div>

            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="Infrastructure" selected>Infrastructure</option>
                <option value="Public Safety">Public Safety</option>
                <option value="Utilities">Utilities</option>
                <option value="Environmental">Environmental</option>
            </select>

            <label for="incident_date">Incident Date & Time</label>
            <input type="datetime-local" id="incident_date" name="incident_date">

            <label for="location">Location <sup style="color:red">*</sup></label>
            <input type="text" id="location" name="location" placeholder="Where did this happen?" required>

            <!-- Geo-Tagging Map -->
            <label for="geo_tagging">Pin Location on Map <sup style="color:red">*</sup></label>
            <div id="map"></div>
            <input type="hidden" id="latitude" name="latitude" required>
            <input type="hidden" id="longitude" name="longitude" required>

            <label for="evidence">Upload Evidence (Images or PDF) - Optional</label>
            <input type="file" id="evidence" name="evidence" accept="image/*,application/pdf">

            <button type="submit">Submit Complaint</button>
        </form>
    </div>

    <script>
        const desc = document.getElementById('description');
        const descCount = document.getElementById('desc-count');

        desc.addEventListener('input', () => {
            descCount.textContent = `${desc.value.length} / 1000`;
        });

        // Initialize the map
        const map = L.map('map').setView([12.971598, 77.594566], 13); // Default view to Bangalore, India
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker;
        map.on('click', function (e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>
</body>
</html>
