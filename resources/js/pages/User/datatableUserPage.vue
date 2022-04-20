<template>
    <div class="data-table-user-page">
       <v-container fluid>
           <bread-crumbs-component :items="items" />
       </v-container>
    </div>
</template>
<script>
import BreadCrumbsComponent from "../../components/BreadCrumbsComponent";
export default {
    data : ()=>({
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
        ]
    }),
    components: {BreadCrumbsComponent}
}
</script>
