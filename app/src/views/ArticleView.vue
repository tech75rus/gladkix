<template>
  <div class="home">
    <Article ></Article>
  </div>
</template>

<script>
import Article from "@/components/Article";


export default {
  name: 'ArticleView',
  components: {
    Article
  },
  data() {
    return {
      article: '',
      editor: '',
    }
  },
}
</script>

<style lang="scss">
@import "highlight.js/scss/monokai";
</style>