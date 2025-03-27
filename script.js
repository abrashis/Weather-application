// const apikey = "bba0f10a17043384450e6d5d91b96d96";
// const apiurl =
//   "https://api.openweathermap.org/data/2.5/weather?units=metric&q=";


const apiurl = "http://localhost/WA/connection.php?q=";

async function getWeather(city) {

       const response = await fetch(apiurl + city);
        const data = await response.json();

console.log(data);

            document.getElementById("city").innerText = data[0].CityName;
            document.getElementById("temp").innerText = data[0].Temperature + "°C";
            document.getElementById("wind").innerText = data[0].WindSpeed + " km/h";
            document.getElementById("humidity").innerText = data[0].Humidity + "%";
            document.getElementById("pressure").innerText = data[0].Pressure + " hPa";
            document.getElementById("direction").innerText = data[0].Direction + "°C";
            document.getElementById("weather-description").innerText = data[0].WeatherCondition;
            
            const weatherCode = "01d"; 
            const iconurl = `https://openweathermap.org/img/wn/${weatherCode}@2x.png`;
            document.getElementById("weather-icon").src = weather[0].Iconurl;
         
}

document.querySelector(".search button").addEventListener("click", () => {
    const city = document.querySelector(".search input").value.trim();
    if (city) {
        getWeather(city);
    } else {
        alert("Please enter valid city. Your spelling may wrong");
    }
});

getWeather("Biratnagar");


function updateDateTime() {
  const now = new Date();
  const time = now.toLocaleTimeString([], {
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  });
  const day = now.toLocaleDateString("en-US", { weekday: "long" });
  const date = now.toLocaleDateString("en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
  document.getElementById("time").innerText = time;
  document.getElementById("day").innerText = day;
  document.getElementById("date").innerText = date;
}

setInterval(updateDateTime, 1000);