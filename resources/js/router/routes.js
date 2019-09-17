export default [
  ...applyRules(['guest'], [
    { path: '', component: require('$comp/auth/AuthWrapper'), redirect: { name: 'login' }, children:
      [
        { path: '/login', name: 'login', component: require('$comp/auth/login/Login'), meta: {rule: 'public'} },
        { path: '/register', name: 'register', component: require('$comp/auth/register/Register'), meta: {rule: 'public'} },
        { path: '/password', component: require('$comp/auth/password/PasswordWrapper'), children:
          [
            { path: '', name: 'forgot', component: require('$comp/auth/password/password-forgot/PasswordForgot') },
            { path: 'reset/:token', name: 'reset', component: require('$comp/auth/password/password-reset/PasswordReset') }
          ]
        }
      ]
    },
  ]),
  ...applyRules(['auth'], [
    { path: '', component: require('$comp/admin/AdminWrapper'), children:
      [
        { path: '', name: 'index', redirect: { name: 'profile' } },
        { path: 'profile', component: require('$comp/admin/profile/ProfileWrapper'), meta: {rule: 'public'}, children:
          [
            { path: '', name: 'profile', component: require('$comp/admin/profile/Profile'), meta: {rule: 'public'} },
            { path: 'edit', name: 'profile-edit', component: require('$comp/admin/profile/edit/ProfileEdit'), meta: {rule: 'public'} }
          ]
        },
        /* 1 Modulo de informativo */
        
        /* 2 Modulo de registro */
        { path: 'productores', name: 'productor', component: require('$comp/admin/productor/Index'), meta: {rule: 'digprococa'} },
        { path: 'productores-al-detalle', name: 'productorDetalle', component: require('$comp/admin/productor_detalle/Index'), meta: {rule: 'digcoin'} },
        { path: 'comercializadores', name: 'comercializador', component: require('$comp/admin/comercializador/Index'), meta: {rule: 'digcoin'} },
        { path: 'empresas', name: 'empresa', component: require('$comp/admin/empresa/Index'), meta: {rule: 'digcoin'} },
        { path: 'entidad-de-investigacion', name: 'entidad_investigacion', component: require('$comp/admin/entidad_investigacion/Index'), meta: {rule: 'digcoin'} },
        { path: 'beneficiarios-de-donaciones', name: 'beneficiario_donacion', component: require('$comp/admin/beneficiario_donacion/Index'), meta: {rule: 'digcoin'} },
        { path: 'beneficiarios-de-desarrollo', name: 'beneficiario_desarrollo', component: require('$comp/admin/beneficiario_desarrollo/Index'), meta: {rule: 'digcoin'} },
        
        

        /* 3 Modulo de actividades de produccion */
        /* 4 Modulo de actividades de comercializacion*/

        { path: 'guias_internacion', name: 'guia_internacion', component: require('$comp/admin/guia_internacion/Index'), meta: {rule: 'digcoin'} },
        { path: 'control_guias_internacion', name: 'control_guia_internacion', component: require('$comp/admin/control_guia_internacion/Index'), meta: {rule: 'digcoin'} },
        { path: 'areas_puestos_venta', name: 'puesto_venta', component: require('$comp/admin/puesto_venta/Index'), meta: {rule: 'digcoin'} },        
        { path: 'retenciones', name: 'retencion', component: require('$comp/admin/retencion/Index'), meta: {rule: 'digcoin'} },
        { path: 'decomisos', name: 'decomiso', component: require('$comp/admin/decomiso/Index'), meta: {rule: 'digcoin'} },
        { path: 'donaciones', name: 'donacion', component: require('$comp/admin/donacion/Index'), meta: {rule: 'digcoin'} },
        { path: 'incineraciones', name: 'incineracion', component: require('$comp/admin/incineracion/Index'), meta: {rule: 'digcoin'} },
        { path: 'devoluciones', name: 'devolucion', component: require('$comp/admin/devolucion/Index'), meta: {rule: 'digcoin'} },
        { path: 'depositos', name: 'deposito', component: require('$comp/admin/deposito/Index'), meta: {rule: 'digcoin'} },
        { path: 'roles', name: 'roles', component: require('$comp/admin/roles/Role'), meta: {rule: 'admin'} },
        { path: 'permisos', name: 'permission', component: require('$comp/admin/permission/Permission'), meta: {rule: 'admin'} },
        { path: 'usuarios', name: 'user', component: require('$comp/admin/user/User'), meta: {rule: 'admin'} },        
        { path: 'poderes', name: 'poder', component: require('$comp/admin/poder/Index'), meta: {rule: 'digcoin'} },
        { path: 'hojas_de_ruta', name: 'hoja_ruta', component: require('$comp/admin/hoja_ruta/Index'), meta: {rule: 'digcoin'} },
        { path: 'control_hojas_de_ruta', name: 'control_hoja_ruta', component: require('$comp/admin/control_hoja_ruta/Index'), meta: {rule: 'digcoin'} },
        
        /* 5 Modulo de desarrollo integral */
        
        /* 6 Modulo de resoluciones */
        { path: 'resoluciones_administrativas', name: 'resolucion_administrativa', component: require('$comp/admin/resolucion_administrativa/Index'), meta: {rule: 'digcoin'} },
        { path: 'consulta_res_administrativa', name: 'consulta_res_administrativa', component: require('$comp/admin/consulta_res_administrativa/Index'), meta: {rule: 'digcoin'} },
        { path: 'sanciones_monetarias', name: 'sanciones_monetarias', component: require('$comp/admin/sanciones_monetarias/Index'), meta: {rule: 'digcoin'} },
        
        /* 7 Modulo de reportes */
        
        /* 8 Modulo de alertas y notificaciones */
        
        /* 9 Modulo de Usuarios y roles */

        /* 10 Modulo de parametrizado */
        { path: 'zona_autorizada', name: 'zona_autorizada', component: require('$comp/admin/zona_autorizada/Index'), meta: {rule: 'admin'} },
        { path: 'departamentos', name: 'departamento', component: require('$comp/admin/departamento/Index'), meta: {rule: 'admin'} },
        { path: 'provincias', name: 'provincia', component: require('$comp/admin/provincia/Index'), meta: {rule: 'admin'} },
        { path: 'municipios', name: 'municipio', component: require('$comp/admin/municipio/Index'), meta: {rule: 'admin'} },
        { path: 'federaciones', name: 'federacion', component: require('$comp/admin/federacion/Index'), meta: {rule: 'admin'} },
        { path: 'regionales', name: 'regional', component: require('$comp/admin/regional/Index'), meta: {rule: 'admin'} },
        { path: 'centrales', name: 'central', component: require('$comp/admin/central/Index'), meta: {rule: 'admin'} },
        { path: 'sindicatos', name: 'sindicato', component: require('$comp/admin/sindicato/Index'), meta: {rule: 'admin'} },
        { path: 'infracciones', name: 'infraccion', component: require('$comp/admin/infraccion/Index'), meta: {rule: 'admin'} },
        { path: 'sanciones', name: 'sancion', component: require('$comp/admin/sancion/Index'), meta: {rule: 'admin'} },
        { path: 'mercados', name: 'mercado', component: require('$comp/admin/mercado/Index'), meta: {rule: 'admin'} },
        { path: 'puestos_de_control', name: 'puesto_de_control', component: require('$comp/admin/puesto_de_control/Index'), meta: {rule: 'admin'} },
        { path: 'rutas', name: 'ruta', component: require('$comp/admin/ruta/Index'), meta: {rule: 'admin'} },
        { path: 'montos', name: 'monto', component: require('$comp/admin/monto/Index'), meta: {rule: 'admin'} },
        { path: 'localidades', name: 'localidad', component: require('$comp/admin/localidad/Index'), meta: {rule: 'admin'} },
        { path: 'marcas_vehiculos', name: 'marca', component: require('$comp/admin/marca/Index'), meta: {rule: 'admin'} },
        { path: 'vehiculos', name: 'vehiculo', component: require('$comp/admin/vehiculo/Index'), meta: {rule: 'admin'} },

        /* 11 Modulo de auditoria */

        /* 12 Modulo de servicios */
        
        /* -- varios-- */
      ]
    },
  ]),
  { path: '*', redirect: { name: 'index' } }
]

function applyRules(rules, routes) {
  for (let i in routes) {
    routes[i].meta = routes[i].meta || {}

    if (!routes[i].meta.rules) {
      routes[i].meta.rules = []
    }
    routes[i].meta.rules.unshift(...rules)

    if (routes[i].children) {
      routes[i].children = applyRules(rules, routes[i].children)
    }
  }

  return routes
}
