<template>
    <div class="wrapper page-wrapper">
        <div class="content-wrapper">
            <div class="content">
                <div class="container-fluid">
                    <h1 v-show="!loading">Trending superpowers</h1>
                    <h1 v-show="loading">Loading . . .</h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'trend',
    data(){
        return{
            loading: false,
        }
    },
    methods:{
        getTrending(){
            this.loading = true;
            axios.get('/api/superpowers/trending')
            .then(response => {
                console.log(response);
                this.loading = false;
            })
            .catch(err => {
                console.log(err);
                this.loading = false;
            })

        }
    },
    mounted(){
        this.getTrending();
    }
}
</script>

<style scoped>

</style>