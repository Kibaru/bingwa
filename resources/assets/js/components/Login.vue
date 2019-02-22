<template>
    <div class="login page-wrapper">

        <!-- <Video /> -->
        <div class="container">

            <div class="card mt-3">
                <div class="card-header">
                    <h3>Login to participate</h3>
                </div>
                <div class="card-body">
                    <div v-if="serverError" class="server-error">{{serverError}}</div>
                    <form class="form-horizontal" @submit.prevent="validateBeforeSubmit">

                        <div class="form-group">
                            <input v-model="username" type="email" class="form-control" :class="{'input-error': errors.has('username')}" v-validate="'required'" name="username" placeholder="Email address">
                            <span class="form-error">{{errors.first('username')}}</span>
                        </div>

                        <div class="form-group">
                            <input v-model="password" type="password" class="form-control" :class="{'input-error': errors.has('password')}" v-validate="'required'" name="password" placeholder="Password">
                            <span class="form-error">{{errors.first('password')}}</span>
                        </div>

                        <div class="form-group">
                            <button type="submit"  style="border-radius:20px;" class="btn btn-primary form-control" :disabled="loading">
                                <div class="lds-ring-container" v-if="loading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </div>
                                Login
                            </button>
                        </div>

                        <div class="form-group">
                            <a class="btn btn-link form-control" href="#">
                                Forgot Your Password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</template>
 <script>
 import Video from './welcome/Video';
import Overview from './welcome/Overview';
    export default {
        name: 'login',
        components:{
            Video,
            Overview,
        },
        data(){
            return{
                username: '',
                password: '',
                serverError: '',
                loading: false,
            }
        },
        methods:{
            validateBeforeSubmit(){
                this.loading = true;
                this.$Progress.start();
                this.$validator.validate().then(result =>{
                    if(result){
                        this.loading = false;
                        this.login();
                        this.$Progress.finish();
                    }else{
                        this.loading = false;
                        this.$Progress.fail();
                    }
                });
            },
            login(){
                this.loading = true;
                this.$Progress.start();
                this.$store.dispatch('retrieveAuthUser', {
                    username: this.username,
                    password: this.password,
                })
                .then(response => {
                    this.loading = false;
                    this.$router.push({name: 'home'})
                    this.$toast.success({
                        title: 'Hallo '+this.$store.getters.authUser.username+', welcome to Chachisha.com.',
                        message: 'Make your superpowers count.'
                    });
                    this.$Progress.finish();
                })
                .catch(error => {
                    this.loading = false;
                    console.log(error);

                    this.serverError = error.response.data.message;
                    this.password = '';
                    this.$Progress.fail();
                })
            }
        },
    }
 </script>

 <style scoped>
    .btn :disabled{
        background: lighten(#60BD4F, 25%);
        cursor: not-allowed;
    }
 </style>

