<script setup>
    import { computed, ref} from 'vue'

    const props = defineProps({
        'imageClass': {
            type: String,
            default: ""
        },
        'url': {
            type: String,
            default: ""
        },
        'title': {
            type: String,
            default: ""
        },
    })

    const audioFile = ref(null)
    const barAudio = ref(null)
    const durationInSec = ref(0)
    const currentTimeInSec = ref(0)
    const reproduce = ref(false)
    const maxVolume = ref(100)
    const currentVolume = ref(100)
    const progressTracking = computed(()=>{ return ((currentTimeInSec.value / durationInSec.value)*100) })
    const progressVolume = computed(()=>{ return ((currentVolume.value / maxVolume.value)*100) })

    /** display **/
    const currentTimeDisplay = computed(()=>{ return calculateTime(currentTimeInSec.value) })
    const durationDisplay = computed(()=>{ return calculateTime(durationInSec.value) })

    /** carregamento metadados do audio **/
    function loadAudioData() {
        durationInSec.value = Math.floor(audioFile.value.duration)
    }

    /** setando tempo corrente do audio **/
   function changeTimeCurrentAudio(){
       if(!reproduce.value)
           togglePlay()
       audioFile.value.currentTime = currentTimeInSec.value
   }

    function updateLineTrack() {
        currentTimeInSec.value = Math.floor(audioFile.value.currentTime)
        if(durationInSec.value == currentTimeInSec.value){
            togglePlay()
            currentTimeInSec.value = 0
        }
    }

    /** play e pause do audio **/
    function togglePlay() {
        reproduce.value = !reproduce.value
        if (reproduce.value)
            audioFile.value.play();
        else
            audioFile.value.pause();
    }

    /** demais funções **/
    const calculateTime = (secs) => {
        const minutes = Math.floor(secs / 60);
        const seconds = Math.floor(secs % 60);
        const returnedSeconds = seconds < 10 ? `0${seconds}` : `${seconds}`;
        return `${minutes}:${returnedSeconds}`;
    }
    
    function jumpAudio(jump) {
        if(jump == "forward")
            audioFile.value.currentTime += 30
        if(jump == "back")
            audioFile.value.currentTime -= 10
    }

    function changeVolume() {
        audioFile.value.volume = (currentVolume.value / 100)
    }


</script>

<template>
    <div :class="['v-audio', imageClass]">
        <audio :src="url" ref="audioFile" @loadedmetadata="loadAudioData" @timeupdate="updateLineTrack">Seu navegador não suporta o elemento de áudio.</audio>
        <div class="player p-3">
            <div class="container-fluid">
                <div class="row header-player">
                    <div class="col-2 d-flex align-items-center">
                        <span class="times">{{ currentTimeDisplay }}</span>
                    </div>
                    <div class="col-8 d-flex justify-content-center align-items-center">
                        <span class="title">{{ title }}</span>
                    </div>
                    <div class="col-2 d-flex align-items-center d-flex justify-content-end">
                        <span class="times">{{ durationDisplay }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 py-4">
                        <div class="progress"></div>
                        <input class="bar-input" :style="{'--seek-before-width': `${progressTracking}%`}" type="range" ref="barAudio" :max="durationInSec" @input="changeTimeCurrentAudio" v-model="currentTimeInSec">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 controls d-flex">
                        <div class="jump" @click="jumpAudio('back')">
                            <span class="back icon-arrow-undo-sharp"></span>
                            <span class="title">10</span>
                        </div>
                        <span v-if="reproduce" @click="togglePlay" class="play icon-pause mx-4"></span>
                        <span v-else @click="togglePlay" class="pause icon-play mx-4"></span>
                        <div class="jump" @click="jumpAudio('forward')">
                            <span class="advance icon-arrow-redo-sharp" ></span>
                            <span class="title">30</span>
                        </div>
                    </div>
                    <div class="col-6 volume d-flex justify-content-end">
                        <div class="d-flex align-items-center">
                            <span class="icon-volume icon-volume-up-fill me-3"></span>
                            <input class="bar-input" :style="{'--seek-before-width': `${progressVolume}%`}" type="range" :max="maxVolume" @input="changeVolume" v-model="currentVolume">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .v-audio{
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        audio{
            display: none;
        }
        .player{
            width: 100%;
            background: $wth-primary;
            box-shadow: $shadow-primary;
            .header-player{
                .title, .times{
                    font-weight: 500;
                    color: $blue-primary;
                }
            }
            .bar-input{
                width: 100%;
                height: 5px;
                position: relative;
                display: block;
                -webkit-appearance: none;
                -moz-appearance: none;
                background-color: $grey-primary;
                outline: none;
                cursor: pointer;
                &::before {
                    content: "";
                    width: var(--seek-before-width);
                    height: 5px;
                    position: absolute;
                    top: 0;
                    left: 0;
                    background-color: $grey-dark-primary;
                    cursor: pointer;
                }
                &:active::-webkit-slider-thumb {
                    transform: scale(1.2);
                }
                &::-webkit-slider-thumb{
                    -webkit-appearance: none;
                    appearance: none;
                    height: 15px;
                    width: 15px;
                    background-color: $blue-primary;
                    border-radius: 50%;
                    cursor: pointer;
                    border: none;
                    position: relative;
                }
            }
            .controls{
                .play, .pause{
                    cursor: pointer;
                    font-size: 2rem;
                    color: $blue-primary;
                    cursor: pointer;
                }
                .jump{
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    cursor: pointer;
                    .back, .advance{
                        font-size: .8rem;
                        color: $blue-primary;;
                    }
                    .title{
                        font-size: .8rem;
                        color: $blue-primary;
                        font-weight: 500;
                    }
                }
            }
            .volume{
                .icon-volume{
                    font-size: 1.5rem;
                    color: $blue-primary;
                    cursor: pointer;
                }
            }
        }
    }
</style>
