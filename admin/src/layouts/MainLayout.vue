<template>
	<div id="main">
		<div id="nav-mobile">
			<router-link to="/" class="menu-item" :class="[button ? 'menu-mobile-item-show' : 'menu-mobile-item-hidden']">
				<img src="../assets/icons/main.png" alt="" v-show="!isActive('/')">
				<img src="../assets/icons/main-active.png" alt="" v-show="isActive('/')">
				<span class="menu-mobile-text">Главная</span>
			</router-link>
			<router-link to="/add-article" class="menu-item" :class="[button ? 'menu-mobile-item-show' : 'menu-mobile-item-hidden']">
				<img src="../assets/icons/user.png" alt="" v-show="!isActive('/add-article')">
				<img src="../assets/icons/user-active.png" alt="" v-show="isActive('/add-article')">
				<span class="menu-mobile-text">Добавить статью</span>
			</router-link>
			<router-link to="/technology-tag" class="menu-item" :class="[button ? 'menu-mobile-item-show' : 'menu-mobile-item-hidden']">
				<img src="../assets/icons/user.png" alt="" v-show="!isActive('/technology-tag')">
				<img src="../assets/icons/user-active.png" alt="" v-show="isActive('/technology-tag')">
				<span class="menu-mobile-text">Теги</span>
			</router-link>
			<button @click="changeMenuMobileSize">
				<img src="../assets/icons/right-arrow.png" alt="" v-show="!button">
				<img src="../assets/icons/left-arrow.png" alt="" v-show="button">
			</button>
		</div>
		<div id="nav">
			<router-link to="/" class="menu-item">
				<img src="../assets/icons/main.png" alt="" v-show="!isActive('/')">
				<img src="../assets/icons/main-active.png" alt="" v-show="isActive('/')">
				<span :class="[button ? 'menu-text-show' : 'menu-text-hidden']">Главная</span>
			</router-link>
			<router-link to="/add-article" class="menu-item">
				<img src="../assets/icons/user.png" alt="" v-show="!isActive('/add-article')">
				<img src="../assets/icons/user-active.png" alt="" v-show="isActive('/add-article')">
				<span :class="[button ? 'menu-text-show' : 'menu-text-hidden']">Добавить статью</span>
			</router-link>
			<router-link to="/technology-tag" class="menu-item">
				<img src="../assets/icons/user.png" alt="" v-show="!isActive('/technology-tag')">
				<img src="../assets/icons/user-active.png" alt="" v-show="isActive('/technology-tag')">
				<span :class="[button ? 'menu-text-show' : 'menu-text-hidden']">Теги</span>
			</router-link>
			<button @click="changeSizeMenu">
				<img src="../assets/icons/right-arrow.png" alt="" v-show="!button">
				<img src="../assets/icons/left-arrow.png" alt="" v-show="button">
			</button>
		</div>
		<div id="nav-clear"></div>
		<div class="content">
			<router-view/>
		</div>
	</div>
</template>
<script>
export default {
	name: 'MainLayOut',
	data() {
		return {
			menu: true,
			button: false
		}
	},
	methods: {
		changeSizeMenu() {
			this.button = !this.button;
			let nav = document.querySelector('#nav');
			let navClear = document.querySelector('#nav-clear');
			if (nav.style.width === '' || nav.style.width === '70px') {
				nav.style.width = '210px';
				navClear.style.width = '230px';
				return;
			}
			nav.style.width = '70px';
			navClear.style.width = '90px';
		},
		changeMenuMobileSize() {
			this.button = !this.button;
			let navMobile = document.querySelector('#nav-mobile');
			if (navMobile.style.width === '' || navMobile.style.width === '50px') {
				navMobile.style.height = '96%';
				navMobile.style.width = '210px';
				return
			}
			navMobile.style.height = '50px';
			navMobile.style.width = '50px';
		},
		isActive(input) {
			return this.$route.path === input;
		}
	}
}

</script>
<style scoped lang="scss">
@import "src/assets/scss/color";

.main {
	min-height: 100vh;
}
#nav-mobile {
	display: none;
}
#nav{
	display: flex;
	flex-direction: column;
	position: fixed;
	left: 10px;
	top: 2%;
	overflow: scroll;
	background-color: $main-color;
	width: 70px;
	height: 96%;
	transition: width .3s;
	border-radius: 5px;
	scrollbar-width: none;
	z-index: 100;
	img {
		width: 25px;
	}
	button {
		padding-top: 10px;
		margin-top: auto;
		margin-bottom: 10px;
		background-color: transparent;
		border: none;
	}
	&::-webkit-scrollbar {
		width: 0;
	}
}
.menu-item {
	position: relative;
	border-radius: 5px;
	margin: 5px 10px;
	padding: 10px 0 8px 12px;
	span {
		position: absolute;
		top: 15px;
		font-size: 14px;
		color: black;
	}
	&:hover {
		background-color: #262232;
	}
}
.menu-text-show {
	left: 50px;
	transition: left .3s, opacity 1.3s;
}
.menu-text-hidden {
	left: -250px;
	opacity: 0;
	transition: left .3s, opacity .1s;
}
.router-link-exact-active {
	background-color: $bgc-active-color;
	span{
		color: $purple;
	}
}

#nav-clear {
	width: 90px;
	min-height: 100vh;
	float: left;
	transition: width .3s;
}
.content {
	display: grid;
	min-height: 100vh;
	padding: 10px;
	text-align: center;
}

@media all and (max-width: 600px) {
	#nav {
		display: none;
	}
	#nav-clear {
		display: none;
	}
	#nav-mobile {
		display: flex;
		flex-direction: column;
		position: fixed;
		width: 50px;
		height: 50px;
		left: 10px;
		bottom: 2%;
		background-color: #860dd2ef;
		border-radius: 5px;
		transition: height .3s, width .3s;
		z-index: 100;
		img {
			width: 25px;
		}
		button {
			width: 100%;
			padding-bottom: 8px;
			margin-top: auto;
			background-color: transparent;
			border: none;
		}
	}
	.menu-mobile-item-show {
		display: block;
		transition: all .3s;
	}
	.menu-mobile-item-hidden {
		display: none;
		transition: all .3s;
	}
	.menu-mobile-text {
		left: 50px;
	}
	.router-link-exact-active {
		background-color: $main-color;
		span{
			color: $purple;
		}
	}
}
</style>
