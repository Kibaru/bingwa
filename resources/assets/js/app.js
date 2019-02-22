require('./bootstrap');
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import moment from 'moment';
import { Form, HasError, AlertError } from 'vform';
import VueProgressBar from 'vue-progressbar';
import Swal from 'sweetalert2';
import VeeValidate from 'vee-validate';
import CxltToastr from 'cxlt-vue2-toastr';

import {routes} from './routes';
import StoreData from './store';
import MainApp from './components/MainApp';

Vue.use(VeeValidate);
Vue.use(VueRouter);
Vue.use(Vuex);

// sweet alert
window.Swal = Swal;
const toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    showConfirmButton: false,
    timer: 5000,
});

// toastr notifiction
const toastrConfigs = {
    position: 'top right',
    showDuration: 2000,
    timeOut: 20000,
    progressBar: true,
}


Vue.use(CxltToastr, toastrConfigs);

window.toast = toast;

//progress bar
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '4px'
})

// vform
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

// vuex
const store = new Vuex.Store(StoreData);

const router =  new VueRouter({
    routes,
    mode: 'history',
});

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)){
        if(!store.getters.loggedIn){
            next({
                name: 'login',
            })
        } else{
            next()
        }
    } else if(to.matched.some(record => record.meta.requiresVisitor)){
        if(store.getters.loggedIn){
            next({
                name: 'welcome',
            })
        } else{
            next()
        }
    } else{
        next()
    }
});

// common auth header
if(store.getters.loggedIn){
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + store.getters.authUser.token
}

// filters
Vue.filter('upText', function(text){
    return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter('myDate', function(created){
    return moment(created).startOf('hour').fromNow();
});

// custom event
window.Fire = new Vue();

const app = new Vue({
    el: '#app',
    store,
    router,
    Form,
    components:{
        MainApp,
    }
});
