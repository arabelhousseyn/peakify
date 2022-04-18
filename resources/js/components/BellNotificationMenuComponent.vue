<template>
    <div class="bell-notification mr-5">
        <v-menu
            bottom
            offset-y
            max-height="350px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-badge
                    bordered
                    color="error"
                    overlap
                >
                    <template v-slot:badge>
                        <span v-text="$store.state.data.count_notification"></span>
                    </template>
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                        small
                    >
                        <v-icon>mdi-bell</v-icon>
                    </v-btn>
                </v-badge>
            </template>
            <v-card>
                <v-card-text>
                    <v-list two-line>
                        <v-list-item-group v-if="this.$store.state.data.count_notification > 0">
                            <v-list-item v-for="(notification,index) in this.$store.state.data.notifications" :key="index" @click="open(notification.id)">
                                <v-list-item-avatar>
                                    <v-icon v-if="notification.read_at == null">mdi-star-outline</v-icon>
                                    <v-icon v-else>mdi-star</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>Numéro de commande : {{notification.data.data.ref}}</v-list-item-title>
                                    <v-list-item-subtitle v-if="notification.read_at == null">
                                        <v-chip color="green" dark>Nouvelle notification</v-chip>
                                    </v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-list-item-action-text>
                                        {{ formatDate(notification.created_at) }}
                                    </v-list-item-action-text>

                                    <v-icon v-if="notification.read_at == null" color="grey lighten-1">mdi-email</v-icon>
                                    <v-icon v-else color="grey lighten-1">mdi-email-open</v-icon>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list-item-group>

                        <v-list-item v-else>
                            <v-list-item-content>
                                <v-list-item-title>Notification vide <v-icon>mdi-baby-face-outline</v-icon> </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                    </v-list>
                </v-card-text>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
import moment from 'moment'
export default {
    data : () => ({
        data : {
            notification_id : null,
        }
    }),
    methods : {
        open(id)
        {
            this.data.notification_id = id
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put('/api/notifications/read',this.data).then(e=>{
                    if(e.status == 204)
                    {
                        this.$router.push('/home/orders')
                    }
                }).catch(err =>{

                })
            })
        },
        formatDate(date) {
            return moment(date).locale('fr').format('dd.mm.YYYY');
        },
    },
    mounted() {

    }
}
</script>
<template>
    <div class="bell-notification mr-5">
        <v-menu
            bottom
            offset-y
            max-height="350px"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-badge
                    bordered
                    color="error"
                    overlap
                >
                    <template v-slot:badge>
                        <span v-text="$store.state.data.count_notification"></span>
                    </template>
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                        small
                    >
                        <v-icon>mdi-bell</v-icon>
                    </v-btn>
                </v-badge>
            </template>
            <v-card>
                <v-card-text>
                    <v-list two-line>
                        <v-list-item-group v-if="this.$store.state.data.count_notification > 0">
                            <v-list-item v-for="(notification,index) in this.$store.state.data.notifications" :key="index" @click="open(notification.id)">
                                <v-list-item-avatar>
                                    <v-icon v-if="notification.read_at == null">mdi-star-outline</v-icon>
                                    <v-icon v-else>mdi-star</v-icon>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>Numéro de commande : {{notification.data.data.ref}}</v-list-item-title>
                                    <v-list-item-subtitle v-if="notification.read_at == null">
                                        <v-chip color="green" dark>Nouvelle notification</v-chip>
                                    </v-list-item-subtitle>
                                </v-list-item-content>

                                <v-list-item-action>
                                    <v-list-item-action-text>
                                        {{ formatDate(notification.created_at) }}
                                    </v-list-item-action-text>

                                    <v-icon v-if="notification.read_at == null" color="grey lighten-1">mdi-email</v-icon>
                                    <v-icon v-else color="grey lighten-1">mdi-email-open</v-icon>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list-item-group>

                        <v-list-item v-else>
                            <v-list-item-content>
                                <v-list-item-title>Notification vide <v-icon>mdi-baby-face-outline</v-icon> </v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>

                    </v-list>
                </v-card-text>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
export default {
    data : () => ({
        data : {
            notification_id : null,
        }
    }),
    methods : {
        open(id)
        {
            this.data.notification_id = id
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.put('/api/notifications/read',this.data).then(e=>{
                    if(e.status == 204)
                    {
                        this.$router.push('/home/orders')
                    }
                }).catch(err =>{

                })
            })
        },
        formatDate(date) {
            return date;
        },
    },
    mounted() {

    }
}
</script>
