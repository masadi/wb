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

import Paginator from './utilities/Paginator';
Vue.component('paginator', Paginator);
import vSelect from 'vue-select'
Vue.component('v-select', vSelect)
    //import { VclFacebook, VclInstagram } from 'vue-content-loading';
    //Vue.component('vcl-facebook', VclFacebook);
    //Vue.component('vcl-instagram', VclInstagram);
import Loader from './utilities/Loader';
Vue.use(Loader);
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