<template>
  <div class="menu-mobile">
    <router-link to="/" class="menu-text menu-text-hidden">Статьи</router-link>
    <router-link to="/works" class="menu-text menu-text-hidden">Работы</router-link>
    <router-link to="/technologies" class="menu-text menu-text-hidden">Технологии</router-link>
    <router-link to="/contact" class="menu-text menu-text-hidden">Контакты</router-link>
    <button class="menu-click" @click="change">x</button>
  </div>
</template>

<script>
export default {
  name: 'MenuMobile',
  data() {
    return {
      showMenu: false
    }
  },
  methods: {
    change() {
      let menu = document.querySelector('.menu-mobile');
      let menuText = document.querySelectorAll('.menu-text');
      menu.classList.toggle('menu-mobile-show');
      this.showMenu = !this.showMenu;
      for (let text of menuText) {
        if (this.showMenu) {
          text.style.display = 'block';
        } else {
          text.style.display = 'none';
        }
      }
    }
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
.menu-mobile {
  display: none;
}
@media all and (max-width: 600px) {
  .menu-mobile {
    display: flex;
    flex-direction: column;
    align-items: start;
    width: 50px;
    height: 50px;
    padding: 10px;
    border: 1px solid #222;
    position: fixed;
    left: 10px;
    bottom: 2vh;
    border-radius: 5px;
    transition: width .3s, height .3s;
    .menu-text {
      margin: 10px 0;
      display: none;
    }
  }
  .menu-mobile-show {
    height: 96vh;
    width: 200px;
  }
  .menu-click {
    position: absolute;
    bottom: 14px;
    left: 19px;
  }
}
</style>
