
require('./bootstrap');
import { BootstrapVue } from 'bootstrap-vue'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'bootstrap-vue/dist/bootstrap-vue-icons.css'

import router from './router';
import Vue from 'vue'
Vue.use(BootstrapVue);

window.Vue = require('vue').default;
window.getRout = function(name, param = '') {
    if (param)
        return route(name, param);
    return route(name);
};

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('main-component', require('./components/App.vue').default);

const app = new Vue({
    router,
    el: '#app',
});
