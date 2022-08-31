<template>
  <div class="home">
    <router-link :to="'/article/' + item.id" v-for="item in articles" class="article gradient-red shadow">
      <h2 class="header">{{ item.header }}</h2>
      <p v-html="item.article"></p>
    </router-link>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'
import axios from 'axios';

export default {
  name: 'HomeView',
  data() {
    return {
      articles: ''
    }
  },
  components: {
    HelloWorld
  },
  mounted() {
    axios.get('https://localhost/articles').then(response => {
      this.articles = response.data;
      console.log(response);
    })
  }
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/gradient";
@import "src/assets/scss/shadow";

.home {
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