<template>
<div class="card">
    <div class="card-header">
        <h2 class="text-center mt-3 orange">Chachi-live <small class="fa fa-comments bg-warning"></small></h2>
    </div>
    <div class="card-body">
        <div ref="feed" class="feed">
            <ul>
                <li v-show="messages.length" v-for="message in messages" class="message" :key="message.id">
                    <div :class="message.user_id == auth.id ? ' sent' : ' received'">
                            <div class="text">
                                <router-link :title="`Click to view ${message.user.username}'s profile.`" :to="`/users/${message.user.id}`">
                                    <img v-if="message.user.avatar == 'profile.png'" :src="`/storage/images/${message.user.avatar}`">
                                    <img v-else :src="`/storage/images/profile/${message.user.avatar}`"> {{message.user.username}}
                                </router-link> {{message.message}} <br>
                                <small class="text-right">{{message.created_at | myDate}}</small>
                            </div>
                    </div>
                </li>
                <li v-show="!messages.length" class="message">
                    <div class="green text-center">
                        No conversation yet! Take the privillege.
                    </div>
                </li>
            </ul>
        </div>
        <div class="composer">
            <textarea name="message" v-model="message" @keydown.enter="send" :placeholder="`Hallo ${auth.username}? Message . . .`"></textarea>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data(){
        return{
            message: '',
        }
    },
    methods:{
        scrollToBottom(){
            setTimeout(() => {
                this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
            }, 50);
        },
        send(e){
            e.preventDefault();

            if(this.message != ''){
                console.log(this.message);
                axios.post(`/api/superpowers/postMessages/${this.$route.params.id}`, {
                    message: this.message
                })
                .then(response => {
                    console.log(response);
                    this.message = '';
                    this.$store.commit('update_chat', response.data);
                })
                .catch(error => {
                    console.log(error);

                });
            }else{
                return;
            }
        },
        getMessages(){
            axios.get(`/api/superpowers/getMessages/${this.$route.params.id}`)
            .then( response => {
                console.log(response);
                this.$store.commit('live_chat', response.data);

            })
            .catch(error => {
                console.log(error);

            });
        }
    },
    computed:{
        auth(){
            return this.$store.getters.auth
        },
        messages(){
            return this.$store.getters.messages
        }
    },
    watch:{
        messages(){
            this.scrollToBottom();
        }
    },
    mounted(){
        this.getMessages();
    }
}
</script>

<style scoped>
    .card-body{
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-radius: 3px;
        padding-top: 0;
        padding-right: 0px;
        padding-left: 10px;
        padding-bottom: 0;
    }
    .composer textarea{
        width: 96%;
        margin: 10px;
        resize: none;
        border-radius: 3px;
        border: 1px solid orange;
        margin: 10px;
    }
    .feed{
        background: #f0f0f0;
        height: 100%;
        max-height: 250px;
        overflow-y: scroll
    }
    .feed ul{
        list-style: none;
        padding: 5px;
    }
    .message{
        margin: 10px 0;
        width: 100%;
    }
    .text{
        max-width: 200px;
        border-radius: 5px;
        padding: 12px;
        display: inline-block;
    }
    .received{
        text-align: left;
    }
    .received .text{
        background: #b2b2b2;
    }
    .sent{
        text-align: right;
    }
    .sent .text{
        background: #81c4f9;
    }
    .text img{
        width:20px;
        height:20px;
        border-radius:100%;
    }
</style>


