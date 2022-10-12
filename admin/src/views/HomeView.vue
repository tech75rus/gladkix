<template>
  <div class="home">
    <h1>GOODLAVKA</h1>
    <button @click="$router.push('/login')">Login</button>
  </div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
import {host} from "../service/host";

export default {
  name: 'Home',
  data() {
    return {
      token: ''
    }
  },
  created() {
    // axios.get(host + '/admin/main', {
    //   headers: {
    //     'token': localStorage.getItem('token')
    //   }
    // }).then(() => {
    //   this.$store.dispatch('actionLoader');
    //   this.$store.dispatch('actionLoginIn');
    // }).catch(() => {
    //   this.$router.push('/login');
    //   this.$store.dispatch('actionLoader');
    // });
  },
}
</script>

<style scoped lang="scss">
button {
  margin: 5px 0;
}
</style>
