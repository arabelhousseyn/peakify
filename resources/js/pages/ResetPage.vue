<template>
    <div class="reset-page">
        <v-container fluid>
            <v-row class="justify-content-center" style="margin-top: 200px;">
                <v-card color="background" elevation="1" width="500">
                    <div class="flex justify-content-center">
                        <p class="text-h6 white--text" style="margin-top: 19px;">Réinitialiser le mot de passe</p>
                    </div>
                    <v-card-text>
                        <form @submit.prevent="reset" method="post">

                            <v-text-field
                                @keydown="check"
                                v-model="data.new_password"
                                color="primary"
                                :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show2 ? 'text' : 'password'"
                                name="input-10-1"
                                label="Nouveau mot de passe"
                                hint="Au moins 8 caractères"
                                counter
                                @click:append="show2 = !show2"
                            ></v-text-field>

                            <v-text-field
                                @keydown="check"
                                v-model="data.new_password_confirmation"
                                color="primary"
                                :append-icon="show3 ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show3 ? 'text' : 'password'"
                                name="input-10-1"
                                label="Confirmer mot de passe"
                                hint="Au moins 8 caractères"
                                counter
                                @click:append="show3 = !show3"
                            ></v-text-field>

                            <v-alert v-if="hasError" border="right" colored-border type="error" elevation="2">
                                <ul>
                                    <li v-for="(error,index) in errors" :key="index"><span>{{error}}</span></li>
                                </ul>
                            </v-alert>

                                    <v-btn :disabled="disabled" type="submit" color="primary">
                                        <span v-if="!loading" class="text-uppercase">Réinitialiser</span>
                                        <v-progress-circular
                                            v-else
                                            indeterminate
                                            color="white"
                                        ></v-progress-circular>
                                    </v-btn>
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
        token : window.location.search.replace('?token=',''),
        data : {
            new_password : null,
            new_password_confirmation : null,
        },
        disabled : true,
        loading : false,
        hasError : false,
        errors : [],
        show1 : false,
        show2 : false,
        show3 : false,
    }),
    methods : {
        verifyToken()
        {
            return (localStorage.getItem('token') == this.token) ? true : false
        },
        reset()
        {
            this.disabled = true
            this.loading = true

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put(`/api/reset-password/${this.token}`,this.data).then(e=>{
                    this.loading = false
                    localStorage.removeItem('token')
                    this.$toast.open({
                        message : 'Mot de passe changé',
                        type : 'success'
                    })
                    this.$router.push('/')
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
                        this.loading = false
                        this.data.new_password = null
                        this.data.new_password_confirmation = null
                    }
                })
            })
        },
        check()
        {
            if(this.hasError)
            {
                this.hasError = false
                this.errors = []
            }
            this.disabled = (this.data.new_password == null || this.data.new_password_confirmation == null) ? true : false
        }
    },
    mounted() {
       if(!this.verifyToken())
       {
           this.$router.push('/')
       }
    }
}
</script>
