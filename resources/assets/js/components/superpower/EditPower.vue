<template>
    <div>
        <div v-show="owner.id == auth.id" class="edit text-right">
            <a href="#" title="Edit this superpower" @click="editPower(superpower)"><i class="fa fa-pen blue"></i></a ><br>
            <a href="#" v-show="contestants.length < 1" title="Delete this superpower" @click="deletePower(superpower.id)"><i class="fa fa-trash red"></i></a >
            <hr style=" background: black;" />
        </div>
         <!-- Edit Superpower -->
        <div class="modal fade" id="updateSuperpower" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary" id="addNewLabel">Update superpower info</h5>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="updatePower">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="superpower" class="control-label">Superpower title</label>

                            <input type="text" name="superpower" v-model="form.superpower" class="form-control" :class="{ 'is-invalid': form.errors.has('superpower') }">
                            <has-error :form="form" field="superpower"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="category" class="control-label">Select category</label>

                            <select name="category" id="category" v-model="form.category" class="form-control" :class="{ 'is-invalid': form.errors.has('category') }">
                                <option value="Select category">Select category</option>
                                <option value="Sports">Sports</option>
                                <option value="Music and Art">Music and Art</option>
                                <option value="Science and Technology">Science and Technology</option>
                                <option value="Educational">Educational</option>
                                <option value="Religion believes">Spiritual believes</option>
                                <option value="Politics">Politics</option>
                                <option value="Tradition and Culture">Tradition and Culture</option>
                                <option value="Comedy">Comedy</option>
                                <option value="Magicians">Magicians</option>
                                <option value="Agricuture">Agricuture</option>
                                <option value="Business">Business</option>
                                <option value="Others">Others</option>
                            </select>
                            <has-error :form="form" field="category"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary mr-auto" :disabled="loading">
                            <div class="lds-ring-container" v-if="loading">
                                <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                            </div>
                            Update
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            form: new Form({
                id: '',
                superpower:'',
                category:'',
            }),
            loading: false,
        }
    },
    methods:{
        editPower(superpower){
            this.form.reset();
            $('#updateSuperpower').modal('show');
            this.form.fill(superpower);
        },
       updatePower(){
            this.$Progress.start();
            this.form.put('/api/superpowers/updatePower/'+this.form.id)
            .then(() => {
                $('#updateSuperpower').modal('hide');
                this.$toast.success({
                    title: 'Updated!',
                    message: 'Superpower info has been updated.'
                });
                this.$Progress.finish();
                this.fetchSuperpowerById();
            })
            .catch(() => {
                this.$Progress.fail();
            })
        },
        deletePower(id){
            Swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                // send request to the server
                if (result.value) {
                    this.$Progress.start();
                    this.form.delete('/api/superpowers/deletePower/'+id)
                    .then((res) => {
                        console.log(res);
                        Swal(
                        'Deleted!',
                        'Superpower has been deleted.',
                        'success'
                        )
                        this.$router.push({name: 'welcome'})
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                        Swal("Failed!", "There was something wrong.", "warning");
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
    },
    mounted(){
        this.fetchSuperpowerById();
    }
}
</script>

<style scoped>
.edit a{
    opacity: 0;
}
.card-header:hover a{
    opacity: 1;
}
</style>


