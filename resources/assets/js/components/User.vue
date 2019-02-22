<template>
    <div class="wrapper page-wrapper">
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <div v-show="loading">Loading . . .</div>
                    <div v-show="!loading" class="card card-widget widget-user mt-3">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div v-if="user.cover == 'cover.jpg'" class="widget-user-header text-white" :style="{ 'background-image': 'url(' + `/storage/images/${user.cover}` + ')' }">
                            <a title="Change cover image" href="#" v-if="user.id == auth.id" @click="coverModal"><i class="fa fa-edit red"></i></a>
                            <h3 class="widget-user-desc">{{user.username}}</h3>
                            <h4 v-if="profile.country !== 'Select your country'" class="widget-user-desc">{{profile.city}} {{profile.country}}</h4>
                        </div>
                        <div v-else class="widget-user-header text-white" :style="{ 'background-image': 'url(' + `/storage/images/covers/${user.cover}` + ')' }">
                            <a title="Change cover image" href="#" v-if="user.id == auth.id" @click="coverModal"><i class="fa fa-edit red"></i></a>
                            <h3 class="widget-user-desc">{{user.username}}</h3>
                            <h4 v-if="profile.country !== 'Select your country'" class="widget-user-desc">{{profile.city}} {{profile.country}}</h4>
                        </div>
                        <div class="widget-user-image">
                            <img v-if="user.avatar == 'profile.png'" style="width:150px; height:150px; border-radius:100%;" :src="`/storage/images/${user.avatar}`" alt="User Avatar">
                            <img v-else style="width:150px; height:150px; border-radius:100%;" :src="getProfileAvatar()" alt="User Avatar">
                            <a title="Change profile image" href="#" v-if="user.id == auth.id"  @click="profileModal"><i class="fa fa-edit red"></i></a>
                        </div>
                        <div class="card-footer">

                            <!-- follow unfollow component -->
                            <Follow />

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                           <UserInfo />
                        </div>
                        <div class="col-md-10">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active show" @click="fetchCreatedPowers" href="#timeline" data-toggle="tab">Timeline</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#won" data-toggle="tab">Won Superpowers</a></li>
                                    <li class="nav-item"><a class="nav-link" @click="fetchClaimedPowers" href="#claimed" data-toggle="tab">Claimed Superpowers</a></li>
                                    <li class="nav-item" v-if="user.id == auth.id"><a class="nav-link" @click="primary(user)" href="#primary" data-toggle="tab">Primary Info</a></li>
                                    <li class="nav-item" v-if="user.id == auth.id"><a class="nav-link" @click="secondary(profile)" href="#secondary" data-toggle="tab">Secondary Info</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                    <!-- /.tab-pane -->
                                    <div class="tab-pane active show" id="timeline">
                                        <Timeline></Timeline>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <!-- won superpowers -->
                                    <div class="tab-pane" id="won">
                                        <Won></Won>
                                    </div>

                                    <!-- Claimed superpowers -->
                                    <div class="tab-pane" id="claimed">
                                        <Claimed></Claimed>
                                    </div>

                                    <div class="tab-pane" id="primary">
                                        <form class="form-horizontal" @submit.prevent="updatePrimary">
                                            <div class="form-group">
                                                <label for="firstname" class="col-sm-2 control-label">First Name</label>

                                                <div class="col-sm-10">
                                                <input type="text" v-model="form.firstname" name="firstname" placeholder="First Name"
                                                        class="form-control" :class="{ 'is-invalid': form.errors.has('firstname') }">
                                                        <has-error :form="form" field="firstname"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="lastname" class="col-sm-4 control-label">Last Name</label>

                                                <div class="col-sm-10">
                                                <input type="text" v-model="form.lastname" name="lastname" placeholder="Last Name"
                                                        class="form-control" :class="{ 'is-invalid': form.errors.has('lastname') }">
                                                        <has-error :form="form" field="lastname"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="username" class="col-sm-4 control-label">Username</label>

                                                <div class="col-sm-10">
                                                <input type="text" v-model="form.username" name="username" placeholder="Username"
                                                        class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                                                        <has-error :form="form" field="username"></has-error>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="email" class="col-sm-4 control-label">Email Address</label>

                                                <div class="col-sm-10">
                                                <input type="email" v-model="form.email" name="email" placeholder="Email Address"
                                                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                                        <has-error :form="form" field="email"></has-error>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                <button  type="submit" class="btn btn-outline-success">Update Info</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane" id="secondary">
                                        <form class="form-horizontal" @submit.prevent="updateSecondary">
                                            <div class="form-group">
                                                <label for="bios" class="col-sm-4 control-label">Bios</label>

                                                <div class="col-sm-10">
                                                    <textarea class="form-control" :class="{ 'is-invalid': form.errors.has('bios') }" v-model="form.bios" name="bio" rows="4" placeholder="Write a short description about yourself"></textarea>
                                                    <has-error :form="form" field="bios"></has-error>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="phone" class="col-sm-4 control-label">Phone Number</label>

                                                <div class="col-sm-10">
                                                <input type="text" v-model="form.phone" name="phone" placeholder="Phone Number"
                                                        class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                                        <has-error :form="form" field="phone"></has-error>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="city" class="col-sm-4 control-label">City of Residence</label>

                                                <div class="col-sm-10">
                                                <input type="text" v-model="form.city" name="city" placeholder="City of Residence"
                                                        class="form-control" :class="{ 'is-invalid': form.errors.has('city') }">
                                                        <has-error :form="form" field="city"></has-error>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="country" class="col-sm-4 control-label">Select your Country</label>

                                                <div class="col-sm-10">
                                                    <select name="country" v-model="form.country" class="form-control" :class="{ 'is-invalid': form.errors.has('country') }">
                                                        <option value="Select your country">Select your country</option>
                                                        <option v-for="country in countries" :key="country.id">{{country.country_name}}</option>
                                                    </select>
                                                    <has-error :form="form" field="country"></has-error>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                <button  type="submit" class="btn btn-outline-success">Update Info</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->

                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                                </div>

                            </div>

                            <!-- Edit Image -->
                            <div class="modal fade" id="updateImage" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 v-show="!editAvatar" class="modal-title text-primary" id="addNewLabel">Update cover image</h5>
                                        <h5 v-show="editAvatar" class="modal-title text-primary" id="addNewLabel">Update profile image</h5>
                                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>


                                    <form @submit.prevent="editAvatar ? updateAvatar() : updateCover()">
                                        <div class="modal-body">
                                            <div v-show="editAvatar" class="widget-user-image text-center">
                                                <img v-if="user.avatar == 'profile.png'" style="width:150px; height:150px; border-radius:100%;" :src="(avatar.length > 200) ? avatar : `/storage/images/${user.avatar}`">
                                                <img v-else style="width:150px; height:150px; border-radius:100%;" :src="getModalAvatar()">
                                            </div>
                                            <div class="lds-ring-container" v-if="loading">
                                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                            </div>
                                            <div v-show="editAvatar" class="form-group text-center mt-3">
                                                <input @change="updateProfile" type="file" name="avatar" ref="avatarInput" style="display:none;">
                                                <input @click="$refs.avatarInput.click()" type="button" class="btn btn-secondary btn-lg" value="Change Avatar">
                                            </div>

                                            <div v-show="!editAvatar">
                                                <div v-if="user.cover == 'cover.jpg'" class="widget-user-header text-white" :style="{ 'background-image': 'url(' + getModalCoverDefault() + ')' }"></div>
                                                <div v-else class="widget-user-header text-white" :style="{ 'background-image': 'url(' + getModalCover() + ')' }"></div>
                                            </div>
                                            <div v-show="!editAvatar" class="form-group text-center mt-3">
                                                <input @change="updateProfileCover" type="file" name="cover" ref="coverInput" style="display:none;">
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
        </div>
    </div>
</template>

<script>
import UserInfo from './user/UserInfo';
import Follow from './user/Follow';
import Timeline from './user/Timeline';
import Won from './user/Won';
import Claimed from './user/Claimed';

export default {
    components:{
        UserInfo,
        Follow,
        Timeline,
        Won,
        Claimed,
    },
    data(){
        return{
            form: new Form({
                firstname: '',
                lastname: '',
                username: '',
                email: '',
                bios: '',
                phone: '',
                city: '',
                country: '',
            }),
            avatar: '',
            cover: '',
            serverErrors: '',
            editAvatar: false,
            loading: false,
        }
    },
    methods:{
        coverModal(){
            this.editAvatar = false;
            this.serverErrors = '';
            $('#updateImage').modal('show');
        },
        profileModal(){
            this.editAvatar = true;
            this.serverErrors = '';
            $('#updateImage').modal('show');
        },
        getProfileAvatar(){
            let avatar = "/storage/images/profile/" + this.user.avatar;
            return avatar;
        },
        getModalCoverDefault(){
            let cover = (this.cover.length > 200) ? this.cover : "/storage/images/" + this.user.cover;
            return cover;
        },
        getModalCover(){
            let cover = (this.cover.length > 200) ? this.cover : "/storage/images/covers/" + this.user.cover;
            return cover;
        },
        getModalAvatar(){
            let avatar = (this.avatar.length > 200) ? this.avatar : "/storage/images/profile/" + this.user.avatar;
            return avatar;
        },
        updateProfile(e){
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
        updateProfileCover(e){
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
            axios.put('/api/users/updateAv', {
                avatar: this.avatar,
            })
            .then(response => {
                // console.log(response);
                this.loading = false;
                $('#updateImage').modal('hide');
                this.$toast.success({
                    title: 'Profile image updated successfully.',
                    message: 'Ensure to use a clear shot.'
                });
                this.$Progress.finish();
                this.fetchUser();
                this.getAuth();
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
            axios.put('/api/users/updateCo', {
                cover: this.cover,
            })
            .then(response => {
                // console.log(response);
                this.loading = false;
                $('#updateImage').modal('hide');
                this.$toast.success({
                    title: 'Cover image updated successfully.',
                    message: 'Ensure to use a clear shot.'
                });
                this.$Progress.finish();
                this.fetchUser();
                this.getAuth();
            })
            .catch(error =>{
                this.loading = false;
                // console.log(error.response.data.errors);
                this.serverErrors = error.response.data.errors.cover;
                this.$Progress.fail();
            })
        },
        updatePrimary(){
            this.$Progress.start();
            this.loading = true;
            this.form.put('/api/users/updatePri')
            .then(response => {
                // console.log(response);
                this.loading = false;
                this.$toast.success({
                    title: 'Primary info updated successfully.',
                    message: 'A complete profile is a great deal.'
                });
                this.$Progress.finish();
                this.fetchUser();
                this.getAuth();
            })
            .catch(error =>{
                // console.log(error);
                this.loading = false;
                this.$Progress.fail();
            })
        },
        updateSecondary(){
            this.$Progress.start();
            this.loading = true;
            this.form.put('/api/users/updateSec')
            .then(response => {
                // console.log(response);
                this.loading = false;
                this.$toast.success({
                    title: 'Secondary info updated successfully.',
                    message: 'A complete profile is a great deal.'
                });
                this.$Progress.finish();
                this.fetchUser();
                this.getAuth();
            })
            .catch(error =>{
                // console.log(error);
                this.loading = false;
                this.$Progress.fail();
            })
        },
        primary(user){
            this.form.firstname = this.$store.getters.user.first_name
            this.form.lastname = this.$store.getters.user.last_name
            this.form.username = this.$store.getters.user.username
            this.form.email = this.$store.getters.user.email
        },
        secondary(profile){
            this.form.bios = this.$store.getters.profile.bios
            this.form.phone = this.$store.getters.profile.phone
            this.form.city = this.$store.getters.profile.city
            this.form.country = this.$store.getters.profile.country
        },
        fetchUser(){
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.authUser.token
            axios.get(`/api/users/${this.$route.params.id}`)
            .then(response => {
                // console.log(response.data);
                this.loading = false;
                this.$store.commit('getUserById', response.data);
            })
            .catch((err) => {
                // console.log(err);
                this.loading = false;
            })
        },
        fetchCreatedPowers(){
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.authUser.token
            axios.get(`/api/users/created/${this.$route.params.id}`)
            .then(response => {
                // console.log(response);
                this.loading = false;
                this.$store.commit('fetchSuperpowers', response.data);
            })
            .catch((err) => {
                // console.log(err);
                this.loading = false;
            })
        },
        fetchClaimedPowers(){
            this.loading = true;
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.authUser.token
            axios.get(`/api/users/claimed/${this.$route.params.id}`)
            .then(response => {
                // console.log(response);
                this.loading = false;
                this.$store.commit('fetchSuperpowers', response.data);
            })
            .catch((err) => {
                // console.log(err);
                this.loading = false;
            })
        },
        getAuth(){
            this.$store.dispatch('getAuth')
            .then(() => {})
            .catch(() => {})
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        auth(){
            return this.$store.getters.auth
        },
        user(){
            return this.$store.getters.user
        },
        profile(){
            return this.$store.getters.profile
        },
        countries(){
            return this.$store.getters.countries
        },
    },
    created(){
       this.fetchUser();
    },
    watch: {
    // call again the method if the route changes
    '$route': 'fetchUser'
    },
}
</script>

<style scoped>
    .widget-user-header{
        background-size: cover;
        background-position: center center;
        height: 250px;
    }
    .widget-user-header a{
        position:absolute;
        right: 10px;
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
</style>


