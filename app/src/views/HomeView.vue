<template>
  <div class="home">
    <router-link to="/article" class="article gradient-green shadow">
      <h2 class="header">Как подключить NGINX к PHP-FPM</h2>
      <p>Веб-сервер NGINX (в качестве обратного прокси-сервера) обслуживает приложения PHP через протокол FastCGI (в качестве внутреннего сервера приложений). NGINX использует PHP-FPM (FastCGI Process Manager), альтернативную реализацию PHP FastCGI, которая работает в фоновом режиме в качестве демона, слушая запросы CGI. Он поставляется с дополнительными функциями, предназначенными для работы с загруженными веб-сайтами или веб-приложениями, но его можно использовать для сайтов любого размера.</p>
    </router-link>
    <router-link to="/article" class="article gradient-pink shadow">
      <h2 class="header">Генератор цветовых тем</h2>
      <p>Инструмент расширяет палитры из CSS/SCSS/Less-переменных и может быть полезен для карманных проектов без фиксированного дизайна. Я немного расскажу про историю появления и покажу как его можно использовать.</p>
    </router-link>
    <router-link to="/article" class="article gradient-purple shadow">
      <h2 class="header">Как подключить NGINX к PHP-FPM</h2>
      <p>Веб-сервер NGINX (в качестве обратного прокси-сервера) обслуживает приложения PHP через протокол FastCGI (в качестве внутреннего сервера приложений). NGINX использует PHP-FPM (FastCGI Process Manager), альтернативную реализацию PHP FastCGI, которая работает в фоновом режиме в качестве демона, слушая запросы CGI. Он поставляется с дополнительными функциями, предназначенными для работы с загруженными веб-сайтами или веб-приложениями, но его можно использовать для сайтов любого размера.</p>
    </router-link>
    <router-link to="/article" class="article gradient-yellow shadow">
      <h2 class="header">Генератор цветовых тем</h2>
      <p>Инструмент расширяет палитры из CSS/SCSS/Less-переменных и может быть полезен для карманных проектов без фиксированного дизайна. Я немного расскажу про историю появления и покажу как его можно использовать.</p>
    </router-link>
    <router-link to="/article" class="article gradient-orange shadow">
      <h2 class="header">Как подключить NGINX к PHP-FPM</h2>
      <p>Веб-сервер NGINX (в качестве обратного прокси-сервера) обслуживает приложения PHP через протокол FastCGI (в качестве внутреннего сервера приложений). NGINX использует PHP-FPM (FastCGI Process Manager), альтернативную реализацию PHP FastCGI, которая работает в фоновом режиме в качестве демона, слушая запросы CGI. Он поставляется с дополнительными функциями, предназначенными для работы с загруженными веб-сайтами или веб-приложениями, но его можно использовать для сайтов любого размера.</p>
    </router-link>
    <router-link to="/article" class="article gradient-blue shadow">
      <h2 class="header">Генератор цветовых тем</h2>
      <p>Инструмент расширяет палитры из CSS/SCSS/Less-переменных и может быть полезен для карманных проектов без фиксированного дизайна. Я немного расскажу про историю появления и покажу как его можно использовать.</p>
    </router-link>
    <router-link to="/article" class="article gradient-ocean shadow">
      <h2 class="header">Как подключить NGINX к PHP-FPM</h2>
      <p>Веб-сервер NGINX (в качестве обратного прокси-сервера) обслуживает приложения PHP через протокол FastCGI (в качестве внутреннего сервера приложений). NGINX использует PHP-FPM (FastCGI Process Manager), альтернативную реализацию PHP FastCGI, которая работает в фоновом режиме в качестве демона, слушая запросы CGI. Он поставляется с дополнительными функциями, предназначенными для работы с загруженными веб-сайтами или веб-приложениями, но его можно использовать для сайтов любого размера.</p>
    </router-link>
    <router-link to="/article" class="article gradient-red shadow">
      <h2 class="header">Генератор цветовых тем</h2>
      <p>Инструмент расширяет палитры из CSS/SCSS/Less-переменных и может быть полезен для карманных проектов без фиксированного дизайна. Я немного расскажу про историю появления и покажу как его можно использовать.</p>
    </router-link>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'HomeView',
  components: {
    HelloWorld
  }
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/gradient";
@import "src/assets/scss/shadow";

.home {
  margin-top: 10px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 15px;

  .article {
    display: flex;
    flex-direction: column;
    align-items: start;
    border-radius: 10px;
    width: 100%;
    overflow: hidden;
    .header {
      font-size: 1.2rem;
    }
    .article__image {
      overflow: hidden;
      height: 50px;
    }
    h2 {
      text-align: start;
      margin: 5px;
    }
    p {
      text-align: start;
      margin: 5px;
    }
  }
}

@media (max-width: 996px) {
  .home {
    grid-template-columns: 1fr;
  }
}
</style>