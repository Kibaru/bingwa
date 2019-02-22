<template>
    <div class="card">
        <div class="card-header">
            <h2 class="widget-user-desc">{{user.first_name}} {{user.last_name}}</h2>
        </div>
        <div v-if="profile.bios" class="card-body">
            <p>{{profile.bios}}</p>
        </div>
        <div v-else class="card-body">
            <p>No bios yet.</p>
        </div>
    </div>
</template>

<script>
export default {
   computed:{
       user(){
           return this.$store.getters.user
       },
       profile(){
           return this.$store.getters.profile
       }
   }
}
</script>

<style>

</style>


