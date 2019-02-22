import Axios from "axios";
import Vue from 'vue';

const userStr = localStorage.getItem('authUser');

export default {
    state: {
        authUser: JSON.parse(userStr) || null,
        loading: false,
        auth:{},
        user:{},
        profile:{},
        followings:[],
        followers:[],
        countries:[],
        superpowers:[],
        superpower:{},
        owner:{},
        contestants:[],
        votes:[],
        messages:[],
    },
    getters: {
        messages(state){
            return state.messages
        },
        votes(state){
            return state.votes
        },
        contestants(state){
            return state.contestants
        },
        owner(state){
            return state.owner
        },
        superpower(state){
            return state.superpower
        },
        superpowers(state){
            return state.superpowers
        },
        loggedIn(state){
            return state.authUser != null
        },
        authUser(state){
            return state.authUser
        },
        auth(state){
            return state.auth
        },
        user(state){
            return state.user
        },
        profile(state){
            return state.profile
        },
        followings(state){
            return state.followings
        },
        followers(state){
            return state.followers
        },
        countries(state){
            return state.countries
        }
    },
    mutations: {
        update_chat(state, payload){
            state.messages.push(payload);
        },
        live_chat(state, payload){
            state.messages = payload;
        },
        fetchSuperpowerById(state, payload){
            state.superpower = payload.power;
            state.owner = payload.owner;
            state.contestants = payload.contestants;
            state.votes = payload.votes;
        },
        update_contestants(state, payload){
            state.contestants.push(payload)
        },
        leave_contestants(state, payload){
            const contestant = state.contestants.find(contestant => {
                return contestant.id === payload
            });

            const index = state.contestants.indexOf(contestant);

            state.contestants.splice(index, 1);
        },
        leave_superpower(state, payload){
            const superpower = state.superpowers.find(superpower => {
                return superpower.id === payload.superpower_id
            });

            const join = superpower.joins.find(join => {
                return join.id === payload.join_id
            });

            const index = superpower.joins.indexOf(join);

            superpower.joins.splice(index, 1);
        },
        update_superpower_joins(state, payload){
            const superpower = state.superpowers.find(superpower => {
                return superpower.id === payload.id
            })

            superpower.joins.push(payload.join)
        },
        update_votes(state, payload){
            state.votes.push(payload)
        },
        fetchVotes(state, payload){
            state.votes = payload;
        },
        fetchSuperpowers(state, payload){
            state.superpowers = payload.powers;
            state.votes = payload.votes;
        },
        getUserById(state, payload){
            state.user = payload.user;
            state.profile = payload.profile;
            state.followings = payload.followings;
            state.followers = payload.followers;
        },
        getAuth(state, payload){
            state.auth = payload.auth;
            state.countries = payload.countries;
        },
        retrieveAuthUser(state, authUser){
            state.authUser = authUser
        },
        destroyToken(state){
           state.authUser = null
           state.user = null
        },
    },
    actions: {
        // get auth user
        getAuth(context){
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.authUser.token
            return new Promise((resolve, reject) => {
                axios.get('/api/auth')
                .then(response => {
                    // console.log(response.data);
                    context.commit('getAuth', response.data)
                    resolve(response)
                })
                .catch(error => {
                    // console.log(error);

                    reject(error)
                })
            })
        },

        // register new user
        registerUser(context, data){
            return new Promise((resolve, reject) => {
                axios.post('/api/register',{
                    firstname: data.firstname,
                    lastname: data.lastname,
                    username: data.username,
                    email: data.email,
                    password: data.password,
                    password_confirmation:data.password_confirmation,
                })
                .then(response => {
                    const token = response.data.token
                    const user = response.data.user
                    const authUser = Object.assign({}, user, {token} );
                    localStorage.setItem('authUser', JSON.stringify(authUser));
                    context.commit('retrieveAuthUser', authUser)
                    resolve(response)
                })
                .catch(error => {
                    // console.log(error);
                    reject(error)
                })
            })
        },

        // logout user
        destroyToken(context){
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.authUser.token
            if(context.getters.loggedIn){
                return new Promise((resolve, reject) => {
                    axios.post('/api/logout')
                    .then(response => {
                        localStorage.removeItem('authUser')
                        context.commit('destroyToken')
                        resolve(response)
                        //    console.log(response);
                    })
                    .catch(error => {
                        localStorage.removeItem('authUser')
                        context.commit('destroyToken')
                        reject(error)
                    })
                })
            }
        },

        // login user
        retrieveAuthUser(context, credentials){
            return new Promise((resolve, reject) => {
                axios.post('/api/login',{
                    username: credentials.username,
                    password: credentials.password,
                })
                .then(response => {

                    // console.log(response.data.user);
                    // console.log(response.data.token);
                    const token = response.data.token
                    const user = response.data.user
                    // localStorage.setItem('access_token', token)
                    // context.commit('retrieveToken', token)
                    const authUser = Object.assign({}, user, {token} );
                    // console.log(authUser);
                    localStorage.setItem('authUser', JSON.stringify(authUser));
                    context.commit('retrieveAuthUser', authUser)
                    resolve(response)
                })
                .catch(error => {
                    // console.log(error);
                    reject(error)
                })
            })
        },
    }
}