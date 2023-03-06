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
      <WeatherDetailsModal
        :user="detailsUser"
        @onModalClose="handleModalClose"
      />
    </div>
  </section>
</template>
<script lang="ts">
import { usersApi } from "@/network/api/home";
import WeatherCard from "@/components/WeatherCard.vue";
import { collect } from "collect.js";
import WeatherDetailsModal from "./WeatherDetailsModal.vue";
import { defineComponent } from "vue";
import type { UserInterface } from "@/interfaces/UserInterface";
export default defineComponent({
  components: { WeatherDetailsModal, WeatherCard },
  data: () => ({
    users: new Array<UserInterface>(),
    chunkedUser: new Array<UserInterface>(),
    detailsUser: {} as UserInterface,
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
        const { data } = await usersApi();
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
      };
    },
    handleDetailsClick(user: UserInterface) {
      this.detailsUser = user;
    },
    handleModalClose() {
      this.detailsUser = {} as UserInterface;
    },
  },
});
</script>
