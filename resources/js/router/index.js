import Vue from 'vue'
import VueRouter from 'vue-router'
import ExampleComponent from '../components/ExampleComponent'
import ContactUsComponent from "../components/ContactUsComponent.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: ExampleComponent
  },
  {
    path: '/dashboard/contact_us',
    name: 'ContactUs',
    component: ContactUsComponent
  },


];
Vue.config.devtools=true;
const router = new VueRouter({
  mode: 'history',
  linkActiveClass: "linkActiveClass",
  routes
});

export default router
