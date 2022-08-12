<template>
  <Menu></Menu>
  <MenuMobile></MenuMobile>
  <router-view/>
</template>

<script>
import Menu from "@/components/Menu";
import MenuMobile from "@/components/Menu/MenuMobile";
export default {
  name: 'App',
  components: {
    MenuMobile,
    Menu
  }
}
</script>

<style lang="scss">
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
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
</style>
