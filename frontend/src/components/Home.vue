<script>
import {usersApi} from "@/network/api/home";
import WeatherCard from "@/components/WeatherCard.vue";
import {collect} from "collect.js";
import WeatherDetailsModal from "./WeatherDetailsModal.vue";

export default {
  components: { WeatherDetailsModal, WeatherCard },
  data: () => ({
    users: [],
    chunkedUser: [],
    detailsUser: {},
    limit: 7,
    skip: 0,
  }),

  mounted() {
    this.fetchUsers();
    this.getNextUser();
  },

  methods: {
    async fetchUsers() {
      try {
        const {data} = await usersApi();
        if (data.code === 200) {
          this.users.push(...data.data.users);
          this.chunkedUser = collect(this.users).take(this.limit).all();
        }
      } catch (e) {
        console.error(e);
      }
    },
    getNextUser() {
      window.onscroll = () => {
        let bottomOfWindow =
            document.documentElement.scrollTop + window.innerHeight ===
            document.documentElement.offsetHeight;
        if (bottomOfWindow) {
          this.skip += this.limit;
          this.chunkedUser.push(
              ...collect(this.users).skip(this.skip).take(this.limit).all()
          );
        }
      }
    },
    handleDetailsClick(user) {
      this.detailsUser = user;
    },
  },
};
</script>

<template>
  <section>
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div
          class="col-12 col-md-5 col-sm-12 col-xs-12 mt-2"
          v-for="(user, key) in chunkedUser"
          :key="key"
        >
          <WeatherCard :user="user" @onDetailsClick="handleDetailsClick" />
        </div>
      </div>
      <WeatherDetailsModal :user="detailsUser" />
    </div>
  </section>
</template>
