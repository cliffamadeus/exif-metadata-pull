<?php
// Include config file
require_once "config.php";

// Processing form data when form is submitted
$form_submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Prepare an insert statement
    $sql = "INSERT INTO records (record_lat, record_lon, record_date,  record_loc, record_temp, record_weather, record_hum, record_wind_speed) 
            VALUES (:record_lat, :record_lon, :record_date, :record_loc, :record_temp, :record_weather, :record_hum, :record_wind_speed)";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":record_lat", $param_lat);
        $stmt->bindParam(":record_lon", $param_lon);
        $stmt->bindParam(":record_date", $param_date);
        $stmt->bindParam(":record_loc", $param_loc);
        $stmt->bindParam(":record_temp", $param_temp);
        $stmt->bindParam(":record_weather", $param_weather);
        $stmt->bindParam(":record_hum", $param_hum);
        $stmt->bindParam(":record_wind_speed", $param_wind_speed);

        // Set parameters
        $param_lat = $_POST['lat'];
        $param_lon = $_POST['lon'];
        $param_date = $_POST['date'];
        $param_loc = $_POST['loc'];
        $param_temp = $_POST['temp'];
        $param_weather = $_POST['weather'];
        $param_hum = $_POST['hum'];
        $param_wind_speed = $_POST['wind_speed'];

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {

            $form_submitted = true;

        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    unset($stmt);
}

// Close connection
unset($pdo);
?>
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
    </style>
</head>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./exif-weather.php">Weather</a>
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

<body>
    <div class="container" style="margin-top: 2rem;">
        <div class="row mb-4">
            <div class="col-md-4">
                <img id="imagePreview" class="img-fluid" src="https://www.freeiconspng.com/uploads/no-image-icon-6.png" width="200px">
            </div>
            <div class="col-md-8">
                <h5 class="card-title">Upload Image</h5>
                <br>
                <input type="file" id="imageInput" class="form-control" onchange="handleImageInput(this);" />
                <div class="mt-3">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group mb-3">
                            <label for="latitudeInput">Latitude</label>
                            <input type="text" class="form-control" id="latitudeInput" name="lat" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label for="longitudeInput">Longitude</label>
                            <input type="text" class="form-control" id="longitudeInput" name="lon" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label for="dateTime">Datetime</label>
                            <input type="text" class="form-control" id="dateTime" name="date" readonly disabled>
                        </div>
                        <div id="weatherInfo" class="mt-3"></div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Save Data
                        </button>
                        <button id="clearButton" class="btn btn-secondary" type="button">Clear</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Details?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to save the data?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="submitForm()">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="welcomeModal" tabindex="-1" aria-labelledby="welcomeModalLabel" aria-hidden="true" data-bs-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="welcomeModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6><b>Information We Collect</b></h6>
                    <p>We collect EXIF metadata from uploaded images, which may include camera settings, date, time, and GPS data. We may also collect personal information you provide, such as your name and email address.</p>
                    <h6><b>How We Use Your Information</b></h6>
                    <p>We use your information to process EXIF data, improve our services, and communicate with you.</p>
                    <h6><b>Data Sharing</b></h6>
                    <p>We do not sell your information. We may share data with service providers and comply with legal obligations.</p>
                    <h6><b>Your Rights</b></h6>
                    <p>You have rights regarding your personal information, including access, correction, and deletion. Contact us to exercise these rights.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Agree</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModalElement = document.getElementById('welcomeModal');
        var myModal = new bootstrap.Modal(myModalElement, { keyboard: false });

        document.getElementById('modalTriggerBtn').addEventListener('click', function() {
            myModal.show();
        });

        document.querySelector('.btn-close').addEventListener('click', function() {
            myModal.hide();
        });

        document.querySelector('.btn-secondary').addEventListener('click', function() {
            myModal.hide();
        });

        myModal.show();
    });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("clearButton").addEventListener("click", function() {
        document.getElementById("imageInput").value = "";
        document.getElementById("imagePreview").src = "https://www.freeiconspng.com/uploads/no-image-icon-6.png";
        document.getElementById("latitudeInput").value = "";
        document.getElementById("longitudeInput").value = "";
        document.getElementById("dateTime").value = "";
        document.getElementById("weatherInfo").innerHTML = "";
        document.getElementById("latitudeInput").disabled = true;
        document.getElementById("longitudeInput").disabled = true;
        document.getElementById("dateTime").disabled = true;
    });
});

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

                    document.getElementById("latitudeInput").value = lat;
                    document.getElementById("longitudeInput").value = lon;
                    document.getElementById("latitudeInput").disabled = false;
                    document.getElementById("longitudeInput").disabled = false;

                    // Fetch weather data using coordinates
                    fetchWeatherData(lat, lon);
                } else {
                    document.getElementById("latitudeInput").value = "No GPS data";
                    document.getElementById("longitudeInput").value = "No GPS data";
                }

                if (date) {
                    document.getElementById("dateTime").value = date;
                    document.getElementById("dateTime").disabled = false;
                } else {
                    document.getElementById("dateTime").value = "No date data";
                }
            });
        };
        reader.readAsDataURL(input.files[0]);
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
                var weatherInfoHtml = `
                    <div class="form-group mb-3">
                        <label for="loc">Location</label>
                        <input type="text" class="form-control" id="loc" name="loc" value="${locName}" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="temp">Temperature</label>
                        <input type="text" class="form-control" id="temp" name="temp" value="${temperature} °C" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="weather">Weather</label>
                        <input type="text" class="form-control" id="weather" name="weather" value="${weatherDescription}" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hum">Humidity</label>
                        <input type="text" class="form-control" id="hum" name="hum" value="${humidity}%" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="wind_speed">Wind Speed</label>
                        <input type="text" class="form-control" id="wind_speed" name="wind_speed" value="${windSpeed} m/s" readonly>
                    </div>
                `;
                document.getElementById("weatherInfo").innerHTML = weatherInfoHtml;
            } else {
                document.getElementById("weatherInfo").innerHTML = `<p>Error fetching weather data</p>`;
            }
        })
        .catch(error => {
            console.error('Error fetching weather data:', error);
            document.getElementById("weatherInfo").innerHTML = `<p>Error fetching weather data</p>`;
        });
}

function submitForm() {
    document.querySelector('form').submit();
}

    </script>
 <!-- Toast HTML -->
 <div class="toast-container">
        <div id="successToast" class="toast text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                New record added successfully!
            </div>
        </div>
    </div>
 <?php if ($form_submitted): ?>
    <script type="text/javascript">
        var successToastEl = document.getElementById('successToast');
        var successToast = new bootstrap.Toast(successToastEl);
        successToast.show();
        document.getElementById('facultyForm').reset();
    </script>
    <?php endif; ?>
</body>
</html>
