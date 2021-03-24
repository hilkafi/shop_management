/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;
import VueRouter from 'vue-router';
import { Form, HasError, AlertError } from 'vform';
import Swal from 'sweetalert2';
import VueProgressBar from 'vue-progressbar';


Vue.use(VueRouter);

window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

window.Swal = Swal;
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

window.Toast = Toast;

const options = {
  color: '#FF0000',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}


Vue.use(VueProgressBar, options);

const routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/category', component: require('./components/Category.vue').default },
    { path: '/product', component: require('./components/Product.vue').default },
    { path: '/head', component: require('./components/Head.vue').default },
    { path: '/sub_head', component: require('./components/SubHead.vue').default },
    { path: '/purchase', component: require('./components/Purchase.vue').default },
    { path: '/sale', component: require('./components/Sale.vue').default },
    { path: '/stock', component: require('./components/Stock.vue').default },
    { path: '*', component: require('./components/NotFound.vue').default }
  ];


Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
});

const app = new Vue({
    el: '#app',
    router
});
