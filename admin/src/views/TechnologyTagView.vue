<template>
  <div class="technology-tag">
    <h2>Список тегов</h2>
    <p v-for="tag in tags">
      {{ tag.name }}
    </p>
    <form @submit.prevent="setTechnologyTag()">
      <input type="text" placeholder="Введите новый тег" v-model="tag">
      <button>Добавить</button>
    </form>
    <div v-if="error" class="error">
      <span>Надо заполнить колонку</span>
    </div>
    <div v-else-if="success" class="success">
      <span>Тэг добавлен</span>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import {host} from "@/service/host";

export default {
  name: 'TechnologyTagView',
  data() {
    return {
      tags: [],
      tag: '',
      error: '',
      success: '',
    }
  },
  methods: {
    setTechnologyTag() {
      this.error = false;
      this.success = false;
      if (this.tag === '') {
        this.error = true;
        return;
      }
      let form = new FormData();
      form.append('name', this.tag);
      axios.post(host + '/set-technology-tag', form, {
        headers: {
          'Content-type': 'application/x-www-form-urlencoded'
        }
      }).then(response => {
        this.success = true;
        this.getTechnologyTags();
        console.log(response.data);
      });
    },
    getTechnologyTags() {
      axios.get(host + '/technology-tags').then(response => {
        this.tags = response.data;
        console.log(response.data);
      })
    }
  },
  created() {
    this.getTechnologyTags();
  },
  mounted() {
  },
}
</script>

<style lang="scss" scoped>
.technology-tag {
  h2 {
    margin-bottom: 20px;
  }
  p {
    margin-bottom: 10px;
  }
  form {
    input {
      padding: 5px;
    }
    button {
      padding: 5px;
    }
  }
}
</style>