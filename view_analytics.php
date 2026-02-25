<!DOCTYPE html>
<html>
<head>
    <title>Complaint Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="barChart"></canvas>
    <script>
        fetch("api/analytics.php")
        .then(res => res.json())
        .then(data => {
            new Chart(document.getElementById("barChart"), {
                type: 'bar',
                data: {
                    labels: data.categories,
                    datasets: [{ label: 'Complaints', data: data.counts, backgroundColor: '#007bff' }]
                }
            });
        });
    </script>
</body>
</html>