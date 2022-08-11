<template>
  <div class="menu-desktop">
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
a {
  color: #000;
  text-decoration: none;
}
.router-link-exact-active {
  color: red;
}
.menu-desktop {
  //position: fixed;
  display: flex;
  justify-content: center;
  align-items: center;
  top: 0;
  left: 0;
  width: 100%;
  height: 40px;
  border: #000 solid 1px;
  z-index: 4;
  a {
    margin: 0 5px;
  }
}
@media all and (max-width: 600px) {
  .menu-desktop {
    display: none;
  }
}
</style>
