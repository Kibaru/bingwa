<template>
    <div>
        <div class="row m-2">
            <div v-if="superpower.joins.length" class="col-md-6 orange">
                <div v-if="has_auth_user_voted" v-for="t in total">
                    <span v-if="auth.id == t.auth_id">You casted your vote to <router-link :title="`Click to view ${t.user.username}'s profile.`" :to="`/users/${t.user_id}`">{{t.user.username}}</router-link></span>
                </div>
                <div v-if="!has_auth_user_voted">Vote your favourite.</div>
            </div>

            <div class="col-md-6 text-right">
                <button class="btn btn-outline-success btn-sm" v-if="!auth_user_is_a_contestant" @click="join">Join challenge</button>
                <button class="btn btn-outline-danger btn-sm" v-else @click="leave">Leave challenge</button>
            </div>
        </div>
        <div v-if="!superpower.joins.length" class="text-center mt-3">
            No contestants yet. Join or invite your friends to contest.
        </div>
        <div v-else>
            <form @submit.prevent="voteUser">
                <div class="timeline-body" v-for="join in superpower.joins" :key="join.id">
                    <div class="card mx-3">
                        <div class="card-body">
                            <input type="radio" class="radio" @click="getUsername(join)" name="vote" :value="join.user.id" v-model="vote">
                            <label for="vote">
                                <router-link :to="`/users/${join.user.id}`">
                                    <img v-if="join.user.avatar == 'profile.png'" :src="`/storage/images/${join.user.avatar}`">
                                    <img v-else :src="`/storage/images/profile/${join.user.avatar}`">
                                </router-link>
                                <router-link :title="`Click to view ${join.user.username}'s profile.`" :to="`/users/${join.user.id}`">
                                    {{join.user.username}}
                                </router-link>
                            </label>
                        </div>
                        <div class="card-footer mt-2">
                            <Vote :powerId="superpower.id" :userId="join.user.id"></Vote>

                            <span class="evidence" v-if="auth.id == join.user.id" title="Add evidence to support your claim." @click="updateModal(join, id)"><i class="fa fa-plus blue"></i></span><br>
                            <span class="evidence" title="View supporting evidence" @click="showModal(join, id, )"><i class="fa fa-eye orange"></i><i class="fa fa-eye orange"></i></span>

                            <!-- Evidence -->
                            <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 v-show="!editMode" class="modal-title text-primary" id="addNewLabel">Evidence to support {{username}}'s claim.</h5>
                                            <h5 v-show="editMode" class="modal-title text-primary" id="addNewLabel">Update evidence to support your claim.</h5>
                                            <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <form v-show="editMode"  @submit.prevent="updateEvidence(id, join)">
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <label for="experience" class="control-label orange">Past experience</label>

                                                    <textarea name="experience" v-model="form.experience" class="form-control" :class="{ 'is-invalid': form.errors.has('experience') }" placeholder="Write your experience with this superpower..."></textarea>
                                                    <has-error :form="form" field="experience"></has-error>
                                                </div>

                                                <div class="form-group">
                                                    <label for="image" class="control-label orange">Supporting Image</label><br>

                                                    <input @change="updateImage" type="file" name="image" class="form-input">
                                                </div>
                                                <div v-if="imageErrors" class="server-error">
                                                    <div v-for="(value, key) in imageErrors" :key="key">{{value}}</div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="url" class="control-label orange">A link to an external source</label>

                                                    <input type="text" v-model="form.url" name="url" placeholder="Add a link" class="form-control" :class="{ 'is-invalid': form.errors.has('url') }">
                                                    <has-error :form="form" field="url"></has-error>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-outline-success mr-auto">Update</button>
                                                <button  type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                        <div v-show="!editMode" class="modal-body">
                                            <h3 class="orange">Name:</h3> <p class="ml-5">{{firstname}} {{lastname}}</p>
                                            <h3 class="orange">Experience:</h3> <p class="ml-5">{{form.experience}}</p>
                                            <h3 class="orange">Image:</h3> <p title="Click to view full page" class="ml-5"><img :src="`/storage/images/evidence/${form.image}`"></p>
                                        </div>

                                        <div v-show="!editMode" class="modal-footer">
                                            <h3 class="mr-auto blue">Link: <small title="Click to visit link" style="cursor: pointer" class="ml-5">{{form.url}}</small></h3>
                                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- end of evidence -->

                        </div>
                    </div>
                </div>
                <div v-if="vote !=''" class="text-right mr-3">
                    <h3 v-show="has_auth_user_voted" v-for="t in total" class="orange">
                        <span v-if="auth.id == t.auth_id">Casted your vote to <router-link :title="`Click to view ${t.user.username}'s profile.`" :to="`/users/${t.user_id}`">{{t.user.username}}</router-link><br><small class="black">Visit superpower to support <router-link :to="`/users/${t.user_id}`">{{t.user.username}}</router-link> through chat.</small></span>
                    </h3>
                    <span  v-show="!has_auth_user_voted">
                        <button type="submit" class="btn btn-primary btn-sm">Vote {{username}}</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Vote from './Vote';
import Evidence from './Evidence';

export default {
    props: ['id'],
    data(){
        return{
            form: new Form({
                experience: '',
                image: '',
                url: '',
            }),
            loading: true,
            editMode: true,
            vote: '',
            userId: '',
            username:'',
            firstname: '',
            lastname: '',
            imageErrors:'',
        }
    },
    components:{
        Vote,
        Evidence,
    },
    methods:{
        // evidence
        showModal(join, id){
            this.editMode = false;
            this.username = '';
            this.form.experience = ''
            this.form.image = ''
            this.form.url = ''
            $('#' + id).modal('show');
            this.username = join.user.username
            this.firstname = join.user.first_name
            this.lastname = join.user.last_name
            this.form.experience = join.experience
            this.form.image = join.image
            this.form.url = join.url
        },
        updateModal(join, id){
            this.editMode = true;
            this.form.experience = ''
            this.form.image = ''
            this.form.url = ''
            $('#' + id).modal('show');
            this.userId = join.user.id;
            this.form.fill(join);
        },
        updateImage(e){
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
                this.form.image = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
        updateEvidence(id){
            this.$Progress.start();
            this.loading = true;
            this.form.put('/api/superpowers/' + id + '/' + this.userId)
            .then(response => {
                console.log(response);
                this.loading = false;
                $('#' + id).modal('hide');
                // this.$store.commit('update_votes', response.data);
                this.$toast.success({
                    title: 'Your have successfully updated your evidence.',
                    message: 'A complete evidence could add to your success.'
                });
                this.$Progress.finish();
            })
            .catch(error =>{
                // console.log(error.response);
                this.imageErrors = error.response.data.errors.image;
                this.$Progress.fail();
            });
        },
        // vote
        getUsername(join){
            this.username = join.user.username;
        },
        voteUser(){
            this.$Progress.start();
            this.loading = true;
            axios.get('/api/superpowers/vote/' + this.id + '/' + this.vote)
            .then(response => {
                // console.log(response);
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
        // join/leave challenge
        join(){
            this.$Progress.start();
            this.loading = true;
            axios.get('/api/superpowers/join/' + this.id)
            .then(response => {
                // console.log(response);
                this.loading = false;
                this.$store.commit('update_superpower_joins', {
                    id: this.id,
                    join: response.data
                });
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
                    this.loading = true;
                    axios.get('/api/superpowers/leave/' + this.id)
                    .then(response => {
                        // console.log(response);
                        this.loading = false;
                        this.$store.commit('leave_superpower', {
                            superpower_id: this.id,
                            join_id: response.data,
                        });
                        Swal(
                        'Left this challenge!',
                        'I cant believe your are such a coward.',
                        'success'
                        )
                        this.$Progress.finish();
                    })
                }
            })
        },
    },
    computed:{
        auth(){
            return this.$store.getters.auth
        },
        // contestants
        contestants(){
            const contestants = []

            this.superpower.joins.forEach( join =>{
                contestants.push(join.user.id)
            });

            return contestants;
        },
        auth_user_is_a_contestant(){
            const check_index = this.contestants.indexOf(
                this.$store.getters.auth.id
            )

            if(check_index === -1)
                return false
            else
                return true
        },
        superpower(){
            return this.$store.state.superpowers.find(superpower =>{
                return superpower.id === this.id
            })
        },
        // votes
        votes(){
            return this.$store.getters.votes
        },
        total(){
            return this.votes.filter((vote) => {
                   return vote.power_id === this.id
            })
        },
        voters(){
            const voters = []

            this.total.forEach(t =>{
                voters.push(t.auth_id)
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
}
</script>

<style scoped>
.card{
    display: flex;
    flex-direction: row;
}
.timeline-body img{
  width:30px;
  height:30px;
  border-radius:100%;
}
.modal-body img{
  width:100px;
  height:100px;
  border-radius:20%;
}
.evidence{
    cursor: pointer;
    color: teal;
    border-bottom: 1px solid transparent;
}
.evidence:hover{
    border-bottom: 1px solid orangered;
}
@media(max-width: 768px){
    .card{
    display: flex;
    flex-direction: column;
    }
}
</style>


