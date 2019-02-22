<template>
    <div class="row">
        <div class="col-sm-4 border-right">
            <div v-if="user.id != auth.id" class="description-block">
                <p class="text-center" v-if="loading">
                    Loading . . .
                </p>
                <p class="text-center" v-if="!loading">
                    <button class="btn btn-success" v-if="status == 0" @click="follow(user)">FOLLOW</button>
                </p>
                <p class="text-center" v-if="!loading">
                    <button class="btn btn-danger"  v-if="status == 1" @click="unfollow(user)">UNFOLLOW</button>
                </p>
            </div>
            <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4 border-right">
            <div class="description-block">
                <span class="description-text">FOLLOWERS</span>
                <h5 class="description-header"><i class="fas fa-users teal"></i> {{followers.length}}</h5>
            </div>
            <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4">
            <div class="description-block">
                <span class="description-text">FOLLOWING</span>
                <h5 class="description-header"><i class="fas fa-users teal"></i> {{followings.length}}</h5>
            </div>
            <!-- /.description-block -->
        </div>
        <!-- /.col -->
    </div>
</template>

<script>
export default {
    data(){
       return{
           status: '',
           loading: true,
       }
   },
   methods: {
       follow(user){
           this.$Progress.start();
           this.loading = true;
          axios.get('/api/follow/' + user.id)
          .then( res => {
                // console.log(res);
                if(res.data == 1)
                {
                    this.loading = false;
                    this.status = 1;
                }
                this.$toast.success({
                    title: 'You successfully followed ' + user.username + '.',
                    message: 'You can now be able to contribute upon ' + user.username + '`s success with your vote.'
                });
                this.$Progress.finish();
                this.fetchUser();
          });
       },
       unfollow(user){
           Swal({
                title: 'Are you sure?',
                text: "You will no longer be able to vote for " + user.username + '.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: 'green',
                confirmButtonText: 'Yes, unfollow!'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    this.loading = true;
                    axios.get('/api/unfollow/' + user.id)
                    .then( res => {
                        //   console.log(res);
                        Swal(
                        'Unfollowed!',
                        'You have successfully unfollowed ' + user.username + '.',
                        'success'
                        )
                        if(res.data == 1)
                        {
                            this.loading = false;
                            this.status = 0;
                        }
                        this.$Progress.finish();
                        this.fetchUser();
                    });
                }
            })
       },
        fetchUser(){
            axios.get(`/api/users/${this.$route.params.id}`)
            .then(response => {
                this.$store.commit('getUserById', response.data);
            })
            .catch((err) => {
            })
        },
   },
    computed:{
        user(){
            return this.$store.getters.user
        },
        profile(){
            return this.$store.getters.profile
        },
        followings(){
            return this.$store.getters.followings
        },
        followers(){
            return this.$store.getters.followers
        },
        auth(){
            return this.$store.getters.auth
        },
    },
    mounted() {
       axios.get(`/api/check_relationship_status/${this.$route.params.id}`)
       .then(res =>{
        //    console.log(res);
           this.status = res.data.status;
           this.loading = false;
       })
   },
}
</script>

<style scoped>

</style>


