<template>
  <div class="list-article">
    <h1>Список статей</h1>
    <div>
      <router-link class="article" :to="'/update-article/' + item.id" v-for="item in articles">
        <h2 class="header" v-html="item.header"></h2>
        <p v-html="item.short_article"></p>
      </router-link>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import {host} from "@/service/host";

export default {
  name: 'AddArticleView',
  data() {
    return {
      articles: '',
    }
  },
  methods: {
  },
  async mounted() {
    await axios.get(host + '/articles').then(response => {
      this.articles = response.data;
    })
  },
}
</script>

<style lang="scss" scoped>
@import "src/assets/scss/color";
.list-article {
  h1 {
    margin-bottom: 30px;
  }
  &>div {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;

    a {
      border: 1px solid $color-text;
      border-radius: 5px;
      transition: .1s;
      padding: 10px;
      p {
        text-align: left;
      }
    }
    a:hover {
      background-color: $bgc-active-color;
    }
  }
}
@media (max-width: 900px) {
  .list-article > div {
    grid-template-columns: 1fr;
  }
}
</style>