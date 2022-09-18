<template>
  <div class="home">
    <router-link :to="'/article/' + item.id" v-for="item in articles" class="article shadow">
      <h2 class="header">{{ item.header }}</h2>
      <p v-html="item.short_article"></p>
    </router-link>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'
import axios from 'axios';
import {host} from "@/service/host";

export default {
  name: 'HomeView',
  data() {
    return {
      articles: '',
      gradient: [
          'gradient-green',
          'gradient-pink',
          'gradient-purple',
          'gradient-yellow',
          'gradient-orange',
          'gradient-blue',
          'gradient-ocean',
          'gradient-red',
      ],
    }
  },
  components: {
    HelloWorld
  },
  async mounted() {
    await axios.get(host + '/articles').then(response => {
      this.articles = response.data;
    })
    let i = 0;
    for (let article of document.querySelectorAll('.article')) {
      if (i > this.gradient.length - 1) {
        i = 0;
      }
      article.classList.add(this.gradient[i]);
      i++;
    }
  },
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