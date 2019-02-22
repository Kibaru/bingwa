<template>

</template>

<script>
export default {
    name: 'logout',
    created(){
        this.$Progress.start();
        this.$store.dispatch('destroyToken')
        .then(response => {
            this.$router.push({name: 'welcome'})
            this.$toast.success({
                title: 'Logged out successfully.',
                message: 'See you soon.'
            });
            this.$Progress.finish();
        })
        .catch(error =>{
            this.$router.push({name: 'welcome'});
            // console.log(error.response);
            this.$Progress.fail();
        })
    }
}
</script>

<style scoped>

</style>


