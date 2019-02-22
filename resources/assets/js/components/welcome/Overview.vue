<template>
    <div class="overview">

        <!-- card 1 -->
        <div class="card">
            <div class="card-header">
                <h1 class="orange">Chachisha.com <small class="ribbon green ml-5"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></small></h1>
            </div>
            <div class="card-body">
                <div class="intro">
                    <p>Hello? If you wouldn't mind some nonesense I really would appreciate some sense on this. Ever thought how the world would be like if you had some superpowers? My guess is that anyone of age enough this is all nonesense to you now but ever found yourself staring
                    at someone doing what they are best at and thought of them as geniuses or some sought of gods?</p> <p>Incase you haven't discove<span v-if="!isShowing">
                    . . .</span><span v-show="isShowing">red, someone else also stares at you doing what you are best at and wonders the same way, a times even without your notice. Now thats the wolrd and you with your superpowers in it. What do you want to do with it? But what
                    if you met someone who claim they can outshine you on these superpowers but because you don't struggle at it, you outshine them. And what if you found a platform exactly where you are fully supported to do exactly this?? Just
                    think of anything, anything you are best at, and that alone earns you a first class honour vacance on our platform.<br>
                    Chachisha, let the world know, become famous, win prizes, be spotted by potential employers, change the world and much more all here at <span class="orange">Chachisha.com.</span>  Don't be too jealous,
                    tell a friend to tell a friend coz everyone have a place . . . you just need to find yours.</span> <span class="text-primary" style="cursor: pointer;" v-if="isShowing" @click="isShowing ^= true"> << show less</span><span class="text-primary" style="cursor: pointer;" v-if="!isShowing" @click="isShowing ^= true">Show more >></span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
export default {
data(){
    return{
        isShowing:false,
    }
}
}
</script>

<style scoped>
    .overview{
    position: relative;
    transform: rotate(-3deg) skew(3deg) scale(.9);
    transition: 0.5s;
    }
    .overview .card{
    width: 90%;
    transition: 0.5s;
    background: black;
    color: white;
    border-radius: 20px;
    top: -20px;
    margin-left: auto;
    margin-right: auto;
    /* transform: translate(20px, -20px); */
    }
    .overview .card p{
        font-size: 20px;
        display:block;
    }
    .overview:hover .card
    {
        transform: rotate(3deg) skew(-3deg) scale(1);
    }
    .ribbon{
        /* display: block; */
        text-align: right;
        border-radius: 5px;
    }
    .overview:hover .ribbon i{
        animation: spin 2s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>


