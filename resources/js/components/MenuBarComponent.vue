<template>
    <div class="menu-bar">
        <v-menu
            v-model="menu"
            :close-on-content-click="false"
            :nudge-width="50"
            offset-x
        >
            <template v-slot:activator="{ on, attrs }">
                <v-avatar size="35">
                    <v-img :src="$store.state.user_logo"></v-img>
                </v-avatar>
                <v-btn v-bind="attrs" v-on="on" elevation="0" color="white">
                    {{ $store.state.user.full_name }} <span><v-icon>mdi-menu-down</v-icon> </span>
                </v-btn>
            </template>

            <v-card>
                <v-list>
                    <v-list-item @click="AdminProfileForward" link>
                        <v-list-item-avatar>
                            <img
                                :src="$store.state.user_logo"
                                :alt="$store.state.user.full_name"
                            >
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title>{{ $store.state.user.full_name }}</v-list-item-title>
                            <v-list-item-subtitle>{{ $store.state.user.type }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        text
                        @click="menu = false"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        color="primary"
                        text
                        @click="logout"
                    >
                        Se déconnecter
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
export default {
    data: () => ({
        menu: false,
        dark_mode: false,
        hints: true,
    }),
    methods : {
        logout()
        {
            axios.get('/sanctum/csrf-cookie').then(res =>{
                axios.get('api/admin/logout',{headers : { 'Authorization' : 'Bearer ' + this.$store.state.user.token }})
                    .then(e =>{
                        if(e.status == 204)
                        {
                            this.$store.commit('SET_OUT')
                            this.$toast.open({
                                message : "Opération effectué",
                                type : 'success'
                            })
                            this.$router.push('/')
                        }

                    }).catch(err =>{
                    console.log(err)
                })
            })
            this.menu = false
        },
        AdminProfileForward()
        {
            this.menu = false
            this.$router.push('/home/profile').catch(err => {});
        }
    }
}
</script>
