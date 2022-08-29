<template>
  <div class="home">
    <h1>Article page</h1>
    <pre>
      <code class="php">
echo 'hello';
      </code>
    </pre>
    <pre>
      <code class="javascript">
let one = 'asdasd';
      </code>
    </pre>
  </div>
</template>

<script>
import hljs from "highlight.js";

export default {
  name: 'ArticleView',
  mounted() {
    hljs.highlightAll()
  }
}
</script>

<style lang="scss">
@import "highlight.js/scss/monokai";
.block {
  text-align: left;
}
</style>