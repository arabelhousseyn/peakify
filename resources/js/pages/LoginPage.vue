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
                                append-outer-icon="mdi-user"
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
                        <span>Mot de passe oublié ? <router-link to="reset">réinitialiser</router-link></span>
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
            has_email : null,
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
        },
        check()
        {
            this.disabled = (this.data.value == null || this.data.password == null) ? true : false
        }
    }
}
</script>
