<template>
  <div>
    <h2 class="head">Статьи</h2>
    <div class="tags shadow">
      <span class="tag-active" @click="setTag(0, $event)">Все</span>
      <span
          @click="setTag(tag.id, $event)"
          v-for="tag in tags"
      >{{ tag.name }}
      </span>
    </div>
    <div class="home" v-if="articles">
      <router-link :to="'/article/' + item.id" v-for="item in articles" class="article shadow">
        <h2 class="header">{{ item.header }}</h2>
        <p v-html="item.short_article"></p>
      </router-link>
    </div>
    <div class="message" v-if="!articles">
      <p>Не найдено статей</p>
    </div>
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
      tags: [],
    }
  },
  components: {
    HelloWorld
  },
  methods: {
    async getArticle(id) {
      await axios.get(host + '/articles', {
        headers: {
          'tags': id
        }
      }).then(response => {
        this.articles = response.data;
      }).catch(error => {
        this.articles = false;
        console.log(error);
      });
      this.getGradient();
    },
    async getTags() {
      await axios.get(host + '/technology-tags').then(response => {
        response.data.forEach(res => {
          this.tags.push(res);
        });
      }).catch(error => {
        console.log(error.data);
      });
    },
    async setTag(id, event = null) {
      await this.getArticle(id);
      if (event) {
        const tagActive = document.querySelectorAll('.tag-active');
        tagActive.forEach(res => {
          res.classList.remove('tag-active');
        });
        const tag = event.target;
        tag.classList.add('tag-active');
      }
    },
    getGradient() {
      let i = 0;
      this.gradient.sort(() => Math.random() - 0.5);
      console.log(this.gradient);
      for (let article of document.querySelectorAll('.article')) {
        if (i > this.gradient.length - 1) {
          i = 0;
        }
        article.classList.add(this.gradient[i]);
        i++;
      }
    },
  },
  async mounted() {
    await this.getTags();
    await this.setTag(0);
  },
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/gradient";
@import "src/assets/scss/shadow";

.head {
  display: none;
  margin-bottom: 20px;
}
.tags {
  display: flex;
  flex-wrap: wrap;
  line-height: 30px;
  margin-bottom: 15px;
  padding: 10px;
  border-radius: 10px;
  span {
    margin: 0 10px;
    cursor: pointer;
  }
}
.tag-active {
  color: red;
}
.home {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-gap: 15px;

  .article {
    display: flex;
    flex-direction: column;
    align-items: start;
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
@media (max-width: 600px) {
  .head{
    display: block;
  }
}
</style>