<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            margin-top: 20px;
        }

        #search-container {
            margin-bottom: 20px;
        }

        #search-container input {
            padding: 10px;
            font-size: 16px;
            width: 250px;
            margin-right: 10px;
        }

        #search-container button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        #search-container button:hover {
            background-color: #0056b3;
        }

        #current-weather {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: 300px;
        }

        #forecast-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .forecast-card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Weather App</h1>
    </header>

    <div id="search-container">
        <input type="text" id="city-input" placeholder="Enter city">
        <button id="search-btn">Search</button>
    </div>

    <div id="current-weather">
        <h2 id="city-name">City Name</h2>
        <p id="temp">--°C</p>
        <p id="description">Weather description</p>
        <p>Humidity: <span id="humidity">--%</span></p>
        <p>Wind: <span id="wind">-- m/s</span></p>
        <p id="day">Day</p>
        <p id="time">Time</p>
    </div>

    <div id="forecast-container">
        <!-- Forecast cards will be dynamically added here -->
    </div>

    <script>
        // Include the JavaScript code here
        const apiKey = "{{ env('WEATHER_API_KEY') }}";
        const baseApiUrl = "{{ env('WEATHER_API_URL') }}";

        document.getElementById('search-btn').addEventListener('click', function () {
            const city = document.getElementById('city-input').value.trim();
            if (city) {
                fetchWeather(city);
                fetchForecast(city);
            } else {
                alert('Please enter a city name.');
            }
        });

        function fetchWeather(city) {
            const weatherApiUrl = `${baseApiUrl}?q=${city}&units=metric&appid=${apiKey}`;

            fetch(weatherApiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('City not found or API error.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.main && data.weather) {
                        displayWeatherData(data);
                    } else {
                        throw new Error('Invalid data format received from the API.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching weather data:', error);
                    alert(error.message);
                });
        }

        function fetchForecast(city) {
            const forecastApiUrl = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&cnt=10&units=metric&appid=${apiKey}`;

            fetch(forecastApiUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('City not found or API error.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.list) {
                        displayForecastData(data.list);
                    } else {
                        throw new Error('Invalid forecast data format.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching forecast data:', error);
                    alert(error.message);
                });
        }

        function displayWeatherData(data) {
            document.getElementById('city-name').innerText = data.name || 'N/A';
            document.getElementById('temp').innerText = data.main?.temp
                ? `${Math.round(data.main.temp)}°C`
                : 'N/A';
            document.getElementById('humidity').innerText = data.main?.humidity
                ? `${data.main.humidity}%`
                : 'N/A';
            document.getElementById('wind').innerText = data.wind?.speed
                ? `${data.wind.speed} m/s`
                : 'N/A';
            document.getElementById('description').innerText = data.weather?.[0]?.description
                ? data.weather[0].description
                : 'N/A';

            const date = new Date();
            document.getElementById('time').innerText = date.toLocaleTimeString();
            document.getElementById('day').innerText = date.toLocaleDateString('en-US', { weekday: 'long' });
        }

        function displayForecastData(forecastList) {
            const forecastContainer = document.getElementById('forecast-container');
            forecastContainer.innerHTML = ''; // Clear previous forecast data

            forecastList.forEach((forecast, index) => {
                if (index % 8 === 0) {
                    const forecastCard = document.createElement('div');
                    forecastCard.className = 'forecast-card';

                    forecastCard.innerHTML = `
                        <p>${new Date(forecast.dt_txt).toLocaleDateString('en-US', { weekday: 'short' })}</p>
                        <p>${forecast.weather[0].description}</p>
                        <p>${Math.round(forecast.main.temp)}°C</p>
                    `;

                    forecastContainer.appendChild(forecastCard);
                }
            });
        }
    </script>
</body>
</html>
