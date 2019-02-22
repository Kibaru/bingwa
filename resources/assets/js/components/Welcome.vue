<template>
    <div>
        <div v-if="!loggedIn" class="page-wrapper">

            <Video />

            <div class="container">

                <form class="form-horizontal mt-3" @submit.prevent="login">
                    <div class="row">
                        <div class="col-3"><h1 class="text-white"><img src="/storage/images/chachisha1.png"> <span class="name">Chachisha</span></h1></div>
                        <div class="col-3">
                            <div class="form-group">
                                <input v-model="log.username" type="email" class="form-control"  name="username" placeholder="Email address" required>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <input v-model="log.password" type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <button type="submit"  style="border-radius:20px;" class="btn btn-primary form-control" :disabled="log.loading">
                                    <div class="lds-ring-container" v-if="log.loading">
                                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                    </div>
                                    Signin
                                </button>
                            </div>
                        </div>

                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div v-if="log.serverError" class="server-error">{{log.serverError}}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <a class="btn btn-link form-control" href="#">
                                    Forgot/Reset Your Password?
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-7">
                        <Overview />
                    </div>
                    <div class="col-md-5">
                        <div class="register">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="indigo">Create a free account with us.</h3>
                                    <h4 class="text-right green">Already have an account? <router-link to="/login">Signin.</router-link></h4>
                                </div>
                                <div class="card-body">
                                    <div v-if="form.serverErrors" class="server-error">
                                        <div v-for="(value, key) in form.serverErrors" :key="key">{{value[0]}}</div>
                                    </div>
                                    <form class="form-horizontal" @submit.prevent="validateBeforeSubmit">

                                        <div class="form-group">
                                            <input v-model="form.firstname" type="text" name="firstname" placeholder="First name"
                                                class="form-control" :class="{'input-error': errors.has('firstname')}" v-validate="'required|min:2'">
                                                <span class="form-error">{{errors.first('firstname')}}</span>
                                        </div>

                                        <div class="form-group">
                                            <input v-model="form.lastname" type="text" name="lastname" placeholder="Last name"
                                                class="form-control" :class="{'input-error': errors.has('lastname')}" v-validate="'required|min:2'">
                                                <span class="form-error">{{errors.first('lastname')}}</span>
                                        </div>

                                        <div class="form-group">
                                            <input v-model="form.username" type="text" name="username" placeholder="Username"
                                                class="form-control" :class="{'input-error': errors.has('username')}" v-validate="'required|min:2'">
                                                <span class="form-error">{{errors.first('username')}}</span>
                                        </div>

                                        <div class="form-group">
                                            <input v-model="form.email" type="text" name="email" placeholder="Email Address"
                                                class="form-control" :class="{'input-error': errors.has('email')}" v-validate="'required|email'">
                                                <span class="form-error">{{errors.first('email')}}</span>
                                        </div>

                                        <div class="form-group">
                                            <input v-model="form.password" id="password" type="password" name="password" placeholder="Password"
                                                class="form-control" :class="{'input-error': errors.has('password')}" v-validate="'required|min:6|confirmed:password'">
                                                <span class="form-error">{{errors.first('password')}}</span>
                                        </div>

                                        <div class="form-group">
                                            <input v-model="form.password_confirmation" id="password-confirm" type="password" name="password_confirmation" ref="password" placeholder="Confirm your password"
                                                class="form-control" :class="{'input-error': errors.has('password_confirmation')}" v-validate="'required|min:6'">
                                            <span class="form-error">{{errors.first('password_confirmation')}}</span>
                                        </div>

                                        <div class="form-group">
                                            <small>By clicking Signup, you agree to our <a href="#">Terms</a>  and <a href="#">Conditions</a>.</small>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" style="border-radius:20px;" class="btn btn-primary form-control" :disabled="form.loading">
                                                <div class="lds-ring-container" v-if="form.loading">
                                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                                </div>
                                                Signup
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Home v-if="loggedIn"/>
    </div>
</template>

<script>
import Home from './Home';
import Video from './welcome/Video';
import Overview from './welcome/Overview';

export default {
    name: 'welcome',
    components:{
        Video,
        Overview,
        Home,
    },
    data(){
        return{
            form: new Form({
                firstname: '',
                lastname: '',
                username: '',
                email: '',
                password: '',
                password_confirmation: '',
                serverErrors: '',
                loading: false,
            }),
            log:{
                username: '',
                password: '',
                serverErrors: '',
                loading: false,
            },
        }
    },
    computed:{
        user(){
          return this.$store.getters.user
        },
        loggedIn(){
            return this.$store.getters.loggedIn
        },
    },
    methods:{
        login(){
            this.log.loading = true;
            this.$Progress.start();
            this.$store.dispatch('retrieveAuthUser', {
                username: this.log.username,
                password: this.log.password,
            })
            .then(response => {
                this.log.loading = false;
                this.$router.push({name: 'welcome'})
                // console.log(response);
                this.$toast.success({
                    title: 'Hallo '+this.$store.getters.authUser.username+', welcome to Chachisha.com.',
                    message: 'Make your superpowers count.'
                });
                this.$Progress.finish();
            })
            .catch(error => {
                this.log.loading = false;
                console.log(error.response);
                this.log.serverError = error.response.data.message;
                this.log.password = '';
                this.$Progress.fail();
            })
        },

        validateBeforeSubmit(){
            this.loading = true;
            this.$Progress.start();
            this.$validator.validate().then(result =>{
                if(result){
                    this.loading = false;
                    this.register();
                    this.$Progress.finish();
                }else{
                    this.loading = false;
                    this.$Progress.fail();
                }
            });
        },

        register(){
            this.form.loading = true;
            this.$Progress.start();
            this.$store.dispatch('registerUser', this.$data.form)
            .then(response => {
                // console.log(response);
                this.form.loading = false;
                this.$router.push({name: 'welcome'})
                this.$toast.success({
                    title: 'Your account has been created successfully.',
                    message: 'You can now signin to participate.'
                });
                this.$Progress.finish();
            })
            .catch(error =>{
                // console.log(response);
                this.form.loading = false;
                this.form.serverErrors = error.response.data.errors;
                this.form.password = '';
                this.form.password_confirmation = '';
                this.$Progress.fail();
            })
        }
    }
 }
 </script>

 <style scoped>
    .register .card{
        background: black;
        opacity: .9;
        color: #fff;
    }
    .btn :disabled{
        background: lighten(#60BD4F, 25%);
        cursor: not-allowed;
    }
    img{
        height: 50px;
        width: 50px;
    }
    @media(max-width: 768px){
        .register{
            background: black;
            opacity: .9;
        }
        .name{display: none;}
    }
 </style>