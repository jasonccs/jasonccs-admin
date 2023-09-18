/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
// https://blog.csdn.net/m0_58565372/article/details/131332115 vue-i18n 实现国际化，支持切换不同语言
import './bootstrap';
import '../css/app.css';
import { createApp } from 'vue';
import store from './store'; // 导入 Vuex store
// import 'element-ui/lib/theme-chalk/index.css'
import 'element-plus/dist/index.css'
import ElementPlus from 'element-plus';
import 'virtual:svg-icons-register'
import SvgIcon from '../js/components/SvgIcon/index.vue';

const app = createApp({});

Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
    app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
});
app.component('svg-icon', SvgIcon);
app.use(ElementPlus);
app.use(store);
app.mount('#app');
