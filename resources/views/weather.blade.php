<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Weather App</a>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-md-6 offset-md-3">
                <form method="POST" action="/weather">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="city" class="form-control" placeholder="Enter city name" required>
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Weather Data -->
        @isset($weather)
            <h2 class="text-center mb-4">Weather for {{ $weather->city->name }}, {{ $weather->city->country }}</h2>
            <div class="row">
                @foreach($weather->list as $forecast)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ date('l, d M Y H:i', strtotime($forecast->dt_txt)) }}</h5>
                                <p class="card-text">
                                    <strong>Temperature:</strong> {{ $forecast->main->temp - 273.15 }} °C<br>
                                    <strong>Condition:</strong> {{ $forecast->weather[0]->description }}<br>
                                    <strong>Humidity:</strong> {{ $forecast->main->humidity }}%<br>
                                    <strong>Wind Speed:</strong> {{ $forecast->wind->speed }} m/s
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @if(session('error'))
                <div class="alert alert-danger text-center">
                    {{ session('error') }}
                </div>
            @endif
        @endisset
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3">
        <p>Weather App &copy; {{ date('Y') }}</p>
    </footer>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
