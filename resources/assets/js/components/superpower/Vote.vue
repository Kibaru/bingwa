<template>
    <div>
        <p class="text-center" v-if="loading">
            Loading . . .
        </p>
        <span v-if="!loading">
            <span>{{count.length}} votes</span>
            <span class="green">{{score}} score</span>
        </span>
    </div>
</template>

<script>
export default {
    props: ['userId'],
    data(){
        return{
            loading:false,
        }
    },
    methods:{

    },
    computed:{
        auth(){
            return this.$store.getters.auth
        },
        votes(){
            return this.$store.getters.votes
        },
        count(){
            return this.votes.filter((vote) => {
                   return vote.user_id === this.userId
            })
        },
        score(){
           var result = Math.round(this.count.length / this.votes.length * 100) + '%';
            return (isNaN(result) ? result : 0);
            // if (isNaN(val)) {
            //     return 0;
            // }
            // return val;
        },
    },
}
</script>

<style>

</style>


