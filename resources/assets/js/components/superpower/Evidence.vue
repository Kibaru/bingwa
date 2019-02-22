<template>
    <div>
        <span class="evidence" v-if="auth.id == contestant.user.id" title="Add evidence to support your claim." @click="updateEvidenceModal(contestant, id)" href="#"><i class="fa fa-plus blue"></i></span><br>
        <span class="evidence" title="View supporting evidence" @click="showEvidenceModal(contestant, id)" href="#"><i class="fa fa-eye orange"></i><i class="fa fa-eye orange"></i></span>

         <!-- Evidence -->
        <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editEvidence" class="modal-title text-primary" id="addNewLabel">Evidence to support {{username}}'s claim.</h5>
                        <h5 v-show="editEvidence" class="modal-title text-primary" id="addNewLabel">Update evidence to support your claim.</h5>
                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form v-show="editEvidence"  @submit.prevent="updateEvidence">
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
                    <div v-show="!editEvidence" class="modal-body">
                        <h3 class="orange">Name:</h3> <p class="ml-5">{{firstname}} {{lastname}}</p>
                        <h3 class="orange">Experience:</h3> <p class="ml-5">{{form.experience}}</p>
                        <h3 class="orange">Image:</h3> <p title="Click to view full page" class="ml-5"><img :src="`/storage/images/evidence/${form.image}`"></p>
                    </div>

                    <div v-show="!editEvidence" class="modal-footer">
                        <h3 class="mr-auto blue">Link: <small title="Click to visit link" style="cursor: pointer" class="ml-5">{{form.url}}</small></h3>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['contestant', 'id'],
    data(){
        return{
            form: new Form({
                experience: '',
                image: '',
                url: '',
            }),
            firstname: '',
            lastname: '',
            username: '',
            editEvidence: false,
            imageErrors:'',
            userId:'',
        }
    },
    methods:{
        showEvidenceModal(contestant, id){
            this.editEvidence = false;
            this.username = '';
            this.form.experience = ''
            this.form.image = ''
            this.form.url = ''
            $('#' + id).modal('show');
            this.username = contestant.user.username
            this.firstname = contestant.user.first_name
            this.lastname = contestant.user.last_name
            this.form.experience = contestant.experience
            this.form.image = contestant.image
            this.form.url = contestant.url
        },
        updateEvidenceModal(contestant, id){
            this.editEvidence = true;
            this.form.experience = ''
            this.form.image = ''
            this.form.url = ''
            $('#' + id).modal('show');
            this.userId = contestant.user.id;
            this.form.fill(contestant);
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
        updateEvidence(){
            this.$Progress.start();
            this.loading = true;
            this.form.put(`/api/superpowers/${this.$route.params.id} /` + this.userId)
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
    },
    computed:{
        auth(){
            return this.$store.getters.auth
        },
    },
}
</script>

<style scoped>
    .evidence{
        cursor: pointer;
        color: teal;
        border-bottom: 1px solid transparent;
    }
    .evidence:hover{
        border-bottom: 1px solid orangered;
    }
    .modal-body img{
        width:100px;
        height:100px;
        border-radius:20%;
    }
</style>

