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
    props: ['powerId', 'userId'],
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
                   return vote.power_id === this.powerId && vote.user_id === this.userId
            })
        },
        total(){
            return this.votes.filter((vote) => {
                   return vote.power_id === this.powerId
            })
        },
        score(){
           var result = Math.round(this.count.length / this.total.length * 100) + '%';
            return (isNaN(result) ? result : 0);
            // if (isNaN(val)) {
            //     return 0;
            // }
            // return val;
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

<style>

</style>


