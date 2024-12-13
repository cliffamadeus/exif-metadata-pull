<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXIF Plotter</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        .home-flex-container {
            display: flex;
            justify-content: start;
            gap: 2rem;
        }
        .modal-backdrop.show {
            background-color: rgba(0, 0, 0, 0.5);
        }
        .toast-container {
            position: fixed;
            top: 25%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .img-upload-flex {
            display: flex;
            justify-content: center;  
            align-items: center;      
            height: 100vh;            
        }

        #map {
            height: 400px;
        }

        .data-section {
            margin-top: 20px;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./exif-basics.php">Basics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./exif-plotter.php">Plotter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#welcomeModal">Privacy Policy</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container" style="margin-top: 2rem;">
    <div class="row mb-4">
        <!-- Image Upload Section -->
        <div class="col-md-3">
            <button id="clearButton" class="btn btn-secondary" type="button">Reset</button>
            <br>
            <img id="imagePreview" style="margin-top:20px" src="https://www.freeiconspng.com/uploads/no-image-icon-6.png" width="200px">
            <input type="file" id="imageInput" class="form-control" style="width:75%; margin-top:10px;" onchange="handleImageInput(this);" />
        </div>

        <!-- Image Data and Map Section -->
        <div class="col-md-9">
            <div class="container mt-4">
                <!-- Image Data Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Image Data</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- EXIF Data Column -->
                            <div class="col-md-6">
                                <h5>EXIF Data</h5>
                                <hr>
                                <div class="col">
                                <h6>Coordinates</h6>
                                <div class="row">
                                    <div class="col">
                                        <strong>Latitude:</strong>
                                        <p id="latitudeData">No GPS data</p>
                                    </div>
                                    <div class="col">
                                        <strong>Longitude:</strong>
                                        <p id="longitudeData">No GPS data</p>
                                    </div>
                                </div>

                                </div>
                                <div class="col">

                                    <div class="row">
                                        <div class="col">
                                            <strong>Approximate Location</strong>
                                            <p id="locData">Fetching location...</p>
                                        </div>
                                        <div class="col">
                                            <strong>Altitude (masl):</strong>
                                            <p id="altitudeData">No altitude data</p>
                                        </div>
                                    </div>




                                 
                                </div>
                               
                            </div>

                            <!-- Weather Data Column -->
                            <div class="col-md-6">
                                <h5>History Weather Data</h5>
                                <hr>
                                <div class="col">
                                    
                                    <div class="row">
                                        <div class="col">
                                            <strong>Temperature:</strong>
                                            <p id="tempData">Fetching temperature...</p>
                                        </div>
                                        <div class="col">
                                            <strong>Weather</strong>
                                            <p id="weatherData">Fetching weather data</p>
                                        </div>
                                    </div>


                                    



                                   
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col">
                                            <strong>Humidity</strong>
                                            <p id="humData">Fetching humidity data</p>
                                        </div>
                                        <div class="col">
                                        <strong>Wind Speed</strong>
                                        <p id="wind_speedData">Fetching wind speed data</p>
                                        </div>
                                    </div>
                                   
                                </div>
                              
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
        <small class="text-body-secondary" id="dateTimeData"></small>
      </div>

                </div>

                <!-- Map Section -->
                <div id="map" class="mt-4" style="height: 400px;"></div>
            </div>
        </div>

    </div>
</div>

    <script>
        // Leaflet map setup
        const myMap = L.map('map').setView([11.635230398105993, 123.70790316297406], 5);  // Initial center position

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | CAFE'
        }).addTo(myMap);

        // Marker object
        let marker;

        // Clear button event listener
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById("clearButton").addEventListener("click", function() {
                document.getElementById("imageInput").value = "";
                document.getElementById("imagePreview").src = "https://www.freeiconspng.com/uploads/no-image-icon-6.png";
                document.getElementById("latitudeData").textContent = "No GPS data";
                document.getElementById("longitudeData").textContent = "No GPS data";
                document.getElementById("altitudeData").textContent = "No altitude data";
                document.getElementById("dateTimeData").textContent = "No date data";
                document.getElementById("locData").textContent = "Fetching location...";
                document.getElementById("tempData").textContent = "Fetching temperature...";
                document.getElementById("weatherData").textContent = "Fetching weather...";
                document.getElementById("humData").textContent = "Fetching humidity...";
                document.getElementById("wind_speedData").textContent = "Fetching wind speed...";

                if (marker) {
                    myMap.removeLayer(marker);  // Remove previous marker
                }
            });
        });

        // Handle image input and extract EXIF data
        function handleImageInput(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("imagePreview").src = e.target.result;
                    EXIF.getData(input.files[0], function() {
                        var lat = EXIF.getTag(this, "GPSLatitude");
                        var lon = EXIF.getTag(this, "GPSLongitude");
                        var date = EXIF.getTag(this, "DateTimeOriginal");

                        if (lat && lon) {
                            var latRef = EXIF.getTag(this, "GPSLatitudeRef") || "N";
                            var lonRef = EXIF.getTag(this, "GPSLongitudeRef") || "W";
                            lat = (lat[0] + lat[1] / 60 + lat[2] / 3600) * (latRef === "N" ? 1 : -1);
                            lon = (lon[0] + lon[1] / 60 + lon[2] / 3600) * (lonRef === "W" ? -1 : 1);

                            document.getElementById("latitudeData").textContent = lat;
                            document.getElementById("longitudeData").textContent = lon;

                            // Fetch weather data using coordinates
                            fetchWeatherData(lat, lon);

                            // Add marker to map
                            if (marker) {
                                myMap.removeLayer(marker);  // Remove previous marker
                            }

                            // Create a new marker with a popup that will open automatically
                            marker = L.marker([lat, lon]).addTo(myMap)
                                .bindPopup(`<b>Latitude:</b> ${lat}<br><b>Longitude:</b> ${lon}<br><b>Date:</b> ${date}`)
                                .openPopup();  // Open the popup immediately

                            // Center the map on the new coordinates
                            myMap.setView([lat, lon], 12);  // Set zoom level to 12 (can adjust as needed)
                        } else {
                            document.getElementById("latitudeData").textContent = "No GPS data";
                            document.getElementById("longitudeData").textContent = "No GPS data";
                        }

                        // Capture and display altitude (if available)
                        var altitude = EXIF.getTag(this, "GPSAltitude");
                        if (altitude !== undefined) {
                            document.getElementById("altitudeData").textContent = altitude;
                        } else {
                            document.getElementById("altitudeData").textContent = "No altitude data";
                        }

                        // Capture and display date
                        if (date) {
                            document.getElementById("dateTimeData").textContent = date;
                        } else {
                            document.getElementById("dateTimeData").textContent = "No date data";
                        }
                    });
                };
                reader.readAsDataURL(input.files[0]);
            }
        }


        // Fetch weather data based on latitude and longitude
        function fetchWeatherData(lat, lon) {
            var apiKey = 'b32786b808e33a9e3d7051cd1a10ad6f';
            var apiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.cod === 200) {
                        var weatherDescription = data.weather[0].description;
                        var temperature = data.main.temp;
                        var humidity = data.main.humidity;
                        var windSpeed = data.wind.speed;
                        var locName = data.name;

                        // Update the HTML elements with the fetched weather data
                        document.getElementById("locData").textContent = locName;
                        document.getElementById("tempData").textContent = `${temperature} °C`;
                        document.getElementById("weatherData").textContent = weatherDescription;
                        document.getElementById("humData").textContent = `${humidity}%`;
                        document.getElementById("wind_speedData").textContent = `${windSpeed} m/s`;
                    } else {
                        document.getElementById("weatherInfo").innerHTML = `<p>Error fetching weather data</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching weather data:', error);
                    document.getElementById("weatherInfo").innerHTML = `<p>Error fetching weather data</p>`;
                });
        }
    </script>
</body>
</html>