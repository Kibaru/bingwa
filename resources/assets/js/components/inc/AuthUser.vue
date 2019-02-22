<template>

</template>

<script>
export default {
    name: 'authUserS',
    methods:{
        getAuth(){
            this.$store.dispatch('getAuth')
            .then(() => {})
            .catch(() => {})
        }
    },
    mounted(){
        this.getAuth();
    }
}
</script>

<style scoped>

</style>