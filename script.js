const apiKey = 'b32786b808e33a9e3d7051cd1a10ad6f'; // Replace with your OpenWeather API key

function fetchWeather(lat, lon) {
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
}

document.getElementById('latitudeInput').addEventListener('change', () => {
    const lat = document.getElementById('latitudeInput').value;
    const lon = document.getElementById('longitudeInput').value;
    fetchWeather(lat, lon);
});

document.getElementById('longitudeInput').addEventListener('change', () => {
    const lat = document.getElementById('latitudeInput').value;
    const lon = document.getElementById('longitudeInput').value;
    fetchWeather(lat, lon);
});

function handleImageInput(input) {
    const img = document.getElementById("imagePreview");
    img.style.display = 'block';
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

        fetchWeather(lat, lon);

        // Get and display the DateTimeOriginal
        var dateTimeOriginal = EXIF.getTag(this, "DateTimeOriginal");
        if (dateTimeOriginal) {
            const formattedDate = formatDate(dateTimeOriginal);
            document.getElementById("dateTime").innerText = formattedDate;
        } else {
            document.getElementById("dateTime").innerText = "No date available";
        }
    });
}

function formatDate(dateTimeString) {
    // Split the dateTimeString into date and time parts
    const [date, time] = dateTimeString.split(' ');

    // Format the date part
    const [year, month, day] = date.split(':');
    const formattedDate = `${day}-${month}-${year}`;

    // Return the formatted date and time
    return `${formattedDate} ${time}`;
}

// Function to clear latitude, longitude, date, weather information, and image input
function clearCoordinates() {
    document.getElementById('latitudeInput').value = '';
    document.getElementById('longitudeInput').value = '';
    document.getElementById('lat').innerText = '';
    document.getElementById('lon').innerText = '';
    document.getElementById('dateTime').innerText = 'No date available';
    document.getElementById('weatherInfo').innerHTML = '';
    
    // Clear the image input and hide the image preview
    const imageInput = document.getElementById('imageInput');
    imageInput.value = '';
    const img = document.getElementById('imagePreview');
    img.style.display = 'none';
    img.src = '';
}

document.getElementById('clearButton').addEventListener('click', clearCoordinates);

// Fetch weather on page load if lat and lon are already set
window.addEventListener('load', () => {
    const lat = document.getElementById('latitudeInput').value;
    const lon = document.getElementById('longitudeInput').value;
    if (lat && lon) {
        fetchWeather(lat, lon);
    }
});
