<template>
  <div class="card p-3">
    <div class="d-flex">
      <h6 class="flex-grow-1">{{ user.name }}</h6>
    </div>
    <div class="d-flex flex-column temp mt-5 mb-3">
      <h1 class="mb-0 font-weight-bold" id="heading">
        {{ weather["temp"] ?? 0 }}&deg; C
      </h1>
      <span>
        {{ weather["weather"] ?? "Cloudy" }}
        <img
          :src="
            'http://openweathermap.org/img/wn/' +
            (weather['icon'] ?? '10d') +
            '.png'
          "
          alt="weather_icon"
      /></span>
    </div>
    <div class="d-flex">
      <div class="temp-details flex-grow-1">
        <p class="my-1">
          <span>Feels Like: {{ weather["feels_like"] ?? 0 }} &deg; C</span>
        </p>
        <p class="my-1">
          <span> Humidity: {{ weather["humidity"] ?? 0 }}% </span>
        </p>
      </div>
      <div>
        <p
          style="cursor: pointer"
          data-toggle="modal"
          data-target="#weatherDetails"
          @click="handleDetailsClick"
        >
          <u>Details</u>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { weathersApi } from "@/network/api/home";

export default {
  name: "WeatherCard",
  props: ["user"],
  data: () => {
    return {
      weather: {},
    };
  },
  mounted() {
    this.fetchWeathers();
  },
  methods: {
    async fetchWeathers() {
      try {
        const { data } = await weathersApi(this.user["email"]);
        if (data.code === 200) {
          this.weather = data.data.weather;
        }
      } catch (e) {
        console.error(e);
      }
    },
    handleDetailsClick() {
      this.$emit("onDetailsClick", this.user);
    },
  },
};
</script>

<style scoped>
html,
body {
  height: 100%;
}

* {
  padding: 0;
  margin: 0;
}

body {
  background-color: #a5a5b1;
  display: grid;
  place-items: center;
}

.card {
  background-color: #fff;
  border-radius: 50px;
  color: #6f707d;
  font-family: "Marcellus", serif;
}

#heading {
  font-size: 55px;
  color: #2b304d;
}

.temp {
  place-items: center;
}

.temp-details > p > span,
.grey {
  color: #727476;
  font-size: 15px;
}

.fa {
  color: #a5a5b1;
}

*:focus {
  outline: none;
}
</style>
