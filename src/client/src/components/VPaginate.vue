<script setup>

const emit = defineEmits(['getParam'])
const props = defineProps([
    'data'
])

function previous() {
    if (props.data.previous) {
        const param = { page: props.data.currentPage - 1 };
        emit('getParam', param)
    }
}

function next() {
    if (props.data.next) {
        const param = { page: props.data.currentPage + 1 };
        emit('getParam', param)
    }
}


function toPage(page) {
    if (page !== props.data.currentPage) {
        const param = { page: page }
        emit('getParam', param)
    }
}

</script>
<template>
    <div class="v-paginate">
        <div class="paginate-container">
            <div class="bl-arrow" @click="previous">
                <span class="icone icon-chevron-left"></span>
            </div>
            <div class="bl-pages d-flex px-1">
                <div :class="[value.active ? 'active' : '', 'number-page mx-1']" v-for="value in props.data?.range"
                    @click="toPage(value.page)"><span>{{ value.page }}</span></div>
            </div>
            <div class="bl-arrow" @click="next">
                <span class="icone icon-chevron-right"></span>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .v-paginate {
        user-select: none;
        width: fit-content;

        .paginate-container {
            display: flex;

            .bl-arrow {
                width: 30px;
                height: 30px;
                background: $grey-primary;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                border-radius: $bdra-primary;

                .icone {
                    color: $blue-primary;
                    font-size: 1.5rem;
                }
            }

            .bl-pages {
                .number-page {
                    width: 30px;
                    height: 30px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    cursor: pointer;
                    border-radius: $bdra-primary;

                    span {
                        font-weight: 400;
                        font-size: 1.2rem;
                    }
                }

                .active {
                    background: $blue-primary;
                    span{
                        color: $grey-primary;
                    }

                }
            }
        }
    }
</style>
