import Vue from 'vue'
import VueRouter from 'vue-router'
import {next} from "lodash/seq";

Vue.use(VueRouter)

const routes = [
    {
        path : '/',
        component: () => import('../pages/LoginPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth') == undefined)
            {
                next()
            }else{
                next('/home')
            }
        },
    },
    {
        path : '/request-password',
        component: () => import('../pages/RequestPasswordPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth') == undefined)
            {
                next()
            }else{
                next('/home')
            }
        },
    },
    {
        path : '/reset',
        component: () => import('../pages/ResetPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth') == undefined)
            {
                next()
            }else{
                next('/home')
            }
        },
    },
    {
        path : '/home',
        component: () => import('../pages/DashboardPage'),
        beforeEnter: (to, from,next) => {
            if(localStorage.getItem('isAuth'))
            {
                next()
            }else{
                next('/')
            }
        },
        children : [
            {
                path : '/',
                component : () => import('../components/MainComponent')
            },
            {
                path : 'users',
                component : () => import('../pages/User/UserPage'),
                children : [
                    {
                        path : '/',
                        component : () => import('../pages/User/datatableUserPage'),
                    },
                    {
                        path : 'add-user',
                        component : () => import('../pages/User/AddUserPage')
                    },
                    {
                        path : 'change-password-user/:id',
                        component : () => import('../pages/User/ChangePasswordUserPage')
                    },
                    {
                        path: 'update-user/:id',
                        name : 'updateUser',
                        props : true,
                        component : () => import('../pages/User/UpdateUserPage')
                    }
                ]
            },
            {
                path : 'cities',
                component : () => import('../pages/City/CityPage'),
                children: [
                    {
                        path: '/',
                        component : () => import('../pages/City/CityDatatablePage')
                    },
                    {
                        path: 'add-city',
                        component : () => import('../pages/City/AddCityPage')
                    },
                    {
                        path: 'update-city/:id',
                        props: true,
                        name : 'UpdateCity',
                        component : () => import('../pages/City/UpdateCityPage')
                    }
                ]
            },
            {
                path : 'clients',
                component : () => import('../pages/Client/ClientPage'),
                children: [
                    {
                        path : '/',
                        component : () => import('../pages/Client/DatatableClientPage')
                    },
                    {
                        path: 'add-client',
                        component : () => import('../pages/Client/AddClientPage')
                    },
                    {
                        path: 'update-client/:id',
                        name : 'updateClient',
                        props: true,
                        component : () => import('../pages/Client/UpdateClientPage')
                    }
                ]
            },
            {
                path: 'categories',
                component : () => import('../pages/Category/CategoryPage'),
                children: [
                    {
                        path: '/',
                        component : () => import('../pages/Category/DatatableCategoryPage')
                    },
                    {
                        path: 'add-category',
                        component : () => import('../pages/Category/AddCategoryPage')
                    },
                    {
                        path: 'update-category/:id',
                        props: true,
                        name : 'updateCategory',
                        component : () => import('../pages/Category/UpdateCategoryPage')
                    }
                ]
            },
            {
              path: 'options',
              component : () => import('../pages/Option/OptionPage'),
              children: [
                  {
                      path: '/',
                      component : () => import('../pages/Option/OptionDatatablePage')
                  },
                  {
                      path: 'update-option/:id',
                      name: 'updateOption',
                      props: true,
                      component : () => import('../pages/Option/UpdateOptionPage')
                  },
                  {
                      path: 'add-option',
                      component : () => import('../pages/Option/AddOptionPage')
                  },
                  {
                      path: 'values/:id',
                      component : () => import('../pages/Option/value/OptionValuePage'),
                      children : [
                          {
                              path : '/',
                              component : () => import('../pages/Option/value/OptionValueDatatablePage')
                          },
                          {
                              path: 'update-option-value/:idd',
                              name : 'updateOptionValue',
                              props : true,
                              component : () => import('../pages/Option/value/UpdateOptionValuePage')
                          },
                          {
                              path: 'add-option-value',
                              component : () => import('../pages/Option/value/AddOptionValuePage')
                          }

                      ]
                  }
              ]
            },
            {
                path: 'products',
                component : () => import('../pages/Product/ProductPage'),
                children: [
                    {
                        path: '/',
                        component : () => import('../pages/Product/DatatableProductPage')
                    },
                    {
                        path: 'add-product',
                        component : () => import('../pages/Product/AddProductPage')
                    },
                    {
                        path : 'update-product/:id',
                        name : 'updateProduct',
                        props: true,
                        component : () => import('../pages/Product/UpdateProductPage')
                    },
                    {
                        path : 'offers/:id',
                        component : () => import('../pages/Product/Offer/ProductOfferPage'),
                        children : [
                            {
                                path: '/',
                                component : () => import('../pages/Product/Offer/ProductOfferDatatable')
                            },
                            {
                                path: 'add-offers',
                                component : () =>  import('../pages/Product/Offer/AddProductOfferPage')
                            },
                            {
                                path: 'update-offer/:idd',
                                name : 'UpdateOffer',
                                props : true,
                                component : () => import('../pages/Product/Offer/UpdateOfferPage')
                            },
                        ]
                    },
                    {
                        path: 'variants/:id',
                        component : () => import('../pages/Product/Variant/VariantPage'),
                        children: [
                            {
                                path: '/',
                                component : () => import('../pages/Product/Variant/DatatableVariantPage')
                            },
                            {
                                path: 'add-variant',
                                component : () => import('../pages/Product/Variant/AddProductVariantPage')
                            },
                            {
                                path: 'update-variant/:idd',
                                name : 'UpdateVariant',
                                props : true,
                                component : () => import('../pages/Product/Variant/UpdateVariantPage')
                            },
                            {
                                path: 'add-options/:iddd',
                                name : 'AddOption',
                                component : () => import('../pages/Product/Variant/Option/AddPproductVariantOptionPage')
                            }
                        ]
                    }
                ]
            },
            {
                path: 'shippers',
                component : () => import('../pages/Shipper/ShipperPage'),
                children: [
                    {
                        path: '/',
                        component : () => import('../pages/Shipper/DatatableShipperPage')
                    },
                    {
                        path: 'add-shipper',
                        component : () => import('../pages/Shipper/AddShipperPage')
                    },
                    {
                        path: 'update-shipper/:id',
                        props: true,
                        name : 'UpdateShipper',
                        component : () => import('../pages/Shipper/UpdateShipperPage')
                    }
                ]
            },
            {
                path: 'channels',
                component : () => import('../pages/Channel/ChannelPage'),
                children: [
                    {
                        path: '/',
                        component : () => import('../pages/Channel/DatatableChannelPage')
                    },
                    {
                        path: 'add-channel',
                        component : () => import('../pages/Channel/AddChannelPage')
                    },
                    {
                        path: 'update-channel/:id',
                        component : () => import('../pages/Channel/UpdateChannelPage'),
                        props: true,
                        name : 'updateChannel'
                    }

                ]
            }

        ]
    }

]



export default new VueRouter({
    mode: 'history',
    routes
})

