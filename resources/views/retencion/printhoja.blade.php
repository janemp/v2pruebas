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
    <div class="page-break p-10" style="border-radius: 0.75em;border: 1px solid #22292f;">
        <table class="w-100 ">
            <tr>
                <th class="w-15 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        
                    </div>
                </th>
                <th class="w-50 align-top">
                    <span class="font-semibold leading-tight text-md" >
                        BOLETA DE RETENCIÓN DE LA HOJA DE COCA<br>
                        <span class="text-xs">(CONFORME AL ARTÍCULO 31 y 32 DEL D.S. N°3318)</span>
                    </span>
                </th>
                <th class="w-20 no-padding no-margins align-top">                   
                    <table class="table-code no-padding no-margins">
                        <tbody>                            
                            <tr>
                                <td class="w-50 text-center bg-grey-darker text-xxs text-white">Nro</td>
                                <td class="text-xs"> {{ $retencion->correlativo }} </td>
                            </tr>
                        </tbody>
                    </table>                
                </th>
            </tr>
            <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>                  
        </table>
        <div class="block">
            <br>
            <div class="font-medium font-bold text-xs">DATOS GENERALES</div>
            <table class="w-100 m-b-10">
                <thead class="">
                    <tr class="font-medium text-sm">
                        <td colspan='10' class="px-15 text-left">
                            
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    <tr class="text-xxs">
                        <td colspan="3" class="w-30 font-bold">Nombres y apellidos del infractor:</td>
                        <td colspan="7" class="w-70">
                            @if ($retencion->hoja_ruta_id)
                            {{ $retencion->hoja_ruta->persona->nombre.' '.$retencion->hoja_ruta->persona->primer_apellido.' '.$retencion->hoja_ruta->persona->segundo_apellido }}
                            @else 
                            {{ $retencion->nombre.' '.$retencion->primer_apellido.' '.$retencion->segundo_apellido }}
                            @endif
                        </td>                   
                    </tr>
                    <tr class="text-xxs">
                        <td colspan="3" class="w-30 font-bold">Número de cedula de identidad:</td>
                        <td colspan="7" class="w-70">
                            @if ($retencion->hoja_ruta_id)
                            {{ $retencion->hoja_ruta->persona->carnet_identidad.' '.$retencion->hoja_ruta->persona->departamento_extension->sigla }}
                            @else 
                            {{ $retencion->carnet_identidad }}
                            @endif
                        </td>
                    </tr>
                    {{-- <tr class="text-xxs">
                        <td colspan="6" class="w-60 font-bold" colspan="2">En caso de productores de hoja de coca, número de carnet de productor:</td>
                        <td class="w-10">
                            @if ($retencion->hoja_ruta_id)
                            {{ $retencion->hoja_ruta->persona->carnet_productor[0]->carnet }}
                            @endif
                        </td>
                        <td colspan="2" class="w-20 font-bold">Iniciales de embalaje</td>
                        <td class="w-20">
                            @if ($retencion->hoja_ruta_id)
                            {{ $retencion->inicial_embalaje }}
                            @endif
                        </td>                        
                    </tr> --}}
                    <tr class="text-xxs">
                        <td colspan="6" class="w-60 font-bold" colspan="2">En caso de productor al detalle o comerciantes al detalle, número de carnet de comercializador:</td>
                        <td class="w-10">
                            @if ($retencion->hoja_ruta_id)
                            {{ $retencion->hoja_ruta->persona }}
                            @endif
                        </td>
                        <td colspan="2" class="w-20 font-bold">Iniciales de embalaje</td>
                        <td class="w-20">
                            @if ($retencion->hoja_ruta_id)
                            {{ $retencion->inicial_embalaje }}
                            @endif
                        </td>                        
                    </tr>
                </tbody>
            </table>
            <br>
            <div class="font-medium font-bold text-xs">CAUSAL DE RETENCIÓN DE LA HOJA DE COCA: (CONFORME AL ARTICULO 31 D.S. N°3318)</div>
            <br>
            <table class="table-info w-100 m-b-10">
                <thead class="">
                    <tr class="font-medium text-sm">
                        <td colspan='10' class="px-15 text-left">
                            
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    @foreach ($causas_retencion as $causa_retencion)
                    <tr class="text-xxs">
                        <td class="w-90 border-l"> {{ $causa_retencion->nombre }} </td>                        
                        <td class="w-10 text-center font-bold">
                            @if ($retencion->causa_retencion_id == $causa_retencion->id)
                            x
                            @endif
                        </td>
                    </tr>                        
                    @endforeach                        
                </tbody>
            </table>
            <table class="w-100 m-b-10">
                <thead class="">
                    <tr class="font-medium text-sm">
                        <td colspan='10' class="px-15 text-left">
                            
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    <tr class="text-xxs">
                        <td class="w-10 font-bold">Lugar:</td>
                        <td colspan="3" class="w-30">
                            {{ $retencion->lugar }}
                        </td>
                        <td class="w-10 font-bold">Fecha:</td>
                        <td colspan="2" class="w-20">
                            {{ Carbon::parse($retencion->created_at)->format('d/m/Y') }}
                        </td>
                        <td class="w-10 font-bold">Hora:</td>
                        <td colspan="2" class="w-20">
                            {{ Carbon::parse($retencion->created_at)->format('H:i') }}
                        </td>
                    </tr>
                    <tr class="text-xxs">
                        <td class="w-10 font-bold">Localidad:</td>
                        <td colspan="3" class="w-30">
                            {{ $retencion->localidad->nombre }}
                        </td>
                        <td class="w-10 font-bold">Municipio:</td>
                        <td colspan="2" class="w-20">
                            {{ $retencion->municipio->nombre }}
                        </td>
                        <td class="w-10 font-bold">Departamento:</td>
                        <td colspan="2" class="w-20">
                            {{ $retencion->departamento->nombre }}
                        </td>
                    </tr>
                    <tr class="text-xxs">
                        <td class="w-10 font-bold">Descripción:</td>
                        <td colspan="9" class="w-30">
                            {{ $retencion->descripcion }}
                        </td>
                    </tr>
                    <tr class="text-xxs">
                        <td colspan="2" class="w-20 font-bold">Cantidad de taques:</td>
                        <td colspan="3" class="w-30">
                            {{ $retencion->numero_taques }}
                        </td>
                        <td colspan="3" class="w-30 font-bold">Peson total en libras (Aproximado):</td>
                        <td colspan="2" class="w-20">
                            {{ $retencion->libras }}
                        </td>
                    </tr>                    
                </tbody>
            </table>
            <br>
            <div class="font-medium font-bold text-xs">PLACAS Y CARACTERISTICAS DEL MEDIO DE TRANSPORTE</div>
            <br>
            <table class="w-100 m-b-10">
                <thead class="">
                    <tr class="font-medium text-sm">
                        <td colspan='10' class="px-15 text-left">
                            
                        </td>
                    </tr>
                </thead>
                <tbody class="table-striped">
                    <tr class="text-xxs">
                        <td class="w-10 font-bold text-center">
                            <p class="border" style="width:30px;text-align:center;margin:0;">
                                @if ($retencion->numero_placa)
                                x
                                @else 
                                &nbsp;
                                @endif
                            </p>
                        </td>
                        <td colspan="3" class="w-30">
                            <span class="font-bold">TERRESTRE: Placa de control </span>
                            {{ $retencion->numero_placa }}
                        </td>
                        <td class="w-10 font-bold">Marca</td>
                        <td colspan="3" class="w-30">
                            @if ($retencion->marca_id)
                            {{ $retencion->marca->nombre }}
                            @endif
                        </td>
                        <td class="w-10 font-bold">Tipo</td>
                        <td colspan="3" class="w-30">
                            @if ($retencion->vehiculo_id)
                            {{ $retencion->vehiculo->nombre }}
                            @endif
                        </td>
                    </tr>
                    <tr class="text-xxs">
                        <td class="w-10 font-bold text-center">
                            <p class="border" style="width:30px;text-align:center;margin:0;">
                                @if ($retencion->numero_vuelo)
                                x
                                @else 
                                &nbsp;
                                @endif
                            </p>
                        </td>
                        <td colspan="3" class="w-30">
                            <span class="font-bold">VUELO: N° de vuelo </span>
                            {{ $retencion->numero_vuelo }}
                        </td>
                        <td class="w-10 font-bold">Empresa</td>
                        <td colspan="3" class="w-30">
                            {{ $retencion->empresa_vuelo }}
                        </td>
                        <td class="w-10 font-bold">Otros</td>
                        <td colspan="3" class="w-30">
                            {{ $retencion->otros_vuelo }}
                        </td>
                    </tr>
                    <tr class="text-xxs">
                        <td class="w-10 font-bold text-center">
                            <p class="border" style="width:30px;text-align:center;margin:0;">
                                @if ($retencion->numero_embarcacion)
                                x
                                @else 
                                &nbsp;
                                @endif
                            </p>
                        </td>
                        <td colspan="3" class="w-30">
                            <span class="font-bold">FLUVIAL: Nombre de embarcación </span>
                            {{ $retencion->nombre_embarcacion }}
                        </td>
                        <td class="w-10 font-bold">Tipo embarcación</td>
                        <td colspan="3" class="w-30">
                            @if ($retencion->embarcacion_id)
                            {{ $retencion->embarcacion->nombre }}
                            @endif
                        </td>
                        <td class="w-10 font-bold">N° de registro</td>
                        <td colspan="3" class="w-30">
                            {{ $retencion->numero_embarcacion }}
                        </td>
                    </tr>
                    <tr class="text-xxs">
                        <td class="w-10 font-bold text-center">
                            Retenida por: 
                        </td>
                        <td colspan="3" class="w-30 font-bold text-center">
                            Grupo Especial de Control de la Coca
                        </td>
                        <td colspan="2" class="w-20 font-bold text-center">
                            <p class="border" style="width:30px;text-align:center;margin:0;">
                                @if ($retencion->responsable == 'GECC')
                                x
                                @else 
                                &nbsp;
                                @endif
                            </p>
                        </td>
                        <td colspan="2" class="w-20 font-bold text-center">
                            Verificador DIGCOIN
                        </td>
                        <td colspan="2" class="w-20 font-bold text-center">
                            <p class="border" style="width:30px;text-align:center;margin:0;">
                                @if ($retencion->responsable == 'DIGCOIN')
                                x
                                @else 
                                &nbsp;
                                @endif
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <div class="font-medium font-bold text-xs">FIRMA DE LOS INTERVINIENTES:</div>
            <br>
            <table class="w-100">                
                <tbody class="table-striped">
                    <tr class="text-xxs" style="height:200px;vertical-align: bottom;">
                        <td colspan="5" class="w-50 font-bold text-center">
                        Sello y firma de la Autoridad que realizó la retención
                        </td>
                        <td colspan="5" class="w-50 font-bold text-center">
                        Nombre y firma del infractor(a)
                        </td>
                    </tr>
                </tbody>
            </table> 
            <br>          
        </div>
    </div>
    <div class="text-xxxs" align="right"> {{ 'Impreso el '.date('d/m/Y H:i') }} </div>

</body>
</html>