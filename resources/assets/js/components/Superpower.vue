<template>
    <div class="wrapper page-wrapper">
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card card-widget widget-user">
                                <div class="card-header">
                                    <EditPower></EditPower>
                                    <div v-if="superpower.cover == 'scover.jpeg'" class="widget-user-header text-white" :style="{ 'background-image': 'url(' + `/storage/images/${superpower.cover}` + ')' }">
                                        <a title="Change superpower cover image" href="#" v-if="owner.id == auth.id" @click="coverModal"><i class="fa fa-edit orange"></i></a>
                                        <h3 class="widget-user-desc red">{{superpower.superpower}}</h3>
                                    </div>
                                    <div v-else class="widget-user-header text-white" :style="{ 'background-image': 'url(' + `/storage/images/covers/${superpower.cover}` + ')' }">
                                        <a title="Change superpower cover image" href="#" v-if="owner.id == auth.id" @click="coverModal"><i class="fa fa-edit orange"></i></a>
                                        <h3 class="widget-user-desc red">{{superpower.superpower}}</h3>
                                    </div>

                                    <div class="widget-user-image">
                                        <img v-if="superpower.avatar == 'superpower.jpg'" style="width:150px; height:150px; border-radius:100%;" :src="`/storage/images/${superpower.avatar}`">
                                        <img v-else style="width:150px; height:150px; border-radius:100%;" :src="`/storage/images/superpower/${superpower.avatar}`" alt="Superpower Avatar">
                                        <a title="Change superpower profile image" href="#" v-if="owner.id == auth.id"  @click="superpowerModal"><i class="fa fa-edit blue"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="timeline timeline-inverse" >
                                        <!-- timeline time label -->
                                        <li class="time-label">
                                            <span class="bg-success">
                                                {{superpower.created_at | myDate}}
                                            </span>
                                        </li>
                                        <li class="text-right">
                                            <small>Created by <router-link :title="`Click to view ${owner.username}'s profile.`" class="teal" :to="`/users/${owner.id}`">{{owner.username}}</router-link></small>
                                        </li>
                                        <!-- /.timeline-label -->
                                        <!-- timeline item -->
                                        <li>

                                            <div class="timeline-item">
                                                <div class="row m-2">
                                                    <div class="col-md-6 orange" v-if="contestants.length">
                                                        <div v-if="has_auth_user_voted" v-for="vote in votes">
                                                            <span v-if="auth.id == vote.auth_id">You casted your vote to <router-link :title="`Click to view ${vote.user.username}'s profile.`" :to="`/users/${vote.user_id}`">{{vote.user.username}}</router-link></span>
                                                        </div>
                                                        <div v-if="!has_auth_user_voted">Vote your favourite.</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="timeline-header text-right">
                                                            <button class="btn btn-success btn-sm" v-if="!auth_user_is_a_contestant" @click="join">Join challenge</button>
                                                            <button class="btn btn-danger btn-sm" v-else @click="leave">Leave challenge</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div v-if="contestants.length">
                                                    <form @submit.prevent="voteUser">
                                                        <div class="timeline-body" v-for="contestant in contestants" :key="contestant.id">
                                                            <div class="card m-3">
                                                                <div class="card-body mt-3">
                                                                    <input type="radio" name="vote" class="radio" @click="getUsername(contestant)" :value="contestant.user.id" v-model="vote">
                                                                    <label for="vote">
                                                                        <router-link :to="`/users/${contestant.user.id}`">
                                                                            <img v-if="contestant.user.avatar == 'profile.png'" style="width:30px; height:30px; border-radius:100%;" :src="`/storage/images/${contestant.user.avatar}`">
                                                                            <img v-else style="width:30px; height:30px; border-radius:100%;" :src="`/storage/images/profile/${contestant.user.avatar}`">
                                                                        </router-link>
                                                                        <router-link :title="`Click to view ${contestant.user.username}'s profile.`" :to="`/users/${contestant.user.id}`">
                                                                            {{contestant.user.username}}
                                                                        </router-link>
                                                                    </label>
                                                                </div>
                                                                <div class="card-footer">
                                                                    <Vote :userId="contestant.user.id"></Vote>
                                                                    <Evidence :id="contestant.id" :contestant="contestant"></Evidence>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div v-if="vote !=''" class="text-right mr-3">
                                                            <h3 v-show="has_auth_user_voted" v-for="vote in votes" class="orange">
                                                                <span v-if="auth.id == vote.auth_id">Casted your vote to <router-link :title="`Click to view ${vote.user.username}'s profile.`" :to="`/users/${vote.user_id}`">{{vote.user.username}}</router-link><br><small class="black">Support <router-link :to="`/users/${vote.user_id}`">{{vote.user.username}}</router-link> through chat.</small></span>
                                                            </h3>
                                                            <span  v-show="!has_auth_user_voted">
                                                                <button type="submit" class="btn btn-primary btn-sm">Vote {{username}}</button>
                                                            </span>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div v-else class="text-center mt-3">
                                                    No contestants yet. Join or invite your friends to contest.
                                                </div>

                                                <div class="timeline-footer mt-3">
                                                </div>
                                            </div>
                                        </li>
                                        <!-- END timeline item -->

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <Chat></Chat>
                        </div>
                    </div>

                     <!-- Edit Image -->
                    <div class="modal fade" id="updateImage" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 v-show="!editAvatar" class="modal-title text-primary" id="addNewLabel">Update cover image</h5>
                                <h5 v-show="editAvatar" class="modal-title text-primary" id="addNewLabel">Update superpower profile image</h5>
                                <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>


                            <form @submit.prevent="editAvatar ? updateAvatar() : updateCover()">
                                <div class="modal-body">
                                    <div v-show="editAvatar" class="widget-user-image text-center">
                                        <img v-if="superpower.avatar == 'superpower.jpg'" style="width:150px; height:150px; border-radius:100%;" :src="(avatar.length > 200) ? avatar : `/storage/images/${superpower.avatar}`">
                                        <img v-else style="width:150px; height:150px; border-radius:100%;" :src="getModalAvatar()">
                                    </div>
                                    <div class="lds-ring-container" v-if="loading">
                                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                    </div>
                                    <div v-show="editAvatar" class="form-group text-center mt-3">
                                        <input @change="updatePowerProfile" type="file" name="avatar" ref="avatarInput" style="display:none;">
                                        <input @click="$refs.avatarInput.click()" type="button" class="btn btn-secondary btn-lg" value="Change Avatar">
                                    </div>

                                    <div v-show="!editAvatar">
                                        <div v-if="superpower.cover == 'scover.jpeg'" class="widget-user-header text-white" :style="{ 'background-image': 'url(' + getModalCoverDefault() + ')' }"></div>
                                        <div v-else class="widget-user-header text-white" :style="{ 'background-image': 'url(' + getModalCover() + ')' }"></div>
                                    </div>

                                    <div v-show="!editAvatar" class="form-group text-center mt-3">
                                        <input @change="updatePowerCover" type="file" name="cover" ref="coverInput" style="display:none;">
                                        <input @click="$refs.coverInput.click()" type="button" class="btn btn-secondary btn-lg" value="Change Cover">
                                    </div>
                                    <div class="server-error" v-show="serverErrors">
                                        <span v-for="(fieldError, fieldName) in serverErrors" :key="fieldName">{{fieldError}}</span>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button v-show="!editAvatar" type="submit" class="btn btn-outline-primary mr-auto">Upload</button>
                                    <button v-show="editAvatar" type="submit" class="btn btn-outline-success mr-auto">Upload</button>
                                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import EditPower from './superpower/EditPower';
import Vote from './superpower/Vote';
import Evidence from './superpower/Evidence';
import Chat from './superpower/Chat';

export default {
    data(){
        return{
            avatar: '',
            cover: '',
            vote: '',
            username:'',
            serverErrors:'',
            editAvatar: false,
            loading: false,
        }
    },
    components:{
        EditPower,
        Vote,
        Evidence,
        Chat,
    },
    methods:{
        getUsername(contestant){
            this.username = contestant.user.username;
        },
        coverModal(){
            this.editAvatar = false;
            this.serverErrors = '';
            $('#updateImage').modal('show');
        },
        superpowerModal(){
            this.editAvatar = true;
            this.serverErrors = '';
            $('#updateImage').modal('show');
        },
        getPowerAvatar(){
            let avatar = "/storage/images/superpower/" + this.$store.getters.superpower.avatar;
            return avatar;
        },
        getModalCoverDefault(){
            let cover = (this.cover.length > 200) ? this.cover : "/storage/images/" + this.$store.getters.superpower.cover;
            return cover;
        },
        getModalCover(){
            let cover = (this.cover.length > 200) ? this.cover : "/storage/images/covers/" + this.$store.getters.superpower.cover;
            return cover;
        },
        getModalAvatar(){
            let avatar = (this.avatar.length > 200) ? this.avatar : "/storage/images/superpower/" + this.$store.getters.superpower.avatar;
            return avatar;
        },
        updatePowerProfile(e){
            this.loading = true;
            let file = e.target.files[0];
            // console.log(file);
            let reader = new FileReader();
            if(file['size'] > 2111775){
                this.loading = false;
                Swal(
                    'Oops...',
                    'Your file is too large!',
                    'error'
                )
            }
            else{
               reader.onloadend = (file) => {
                // console.log('RESULT', reader.result)
                this.loading = false;
                this.avatar = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
        updatePowerCover(e){
            this.loading = true;
            let file = e.target.files[0];
            // console.log(file);
            let reader = new FileReader();
            if(file['size'] > 2111775){
                this.loading = false;
                Swal(
                    'Oops...',
                    'Your file is too large!',
                    'error'
                )
            }
            else{
               reader.onloadend = (file) => {
                // console.log('RESULT', reader.result)
                this.loading = false;
                this.cover = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
        updateAvatar(){
            this.loading = true;
            this.$Progress.start();
            axios.put(`/api/superpowers/updatePowerAvatar/${this.$route.params.id}`, {
                avatar: this.avatar,
            })
            .then(response => {
                // console.log(response);
                this.loading = false;
                $('#updateImage').modal('hide');
                this.$toast.success({
                    title: 'Superpower profile image updated.',
                    message: 'Ensure to use a clear shot.'
                });
                this.$Progress.finish();
                this.fetchSuperpowerById();
            })
            .catch(error =>{
                this.loading = false;
                // console.log(error.response.data.errors);
                this.serverErrors = error.response.data.errors.avatar;
                this.$Progress.fail();
            })
        },
        updateCover(){
            this.loading = true;
            this.$Progress.start();
            axios.put(`/api/superpowers/updatePowerCover/${this.$route.params.id}`, {
                cover: this.cover,
            })
            .then(response => {
                // console.log(response);
                this.loading = false;
                $('#updateImage').modal('hide');
                this.$toast.success({
                    title: 'Superpower cover image updated.',
                    message: 'Ensure to use a clear shot.'
                });
                this.$Progress.finish();
                this.fetchSuperpowerById();
            })
            .catch(error =>{
                this.loading = false;
                // console.log(error.response.data.errors);
                this.serverErrors = error.response.data.errors.cover;
                this.$Progress.fail();
            })
        },
        voteUser(){
            this.$Progress.start();
            this.loading = true;
            axios.get(`/api/superpowers/vote/${this.$route.params.id}/` + this.vote)
            .then(response => {
                console.log(response);
                this.loading = false;
                this.$store.commit('update_votes', response.data);
                this.$toast.success({
                    title: 'Your vote was successfully casted to ' + this.username,
                    message: 'Thanks for your vote.'
                });
                this.$Progress.finish();
            })
            .catch(err =>{
                console.log(err);
            });
        },
        join(){
            this.$Progress.start();
            axios.get(`/api/superpowers/join/${this.$route.params.id}`)
            .then(response => {
                // console.log(response)
                this.$store.commit('update_contestants', response.data);
                this.$toast.success({
                    title: 'Your have successfully joined this challenge.',
                    message: 'Invite your friends to vote you in.'
                });
                this.$Progress.finish();
            })
        },
        leave(){
            Swal({
                title: 'Are you sure?',
                text: "You risk your chance of becoming this superman of your dream.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: 'green',
                confirmButtonText: 'Yes, leave!'
            }).then((result) => {
                if (result.value) {
                    this.$Progress.start();
                    axios.get(`/api/superpowers/leave/${this.$route.params.id}`)
                    .then(response => {
                        // console.log(response);
                        this.$store.commit('leave_contestants', response.data);
                        Swal(
                        'Left challenge!',
                        'I cant believe your are such a coward.',
                        'success'
                        )
                        this.$Progress.finish();
                    })
                }
            })
        },
        fetchSuperpowerById(){
            axios.get(`/api/superpowers/fetch/${this.$route.params.id}`)
            .then(response => {
                // console.log(response.data);
                this.$store.commit('fetchSuperpowerById', response.data);
            })
            .catch(error => {})
        },
    },
    computed:{
        auth(){
            return this.$store.getters.auth
        },
        superpower(){
            return this.$store.getters.superpower
        },
        owner(){
            return this.$store.getters.owner
        },
        contestants(){
           return this.$store.getters.contestants
        },
        // contestants ids
        contestants_ids(){
            const contestants_ids = []

            this.contestants.forEach(contestant =>{
                contestants_ids.push(contestant.user.id)
            });

            return contestants_ids;
        },
        auth_user_is_a_contestant(){
            const check_index = this.contestants_ids.indexOf(
                this.$store.getters.auth.id
            )

            if(check_index === -1)
                return false
            else
                return true
        },
        // votes
        votes(){
            return this.$store.getters.votes
        },
        voters(){
            const voters = []

            this.votes.forEach(vote =>{
                voters.push(vote.auth_id)
            });

            return voters;
        },
        has_auth_user_voted(){
            const check_index = this.voters.indexOf(
                this.$store.getters.auth.id
            )

            if(check_index === -1)
                return false
            else
                return true
        },
    },
    mounted(){
        this.fetchSuperpowerById();
    },
    watch: {
    // call again the method if the route changes
    '$route': 'fetchSuperpowerById'
    },
}
</script>

<style scoped>
    .card-widget{
        height: 777px;
        overflow-y: scroll;
    }
    .widget-user-header{
        background-size: cover;
        min-width: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        height: 220px;
    }
    .widget-user-header a{
        position:absolute;
        right: 20px;
        display: none;
    }
    .widget-user-image a{
        position:absolute;
        right: 0;
        top: 0;
        display: none;
    }
    .widget-user-header:hover a{
        display: block;
    }
    .widget-user-image:hover a{
        display: block;
    }
    .timeline-header{
        left: 0;
    }
    .timeline-body .card{
        display: flex;
        flex-direction: row;
    }
    @media(max-width: 768px){
       .timeline-body .card{
        display: flex;
        flex-direction: column;
        }
    }
</style>


