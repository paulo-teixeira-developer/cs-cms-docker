<script setup>
import { onMounted, watch} from 'vue'
import { useRoute, useRouter } from 'vue-router'

/**component**/
import VButtonLink from '@/components/VButtonLink.vue'
import VPaginate from '@/components/VPaginate.vue'
import PostTable from '@/components/PostTable.vue'
/**stores**/
import { usePostStore } from '@/stores/post.js'

/**propertys**/
const postStore = usePostStore()
const route = useRoute()
const router = useRouter()

onMounted(() => {
    postStore.getPosts(route.query)
})

watch(() => route.query, (newQuery) => {

    if (route.name == "postList")
        postStore.getPosts(newQuery)
})

function pagination(param) {
    router.push({ name: 'postList', query: {...param, ...{order: 'desc'}} })
}

function goShow(id) {
    router.push({ name: 'postShow', params: { id: id } })
}

</script>
<template>
    <div class="post-index">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <v-button-link theme="b-outline-blue" :push="{ name: 'postInsert' }">Novo Post</v-button-link>
                </div>
            </div>
            <div class="row">
                <div class="col-12 py-3">
                    <post-table :content="postStore.posts.data" @action="goShow" />
                </div>
                <div class="col-12 d-flex justify-content-center pt-3">
                    <v-paginate :data="postStore.posts.paginate" @get-param="pagination" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.post-index{
    @include v-content-main;
}
</style>


