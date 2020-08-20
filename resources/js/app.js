/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// 1. Comment out this following line:
//window.Vue = require('vue');

// 2. Add below the above commented-out line:
import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
import VueRouter from "vue-router";

window.Vue = Vue;
Vue.use(VueRouter);
import { HasError, AlertError } from 'vform';
import Multiselect from 'vue-multiselect'
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('multiselect', Multiselect)
import Form from "./utilities/Form";
window.Form = Form;
import router from './routes';
import Swal from 'sweetalert2';
//import CKEditor from '@ckeditor/ckeditor5-vue';
//Vue.use(CKEditor);
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use(CKEditor);
require('select2');
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
window.Swal = Swal;
window.Toast = Toast;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
//Vue.component('Instrumen', require('./components/Instrumen.vue').default);
//import Instrumen from './components/Instrumen.vue' //IMPORT COMPONENT DATATABLENYA
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//import './css/styles.css';
import Paginator from './utilities/Paginator';
Vue.component('paginator', Paginator);
import vSelect from 'vue-select'
Vue.component('v-select', vSelect)
Vue.mixin({
    data: function() {
        return {
            get detilUser() {
                return user;
            },
        }
    },
    methods: {
        hasRole: function(role) {
            for (var i = 0; i < this.user.roles.length; i++) {
                if (this.user.roles[i].name == role) {
                    return true
                }
            }
            return false
        },
    }
})
new Vue({
    el: '#pmp_smk',
    router,
    //detilUser: user
    /*data: {
        loading: true,
    }*/
});