<template>
  <div class="article-create">
    <div id="editor"></div>
    <div class="tags">
      <label v-for="tag in tags">
        {{ tag.name }}
        <input type="checkbox" :value=tag.id v-model="selectedTags">
      </label>
    </div>
    <button class="button-update" @click="loadMessage">Обновить статью</button>
    <button class="button-delete" @click="deleteArticle()">Удалить статью</button>
    <span class="success" v-if="success">Статья добавлена</span>
    <span class="error" v-else-if="error">Произошла ошибка при добавлении статьи</span>
    <span class="delete" v-else-if="deleteMessage">{{ deleteMessage }}</span>
  </div>
</template>

<script>
import axios from 'axios';
import {host} from "@/service/host";
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Image from '@editorjs/image';
import Raw from '@editorjs/raw';

export default {
  name: 'AddArticleView',
  data() {
    return {
      header: '',
      editor: '',
      text: '',
      tags: '',
      selectedTags: [],
      success: '',
      error: '',
      article: '',
      deleteMessage: '',
    }
  },
  methods: {
    loadMessage() {
      this.editor.save().then(result => {
        let form = new FormData();
        form.append('article', JSON.stringify(result));
        let onceHeader = true;
        let onceShortArticle = true;
        for (let block of result.blocks) {
          if (onceHeader && block.type === 'header') {
            form.append('header', block.data.text);
            onceHeader = false;
          }
          if (onceShortArticle && block.type === 'paragraph') {
            form.append('short_article', block.data.text);
            onceShortArticle = false;
          }
        }
        if (this.selectedTags.length > 0) {
          form.append('tags', this.selectedTags);
        }
        axios.post(host + '/admin/article-update/' + this.$route.params.id, form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(() => {
          this.success = true;
        }).catch(error => {
          this.error = true;
          console.log(error);
        })
      })
    },
    deleteArticle() {
      axios.delete(host + '/admin/article-delete/' + this.$route.params.id).then(() => {
        this.deleteMessage = 'Статья удалена';
      }).catch(() => {
        this.deleteMessage = 'Произошла ошибка при удалении';
      });
    },
    async getArticle() {
      await axios.get(host + '/technology-tags').then(response => {
        this.tags = response.data
      });
      await axios.get(host + '/article/' + this.$route.params.id).then(response => {
        this.article = JSON.parse(response.data.article);
        for (let tag of response.data.technologyTags) {
          this.selectedTags.push(tag.id);
        }
      })
    },
  },
  async mounted() {
    await this.getArticle();
    this.editor = new EditorJS({
      holder: 'editor',
      autofocus: true,
//      readOnly: true,
      tools: {
        header: {
          class: Header,
          inlineToolbar : false
        },
        image: {
          class: Image,
          config: {
            endpoints: {
              byFile: host + '/image-file', // Your backend file uploader endpoint
            }
          }
        },
        raw: {
          class: Raw
        }
      },
      data: this.article
    });
  },
}
</script>

<style lang="scss">
@import "src/assets/scss/color";

.ce-block__content {
  background: #292d38;
  border-radius: 5px;
  margin: 5px auto;
}
.article-create {
  button {
    padding: 7px;
    margin: 5px;
    background-color: rgba(255, 255, 255, .2);
    border: 1px solid #d8dbdf;
    color: #d8dbdf;
    border-radius: 5px;
    font-size: 1rem;
    transition: .1s;
  }
  .button-update:hover {
    background-color: #ff801fa1;
  }
  .button-delete:hover {
    background-color: #d94949a1;
  }
}
.tags {
  margin-bottom: 30px;
  label {
    margin-right: 10px;
    cursor: pointer;
  }
  input {
    cursor: pointer;
  }
}
.success {
  display: block;
  color: $success;
  padding-top: 10px;
}
.error {
  display: block;
  color: $error;
  padding-top: 10px;
}
.delete {
  display: block;
  color: $warning;
  padding-top: 10px;
}
</style>