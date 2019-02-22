<template>
    <div>
        <!-- video background -->
        <video autoPlay muted loop class="video-background">
            <source src="/storage/images/bingwa.mp4" type="video/mp4" />
        </video>
        <div class="overlay"></div>
    </div>
</template>

<script>
export default {

}
</script>

<style scoped>
    .video-background{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        min-width: 100%;
        min-height: 100%;
        width: auto;
        height: auto;
        z-index: -100;
        overflow-y: scroll ;
        margin-bottom: 0;
    }
    .overlay{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        min-width: 100%;
        min-height: 155%;
        width: auto;
        height: auto;
        z-index: -50;
        background: black;
        opacity: 0.8;
    }
</style>


