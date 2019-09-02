<template>
  <v-navigation-drawer fixed app :permanent="$vuetify.breakpoint.mdAndUp" light :mini-variant.sync="$vuetify.breakpoint.mdAndUp && mini" :clipped="$vuetify.breakpoint.mdAndUp" :value="mini">
    <v-toolbar flat class="transparent">
      <v-list class="pa-0">
        <v-list-tile avatar>
          <v-list-tile-avatar v-show="$vuetify.breakpoint.mdAndUp && mini">
            <v-btn icon @click.native.stop="navToggle" class="mx-0">
              <v-icon>chevron_right</v-icon>
            </v-btn>
          </v-list-tile-avatar>
          <v-list-tile-content>
            <v-list-tile-title>{{ name }}</v-list-tile-title>
          </v-list-tile-content>
          <v-list-tile-action>
            <v-btn icon @click.native.stop="navToggle">
              <v-icon>chevron_left</v-icon>
            </v-btn>
          </v-list-tile-action>
        </v-list-tile>
      </v-list>
    </v-toolbar>
    <v-list class="py-0" dense v-for="(group, index) in items" :key="index">
      <v-divider class="mb-2" :class="{ 'mt-0': !index, 'mt-2': !!index }" v-if="!!group.length"></v-divider>
      <div v-for="item in group" :key="item.title">
        <v-list-group
            v-if="!!item.items"
            :prepend-icon="item.icon"
            no-action
        >
          <v-list-tile slot="activator">
            <v-list-tile-content>
              <v-tooltip top>
                <v-list-tile-title slot="activator">{{ item.title }}</v-list-tile-title>
              <span>{{ item.title }}</span>
              </v-tooltip>
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile
              v-for="subItem in item.items"
              :key="subItem.title"
              @click="subItem.action ? subItem.action() : false"
              :to="subItem.to"
              ripple
              :exact="subItem.exact !== undefined ? subItem.exact : true"
          >
            <v-list-tile-content class="pl-0">
              <v-tooltip top>
                <v-list-tile-title slot="activator">{{ subItem.title }}</v-list-tile-title>
                <span>{{ subItem.title }}</span>
              </v-tooltip>
            </v-list-tile-content>
            <v-list-tile-action>
              <v-icon>{{ subItem.icon }}</v-icon>
            </v-list-tile-action>
          </v-list-tile>
        </v-list-group>
        <v-list-tile
            v-else
            @click.native="item.action ? item.action() : false"
            href="javascript:void(0)"
            :to="item.to"
            ripple
            :exact="item.exact !== undefined ? item.exact : true"
        >
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </div>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    data: () => ({
        items: [],
        name: null
    }),

    props: ['mini'],

    computed: mapGetters({
      auth: 'auth/user'
    }),

    watch: {
      authName(val) {
        if (val) {
          this.name = val
        }
      }
    },

    mounted() {
      this.name = this.auth.name
      this.navigation()
    },

    methods: {
      navToggle() {
        this.$emit('navToggle')
      },

      async logout() {
        await this.$store.dispatch('auth/logout')

        this.$toast.info('sessión terminada.')
        this.$router.push({ name: 'login' })
      },
      findRole(rol) {
        var name=new Array();
        for (var r of rol) {
          name.push(r.name);
        }
        return name
      },

      navigation() {
        var rols = this.findRole(this.auth.roles)
        if(rols.includes('admin')) {
          this.items = [
            [
              { title: 'Perfil', icon: 'person', to: {name: 'profile'}, exact: false }
            ],
            [
              { 
                title: 'Modulo de registro', 
                icon: 'list',
                items: [
                  { title: 'Productores', icon: 'forward', to: {name: 'productor'}, exact: false},
                  { title: 'Productores al detalle', icon: 'forward', to: {name: 'productorDetalle'}, exact: false},
                  { title: 'Comerciantes al detalle', icon: 'forward', to: {name: 'comercializador'}, exact: false},
                  { title: 'Empresas de industrialización', icon: 'forward', to: {name: 'empresa'}, exact: false},
                  { title: 'Entidades de investigación', icon: 'forward', to: {name: 'entidad_investigacion'}, exact: false},
                  { title: 'Beneficiarios de donaciones', icon: 'forward', to: {name: 'beneficiario_donacion'}, exact: false},
                  { title: 'Beneficiarios para proyectos de desarrollo integral', icon: 'forward', to: {name: 'beneficiario_desarrollo'}, exact: false},
                  
                ]
              }
            ],            
            [
              { 
                title: 'Módulo de Actividades de Comercialización e Industrialización', 
                icon: 'list',
                items: [
                  { title: 'Guías de internación', icon: 'forward', to: {name: 'guia_internacion'}, exact: false},
                  { title: 'Control de guías de internación', icon: 'forward', to: {name: 'control_guia_internacion'}, exact: false},
                  { title: 'Emisión de hojas de ruta', icon: 'forward', to: {name: 'hoja_ruta'}, exact: false},
                  { title: 'Control de hojas de ruta', icon: 'forward', to: {name: 'control_hoja_ruta'}, exact: false},
                  { title: 'Transporte y Comercialización con Poder', icon: 'forward', to: {name: 'poder'}, exact: false},
                  { title: 'Áreas y puestos de venta', icon: 'forward', to: {name: 'puesto_venta'}, exact: false},
                  { title: 'Retenciones', icon: 'forward', to: {name: 'retencion'}, exact: false},
                  { title: 'Depositos', icon: 'forward', to: {name: 'deposito'}, exact: false},
                  { title: 'Decomisos', icon: 'forward', to: {name: 'decomiso'}, exact: false},
                  { title: 'Devoluciones', icon: 'forward', to: {name: 'devolucion'}, exact: false},
                  { title: 'Incineraciones', icon: 'forward', to: {name: 'incineracion'}, exact: false},
                  { title: 'Donaciones', icon: 'forward', to: {name: 'donacion'}, exact: false},                  
                ]
              }
            ],
            [
              { 
                title: 'Módulo de Parametrización', 
                icon: 'list',
                items: [
                  { title: 'Zonas Autorizadas', icon: 'forward', to: {name: 'zona_autorizada'}, exact: false},
                  { title: 'Departamentos', icon: 'forward', to: {name: 'departamento'}, exact: false},
                  { title: 'Provincias', icon: 'forward', to: {name: 'provincia'}, exact: false},
                  { title: 'Municipios', icon: 'forward', to: {name: 'municipio'}, exact: false},
                  { title: 'Regionales', icon: 'forward', to: {name: 'regional'}, exact: false},
                  { title: 'Federaciones', icon: 'forward', to: {name: 'federacion'}, exact: false},
                  { title: 'Centrales', icon: 'forward', to: {name: 'central'}, exact: false},
                  { title: 'Sindicatos', icon: 'forward', to: {name: 'sindicato'}, exact: false},
                  { title: 'Infracciones', icon: 'forward', to: {name: 'infraccion'}, exact: false},
                  { title: 'Sanciones', icon: 'forward', to: {name: 'sancion'}, exact: false},
                  { title: 'Mercados', icon: 'forward', to: {name: 'mercado'}, exact: false},
                  { title: 'Puestos de control', icon: 'forward', to: {name: 'puesto_de_control'}, exact: false},
                  { title: 'Rutas', icon: 'forward', to: {name: 'ruta'}, exact: false},
                  { title: 'Montos', icon: 'forward', to: {name: 'monto'}, exact: false},
                  { title: 'Localidades', icon: 'forward', to: {name: 'localidad'}, exact: false},
                  { title: 'Marcas de vehículos', icon: 'forward', to: {name: 'marca'}, exact: false},
                  { title: 'Vehículos', icon: 'forward', to: {name: 'vehiculo'}, exact: false},
                ]
              }
            ],
            [
              { 
                title: 'Módulo de Resoluciones Administrativas', 
                icon: 'list',
                items: [
                  { title: 'Resoluciones administrativas', icon: 'forward', to: {name: 'resolucion_administrativa'}, exact: false},
                  { title: 'Consulta de Resoluciones Administrativas', icon: 'forward', to: {name: 'consulta_resolucion_administrativa'}, exact: false},
                ]
              }
            ],
            [
              { 
                title: 'Módulo de Usuarios, Roles y Permisos', 
                icon: 'list',
                items: [
                  { title: 'Usuarios', icon: 'forward', to: {name: 'user'}, exact: false},
                  { title: 'Roles', icon: 'forward', to: {name: 'roles'}, exact: false},
                  { title: 'Permisos', icon: 'forward', to: {name: 'permission'}, exact: false},
                ]
              }
            ],           
            [
              { title: 'Salir', icon: 'power_settings_new', action: this.logout }
            ]
          ]
        }
        //////////////////////////////////////
        if(rols.includes('digcoin')) {
          this.items = [
            [
              { title: 'Perfil', icon: 'person', to: {name: 'profile'}, exact: false }
            ],
            [
              { 
                title: 'Modulo de registro', 
                icon: 'list',
                items: [
                  { title: 'Productores al detalle', icon: 'forward', to: {name: 'productorDetalle'}, exact: false},
                  { title: 'Comercializadores al detalle', icon: 'forward', to: {name: 'comercializador'}, exact: false},
                  { title: 'Empresas de industrialización', icon: 'forward', to: {name: 'empresa'}, exact: false},
                  { title: 'Entidades de investigación', icon: 'forward', to: {name: 'entidad_investigacion'}, exact: false},
                  { title: 'Beneficiarios de donaciones', icon: 'forward', to: {name: 'beneficiario_donacion'}, exact: false},
                  { title: 'Beneficiarios para proyectos de desarrollo integral', icon: 'forward', to: {name: 'beneficiario_desarrollo'}, exact: false},
                ]
              }
            ],
            [
              { 
                title: 'Módulo de Actividades de Comercialización e Industrialización', 
                icon: 'list',
                items: [
                  { title: 'Guías de internación', icon: 'forward', to: {name: 'guia_internacion'}, exact: false},
                  { title: 'Control de guías de internación', icon: 'forward', to: {name: 'control_guia_internacion'}, exact: false},
                  { title: 'Emisión de hojas de ruta', icon: 'forward', to: {name: 'hoja_ruta'}, exact: false},
                  { title: 'Control de hojas de ruta', icon: 'forward', to: {name: 'control_hoja_ruta'}, exact: false},
                  { title: 'Transporte y Comercialización con Poder', icon: 'forward', to: {name: 'poder'}, exact: false},
                  { title: 'Áreas y puestos de venta', icon: 'forward', to: {name: 'puesto_venta'}, exact: false},
                  { title: 'Retenciones', icon: 'forward', to: {name: 'retencion'}, exact: false},
                  { title: 'Depositos', icon: 'forward', to: {name: 'deposito'}, exact: false},
                  { title: 'Decomisos', icon: 'forward', to: {name: 'decomiso'}, exact: false},
                  { title: 'Devoluciones', icon: 'forward', to: {name: 'devolucion'}, exact: false},
                  { title: 'Incineraciones', icon: 'forward', to: {name: 'incineracion'}, exact: false},
                  { title: 'Donaciones', icon: 'forward', to: {name: 'donacion'}, exact: false},
                ]
              }
            ],
            [
              { 
                title: 'Módulo de Resoluciones Administrativas', 
                icon: 'list',
                items: [
                  { title: 'Resoluciones administrativas', icon: 'forward', to: {name: 'resolucion_administrativa'}, exact: false},
                  { title: 'Consulta de Resoluciones Administrativas', icon: 'forward', to: {name: 'consulta_resolucion_administrativa'}, exact: false},
                ]
              }
            ],
            [
              { title: 'Salir', icon: 'power_settings_new', action: this.logout }
            ]
          ]
        }
        //////////////////////////////////////
        if(rols.includes('digprococa')) {
          this.items = [
            [
              { title: 'Perfil', icon: 'person', to: {name: 'profile'}, exact: false }
            ],
            [
              { 
                title: 'Modulo de registro', 
                icon: 'list',
                items: [
                  { title: 'Productores', icon: 'forward', to: {name: 'productor'}, exact: false},
                ]
              }
            ],
            [
              { 
                title: 'Módulo de Resoluciones Administrativas', 
                icon: 'list',
                items: [
                  { title: 'Resoluciones administrativas', icon: 'forward', to: {name: 'resolucion_administrativa'}, exact: false},
                  { title: 'Consulta de Resoluciones Administrativas', icon: 'forward', to: {name: 'consulta_resolucion_administrativa'}, exact: false},
                ]
              }
            ],
            [
              { title: 'Salir', icon: 'power_settings_new', action: this.logout }
            ]
          ]
        }
      }
    }
  }
</script>
