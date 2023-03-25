<script setup>
const props = defineProps({
    'btnClass': {
        type: String,
        default: ""
    },
    'theme': {
        type: String,
        default: "b-default"
    },
    'tabs': {
        type: Array,
        default: false
    }
})
const emit = defineEmits(['action'])

function clickBtn() {
    if (!props.disabled)
        emit('action')
}
</script>

<template>
    <div class="v-navigation-bar">
        <router-link v-for="tab in tabs" :to="{ name: tab.route }" class="bl-item"><span>{{tab.name}}</span></router-link>
    </div>
</template>

<style scoped lang="scss">
    .v-navigation-bar{
        width: 100%;
        border-bottom: $grey-primary solid 2px;
        .bl-item{
            display: inline-block;
            margin-bottom: -2px;
            padding: 1rem;
            span{
                font-size: .9rem;
            }
        }
        .router-link-active{
            border-bottom: $blue-primary solid 2px;
        }
    }
</style>
