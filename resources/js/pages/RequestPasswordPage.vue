<template>
    <div class="request-password-page">
        <v-container fluid>
            <v-row class="justify-content-center" style="margin-top: 200px;">
                        <router-link to="/" class="flex justify-content-center">
                            <v-btn color="primary"><v-icon>mdi-arrow-left</v-icon> Retour</v-btn>
                        </router-link>
                <v-card color="background" elevation="1" width="500" class="mt-7">
                    <div class="flex justify-content-center">
                        <p class="text-h6 white--text" style="margin-top: 19px;">Demander la réinitialisation du mot de passe</p>
                    </div>
                    <v-card-text>
                        <form @submit.prevent="request" method="post">
                            <v-text-field
                                v-model="data.email"
                                color="primary"
                                label="Email"
                                prepend-inner-icon="mdi-account"
                                @keydown="check"
                            ></v-text-field>

                            <v-alert v-if="hasError" border="right" colored-border type="error" elevation="2">
                                <ul>
                                    <li v-for="(error,index) in errors" :key="index"><span>{{error}}</span></li>
                                </ul>
                            </v-alert>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn :disabled="disabled" v-bind="attrs" v-on="on" type="submit" color="primary">
                                        <span v-if="!loading" class="text-uppercase">envoyer une demande</span>
                                        <v-progress-circular
                                            v-else
                                            indeterminate
                                            color="white"
                                        ></v-progress-circular>
                                    </v-btn>
                                </template>
                                <span>réinitialiser</span>
                            </v-tooltip>
                        </form>
                    </v-card-text>
                </v-card>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data : ()=>({
        data : {
            email : null,
        },
        disabled : true,
        loading : false,
        hasError : false,
        errors : []
    }),
    methods : {
        request()
        {
            this.disabled = true
            this.loading = true

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/request-reset-password',this.data).then(e=>{
                    this.loading = false
                    this.$toast.open({
                        message : 'Vérifier votre boîte de réception ou vos spams',
                        type : 'success'
                    })
                    this.data.email = null
                }).catch(err => {
                    if(err.response.status == 422)
                    {
                        let errors = err.response.data.errors
                        let values = Object.values(errors)
                        for (let i = 0;i<values.length;i++)
                        {
                            this.errors.push(values[i][0])
                        }
                        this.hasError = true
                        this.data.email = null
                        this.loading = false
                    }
                })
            })
        },
        check()
        {
            this.hasError = false
            this.errors = []
            this.disabled = (this.data.email == null) ? true : false
        }
    }
}
</script>
