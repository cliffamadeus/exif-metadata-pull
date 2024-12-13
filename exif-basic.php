<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXIF Plotter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        .main {
            width: 95%;
            margin-left: 3%;
            justify-content: center;
        }
        .image-preview {
            width: 200px;
        }
        .image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
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

<div class="main" style="margin-top: .25rem;">
    <div class="card mb-4">
        <div class="card-header text-bg-light">
            <h5 class="card-title mb-0">Metadata Processing</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Image and Input Section -->
                <div class="col-md-4 image-section">
                    <img id="imagePreview" class="image-preview" src="https://www.freeiconspng.com/uploads/no-image-icon-6.png" alt="Image Preview" />
                    <input type="file" id="imageInput" style="margin-top:15px;" class="form-control" onchange="handleImageInput(this);" />
                    <div class="text-center mt-3">
                        <small id="dateTimeData">No date data</small>
                    </div>
                </div>

                <!-- Data Section (EXIF & Weather) -->
                <div class="col-md-8 data-section">
                    <div class="row">
                        <!-- EXIF Data Column -->
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <input type="hidden" id="latitudeData" value="No GPS data">
                                </div>
                                <div class="col">
                                    <input type="hidden" id="longitudeData" value="No GPS data">
                                </div>
                            </div>
                            


                            <h5>History Weather Data</h5>
                            <hr>
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
                            <div class="row">
                                <div class="col">
                                    <strong>Temperature:</strong>
                                    <p id="tempData">Fetching temperature...</p>
                                    <div class="col">
                                    <strong>Humidity</strong>
                                    <p id="humData">Fetching humidity data</p>
                                    </div>
                                    <div class="col">
                                        <strong>Wind Speed</strong>
                                        <p id="wind_speedData">Fetching wind speed data</p>
                                    </div>
                                </div>
                                <div class="col" style="justify-content: center;">
                                    <img id="weatherIcon" src="https://play-lh.googleusercontent.com/-8wkZVkXugyyke6sDPUP5xHKQMzK7Ub3ms2EK9Jr00uhf1fiMhLbqX7K9SdoxbAuhQ" alt="" style="width: 150px; height: 150px;" />
                                    <h4 id="weatherData">Fetching weather data</h4>
                                </div>
                            </div>
                            <div class="row">
                               
                            </div>
                        </div>

                        <button id="clearButton" class="btn btn-secondary" type="button" onclick="resetImage()">Reset</button>

                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div id="map" class="mt-4" style="height: 400px;"></div>
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

    // Handle image input and extract EXIF data
    function handleImageInput(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("imagePreview").src = e.target.result;

                // Enable reset button when data is available
                document.getElementById("clearButton").disabled = false; // Enable reset button

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
                            .bindPopup(`<b>Latitude:</b> ${lat}<br><b>Longitude:</b> ${lon}<br><b>Date:</b> ${formatDate(date)}`)
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
                        const readableDate = formatDate(date);
                        document.getElementById("dateTimeData").textContent = readableDate;
                    } else {
                        document.getElementById("dateTimeData").textContent = "No date data";
                    }

                });
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Reset the image and data, and disable the reset button
    // Reset the image and data, and disable the reset button
    function resetImage() {
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

        // Reset the weather icon to a default image (or leave it empty if no image is to be shown)
        document.getElementById("weatherIcon").src = "https://play-lh.googleusercontent.com/-8wkZVkXugyyke6sDPUP5xHKQMzK7Ub3ms2EK9Jr00uhf1fiMhLbqX7K9SdoxbAuhQ"; // Default clear sky icon or a placeholder

        if (marker) {
            myMap.removeLayer(marker);  // Remove previous marker
        }

        myMap.setView([11.635230398105993, 123.70790316297406], 5);

        // Disable the reset button when there's no data
        document.getElementById("clearButton").disabled = true;  // Disable reset button
    }


    // Helper function to format the EXIF date string
    function formatDate(date) {
        const dateParts = date.split(" "); 
        const dateFormatted = dateParts[0].replace(/:/g, '-'); 
        const timeFormatted = dateParts[1];

        const isoFormattedDate = `${dateFormatted}T${timeFormatted}`;
        const formattedDate = new Date(isoFormattedDate);

        if (isNaN(formattedDate.getTime())) {
            return "Invalid date";
        } else {
            return formattedDate.toLocaleString('en-US', {
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric', 
                hour: 'numeric', 
                minute: 'numeric', 
                second: 'numeric', 
                hour12: true 
            });
        }
    }

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
                var iconCode = data.weather[0].icon;  // Get the icon code

                // Function to capitalize first letter of each word
                function capitalizeWeatherDescription(description) {
                    return description.split(' ').map(word => {
                        return word.charAt(0).toUpperCase() + word.slice(1);
                    }).join(' ');
                }

                // Capitalize the weather description
                weatherDescription = capitalizeWeatherDescription(weatherDescription);

                // Set weather location, temperature, and other data
                document.getElementById("locData").textContent = locName;
                document.getElementById("tempData").textContent = `${temperature} °C`;
                document.getElementById("weatherData").textContent = weatherDescription;
                document.getElementById("humData").textContent = `${humidity}%`;
                document.getElementById("wind_speedData").textContent = `${windSpeed} m/s`;

                // Set the weather icon using the icon code (100x100)
                var weatherIconUrl = `https://openweathermap.org/img/wn/${iconCode}@4x.png`; // 100x100 icon
                document.getElementById("weatherIcon").src = weatherIconUrl;

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
