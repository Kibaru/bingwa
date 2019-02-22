<template>
    <div>
        <!-- The timeline -->
        <ul class="timeline timeline-inverse">
            <!-- timeline time label -->
            <li class="time-label">
                <span class="bg-danger">
                10 Feb. 2014
                </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <div v-if="!superpowers.length" class="text-center">
                <p>{{user.username}} has not created any superpower yet.</p>
            </div>
            <li v-else v-for="superpower in superpowers" :key="superpower.id">
                <img v-if="superpower.avatar == 'superpower.jpg'" style="width:70px; height:70px; border-radius:100%;" :src="`/storage/images/${superpower.avatar}`" alt="Superpower Avatar">
                <img v-else style="width:70px; height:70px; border-radius:100%;" :src="`/storage/images/superpower/${superpower.avatar}`" alt="Superpower Avatar">

                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i>{{superpower.created_at | myDate}}</span>
                    <span class="time">Created by <router-link :to="`/users/${superpower.user.id}`">{{superpower.user.username | upText}}</router-link></span>

                    <h1 class="timeline-header"><router-link title="Click to view this superpower" :to="`/superpowers/${superpower.id}`">{{superpower.superpower | upText}}</router-link></h1>

                    <Join :id="superpower.id"></Join>

                    <div class="timeline-footer">
                    </div>
                </div>
            </li>
            <!-- END timeline item -->
        </ul>
    </div>
</template>

<script>
import Join from '../home/Join';

export default {
    components:{
        Join,
    },
    methods:{
        fetchCreatedPowers(){
            axios.get(`/api/users/created/${this.$route.params.id}`)
            .then(response => {
                // console.log(response);
                this.$store.commit('fetchSuperpowers', response.data);
            })
            .catch((err) => {
                // console.log(err);
            })
        },
    },
    computed:{
        auth(){
            return this.$store.getters.auth
        },
        superpowers(){
            return this.$store.getters.superpowers
        },
        user(){
            return this.$store.getters.user
        },
    },
    mounted(){
        this.fetchCreatedPowers();
    },
}
</script>

<style scoped>

</style>

