<template>
    <div>
        <a v-if="auth.id == join.user.id" title="Add evidence to support your claim." @click="updateModal(join, powerId, joinId)" href="#"><i class="fa fa-plus red"></i></a><br>
        <a @click="showModal(join, powerId, joinId)" href="#">View supporting evidence</a>

         <!-- Evidence -->
        <div class="modal fade" :id="powerId + joinId" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="!editMode" class="modal-title text-primary" id="addNewLabel">Evidence to support {{username}}'s claim.</h5>
                        <h5 v-show="editMode" class="modal-title text-primary" id="addNewLabel">Update evidence to support your claim.</h5>
                        <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form v-show="editMode"  @submit.prevent="updateJoin">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="experience" class="col-sm-4 control-label">Experience</label>

                                <textarea class="form-control" :class="{ 'is-invalid': form.errors.has('experience') }" v-model="form.experience" name="bio" rows="4" placeholder="Write a short description about yourself"></textarea>
                                <has-error :form="form" field="experience"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="image" class="control-label">Supporting Image</label><br>

                                <input type="file" name="image" class="form-input" :class="{ 'is-invalid': form.errors.has('image') }">
                                    <has-error :form="form" field="image"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="video" class="control-label">Supporting Video</label><br>

                                <input type="file" name="video" class="form-input" :class="{ 'is-invalid': form.errors.has('video') }">
                                    <has-error :form="form" field="video"></has-error>
                            </div>

                            <div class="form-group">
                                <label for="url" class="control-label">A link to an external source</label>

                                <input type="url" v-model="form.url" name="url" placeholder="Add a link"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('url') }">
                                    <has-error :form="form" field="url"></has-error>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-success mr-auto">Update</button>
                            <button  type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                    <div v-show="!editMode" class="modal-body">
                        <p>{{firstname}} {{lastname}}</p>
                    </div>

                    <div v-show="!editMode" class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['join', 'powerId', 'joinId'],
    data(){
        return{
            form: new Form({
                experience: '',
                video: '',
                image: '',
                url: '',
            }),
            firstname: '',
            lastname: '',
            username: '',
            editMode: true,
        }
    },
    methods:{
        showModal(join, powerId, joinId){
            this.editMode = false;
            $('#' + powerId + ',' + joinId).modal('show');
            this.username = join.user.username
            this.firstname = join.user.first_name
            this.lastname = join.user.last_name
        },
        updateModal(join, powerId, joinId){
            this.editMode = true;
            $('#' + powerId + joinId).modal('show');
            this.form.fill(join);
        },
        updateJoin(){

        }
    },
    computed:{
        auth(){
            return this.$store.getters.auth
        },
    },
}
</script>

<style scoped>

</style>


