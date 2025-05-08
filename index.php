<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geofencing Example with Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
        #map { height: 500px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        #status { padding: 10px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Demo Geofencing</h1>
    <div id="map"></div>
    <div id="status">Checking location...</div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        const locations = [
            { name: "DBL ISTS", lat: 14.73990, lng: 120.98754, radius: 50},
            { name: "WL MAIN", lat: 14.737567, lng: 120.99018, radius: 50},
            { name: "WL BIGNAY", lat: 14.747861, lng: 121.00390, radius: 50 }
        ];
        
        const map = L.map('map').setView([locations[0].lat, locations[0].lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        
        locations.forEach(loc => {
            L.circle([loc.lat, loc.lng], {
                color: 'blue',
                fillColor: '#99ccff',
                fillOpacity: 0.3,
                radius: loc.radius
            }).addTo(map).bindPopup(loc.name);
        });
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                const userMarker = L.marker([userLat, userLng]).addTo(map);
                userMarker.bindPopup("You are here").openPopup();
                map.setView([userLat, userLng], 13);
                const statusDiv = document.getElementById('status');
                
                const insideGeofence = locations.some(loc => {
                    const distance = map.distance([userLat, userLng], [loc.lat, loc.lng]);
                    return distance <= loc.radius;
                });
                
                if (insideGeofence) {
                    statusDiv.textContent = "You are inside a geofenced area.";
                    statusDiv.classList.add('success');
                } else {
                    statusDiv.textContent = "You are outside all geofenced areas.";
                    statusDiv.classList.add('error');
                }
            }, error => {
                document.getElementById('status').textContent = "Error getting location: " + error.message;
            });
        } else {
            document.getElementById('status').textContent = "Geolocation is not supported by this browser.";
        }
    </script>
</body>
</html>
