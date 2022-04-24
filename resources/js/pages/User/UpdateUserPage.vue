<template>
    <div class="update-user-page">
        <v-container fluid>
            <bread-crumbs-component :items="items" />
            <v-btn class="mt-3" @click="$router.push('/home/users')" color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>
            <v-card width="650" class="mt-5" elevation="0">
                <v-card-title>Modifier un utilisateur</v-card-title>
                <v-card-text>
                    <form class="flex justify-content-center mb-3" @submit.prevent="update">
                        <v-row>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    label="Nom complet*"
                                    prepend-inner-icon="mdi-account"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    required
                                    label="Nom d'utilisateur*"
                                    prepend-inner-icon="mdi-account"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    type="email"
                                    solo
                                    required
                                    label="Email*"
                                    prepend-inner-icon="mdi-email"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" lg="6" sm="6">
                                <v-text-field
                                    solo
                                    label="Téléphone"
                                    prepend-inner-icon="mdi-phone"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <v-text-field
                                    solo
                                    label="Fonction"
                                    prepend-inner-icon="mdi-pencil"
                                ></v-text-field>
                            </v-col>

                            <v-alert v-if="hasError" border="right" colored-border type="error" elevation="2">
                                <ul>
                                    <li v-for="(error,index) in errors" :key="index"><span>{{error}}</span></li>
                                </ul>
                            </v-alert>

                            <v-btn :disabled="disabled" text type="submit" color="success">
                                <span v-if="!loading"><v-icon>mdi-plus</v-icon> Ajouter</span>
                                <v-progress-circular
                                    v-else
                                    indeterminate
                                ></v-progress-circular>
                            </v-btn>
                        </v-row>
                    </form>
                </v-card-text>
            </v-card>
        </v-container>
    </div>
</template>
<script>
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent";
export default {
    props : ['data'],
    data : ()=>({
        user_id : window.location.href.split('/').pop(),
        items : [
            {
                text: 'Tableau de bord',
                disabled: false,
                href: '/',
            },
            {
                text: 'Utilisateurs',
                disabled: false,
                href: '/home/users',
            },
            {
                text: 'Modifier un utilisateur',
                disabled: true,
                href: '',
            },
        ],
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],
    }),
    components: {BreadCrumbsComponent},
    methods : {
        update()
        {

        },
        init()
        {

        }
    },
    mounted() {

    }
}
</script>
