<template>
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="page-wrapper">
                        <form class="my-3" @submit.prevent="createSuperpower" >
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <input type="text" name="superpower" v-model="form.superpower" class="form-control" :class="{ 'is-invalid': form.errors.has('superpower') }" :placeholder="`What's your superpower today ${auth.username}?`">
                                        <has-error :form="form" field="superpower"></has-error>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" :disabled="loading">
                                            <div class="lds-ring-container" v-if="loading">
                                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                            </div>
                                            Create
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="card">
                           <div class="card-body">
                                <ul class="timeline timeline-inverse" >
                                    <!-- timeline item -->
                                    <li class="text-center" v-if="loading">Loading . . .</li>
                                    <li v-if="!loading" v-for="superpower in superpowers" :key="superpower.id">
                                        <img v-if="superpower.avatar == 'superpower.jpg'" style="width:70px; height:70px; border-radius:100%;" :src="`/storage/images/${superpower.avatar}`" alt="Superpower Avatar">
                                        <img v-else style="width:70px; height:70px; border-radius:100%;" :src="`/storage/images/superpower/${superpower.avatar}`" alt="Superpower Avatar">

                                        <div class="timeline-item">
                                            <span class="time item"><i class="fa fa-clock-o"></i>{{superpower.created_at | myDate}}</span>
                                            <span class="time item">Created by <router-link :title="`Click to view ${superpower.user.username}'s profile.`" :to="`/users/${superpower.user.id}`">{{superpower.user.username | upText}}</router-link></span>

                                            <h1 class="timeline-header item"><router-link title="Click to visit this superpower" :to="`/superpowers/${superpower.id}`">{{superpower.superpower | upText}}</router-link></h1>

                                            <Join :id="superpower.id"></Join>

                                            <div class="timeline-footer">
                                            </div>
                                        </div>
                                    </li>
                                    <!-- END timeline item -->

                                </ul>
                           </div>
                           <!-- <div class="card-footer">
                              <pagination :data="superpowers" @pagination-change-page="getResults"></pagination>
                           </div> -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Join from './home/Join'

export default {
    name: 'home',
    components:{
        Join,
    },
    data(){
        return{
            form: new Form({
                superpower:'',
            }),
            loading: false,
        }
    },
    methods:{
        createSuperpower(){
            this.loading = true;
            this.$Progress.start();
            this.form.post('api/superpowers/create')
            .then(response=>{
                // console.log(response);
                this.loading = false;
                this.form.superpower = '';
                this.$toast.success({
                    title: 'Your superpower was created successfully.',
                    message: 'You can now invite your friends for this challenge.'
                });
                this.$Progress.finish();
                this.fetchSuperpowers()
            })
            .catch(error=>{
                this.loading = false;
                this.$Progress.fail();
            })
        },
        fetchSuperpowers(){
            this.loading = true;
            this.$Progress.start();
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.authUser.token
            axios.get('api/superpowers/fetch')
            .then(response => {
                this.loading = false;
                // console.log(response.data);
                this.$store.commit('fetchSuperpowers', response.data);
                this.$Progress.finish();
            })
            .catch(error => {
                this.loading = false;
                this.$Progress.fail();
            })
        },
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        auth(){
            return this.$store.getters.auth
        },
        superpowers(){
            return this.$store.getters.superpowers
        },
    },
    mounted(){
      this.fetchSuperpowers();
    }
}
</script>

<style scoped>
    @media(max-width: 768px){
       .timeline-item{
        display: flex;
        flex-direction: column;
        }
    }
</style>
