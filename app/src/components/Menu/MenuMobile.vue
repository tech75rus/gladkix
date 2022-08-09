<template>
  <div class="menu">
    <router-link to="/">Статьи</router-link> |
    <router-link to="/about" class="">Работы</router-link>
    <router-link to="/1" class="">Технологии</router-link>
    <router-link to="/2" class="">Контакты</router-link>
  </div>
</template>

<script>
export default {
  name: 'Menu',
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
