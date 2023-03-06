<template>
  <div
    class="modal fade"
    id="weatherDetails"
    tabindex="-1"
    role="dialog"
    aria-labelledby="weatherDetailsLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="weatherDetailsLabel">
            {{ user.name ?? "" }} Weather Details
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row">
              <div class="col-sm-12">
                <div id="card" class="weater">
                  <div class="city-selected">
                    <article>
                      <div class="info">
                        <div class="night">
                          <b>{{ weather.weather ?? "Cloudy" }}</b>
                          <img
                            class="icon"
                            :src="
                              'http://openweathermap.org/img/wn/' +
                              (weather['icon'] ?? '10d') +
                              '.png'
                            "
                            alt="weather_icon"
                          />
                        </div>

                        <div class="temp">{{ weather.temp ?? 0 }}&deg; C</div>
                      </div>
                    </article>
                    <div class="row">
                      <div class="col-md-6 text-center" style="color: #fff">
                        <b
                          ><p>Min: {{ weather.temp_min ?? 0 }}&deg; C</p></b
                        >
                      </div>
                      <div class="col-md-6 text-center" style="color: #fff">
                        <b
                          ><p>
                            Feels Like: {{ weather.feels_like ?? 0 }}&deg; C
                          </p></b
                        >
                      </div>
                      <div class="col-md-6 text-center" style="color: #fff">
                        <b
                          ><p>Max: {{ weather.temp_max ?? 0 }}&deg; C</p></b
                        >
                      </div>
                      <div class="col-md-6 text-center" style="color: #fff">
                        <b
                          ><p>Wind: {{ weather.wind ?? 0 }}</p></b
                        >
                      </div>
                    </div>
                  </div>

                  <div class="days">
                    <div class="row row-no-gutter">
                      <div
                        class="col-md-3"
                        v-for="(details, key) in weatherDetails"
                        :key="key"
                      >
                        <div class="day">
                          <h1>{{ details.date ?? "12/11/2022" }}</h1>
                          <p>
                            {{ details.temp ?? 0 }}&deg; C
                            <img
                              :src="
                                'http://openweathermap.org/img/wn/' +
                                (details['icon'] ?? '10d') +
                                '.png'
                              "
                              alt="weather_icon"
                            />
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            @click="onModalClose"
          >
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { weatherDetailsApi } from "@/network/api/home";
import { defineComponent } from "vue";
import type { WeatherInterface } from "@/interfaces/WeatherInterface";
import type { WeatherDetailsInterface } from "@/interfaces/WeatherDetailsInterface";

export default defineComponent({
  name: "WeatherDetailsModal.vue",
  props: ["user"],
  data() {
    return {
      weatherDetails: new Array<WeatherDetailsInterface>(),
      weather: {} as WeatherInterface,
    };
  },
  watch: {
    user(newData: { email: any }) {
      if (!newData.email) return;
      this.fetchWeatherDetail();
    },
  },
  methods: {
    onModalClose(): void {
      this.$emit("onModalClose");
    },
    async fetchWeatherDetail(): Promise<void> {
      try {
        const { data } = await weatherDetailsApi(this.user["email"]);
        if (data.code === 200) {
          this.weatherDetails = data.data.details;
          this.weather = data.data.weather;
        }
      } catch (e) {
        console.error(e);
      }
    },
  },
});
</script>
<style scoped>
body {
  background: #f2f2f2;
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 95px 0;
  font-family: "Source Sans Pro", sans-serif;
  font-weight: 200;
}

.row-no-gutter {
  margin-right: 0;
  margin-left: 0;
}

.row-no-gutter [class*="col-"] {
  padding-right: 0;
  padding-left: 0;
}

#card {
  background: #fff;
  position: relative;

  -webkit-box-shadow: 0px 1px 10px 0px rgba(207, 207, 207, 1);
  -moz-box-shadow: 0px 1px 10px 0px rgba(207, 207, 207, 1);
  box-shadow: 0px 1px 10px 0px rgba(207, 207, 207, 1);

  -webkit-transition: all 0.5s ease;
  -moz-transition: all 0.5s ease;
  -ms-transition: all 0.5s ease;
  -o-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

.city-selected {
  position: relative;
  overflow: hidden;
  min-height: 200px;
  background: #3d6aa2;
}

article {
  position: relative;
  z-index: 2;
  color: #fff;
  padding: 20px;

  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-direction: row;
  -ms-flex-direction: row;
  flex-direction: row;
  -webkit-flex-wrap: wrap;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-justify-content: space-between;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-align-content: flex-start;
  -ms-flex-line-pack: start;
  align-content: flex-start;
  -webkit-align-items: flex-start;
  -ms-flex-align: start;
  align-items: flex-start;
}

.info .city,
.night {
  font-size: 24px;
  font-weight: 200;
  position: relative;

  -webkit-order: 0;
  -ms-flex-order: 0;
  order: 0;
  -webkit-flex: 0 1 auto;
  -ms-flex: 0 1 auto;
  flex: 0 1 auto;
  -webkit-align-self: auto;
  -ms-flex-item-align: auto;
  align-self: auto;
}

.info .city:after {
  content: "";
  width: 15px;
  height: 2px;
  background: #fff;
  position: relative;
  display: inline-block;
  vertical-align: middle;
  margin-left: 10px;
}

.city span {
  color: #fff;
  font-size: 13px;
  font-weight: bold;

  text-transform: lowercase;
  text-align: left;
}

.night {
  font-size: 15px;
  text-transform: uppercase;
}

.icon {
  width: 84px;
  height: 84px;
  -webkit-order: 0;
  -ms-flex-order: 0;
  order: 0;
  -webkit-flex: 0 0 auto;
  -ms-flex: 0 0 auto;
  flex: 0 0 auto;
  -webkit-align-self: center;
  -ms-flex-item-align: center;
  align-self: center;

  overflow: visible;
}

.temp {
  font-size: 73px;
  display: block;
  position: relative;
  font-weight: bold;
}

svg {
  color: #fff;
  fill: currentColor;
}

.wind svg {
  width: 18px;
  height: 18px;
  margin-top: 20px;
  margin-right: 10px;
  vertical-align: bottom;
}

.wind span {
  font-size: 13px;
  text-transform: uppercase;
}

.city-selected:hover figure {
  opacity: 0.4;
}

figure {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
  background-position: center;
  background-size: cover;
  opacity: 0.1;
  z-index: 1;

  -webkit-transition: all 0.5s ease;
  -moz-transition: all 0.5s ease;
  -ms-transition: all 0.5s ease;
  -o-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

.days .row [class*="col-"]:nth-child(2) .day {
  border-width: 0 1px 0 1px;
  border-style: solid;
  border-color: #eaeaea;
}

.days .row [class*="col-"] {
  -webkit-transition: all 0.5s ease;
  -moz-transition: all 0.5s ease;
  -ms-transition: all 0.5s ease;
  -o-transition: all 0.5s ease;
  transition: all 0.5s ease;
}

.days .row [class*="col-"]:hover {
  background: #eaeaea;
}

.day {
  padding: 10px 0px;
  text-align: center;
}

.day h1 {
  font-size: 14px;
  text-transform: uppercase;
  margin-top: 10px;
}

.day svg {
  color: #000;
  width: 32px;
  height: 32px;
}
</style>
