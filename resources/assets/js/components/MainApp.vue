<template>
    <div>
        <MainHeader v-if="loggedIn" />
        <transition name="router-animation" enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
            <router-view></router-view>
        </transition>
        <vue-progress-bar></vue-progress-bar>
        <AuthUser v-if="loggedIn"></AuthUser>
        <MainFooter />
    </div>
</template>

<script>
import MainHeader from './inc/MainHeader';
import MainFooter from './inc/MainFooter';
import AuthUser from './inc/AuthUser';

export default {
    name: 'main-app',
    components:{
        MainHeader,
        MainFooter,
        AuthUser,
    },
    computed:{
        loggedIn(){
        return this.$store.getters.loggedIn
        }
    },
}
</script>

<style lang="scss">
@import '~cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css';
@import url("https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css");
.server-error{
     margin-bottom: 12px;
     font-size: 16p;
     padding: 10px 16px;
     color: #a94442;
     background: #f3DEDE;
     border-radius: 7px;
 }
 .form-error{
     font-size: 16px;
     color: #a94442;
 }
 .input-error{
     border: 1px solid #a94442;
 }
 .page-wrapper{
     margin-top: 80px;
     animation-duration: 0.2s;
 }
//  css spinner
 .lds-ring-container{
     position: absolute;
     right: 50%;
 }
 .lds-ring{
     display: inline-block;
     position: relative;
     width: 64px;
     height: 64px;
 }
 .lds-ring div{
     box-sizing: border-box;
     display: block;
     position: absolute;
     width: 20px;
     height: 20px;
     border: 2px solid green;
     border-radius: 50%;
     animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
     border-color: green transparent transparent transparent;
 }
 .lds-ring div:nth-child(1){
     animation-delay: 0.45s;
 }
 .lds-ring div:nth-child(2){
     animation-delay: 0.3s;
 }
 .lds-ring div:nth-child(3){
     animation-delay: 0.15s;
 }
 .modal {
    overflow-y: auto;
}

.modal-open {
    overflow: auto;
}

.modal-open[style] {
    padding-right: 0px !important;
}
 @keyframes lds-ring {
     0%{
         transform: rotate(0deg);
     }
     100%{
         transform: rotate(360deg)
     }
 }

/* radio button */
.radio{
  height: 21px;
  width: 21px;
}
@media(max-width: 768px){
    .page-wrapper{
     margin-top: 110px;
 }
}
</style>



