document.getElementById('getWeather').addEventListener('click', function() {
    const lat = document.getElementById('latitudeInput').value;
    const lon = document.getElementById('longitudeInput').value;
    const apiKey = 'b32786b808e33a9e3d7051cd1a10ad6f';

    if (lat && lon) {
        fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric`)
            .then(response => response.json())
            .then(data => {
                const weatherInfo = `
                    <p><strong>Location:</strong> ${data.name}</p>
                    <p><strong>Temperature:</strong> ${data.main.temp}°C</p>
                    <p><strong>Weather:</strong> ${data.weather[0].description}</p>
                    <p><strong>Humidity:</strong> ${data.main.humidity}%</p>
                    <p><strong>Wind Speed:</strong> ${data.wind.speed} m/s</p>
                `;
                document.getElementById('weatherInfo').innerHTML = weatherInfo;
            })
            .catch(error => {
                document.getElementById('weatherInfo').innerHTML = `<p>Error fetching weather data</p>`;
                console.error('Error:', error);
            });
    } else {
        document.getElementById('weatherInfo').innerHTML = `<p>Please enter both latitude and longitude</p>`;
    }
});

function handleImageInput(input) {
    var img = document.getElementById("imagePreview");
    const reader = new FileReader();
    reader.onload = (e) => {
        img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);

    EXIF.getData(input.files[0], function () {
        var gpsData = EXIF.getTag(this, "GPSLatitude") || [];
        var lat = gpsData[0] + gpsData[1] / 60 + gpsData[2] / 3600;
        var gpsDataLon = EXIF.getTag(this, "GPSLongitude") || [];
        var lon = gpsDataLon[0] + gpsDataLon[1] / 60 + gpsDataLon[2] / 3600;

        document.getElementById("lat").innerText = lat;
        document.getElementById("lon").innerText = lon;

        // Bind the values from the spans to the input fields
        document.getElementById('latitudeInput').value = lat;
        document.getElementById('longitudeInput').value = lon;
    });
}