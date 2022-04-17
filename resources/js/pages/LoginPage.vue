<template>
    <div class="login-page">
        <v-container fluid>
            <v-row class="justify-content-center" style="margin-top: 200px;">
                <v-card color="background" elevation="1" width="500">
                    <div class="flex justify-content-center">
                        <img style="width: 350px; margin-top: 28px;" :src="$store.state.logo"></img>
                    </div>
                    <v-card-text>
                        <form @submit.prevent="login" method="post">
                            <v-text-field
                                v-model="data.value"
                                color="primary"
                                label="Nom d'utilisateur ou email"
                                prepend-inner-icon="mdi-account"
                                @keydown="check"
                            ></v-text-field>

                            <v-text-field
                                @keydown="check"
                                v-model="data.password"
                                color="primary"
                                :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                :type="show1 ? 'text' : 'password'"
                                name="input-10-1"
                                label="Mote de passe"
                                hint="Au moins 8 caractères"
                                counter
                                @click:append="show1 = !show1"
                            ></v-text-field>

                            <v-alert v-if="hasError" border="right" colored-border type="error" elevation="2">
                                <ul>
                                    <li v-for="(error,index) in errors" :key="index"><span>{{error}}</span></li>
                                </ul>
                            </v-alert>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn :disabled="disabled" v-bind="attrs" v-on="on" type="submit" color="primary">
                                        <v-icon v-if="!loading">mdi-login-variant</v-icon>
                                        <v-progress-circular
                                            v-else
                                            indeterminate
                                            color="white"
                                        ></v-progress-circular>
                                    </v-btn>
                                </template>
                                <span>Connexion</span>
                            </v-tooltip>
                        </form>

                        <v-divider></v-divider>
                        <span>Mot de passe oublié ? <router-link to="request-password">réinitialiser</router-link></span>
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
            value : null,
            password : null,
        },
        show1: false,
        disabled : true,
        loading : false,
        hasError : false,
        errors : []
    }),
    methods : {
        login()
        {
            this.loading = true
            this.disabled = true
            let form = new FormData

            form.append('password',this.data.password)

            if(this.validateEmail(this.data.value))
            {
                form.append('email',this.data.value)
                form.append('has_email',1)
            }else{
                form.append('username',this.data.value)
                form.append('has_email',0)
            }

            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/api/login',form).then(e=>{
                    this.loading = false
                    this.$store.commit('SET_AUTH',true)
                    this.$store.commit('SET_USER',e.data)
                    localStorage.setItem('isAuth',true)
                    localStorage.setItem('data',JSON.stringify(e.data))
                    this.$router.push('/home')
                }).catch(err => {
                    if(err.response.status == 404)
                    {
                        let error = err.response.data.message

                        this.errors.push(error)
                        this.hasError = true
                        this.loading = false
                    }

                    if(err.response.status == 429)
                    {
                        this.$toast.open({
                            message : err.response.data.message,
                            type : 'error'
                        })
                        this.loading = false
                    }

                    if(err.response.status == 422)
                    {
                        let errors = err.response.data.errors
                        let values = Object.values(errors)
                        for (let i = 0;i<values.length;i++)
                        {
                            this.errors.push(values[i][0])
                        }
                        this.hasError = true
                        this.empty()
                        this.loading = false
                    }
                })
            })
        },
        check()
        {
            this.hasError = false
            this.errors = []
            this.disabled = (this.data.value == null || this.data.password == null) ? true : false
        },

        validateEmail(email)
        {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        },
        empty()
        {
            this.data.value = null
            this.data.password = null
        }
    }
}
</script>
