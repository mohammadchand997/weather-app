<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            text-align: center;
            margin: 20px 0;
        }

        header h1 {
            font-size: 32px;
            color: #444;
        }

        form {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            margin: 0;
            width: 300px;
        }

        form button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        form button:hover {
            background-color: #555;
        }

        #current-weather {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 600px;
        }

        #current-weather h1 {
            font-size: 80px;
            margin: 0;
            color: #444;
        }

        #current-weather .city {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
            color: #555;
        }

        #current-weather .details p {
            font-size: 18px;
            margin: 5px 0;
            color: #666;
        }

        .forecast-container {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            width: 90%;
            max-width: 1000px;
        }

        .forecast-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 120px;
            margin: 5px;
        }

        .forecast-card h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }

        .forecast-card img {
            width: 50px;
            margin-bottom: 5px;
        }

        .forecast-card .temp {
            font-size: 22px;
            margin: 5px 0;
            font-weight: bold;
            color: #444;
        }

        .forecast-card p {
            font-size: 14px;
            margin: 0;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <h1>Weather App</h1>
    </header>

    <!-- Search Form -->
    <form action="{{ route('weather.index') }}" method="GET">
        <input type="text" name="city" placeholder="Search city..." value="{{ $city ?? '' }}">
        <button type="submit">Search</button>
    </form>

    <!-- Current Weather -->
    @if(isset($weatherData['error']))
        <p style="color: red; text-align: center;">{{ $weatherData['error'] }}</p>
    @else
        <div id="current-weather">
            <h1>{{ round($weatherData['list'][0]['main']['temp']) }}°</h1>
            <div class="city">{{ $city ?? 'Unknown City' }}</div>
            <div class="details">
                <p>{{ \Carbon\Carbon::now()->format('l, jS F Y') }}</p>
                <p>{{ ucfirst($weatherData['list'][0]['weather'][0]['description']) }}</p>
                <p>Humidity: {{ $weatherData['list'][0]['main']['humidity'] }}%</p>
                <p>Wind: {{ $weatherData['list'][0]['wind']['speed'] }} m/s</p>
            </div>
        </div>

        <!-- Forecast -->
        <div class="forecast-container">
            @foreach($weatherData['list'] as $key => $forecast)
                @if($key % 8 == 0) <!-- Shows daily forecast -->
                    <div class="forecast-card">
                        <h3>{{ \Carbon\Carbon::parse($forecast['dt_txt'])->format('D, jS') }}</h3>
                        <img src="http://openweathermap.org/img/wn/{{ $forecast['weather'][0]['icon'] }}@2x.png" alt="Weather icon">
                        <div class="temp">{{ round($forecast['main']['temp']) }}°</div>
                        <p>{{ ucfirst($forecast['weather'][0]['description']) }}</p>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</body>
</html>
