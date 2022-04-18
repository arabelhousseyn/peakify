<template>
    <div>
        <v-app-bar app color="white" elevation="0" clipped-right>
            <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
            <v-spacer></v-spacer>
            <bell-notification-menu-component />
            <menu-bar-component />
        </v-app-bar>

        <v-navigation-drawer app color="secondary" v-model="drawer" fixed>
            <v-list>
                <v-list-item class="px-2 d-flex justify-content-center">
                    <a href="/home"><img style="width: 150px;" :src="$store.state.sm_logo"></img></a>
                </v-list-item>
            </v-list>

            <v-list dark>
                <v-list-item-group
                    v-model="selectedItem"
                    color="primary"
                >
                    <v-list-item
                        @click="()=>{this.$router.push('/').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-home</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Acceuil</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-group
                        prepend-icon="mdi-account"
                        no-action
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title>Comptes</v-list-item-title>
                            </v-list-item-content>
                        </template>

                        <v-list-item style="border-right: 4px solid;" @click="()=>{this.$router.push('/home/admins').catch(err => {})}">
                            <v-list-item-content>
                                <v-list-item-title>Administrateurs</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                        <v-list-item style="border-right: 4px solid;" @click="()=>{this.$router.push('/home/users').catch(err => {})}">
                            <v-list-item-content>
                                <v-list-item-title>Utilisateurs</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>


                        <v-list-item style="border-right: 4px solid;" @click="()=>{this.$router.push('/home/sellers').catch(err => {})}">
                            <v-list-item-content>
                                <v-list-item-title>Vendeurs</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>


                        <v-list-item style="border-right: 4px solid;" @click="()=>{this.$router.push('/home/shippers').catch(err => {})}">
                            <v-list-item-content>
                                <v-list-item-title>Livreurs</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-group>

                    <v-list-item
                        @click="()=>{this.$router.push('/home/provinces').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-home-map-marker</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Willayas</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        @click="()=>{this.$router.push('/home/signs').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-bookmark</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Marques</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        @click="()=>{this.$router.push('/home/types').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-collage</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Types</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        @click="()=>{this.$router.push('/home/orders').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-cart</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Commandes</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        @click="()=>{this.$router.push('/home/notifications').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-broadcast</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Envoi des notifications</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        @click="()=>{this.$router.push('/home/statistics').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-chart-box</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Statistiques</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        @click="()=>{this.$router.push('/home/ads').catch(err => {})}"
                        style="border-right: 4px solid;"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-google-ads</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>Publicités</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
    </div>
</template>

<script>
import MenuBarComponent from "./MenuBarComponent"
import BellNotificationMenuComponent from './BellNotificationMenuComponent'
export default {
    components: {MenuBarComponent,BellNotificationMenuComponent},
    data: () => ({
        selectedItem: 0,
        drawer: null,
    }),
    methods : {
        init()
        {
            let path = window.location.pathname.replace('/','')
            if(path == 'home')
            {
                this.selectedItem = 0
            }else if(path.includes('home/users'))
            {
                this.selectedItem = 1;
            }else if(path.includes('home/sellers'))
            {
                this.selectedItem = 1;
            }else if(path.includes('home/shippers'))
            {
                this.selectedItem = 1;
            }
            else if(path.includes('home/provinces'))
            {
                this.selectedItem = 2;
            }
            else if(path.includes('home/signs'))
            {
                this.selectedItem = 3;
            }
            else if(path.includes('home/types'))
            {
                this.selectedItem = 4;
            }
            else if(path.includes('home/orders'))
            {
                this.selectedItem = 5;
            }
            else if(path.includes('home/notifications'))
            {
                this.selectedItem = 6;
            }
            else if(path.includes('home/statistics'))
            {
                this.selectedItem = 7;
            }
            else if(path.includes('home/ads'))
            {
                this.selectedItem = 8;
            }
            else{
                this.selectedItem = null
            }

        }
    },
    mounted() {
        this.init()
    }
};
</script>
