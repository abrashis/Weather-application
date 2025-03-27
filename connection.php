<?php
$serverName = "localhost";
$userName = "root";
$password = "";

$conn = mysqli_connect($serverName, $userName, $password);
if (!$conn) {
    echo json_encode(['error' => 'Database connection failed.']);
    exit();
}

$createDatabase = "CREATE DATABASE IF NOT EXISTS AshisWeatherApp";
if (!mysqli_query($conn, $createDatabase)) {
    echo json_encode(['error' => 'Failed to create database.']);
    exit();
}

mysqli_select_db($conn, 'AshisWeatherApp');

$createTable = "CREATE TABLE IF NOT EXISTS weatherdetail (
    CityName VARCHAR(50) NOT NULL,
    Temperature INT NOT NULL,
    WindSpeed FLOAT NOT NULL,
    Humidity FLOAT NOT NULL,
    Pressure FLOAT NOT NULL,
    MainWeatherCondition VARCHAR(30) NOT NULL,
    WeatherCondition VARCHAR(50) NOT NULL,
    Direction FLOAT NOT NULL,
    Date VARCHAR(50) NOT NULL
);";

if (!mysqli_query($conn, $createTable)) {
    echo json_encode(['error' => 'Failed to create table.']);
    exit();
}

$city = isset($_GET['q']) ? $_GET['q'] : "Biratnagar";
$selectAllData = "SELECT * FROM weatherdetail WHERE CityName = '$city'";
$result = mysqli_query($conn, $selectAllData);

if (!$result || mysqli_num_rows($result) == 0) {
    $apikey = "d49fcb4b919ef990a1ab81e6c6e252d7";
    $url = "https://api.openweathermap.org/data/2.5/weather?units=metric&appid=$apikey&q=" . urlencode($city);
    $response = file_get_contents($url);

    if ($response === false) {
        echo json_encode(['error' => 'Failed to fetch weather data.']);
        exit();
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Invalid JSON response from weather API.']);
        exit();
    }

    if (isset($data['name'])) {
        $CityName = $data['name'];
        $Temperature = $data['main']['temp'];
        $WindSpeed = $data['wind']['speed'];
        $Humidity = $data['main']['humidity'];
        $Pressure = $data['main']['pressure'];
        $icon = $data['weather'][0]['icon'];
        $WeatherCondition = $data['weather'][0]['description'];
        $Direction = $data['wind']['deg'];

        $insertData = "INSERT INTO weatherdetail (CityName, Temperature, WindSpeed, Humidity, Pressure, MainWeatherCondition, WeatherCondition, Direction)
                       VALUES ('$CityName', '$Temperature', '$WindSpeed', '$Humidity', '$Pressure', '$icon', '$WeatherCondition', '$Direction')";

        mysqli_query($conn, $insertData);
    } else {
        echo json_encode(['error' => 'City not found.']);
        exit();
    }
}

$result = mysqli_query($conn, $selectAllData);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

header('Content-Type: application/json');
echo json_encode($rows);
?>
