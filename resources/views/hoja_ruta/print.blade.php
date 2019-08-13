<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
</head>
<body style="border: 0;border-radius: 0;">
    <br>
    <br>
    <br>
    <br>

    <div style="width: 100%">
        {{-- Aquí se cambia el precio de la coca, 7 es el precio por unidad--}}
        <div style="width: 100%; margin-right: 10px">
            <span style="float: right"><b>Bs. {{ $hoja_ruta->taques * 7}}</b></span>
        </div>
        <br>
        <br>
        <br>
        <div style="width: 100%">
            <table style="float: right;">
                <tr>
                    <th>
                        <div>
                        </div>
                    </th>
                    <th>
                        <span>
                            
                        </span>
                    </th>
                    <th>                   
                        <table>
                            <tbody>                            
                                <tr>
                                    <td style="text-align: right"> {{ $hoja_ruta->correlativo }} </td>
                                </tr>
                                <tr>
                                    <td style="white-space:nowrap;">La Paz {{ Carbon::parse(date('Y/m/d'))->day }} de {{ Carbon::parse(date('Y/m/d'))->formatLocalized('%B') }} del {{ Carbon::parse(date('Y/m/d'))->year }}</td>
                                </tr>
                            </tbody>
                        </table>                
                    </th>
                </tr>                  
            </table>
        </div>
        
        <div style="width: 100%">            
            <table style="width: 100%">
                <thead>
                    <tr>
                        <td colspan='4'>
                            <b>1.- DATOS DEL COMERCIANTE</b> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan='1'><b>Nombre del Comerciante: </b></td>
                        <td colspan='3'>
                            <i>{{ $hoja_ruta->persona->nombre.' '.$hoja_ruta->persona->primer_apellido.' '.$hoja_ruta->persona->segundo_apellido }}</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                    <tr>
                        <td><b>CI:</b></td>
                        <td>
                            <i>{{ $hoja_ruta->persona->carnet_identidad.' '.$hoja_ruta->persona->departamento_extension->sigla }}</i>
                        </td>
                        <td><b>Codigo: </b> </td>
                        <td>
                            {!! DNS1D::getBarcodeHTML($hoja_ruta->persona->carnet_identidad, "C39") !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                    <tr>
                        <td colspan='1'><b>Domicilio:</b> </td>
                        <td colspan='3'>
                            <i>{{ $hoja_ruta->persona->domicilio }}</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                    <tr>
                        <td colspan='1'><b>Puesto de venta: </b> </td>
                        <td colspan='3'>
                            <i>{{ $hoja_ruta->puesto_venta->mercado }}</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                </tbody>
            </table>
            <table style="width:100%">
                <thead>
                    <tr>
                        <td colspan='12'>
                            <b>2.- DATOS SOBRE EL PRODUCTO Y LA AUTORIZACION</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12" style="border-bottom: 1px solid #22292f;">
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3"><b>Cantidad:</b> </td>
                        <td colspan="3">
                            <i>{{ $hoja_ruta->taques }} Taques</i>
                        </td>
                        <td colspan="3"><b>Tiempo validez: </b> </td>
                        <td colspan="3">
                            <i>{{ Carbon::parse($hoja_ruta->fecha_final)->formatLocalized('%B') }} del {{ date("Y", strtotime($hoja_ruta->fecha_final)) }}</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12" style="border-bottom: 1px solid #22292f;">
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: center; border-right: 1px solid #22292f">
                            <b>Procedencia de la coca: </b><br>
                            {{ $hoja_ruta->procedencia->nombre }}
                        </td>
                        <td colspan="4" style="text-align: center; border-right: 1px solid #22292f">
                            <b>Destino: </b><br>
                            {{ $hoja_ruta->localidad->nombre }} <br>
                            <b>Localidad:</b><br>
                        </td>
                        <td colspan="4" style="text-align: center">
                            {{ $hoja_ruta->destino->nombre }} <br>
                            <b>Departamento: </b><br>
                        </td>
                    </tr>                       
                    <tr>
                        <td colspan="12" style="border-bottom: 1px solid #22292f;">
                    </tr>
                </tbody>
            </table>
            <table style="width:100%">
                <thead>
                    <tr>
                        <td colspan='4'>
                            <b>3.- DATOS DEL MEDIO DE TRANSPORTE</b> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Tipo de Vehículo:</b> </td>
                        <td>
                            <i>{{ $hoja_ruta->vehiculo->nombre }}</i>
                        </td>
                        <td><b>Marca:</b> </td>
                        <td>
                            <i>{{ $hoja_ruta->marca->nombre }}</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                    <tr>
                        <td class="text-left w-20 px-10 py-3 font-bold"><b>Nro. de Placa: </b></td>
                        <td class="text-left px-5 py-3">
                            <i>{{ $hoja_ruta->numero_placa }}</i>
                        </td>
                        <td class="text-left w-20 px-10 py-3 font-bold"><b>Color: </b> </td>
                        <td class="text-left px-5 py-3">
                            <i>{{ $hoja_ruta->color }}</i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                    <tr class="text-sm">
                        <td colspan="2" class="text-left w-20 px-10 py-3 font-bold"><b>Nombre del conductor:</b>  </td>
                        <td colspan="2" class="text-left px-5 py-3">
                            <i>{{ $hoja_ruta->nombre_conductor }}</i>
                        </td>
                    </tr>                        
                    <tr>
                        <td colspan="4" style="border-bottom: 1px solid #22292f;">
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%">
                <tbody>
                    <tr style="height: 130px; vertical-align: bottom;">
                        <td style="text-align: center; width: 35%"><b>Jefe de comercialización</b> </td>
                        <td style="text-align: center; width: 35%"><b>Director General DIGCOIN</b> </td>
                        <td style="text-align: center; width: 30%"><i>{{ $hoja_ruta->persona->nombre.' '.$hoja_ruta->persona->primer_apellido.' '.$hoja_ruta->persona->segundo_apellido }}</i><br><b>Firma Interesado</b></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom: 1px solid #22292f;">
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%">
                <thead>
                    <tr>
                        <td style="text-align:center">
                            <b>CONTROL DE LOS RETENES DE TRANSITO</b> 
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid #22292f;">
                    </tr>
                </thead>
            </table>
            <div style="width: 100%; height: 420px">
                @php
                    $count = 1;
                @endphp
                @foreach($hoja_ruta->ruta->puestos_de_control as $puesto)
                <div style="width: 220px; display:inline-block; height: 140px">
                    <center>
                        <div style="width: 120px; height: 60%">
                            {!! QrCode::size(110)->generate($puesto->hash); !!} 
                        </div>
                        <div style="height: 40%; font-size: 15px">
                            {{$count++}}.- {{ $puesto->nombre }}
                        </div>
                    </center>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div style="width: 100%;">
        <table width="100%">
            <tr>
                <td style="border-bottom: 2px solid #22292f;">
            </tr>
            <tr>
                <td class="text-center" style="height: 100px; font-size: 19px" align="justify">
                    <b>NOTA: La presente HOJA DE RUTA, autoriza a quién le sea extendido legalmente, al traslado de la Hoja
                    de Coca de los centros de producción a los mercados campesinos y de estos a los compradores y/o
                    detallistas. En caso de que el presente Comprobante tenga borrones o alteraciones quedará nulo en
                    su valor legal.</b>
                </td>               
            </tr>
        </table>
    </div>
</body>
</html>