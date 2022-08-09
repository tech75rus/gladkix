<template>
  <MenuDesktop></MenuDesktop>
</template>

<script>
import MenuDesktop from "@/components/Menu/MenuDesktop";

export default {
  name: 'Menu',
  components: {
    MenuDesktop
  },
  props: {
    msg: String
  }
}
</script>

<style scoped lang="scss">
a {
  color: #000;
  text-decoration: none;
}
.router-link-exact-active {
  color: red;
}
</style>
