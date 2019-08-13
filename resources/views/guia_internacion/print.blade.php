<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        <?php include public_path('css/report.css') ?>
    </style>    
</head>
<body style="border: 0;border-radius: 0;">
    <table class="w-100 ">
        <tr>
            <th class="w-15 text-left no-padding no-margins align-middle">
                <div class="text-center">
                    
                </div>
            </th>
            <th class="w-50 align-top">
                <span class="font-semibold leading-tight text-md" >
                    Guía de internación
                </span>
            </th>
            <th class="w-20 no-padding no-margins align-top">                   
                <table class="table-code no-padding no-margins">
                    <tbody>                            
                        <tr>
                            <td class="text-center bg-grey-darker text-xxs text-white">Nro</td>
                            <td class="text-xs"> {{ $guia_internacion->correlativo }} </td>
                        </tr>
                    </tbody>
                </table>                
            </th>
        </tr>             
    </table>
    <div class="page-break p-10" style="border-radius: 0.75em;border: 1px solid #22292f;">        
        <div class="block">
            <table class="w-100 m-b-10">
                <tbody class="">
                    <tr class="text-xs">
                        <tr class="text-xs">
                            <td colspan="2" class="w-30 font-bold"></td>
                            <td colspan="4" class="w-30"></td>
                            <td class="w-20 font-bold">Procedencia Regional:</td>
                            <td colspan="3" class="w-30" style="border-bottom: 1px dashed;">
                                @if(count($guia_internacion->persona->carnet_productor) > 0)
                                {{ $guia_internacion->persona->carnet_productor[0]->regional->nombre }}
                                @endif
                            </td>                        
                        </tr>
                    </tr>
                </tbody>
            </table>
            <div class="font-medium font-bold text-xs">1. DATOS DEL PRODUCTOR</div>
            <br>
            <table class="w-100 m-b-10">                
                <tbody class="">
                    <tr class="text-xs">
                        <td colspan="2" class="w-30 font-bold">Nombres y apellidos: </td>
                        <td colspan="4" class="w-40" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->persona->nombre.' '.$guia_internacion->persona->primer_apellido.' '.$guia_internacion->persona->segundo_apellido }}                        
                        </td>
                        <td class="w-10 font-bold" align="right">CI.: </td>
                        <td colspan="3" class="w-30" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->persona->carnet_identidad. ' '.$guia_internacion->persona->departamento_extension->sigla }}
                        </td>                        
                    </tr>
                    <tr class="text-xs">
                        <td colspan="3" class="w-25 font-bold">Número de carnet de productor: </td>
                        <td colspan="2" class="w-25" style="border-bottom: 1px dashed;">
                            @if(count($guia_internacion->persona->carnet_productor) > 0)
                            {{ $guia_internacion->persona->carnet_productor[0]->carnet }}
                            @endif
                        </td>
                        <td colspan="2" class="w-20 font-bold" align="right">Comunidad: </td>
                        <td colspan="3" class="w-30" style="border-bottom: 1px dashed;">
                            @if(count($guia_internacion->persona->carnet_productor) > 0)
                            {{ $guia_internacion->persona->carnet_productor[0]->comunidad->nombre }}
                            @endif
                        </td>                        
                    </tr>
                </tbody>
            </table>
            <br>
            <div class="font-medium font-bold text-xs">2. DATOS DE GUÍA DE INTERNACIÓN</div>
            <br>
            <table class="w-100 m-b-10">                
                <tbody class="">
                    <tr class="text-xs">
                        <td class="font-bold" colspan="3">Número orden comunal: </td>
                        <td class="" colspan="2" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->orden_comunal }}
                        </td>
                        <td class="font-bold" colspan="3" align="right">Fecha orden comunal: </td>
                        <td class="" colspan="3" style="border-bottom: 1px dashed;">
                        {{ Carbon::parse($guia_internacion->fecha_orden_comunal)->format('d/m/Y') }}
                        </td>                        
                    </tr>
                    <tr class="text-xs">
                        <td class="font-bold" colspan="3">Número de taques(bultos): </td>
                        <td class="" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->numero_taques }}
                        </td>
                        <td class="font-bold" align="right">Libras: </td>
                        <td class="" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->libras }}
                        </td>
                        <td class="font-bold" colspan="2" align="right">Procedencia: </td>
                        <td class="" colspan="2" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->procedencia }}
                        </td>                        
                    </tr>
                    <tr class="text-xs">
                        <td class="font-bold" colspan="3">Nombres y apellidos del conductor </td>
                        <td class="" colspan="7" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->conductor }}
                        </td>
                    </tr>
                    <tr class="text-xs">
                        <td class="w-10 font-bold">N° Licencia </td>
                        <td class="w-10" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->licencia }}
                        </td>
                        <td class="w-10 font-bold" align="right">Vehículo: </td>
                        <td class="w-10" style="border-bottom: 1px dashed;">
                        @if ($guia_internacion->vehiculo_id)
                        {{ $guia_internacion->vehiculo->nombre }}
                        @endif
                        </td>
                        <td class="w-10 font-bold" align="right">Marca: </td>
                        <td class="w-10" style="border-bottom: 1px dashed;">
                        @if ($guia_internacion->marca_id)
                        {{ $guia_internacion->marca->nombre }}
                        @endif
                        </td>
                        <td class="w-10 font-bold" align="right">Color: </td>
                        <td class="w-10" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->color }}
                        </td>
                        <td class="w-10 font-bold" align="right">N° Placa: </td>
                        <td class="w-10" style="border-bottom: 1px dashed;">
                        {{ $guia_internacion->numero_placa }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="float: left;width:350px;">            
                <table class="w-100 m-b-10">
                    <thead class="">
                        <tr class="font-medium font-bold text-sm">
                            <td colspan="2" class="px-15 text-center">
                                RETEN DE SALIDA DEL PRODUCTO
                            </td>
                        </tr>
                    </thead>
                    <tbody class="">
                        <tr class="text-xs">
                            <td class="font-bold" width="125">Fecha y hora: </td>
                            <td class="text-center" style="border-bottom: 1px dashed;">{{ Carbon::parse($guia_internacion->created_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr class="text-xs">
                            <td class="font-bold" style="height:100px;">Firma funcionario</td>
                            <td class=""></td>
                        </tr>
                        <tr class="text-xs">
                            <td class="font-bold">Nombres y apellidos: </td>
                            <td class="" style="border-bottom: 1px dashed;">
                                {{ $guia_internacion->user->name.' '.$guia_internacion->user->last_name.' '.$guia_internacion->user->mothers_last_name }}
                            </td>
                        </tr>
                        <tr class="text-xs">
                            <td class="font-bold" width="125">Puesto de control: </td>
                            <td class="text-center" style="border-bottom: 1px dashed;">
                                @if ($guia_internacion->ruta_id)
                                {{ $guia_internacion->ruta->puestos_de_control[0]->nombre }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="float: left;width:348px;">
                <table class="w-100 m-b-10">
                    <thead class="">
                        <tr class="font-medium font-bold text-sm">
                            <td colspan='2' class="px-15 text-center">
                                INGRESO MERCADO DE LA COCA LEGAL
                            </td>
                        </tr>
                    </thead>
                    <tbody class="">
                        <tr class="text-xs">
                            <td class="font-bold" width="125">Fecha y hora: </td>
                            <td class="text-center" style="border-bottom: 1px dashed;">
                                @if (count($guia_internacion->control_guia_internacion) > 0)
                                {{ Carbon::parse($guia_internacion->updated_at)->format('d/m/Y H:i') }}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-xs">
                            <td class="font-bold" style="height:100px;">Firma funcionario</td>
                            <td class=""></td>
                        </tr>
                        <tr class="text-xs">
                            <td class="font-bold">Nombres y apellidos: </td>
                            <td class="" style="border-bottom: 1px dashed;">
                                @if (count($guia_internacion->control_guia_internacion) > 0)
                                {{ $guia_internacion->user->name.' '.$guia_internacion->user->last_name.' '.$guia_internacion->user->mothers_last_name }}
                                @endif
                            </td>
                        </tr>
                        <tr class="text-xs">
                            <td class="font-bold" width="125">Puesto de control: </td>
                            <td class="text-center" style="border-bottom: 1px dashed;">
                                @if ($guia_internacion->ruta_id)
                                {{ $guia_internacion->ruta->puestos_de_control[1]->nombre }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div style="clear: both;"></div>
            <div style="text-align: right;">
                @php($origen = ($guia_internacion->ruta_id)?$guia_internacion->ruta->puestos_de_control[0]->nombre:'-')
                @php($destino = ($guia_internacion->ruta_id)?$guia_internacion->ruta->puestos_de_control[1]->nombre:'-')
                @php($data = base64_encode(
                    'INTERNACION|'.
                    $guia_internacion->id.'|'.
                    $guia_internacion->correlativo.'|'.
                    strtoupper($guia_internacion->persona->nombre.' '.$guia_internacion->persona->primer_apellido.' '.$guia_internacion->persona->segundo_apellido).'|'.
                    $guia_internacion->persona->carnet_identidad.'|'.
                    $guia_internacion->persona->departamento_extension->sigla.'|'.
                    $guia_internacion->numero_taques.'|'.
                    $guia_internacion->libras.'|'.
                    $origen.'|'.
                    $destino.'|'))
                {!! QrCode::margin(0)->size(80)->generate($data); !!}
            </div>
            <div style="font-size: 6px;">NOTA: Queda terminantemente prohibido bajo sanción de ley, adulterar la declaración de cantidad, peso y destino, LA PRESENTE NO TENDRÁ VALIDEZ SI EXISTEN BORRONES O ALTERACIONES DE CUALQUIER TIPO.</div>
        </div>
    </div>
    <div class="text-xxxs" align="right"> {{ 'Impreso el '.date('d/m/Y H:i') }} </div>

</body>
</html>