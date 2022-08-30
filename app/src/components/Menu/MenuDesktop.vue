<template>
  <div class="menu-desktop show-menu-desktop">
    <router-link to="/">Статьи</router-link>
    <router-link to="/works" class="">Работы</router-link>
    <router-link to="/technologies" class="">Технологии</router-link>
    <router-link to="/contact" class="">Контакты</router-link>
  </div>
</template>

<script>
export default {
  name: 'Menu'
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/shadow";

a {
  color: #000;
  text-decoration: none;
}
.router-link-exact-active {
  color: red;
}
.menu-desktop {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  background-color: #fff;
  width: 100%;
  height: 70px;
  z-index: 4;
  a {
    margin: 0 15px;
  }
}
@media all and (max-width: 600px) {
  .menu-desktop {
    display: none;
  }
}
</style>
