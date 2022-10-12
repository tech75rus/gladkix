<template>
	<div>
		<form @submit.prevent="login">
			<h2>Вход</h2>
			<input type="text" placeholder="Введите логин" v-model.lazy="name">
			<input type="password" placeholder="Введите пароль" v-model.lazy="password">
			<router-link to="/registration">Регистрация</router-link>
			<button>Войти</button>
		</form>
		<span v-if="error">{{ error }}</span>
	</div>
</template>

<script>
import axios from "axios";
import {host} from "@/service/host";

export default {
	name: "Login",
	data() {
		return {
			name: '',
			password: '',
			error: ''
		}
	},
	created() {
	},
	mounted() {
		this.$store.dispatch('actionLoader');
	},
	methods: {
		// login() {
		// 	let data = new FormData();
		// 	data.append('name', this.name);
		// 	data.append('password', this.password);
		// 	axios.post(host + '/admin/login', data, {
		// 		headers: {
		// 			'Content-Type': 'application/x-www-form-urlencoded'
		// 		}
		// 	}).then(response => {
		// 		localStorage.setItem('token', response.headers.token);
		// 		this.$store.dispatch('actionLoginIn');
		// 		this.$router.push('/');
		// 	}).catch(error => {
		// 		this.error = error.response.data;
		// 	})
		// }
	}
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/input";
@import "src/assets/scss/button";
div {
	width: 100%;
	h2 {
		margin-bottom: 20px;
	}
	form {
		display: flex;
		flex-direction: column;
		align-items: center;
		a {
			margin-top: 15px;
			margin-bottom: 15px;
			font-size: 14px;
			color: black;
			&:hover {
				color: #860dd2;
			}
		}
	}
}
</style>
