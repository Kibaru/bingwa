<template>
    <div class="autocomplete">
        <a href="#" class="input orange" @click="toggleVisible"><i class="fa fa-search"></i> users</a>
        <div class="popover" v-show="visible">
            <input type="text"
            ref="input"
            v-model="query"
            @keydown.up="up"
            @keydown.down="down"
            @keydown.enter="selectItem"
            placeholder="search users . . ."
            >
            <div class="options" ref="optionsList" v-if="queryResult">
                <ul>
                    <li v-for="(user, index) in users"
                        :key="index"
                        :class="{ 'selected': (selected == index)}"
                        @click="itemClicked(index)">
                            <router-link :to="`/users/${user.id}`">
                                <img v-if="user.avatar == 'profile.png'" :src="`/storage/images/${user.avatar}`">
                                <img v-else :src="`/storage/images/profile/${user.avatar}`">
                                <span>{{user.username}}</span>
                            </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data() {
      return{
        visible: false,
        itemHeight: 39,
        selected: 0,
        selectedItem: null,
        query: '',
        items: [],
      }
  },
  methods:{
    toggleVisible(){
        this.visible = !this.visible;
        setTimeout(() => {
            this.$refs.input.focus();
        }, 50);
    },
    allUsers(){
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.authUser.token
        axios.get('/api/users')
        .then(response =>{
            // console.log(response.data);
            this.items = response.data;
        })
        .catch(err => {
            console.log(err);
        })
    },

    itemClicked(index){
        this.selected = index;
        // console.log(this.users[index]);
        this.selectItem();
    },
    selectItem(){
        this.selectedItem = this.users[this.selected];
        this.$router.push('/users/' + this.users[this.selected].id);
        this.visible = false;
    },
    up(){
        if(this.selected == 0){
            return;
        }

        this.selected -= 1;
        this.scrollToItem();
    },
    down(){
        if(this.selected >= this.users.length - 1){
            return;
        }

        this.selected += 1;
        this.scrollToItem();
    },
    scrollToItem(){
        this.$refs.optionsList.scrollTop = this.selected * this.itemHeight
    },
  },
  computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        users(){
            if(this.query == ''){
                return [];
            }
            return this.items.filter((item) => {
                   return item.username.toLowerCase().includes(this.query.toLowerCase())
            })
        },
        queryResult(){
            return this.query != ''
        },
    },
    mounted(){
        this.allUsers();
        setInterval(() => {this.allUsers()}, 1000*60*60);
    },
}
</script>

<style scoped>
    .autocomplete{
        width: 50%;
        position: relative;
    }
    .input{
        height: 40px;
        border-radius: 3px;
        text-decoration: none;
        border: 2px solid transparent;
        box-shadow: 0 0 10px #eceaea;
        font-size: 25px;
        margin-left: 10px;
        padding: 5px;
        cursor: pointer;
    }
    .input:hover{
       border: 2px solid #fb6806;
    }
    .popover{
        min-height: 50px;
        border: 2px solid lightgray;
        position: absolute;
        top: 46px;
        left: 10px;
        right: 0;
        background: #fff;
        border-radius: 3px;
        text-align: center;
    }
    .popover input{
        width: 95%;
        margin-top: 5px;
        height: 40px;
        padding-left: 8px;
        font-size: 16px;
        border-radius: 3px;
        border: 1px solid lightgray;
    }
    .options{
        max-height: 150px;
        overflow-y: scroll;
        margin-top: 5px;
    }
    .options ul{
       list-style-type: none;
       text-align: left;
       padding-left: 0;
    }
    .options ul li{
        border-bottom: 1px solid lightgray;
        padding: 10px;
        cursor: pointer;
        background: #f1f1f1;
        font-weight: 600;
        font-size: 20px;
    }
    .options ul li a{
        text-decoration: none;
        color: orangered;
    }
    .options li.selected{
        background: grey;
        color: #fff;
        font-weight: 600;
        font-size: 20px;
        border-radius: 3px;
    }
    .options li.selected a{
        color: #fff;
    }
    img{
        width:25px;
        height:25px;
        border-radius:50%;
    }
</style>





