<script setup>

    /** component **/
    import VNavigationBar from '@/components/VNavigationBar.vue'
    import VReadOnlyField from '@/components/VReadOnlyField.vue'

    /** store **/
    import { useAccountStore } from '@/stores/account.js'
    import { useAuthStore } from '@/stores/auth.js'

    /** propiedades do componente **/
    const accountStore = useAccountStore();
    const authStore = useAuthStore();

</script>

<template>
    <div class="account-general">
       <div class="container-fluid">
           <div class="row content">
               <div class="col-12 d-flex justify-content-center">
                   <div class="profile d-flex flex-column justify-content-center align-items-center">
                       <div class="account-img mb-3 d-flex justify-content-center align-items-center" :style="{ backgroundImage: `url(${accountStore.accountImg})` }">
                           <span v-if="!accountStore.accountImg" class="abbr-name">{{ authStore.auth.user.abbrName }}</span>
                       </div>
                       <span class="full-name mb-2">{{ accountStore.accountState.person.name+"&nbsp;"+accountStore.accountState.person.last_name }}</span>
                       <span class="email mb-4">{{ accountStore.accountState.email }}</span>
                   </div>
               </div>
               <div class="col-12">
                   <div class="info p-3">
                       <v-read-only-field size="small" type="vertical" class="mb-3" label="Data de nascimento" :content="accountStore.accountState.person.birth"/>
                       <v-read-only-field size="small" type="vertical" class="mb-3" label="Profissão" :content="accountStore.accountState.person.profession"/>
                       <v-read-only-field size="small" type="vertical" label="Biografia" :content="accountStore.accountState.person.biography"/>
                   </div>
               </div>
           </div>
       </div>
    </div>
</template>

<style scoped lang="scss">
    .account-general {
        width: 100%;
        height: max-content;
        @include v-content-main;
        .content{
            .profile{
                width: max-content;
                .account-img {
                    width: 150px;
                    height: 150px;
                    background: $blue-primary;
                    border-radius: 50%;
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;
                    box-shadow: $shadow-primary;
                    .abbr-name{
                        color: $wth-primary;
                        font-weight: 400;
                        font-size: 3rem;
                    }
                }
                .full-name{
                    font-size: 1rem;
                    font-weight: 500;
                }
                .email{
                    font-size: .8rem;
                    font-weight: 400;
                }
            }


            .info{
                width: 100%;
                height: max-content;
                background: $wth-primary;
                //border: $br-primary;
                border-radius: $bdra-primary;
                box-shadow: $shadow-primary;
            }
        }
    }
</style>