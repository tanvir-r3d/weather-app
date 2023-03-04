<script>
import {usersApi, weathersApi} from "@/network/api/home";

export default {
  data: () => ({
    users: [],
    weathers: [],
  }),

  mounted() {
    this.fetchUsers();
  },

  methods: {
    async fetchUsers() {
      try {
        const {data} = await usersApi();
        if (data.code === 200) {
          this.users.push(...data.data.users);
        }
      } catch (e) {
        console.error(e);
      }
    },
    async fetchWeathers(limit = 5, offset = 0) {
      try {
        const {data} = await weathersApi(limit, offset);
        if (data.code === 200) {
          this.weathers.push(...data.data.weathers);
        }
      } catch (e) {
        console.error(e);
      }
    },
  },
};
</script>

<template>
  <section>

    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-12 col-md-3 col-sm-12 col-xs-12 m-1" v-for="(user, key) in users" :key="key">
          <div class="card p-3">
            <div class="d-flex">
              <h6 class="flex-grow-1">{{ user.name }}</h6>
            </div>
            <div class="d-flex flex-column temp mt-5 mb-3">
              <h1 class="mb-0 font-weight-bold" id="heading"> {{ weathers[key]['temp'] }}&deg; C </h1>
              <span>{{ weathers[key]['weather'] }}  <img
                  :src="'http://openweathermap.org/img/wn/'+weathers[key]['icon']+'.png'"></span>
            </div>
            <div class="d-flex">
              <div class="temp-details flex-grow-1">
                <p class="my-1">
                  <span>Feels Like: {{ weathers[key]['feels_like'] }} &deg; C</span>
                </p>
                <p class="my-1">
                  <span> Humidity: {{ weathers[key]['humidity'] }}% </span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
</template>
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Marcellus&display=swap');

html, body {
  height: 100%;
}

* {
  padding: 0px;
  margin: 0px;
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
  font-family: 'Marcellus', serif;
}

#heading {
  font-size: 55px;
  color: #2b304d;
}

.temp {
  place-items: center;
}

.temp-details > p > span, .grey {
  color: #727476;
  font-size: 15px;
}

.fa {
  color: #a5a5b1
}

*:focus {
  outline: none;
}
</style>