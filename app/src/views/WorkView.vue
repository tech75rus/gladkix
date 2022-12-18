<template>
  <div class="home">
    <h1>Работы</h1>
    <router-link to="/work/random-color" class="work show-menu-desktop" target="_blank">
      <img src="../assets/images/random-color.png" alt="random color">
      <div class="description">
        <h2>Random Color</h2>
        <p>Генерация слечайных цветов, с возможность сохранения каждого отдельно</p>
      </div>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'WorkView'
}
</script>

<style lang="scss" scoped>
@import "src/assets/scss/shadow";
.home {
  &>h1 {
    margin-bottom: 20px;
  }
}
.work {
  width: 100%;
  overflow: hidden;
  display: block;
  margin-bottom: 20px;

  img {
    object-fit: cover;
    width: 100%;
    height: 400px;
  }

  .description {
    padding-bottom: 10px;
    p {
      text-align: start;
      padding-left: 10px;
    }
  }
}
@media (max-width: 600px) {
  .work {
    img {
      height: 250px;
    }
  }
}
</style>