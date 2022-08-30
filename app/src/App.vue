<template>
  <MenuDesktop></MenuDesktop>
  <MenuMobile></MenuMobile>
  <router-view class="wrapper"/>
</template>

<script>
import MenuDesktop from "@/components/Menu/MenuDesktop";
import MenuMobile from "@/components/Menu/MenuMobile";

export default {
  name: 'App',
  components: {
    MenuMobile,
    MenuDesktop,
  }
}
</script>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: Nunito,serif;
  font-weight: 400;
}
h2 {
  font-weight: 600;
}
a {
  color: #2a2a2a;
  text-decoration: none;
}
#app {
  font-family: Inter, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
.wrapper {
  margin: 90px auto 30px auto;
  max-width: 1198px;
  min-width: 320px;
  padding: 0 15px;
}
@font-face {
  font-family: "Nunito";
  src: local("Nunito"), url("./assets/fonts/Nunito/static/Nunito-Regular.ttf") format("truetype");
}

@media (max-width: 600px) {
  .wrapper {
    margin: 20px auto 30px auto;
  }
}
</style>
