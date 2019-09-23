<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['guest:api']], function() {
    Route::post('login', 'Auth\LoginController@login');
    Route::post('login/refresh', 'Auth\LoginController@refresh');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::post('register', 'Auth\RegisterController@register');
});
Route::group(['middleware' => ['jwt']], function() {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get('me', 'Auth\LoginController@me');
    Route::put('profile', 'ProfileController@update');
    Route::put('updatepass/{id}', 'UserController@updatepass');
    //ROLES 1
    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('user', 'UserController');
        Route::resource('role', 'RoleController');
        Route::resource('permission', 'PermissionController');
    });
    Route::group(['middleware' => ['role:admin|digprococa']], function () {
        Route::get('carnet_productor/print/{id}', 'CarnetProductorController@print')->middleware('permission:imprimir');
        Route::get('carnet_productor/fill/{param}', 'CarnetProductorController@fill')->middleware('permission:listar');
        Route::get('carnet_productor', 'CarnetProductorController@index')->middleware('permission:listar');
        Route::get('carnet_productor/{id}', 'CarnetProductorController@show')->middleware('permission:listar');
        Route::post('carnet_productor', 'CarnetProductorController@store')->middleware('permission:registrar');
        Route::put('carnet_productor/{id}', 'CarnetProductorController@update')->middleware('permission:editar');
        Route::delete('carnet_productor/{id}', 'CarnetProductorController@destroy')->middleware('permission:eliminar');
    });
    Route::group(['middleware' => ['role:admin|digcoin']], function () {
        Route::get('empresa/fill/{param}', 'EmpresaController@fill')->middleware('permission:listar');
        Route::get('empresa', 'EmpresaController@index')->middleware('permission:listar');
        Route::post('empresa', 'EmpresaController@store')->middleware('permission:registrar');
        Route::put('empresa/{id}', 'EmpresaController@update')->middleware('permission:editar');
        Route::delete('empresa/{id}', 'EmpresaController@destroy')->middleware('permission:eliminar');

        Route::get('autorizacion/print/{id}', 'AutorizacionController@print')->middleware('permission:imprimir');
        Route::get('autorizacion/fill/{param}', 'AutorizacionController@fill')->middleware('permission:listar');
        Route::get('autorizacion', 'AutorizacionController@index')->middleware('permission:listar');
        Route::get('autorizacion/{id}', 'AutorizacionController@show')->middleware('permission:listar');
        Route::post('autorizacion', 'AutorizacionController@store')->middleware('permission:registrar');
        Route::put('autorizacion/{id}', 'AutorizacionController@update')->middleware('permission:editar');
        Route::delete('autorizacion/{id}', 'AutorizacionController@destroy')->middleware('permission:eliminar');

        Route::get('entidad_investigacion/fill/{param}', 'EntidadInvestigacionController@fill')->middleware('permission:listar');
        Route::get('entidad_investigacion', 'EntidadInvestigacionController@index')->middleware('permission:listar');
        Route::get('entidad_investigacion/{id}', 'EntidadInvestigacionController@show')->middleware('permission:listar');
        Route::post('entidad_investigacion', 'EntidadInvestigacionController@store')->middleware('permission:registrar');
        Route::put('entidad_investigacion/{id}', 'EntidadInvestigacionController@update')->middleware('permission:editar');
        Route::delete('entidad_investigacion/{id}', 'EntidadInvestigacionController@destroy')->middleware('permission:eliminar');

        Route::get('beneficiario_donacion/fill/{param}', 'BeneficiarioDonacionController@fill')->middleware('permission:listar');
        Route::get('beneficiario_donacion', 'BeneficiarioDonacionController@index')->middleware('permission:listar');
        Route::get('beneficiario_donacion/{id}', 'BeneficiarioDonacionController@show')->middleware('permission:listar');
        Route::post('beneficiario_donacion', 'BeneficiarioDonacionController@store')->middleware('permission:registrar');
        Route::put('beneficiario_donacion/{id}', 'BeneficiarioDonacionController@update')->middleware('permission:editar');
        Route::delete('beneficiario_donacion/{id}', 'BeneficiarioDonacionController@destroy')->middleware('permission:eliminar');

        Route::get('guia_internacion/print/{id}', 'GuiaInternacionController@print')->middleware('permission:imprimir');
        Route::get('guia_internacion/max/{param}', 'GuiaInternacionController@max')->middleware('permission:listar');
        Route::get('guia_internacion/fill/{param}', 'GuiaInternacionController@fill')->middleware('permission:listar');
        Route::get('guia_internacion', 'GuiaInternacionController@index')->middleware('permission:listar');
        Route::post('guia_internacion', 'GuiaInternacionController@store')->middleware('permission:registrar');
        Route::put('guia_internacion/{id}', 'GuiaInternacionController@update')->middleware('permission:editar');
        Route::delete('guia_internacion/{id}', 'GuiaInternacionController@destroy')->middleware('permission:eliminar');

        Route::get('control_guia_internacion/fill/{param}', 'ControlGuiaInternacionController@fill')->middleware('permission:listar');
        Route::get('control_guia_internacion', 'ControlGuiaInternacionController@index')->middleware('permission:listar');
        Route::post('control_guia_internacion', 'ControlGuiaInternacionController@store')->middleware('permission:registrar');
        Route::put('control_guia_internacion/{id}', 'ControlGuiaInternacionController@update')->middleware('permission:editar');
        Route::delete('control_guia_internacion/{id}', 'ControlGuiaInternacionController@destroy')->middleware('permission:eliminar');

        Route::get('hoja_ruta/max/{param}', 'HojaRutaController@max')->middleware('permission:listar');
        Route::get('hoja_ruta/fill/{param}', 'HojaRutaController@fill')->middleware('permission:listar');
        Route::get('hoja_ruta/print/{id}', 'HojaRutaController@print')->middleware('permission:imprimir');
        Route::get('hoja_ruta', 'HojaRutaController@index')->middleware('permission:listar');
        Route::post('hoja_ruta', 'HojaRutaController@store')->middleware('permission:registrar');
        Route::put('hoja_ruta/{id}', 'HojaRutaController@update')->middleware('permission:editar');
        Route::delete('hoja_ruta/{id}', 'HojaRutaController@destroy')->middleware('permission:eliminar');

        Route::get('control_hoja_ruta/fill/{params}', 'ControlHojaRutaController@fill')->middleware('permission:listar');
        Route::get('control_hoja_ruta', 'ControlHojaRutaController@index')->middleware('permission:listar');
        Route::post('control_hoja_ruta', 'ControlHojaRutaController@store')->middleware('permission:registrar');
        Route::put('control_hoja_ruta/{id}', 'ControlHojaRutaController@update')->middleware('permission:editar');
        Route::delete('control_hoja_ruta/{id}', 'ControlHojaRutaController@destroy')->middleware('permission:eliminar');

        Route::get('autorizacion_cpv/fill/{params}', 'AutorizacionCambioPuestoVentaController@fill')->middleware('permission:listar');
        Route::get('autorizacion_cpv', 'AutorizacionCambioPuestoVentaController@index')->middleware('permission:listar');
        Route::post('autorizacion_cpv', 'AutorizacionCambioPuestoVentaController@store')->middleware('permission:registrar');
        Route::put('autorizacion_cpv/{id}', 'AutorizacionCambioPuestoVentaController@update')->middleware('permission:editar');
        Route::delete('autorizacion_cpv/{id}', 'AutorizacionCambioPuestoVentaController@destroy')->middleware('permission:eliminar');

        Route::get('poder/fill/{params}', 'PoderController@fill')->middleware('permission:listar');
        Route::get('poder', 'PoderController@index')->middleware('permission:listar');
        Route::post('poder', 'PoderController@store')->middleware('permission:registrar');
        Route::put('poder/{id}', 'PoderController@update')->middleware('permission:editar');
        Route::delete('poder/{id}', 'PoderController@destroy')->middleware('permission:eliminar');

        Route::get('puesto_venta/fill/{params}', 'PuestoVentaController@fill')->middleware('permission:listar');
        Route::get('puesto_venta', 'PuestoVentaController@index')->middleware('permission:listar');
        Route::post('puesto_venta', 'PuestoVentaController@store')->middleware('permission:registrar');
        Route::put('puesto_venta/{id}', 'PuestoVentaController@update')->middleware('permission:editar');
        Route::delete('puesto_venta/{id}', 'PuestoVentaController@destroy')->middleware('permission:eliminar');

        Route::get('persona_puesto_venta/fill/{params}', 'PersonaPuestoVentaController@fill')->middleware('permission:listar');
        Route::get('persona_puesto_venta', 'PersonaPuestoVentaController@index')->middleware('permission:listar');
        Route::post('persona_puesto_venta', 'PersonaPuestoVentaController@store')->middleware('permission:registrar');
        Route::put('persona_puesto_venta/{id}', 'PersonaPuestoVentaController@update')->middleware('permission:editar');
        Route::delete('persona_puesto_venta/{id}', 'PersonaPuestoVentaController@destroy')->middleware('permission:eliminar');

        Route::get('retencion/print/{id}', 'RetencionController@print')->middleware('permission:imprimir');
        Route::get('retencion/printhoja/{id}', 'RetencionController@printHoja')->middleware('permission:imprimir');
        Route::get('retencion/max/{param}', 'RetencionController@max')->middleware('permission:listar');
        Route::get('retencion/fill/{param}', 'RetencionController@fill')->middleware('permission:listar');
        Route::get('retencion', 'RetencionController@index')->middleware('permission:listar');
        Route::post('retencion', 'RetencionController@store')->middleware('permission:registrar');
        Route::put('retencion/{id}', 'RetencionController@update')->middleware('permission:editar');
        Route::delete('retencion/{id}', 'RetencionController@destroy')->middleware('permission:eliminar');

        Route::get('deposito/saldo', 'DepositoController@saldo')->middleware('permission:listar');
        Route::get('deposito/max/{param}', 'DepositoController@max')->middleware('permission:listar');
        Route::get('deposito/fill/{params}', 'DepositoController@fill')->middleware('permission:listar');
        Route::get('deposito', 'DepositoController@index')->middleware('permission:listar');
        Route::post('deposito', 'DepositoController@store')->middleware('permission:registrar');
        Route::put('deposito/{id}', 'DepositoController@update')->middleware('permission:editar');
        Route::delete('deposito/{id}', 'DepositoController@destroy')->middleware('permission:eliminar');

        Route::get('decomiso/fill/{param}', 'DecomisoController@fill')->middleware('permission:listar');
        Route::get('decomiso', 'DecomisoController@index')->middleware('permission:listar');
        Route::post('decomiso', 'DecomisoController@store')->middleware('permission:registrar');
        Route::put('decomiso/{id}', 'DecomisoController@update')->middleware('permission:editar');
        Route::delete('decomiso/{id}', 'DecomisoController@destroy')->middleware('permission:eliminar');

        Route::get('control_puesto/fill/{params}', 'ControlPuestoController@fill')->middleware('permission:listar');
        Route::get('control_puesto', 'ControlPuestoController@index')->middleware('permission:listar');
        Route::post('control_puesto', 'ControlPuestoController@store')->middleware('permission:registrar');
        Route::put('control_puesto/{id}', 'ControlPuestoController@update')->middleware('permission:editar');
        Route::delete('control_puesto/{id}', 'ControlPuestoController@destroy')->middleware('permission:eliminar');

        Route::get('donacion/fill/{params}', 'DonacionController@fill')->middleware('permission:listar');
        Route::get('donacion', 'DonacionController@index')->middleware('permission:listar');
        Route::post('donacion', 'DonacionController@store')->middleware('permission:registrar');
        Route::put('donacion/{id}', 'DonacionController@update')->middleware('permission:editar');
        Route::delete('donacion/{id}', 'DonacionController@destroy')->middleware('permission:eliminar');

        Route::get('incineracion/fill/{params}', 'IncineracionController@fill')->middleware('permission:listar');
        Route::get('incineracion', 'IncineracionController@index')->middleware('permission:listar');
        Route::post('incineracion', 'IncineracionController@store')->middleware('permission:registrar');
        Route::put('incineracion/{id}', 'IncineracionController@update')->middleware('permission:editar');
        Route::delete('incineracion/{id}', 'IncineracionController@destroy')->middleware('permission:eliminar');

        Route::get('devolucion/fill/{params}', 'DevolucionController@fill')->middleware('permission:listar');
        Route::get('devolucion', 'DevolucionController@index')->middleware('permission:listar');
        Route::post('devolucion', 'DevolucionController@store')->middleware('permission:registrar');
        Route::put('devolucion/{id}', 'DevolucionController@update')->middleware('permission:editar');
        Route::delete('devolucion/{id}', 'DevolucionController@destroy')->middleware('permission:eliminar');
    });
    Route::group(['middleware' => ['role:admin|digprococa|digcoin']], function () {
        Route::get('persona/fill/{param}', 'PersonaController@fill')->middleware('permission:listar');
        Route::get('persona/hoja_ruta', 'PersonaController@hoja_ruta');
        Route::get('persona', 'PersonaController@index')->middleware('permission:listar');
        Route::post('persona', 'PersonaController@store')->middleware('permission:registrar');
        Route::put('persona/{id}', 'PersonaController@update')->middleware('permission:editar');
        Route::delete('persona/{id}', 'PersonaController@destroy')->middleware('permission:eliminar');

        Route::get('productor', 'ProductorController@index')->middleware('permission:listar');
        Route::post('productor', 'ProductorController@store')->middleware('permission:registrar');
        Route::put('productor/{id}', 'ProductorController@update')->middleware('permission:editar');
        Route::delete('productor/{id}', 'ProductorController@destroy')->middleware('permission:eliminar');

        Route::get('tipo_persona', 'TipoPersonaController@index')->middleware('permission:listar');
        Route::post('tipo_persona', 'TipoPersonaController@store')->middleware('permission:registrar');
        Route::put('tipo_persona/{id}', 'TipoPersonaController@update')->middleware('permission:editar');
        Route::delete('tipo_persona/{id}', 'TipoPersonaController@destroy')->middleware('permission:eliminar');

        Route::get('cesacion/fill/{param}', 'CesacionController@fill')->middleware('permission:listar');
        Route::get('cesacion', 'CesacionController@index')->middleware('permission:listar');
        Route::post('cesacion', 'CesacionController@store')->middleware('permission:registrar');
        Route::put('cesacion/{id}', 'CesacionController@update')->middleware('permission:editar');
        Route::delete('cesacion/{id}', 'CesacionController@destroy')->middleware('permission:eliminar');

        Route::get('persona_cesacion/fill/{param}', 'PersonaCesacionController@fill')->middleware('permission:listar');
        Route::get('persona_cesacion', 'PersonaCesacionController@index')->middleware('permission:listar');
        Route::post('persona_cesacion', 'PersonaCesacionController@store')->middleware('permission:registrar');
        Route::put('persona_cesacion/{id}', 'PersonaCesacionController@update')->middleware('permission:editar');
        Route::delete('persona_cesacion/{id}', 'PersonaCesacionController@destroy')->middleware('permission:eliminar');

        Route::get('sustitucion/fill/{param}', 'SustitucionController@fill')->middleware('permission:listar');
        Route::get('sustitucion', 'SustitucionController@index')->middleware('permission:listar');
        Route::post('sustitucion', 'SustitucionController@store')->middleware('permission:registrar');
        Route::put('sustitucion/{id}', 'SustitucionController@update')->middleware('permission:editar');
        Route::delete('sustitucion/{id}', 'SustitucionController@destroy')->middleware('permission:eliminar');

        Route::get('comunidad/fill/{param}', 'ComunidadController@fill')->middleware('permission:listar');
        Route::get('comunidad', 'ComunidadController@index')->middleware('permission:listar');
        Route::post('comunidad', 'ComunidadController@store')->middleware('permission:registrar');
        Route::put('comunidad/{id}', 'ComunidadController@update')->middleware('permission:editar');
        Route::delete('comunidad/{id}', 'ComunidadController@destroy')->middleware('permission:eliminar');

        Route::get('carnet_comercializador/print/{id}', 'CarnetComercializadorController@print')->middleware('permission:imprimir');
        Route::get('carnet_comercializador/fill/{param}', 'CarnetComercializadorController@fill')->middleware('permission:listar');
        Route::get('carnet_comercializador', 'CarnetComercializadorController@index')->middleware('permission:listar');
        Route::get('carnet_comercializador/{id}', 'CarnetComercializadorController@show')->middleware('permission:listar');
        Route::post('carnet_comercializador', 'CarnetComercializadorController@store')->middleware('permission:registrar');
        Route::put('carnet_comercializador/{id}', 'CarnetComercializadorController@update')->middleware('permission:editar');
        Route::delete('carnet_comercializador/{id}', 'CarnetComercializadorController@destroy')->middleware('permission:eliminar');

        Route::get('resolucion_administrativa/fill/{param}', 'ResolucionAdministrativaController@fill');
        Route::get('resolucion_administrativa', 'ResolucionAdministrativaController@index')->middleware('permission:listar');
        Route::post('resolucion_administrativa', 'ResolucionAdministrativaController@store')->middleware('permission:registrar');
        Route::put('resolucion_administrativa/{id}', 'ResolucionAdministrativaController@update')->middleware('permission:editar');
        Route::delete('resolucion_administrativa/{id}', 'ResolucionAdministrativaController@destroy')->middleware('permission:eliminar');
        
        Route::get('tipo_resolucion', 'TipoResolucionController@index')->middleware('permission:listar');
        Route::post('tipo_resolucion', 'TipoResolucionController@store')->middleware('permission:registrar');
        Route::put('tipo_resolucion/{id}', 'TipoResolucionController@update')->middleware('permission:editar');
        Route::delete('tipo_resolucion/{id}', 'TipoResolucionController@destroy')->middleware('permission:eliminar');

        Route::get('tipo_sancion', 'TipoSancionController@index')->middleware('permission:listar');
        Route::post('tipo_sancion', 'TipoSancionController@store')->middleware('permission:registrar');
        Route::put('tipo_sancion/{id}', 'TipoSancionController@update')->middleware('permission:editar');
        Route::delete('tipo_sancion/{id}', 'TipoSancionController@destroy')->middleware('permission:eliminar');

        Route::get('sancion/fill/{param}', 'SancionController@fill');
        Route::get('sancion/showfill/{param}', 'SancionController@showfill');
        Route::get('sancion', 'SancionController@index')->middleware('permission:listar');
        Route::post('sancion', 'SancionController@store')->middleware('permission:registrar');
        Route::put('sancion/{id}', 'SancionController@update')->middleware('permission:editar');
        Route::delete('sancion/{id}', 'SancionController@destroy')->middleware('permission:eliminar');
        
        Route::get('infraccion/fill/{param}', 'InfraccionController@fill');
        Route::get('infraccion', 'InfraccionController@index')->middleware('permission:listar');
        Route::post('infraccion', 'InfraccionController@store')->middleware('permission:registrar');
        Route::put('infraccion/{id}', 'InfraccionController@update')->middleware('permission:editar');
        Route::delete('infraccion/{id}', 'InfraccionController@destroy')->middleware('permission:eliminar');

        Route::get('comercializador_infraccion/fill/{param}', 'ComercializadorInfraccionController@fill');
        Route::get('comercializador_infraccion', 'ComercializadorInfraccionController@index')->middleware('permission:listar');
        Route::post('comercializador_infraccion', 'ComercializadorInfraccionController@store')->middleware('permission:registrar');
        Route::put('comercializador_infraccion/{id}', 'ComercializadorInfraccionController@update')->middleware('permission:editar');
        Route::delete('comercializador_infraccion/{sancion_id}', 'ComercializadorInfraccionController@destroy')->middleware('permission:eliminar');


    });
    Route::group(['middleware' => ['role:admin|digprococa|digcoin|fonadin|oii']], function () {
        Route::get('zona_productiva/fill/{param}', 'ZonaProductivaController@fill');
        Route::resource('zona_productiva', 'ZonaProductivaController');
        Route::get('departamento/fill/{param}', 'DepartamentoController@fill');
        Route::resource('departamento', 'DepartamentoController');
        Route::get('provincia/fill/{param}', 'ProvinciaController@fill');
        Route::resource('provincia', 'ProvinciaController');
        Route::get('municipio/fill/{param}', 'MunicipioController@fill');
        Route::resource('municipio', 'MunicipioController');
        Route::get('federacion/fill/{param}', 'FederacionController@fill');
        Route::get('/federacion/codigo/{code}', 'FederacionController@codevalidate');
        Route::resource('federacion', 'FederacionController');
        Route::get('pais/fill/{param}', 'PaisController@fill');
        Route::resource('pais', 'PaisController');
        Route::get('regional/fill/{param}', 'RegionalController@fill');
        Route::resource('regional', 'RegionalController');
        Route::get('central/fill/{param}', 'CentralController@fill');
        Route::get('/central/codigo/{code}', 'CentralController@codevalidate');
        Route::resource('central', 'CentralController');
        Route::get('/sindicato/codigo/{code}', 'SindicatoController@codevalidate');
        Route::resource('sindicato', 'SindicatoController');
        Route::get('/infraccion/codigo/{code}', 'InfraccionController@codevalidate');
        Route::resource('infraccion', 'InfraccionController');
        Route::get('/sancion/codigo/{code}', 'SancionController@codevalidate');
        Route::resource('sancion', 'SancionController');
        Route::get('/mercado/codigo/{code}', 'MercadoController@codevalidate');
        Route::resource('mercado', 'MercadoController');
        Route::get('/puesto_de_control/codigo/{code}', 'PuestoControlController@codevalidate');
        Route::resource('puesto_de_control', 'PuestoControlController');
        Route::get('/ruta/codigo/{code}', 'RutaController@codevalidate');
        Route::get('/ruta/fill/{param}', 'RutaController@fill');
        Route::resource('ruta', 'RutaController');
        Route::resource('monto', 'MontoController');
        Route::get('vehiculo/fill/{param}', 'VehiculoController@fill');
        Route::resource('vehiculo', 'VehiculoController');
        Route::get('marca/fill/{param}', 'MarcaController@fill');
        Route::resource('marca', 'MarcaController');     
        Route::post('upload/file', 'UploadController@file');
        Route::post('upload/base64_image', 'UploadController@base64_image');
        Route::post('upload/delete', 'UploadController@delete');
        Route::get('causa_retencion/fill/{param}', 'CausaRetencionController@fill');
        Route::resource('causa_retencion', 'CausaRetencionController');
        Route::get('localidad/fill/{param}', 'LocalidadController@fill');
        Route::resource('localidad', 'LocalidadController');
        Route::get('embarcacion/fill/{param}', 'EmbarcacionController@fill');
        Route::resource('embarcacion', 'EmbarcacionController');
        Route::get('responsable/fill/{param}', 'ResponsableController@fill');
        Route::resource('responsable', 'ResponsableController');  
        Route::get('entidad/fill/{param}', 'EntidadController@fill');
        Route::resource('entidad', 'EntidadController');
        Route::get('motivo_incineracion/fill/{param}', 'MotivoIncineracionController@fill');
        Route::resource('motivo_incineracion', 'MotivoIncineracionController'); 
        Route::get('incineracion_adjunto/fill/{param}', 'IncineracionAdjuntoController@fill');
        Route::resource('incineracion_adjunto', 'IncineracionAdjuntoController');
        Route::get('veedor/fill/{param}', 'VeedorController@fill');
        Route::resource('veedor', 'VeedorController');
    });
});