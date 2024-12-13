const apiKey = 'b32786b808e33a9e3d7051cd1a10ad6f'; // Replace with your OpenWeather API key

function fetchWeather(lat, lon, timestamp) {
    if (lat !== '' && lon !== '') {
        let apiUrl;
        if (timestamp) {
            apiUrl = `https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=${lat}&lon=${lon}&dt=${timestamp}&appid=${apiKey}&units=metric`;
        } else {
            apiUrl = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`;
        }

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                let weatherInfo;
                if (timestamp) {
                    weatherInfo = `
                        <div class="form-group mb-3">
                            <label>Date:</label>
                            <input type="text" class="form-control" name="date"  value="${new Date(timestamp * 1000).toLocaleDateString()}" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Temperature:</label>
                            <input type="text" class="form-control"  name="temp" value="${data.current.temp}°C" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Weather:</label>
                            <input type="text" class="form-control" name="weather" value="${data.current.weather[0].description}" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Humidity:</label>
                            <input type="text" class="form-control"  name="hum" value="${data.current.humidity}%" readonly disabled>
                        </div>
                        <div class="form-group">
                            <label>Wind Speed:</label>
                            <input type="text" class="form-control"  name="wind_speed" value="${data.current.wind_speed} m/s" readonly disabled>
                        </div>
                    `;
                } else {
                    weatherInfo = `
                         <div class="form-group mb-3">
                            <label>Date:</label>
                            <input type="text" class="form-control" name="date"  value="${new Date(timestamp * 1000).toLocaleDateString()}" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Temperature:</label>
                            <input type="text" class="form-control"  name="temp" value="${data.current.temp}°C" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Weather:</label>
                            <input type="text" class="form-control" name="weather" value="${data.current.weather[0].description}" readonly disabled>
                        </div>
                        <div class="form-group mb-3">
                            <label>Humidity:</label>
                            <input type="text" class="form-control"  name="hum" value="${data.current.humidity}%" readonly disabled>
                        </div>
                        <div class="form-group">
                            <label>Wind Speed:</label>
                            <input type="text" class="form-control"  name="wind_speed" value="${data.current.wind_speed} m/s" readonly disabled>
                        </div>
                    `;
                }
                document.getElementById('weatherInfo').innerHTML = weatherInfo;
            })
            .catch(error => {
                document.getElementById('weatherInfo').innerHTML = `<p>Error fetching weather data</p>`;
                console.error('Error:', error);
            });
    } else {
        document.getElementById('weatherInfo').innerHTML = `<p>Please enter both latitude and longitude</p>`;
    }
}

function handleImageInput(input) {
    const img = document.getElementById("imagePreview");
    img.style.display = 'block';
    const reader = new FileReader();
    reader.onload = (e) => {
        img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);

    EXIF.getData(input.files[0], function () {
        // Get GPS Latitude and Longitude
        var gpsData = EXIF.getTag(this, "GPSLatitude") || [];
        var lat = gpsData[0] + gpsData[1] / 60 + gpsData[2] / 3600;
        var gpsDataLon = EXIF.getTag(this, "GPSLongitude") || [];
        var lon = gpsDataLon[0] + gpsDataLon[1] / 60 + gpsDataLon[2] / 3600;

        document.getElementById('latitudeInput').value = lat;
        document.getElementById('longitudeInput').value = lon;

        // Get GPS Altitude (elevation)
        var altitude = EXIF.getTag(this, "GPSAltitude");
        if (altitude !== undefined) {
            // Check if altitude is in meters or feet (usually meters)
            document.getElementById('altitudeInput').value = altitude;
        } else {
            document.getElementById('altitudeInput').value = "No altitude data available";
        }

        // Get DateTimeOriginal from EXIF metadata
        var dateTimeOriginal = EXIF.getTag(this, "DateTimeOriginal");
        if (dateTimeOriginal) {
            const formattedDate = formatDate(dateTimeOriginal);
            document.getElementById("dateTime").value = formattedDate;

            const timestamp = new Date(dateTimeOriginal).getTime() / 1000;
            fetchWeather(lat, lon, timestamp);
        } else {
            document.getElementById("dateTime").value = "No date available";
        }
    });
}


function formatDate(dateTimeString) {
    const [date, time] = dateTimeString.split(' ');
    const [year, month, day] = date.split(':');
    const formattedDate = `${day}-${month}-${year}`;
    return `${formattedDate} ${time}`;
}

function clearCoordinates() {
    document.getElementById('latitudeInput').value = '';
    document.getElementById('longitudeInput').value = '';
    document.getElementById('dateTime').value = 'No date available';
    document.getElementById('weatherInfo').innerHTML = '';

    const imageInput = document.getElementById('imageInput');
    imageInput.value = '';
    const img = document.getElementById('imagePreview');
    img.style.display = 'none';
    img.src = '';
}

document.getElementById('clearButton').addEventListener('click', clearCoordinates);

window.addEventListener('load', () => {
    const lat = document.getElementById('latitudeInput').value;
    const lon = document.getElementById('longitudeInput').value;
    if (lat && lon) {
        fetchWeather(lat, lon);
    }
});