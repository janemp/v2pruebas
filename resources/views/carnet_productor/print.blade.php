<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <style type="text/css" media="print">
        div.page
        {
            page-break-after: always;
            page-break-inside: avoid;
        }
    </style> 
</head>
<body>    
    <div class="page" align="center" style="margin: 0 0 0 -8px;">
        <table style="background: #00258e;width:360px; margin: 0 0 0 -8px;color: #fff;font-weight:bold;">
                <tr>
                    <td width="60" style="text-align:center;">
                    <img src="{{ public_path("/img/escudo.png") }}" width="35">
                    </td>
                    <td style="text-align: center;line-height:4px;">
                        <p style="font-size: 6pt;">MINISTERIO DE DESARROLLO RURAL Y TIERRAS</p>
                        <p style="font-size: 5pt;">VICEMINISTERIO DE COCA Y DESARROLLO INTEGRAL</p>
                        <p style="font-size: 4pt;">DIRECCION GENERAL DE DESARROLLO INTEGRAL DE LAS</p>
                        <p style="font-size: 4pt;">REGIONES PRODUCTORAS DE COCA</p>
                    </td>
                    <td width="70" style="text-align:center;">
                    <img src="{{ public_path("/img/logo.png") }}" width="60">
                    </td>                
                </tr>
                <tr style="background: linear-gradient(70deg, black, white)">
                    <td colspan="3" style="background: linear-gradient(to right, blue, white) !important;">
                        
                    </td>
                </tr>
            </table>
        <div style="text-align:center;font-size:8pt;font-weight:bold;">CARNET DE PRODUCTOR DE COCA</div>
        <div style="text-align:center;font-size:7pt;">DEPARTAMENTO DE {{ strtoupper($carnet_productor->departamento->nombre) }} </div>
        <table width="100%" style="font-size:5pt;margin: 0 0 0 -5px;line-height:5px;">
            <tr>
                <td><img width="60" src="{{ public_path().'/'.$carnet_productor->persona->fotografia }}" alt=""/></td>
                <td width="200">
                    <table>
                        <tr>
                            <td> <strong>PROVINCIA:</strong> {{ strtoupper($carnet_productor->provincia->nombre) }} </td>
                        </tr>
                        <tr>
                            <td> <strong>MUNICIPIO:</strong> {{ strtoupper($carnet_productor->municipio->nombre) }} </td>
                        </tr>
                        <tr>
                            <td> <strong>FEDERACION:</strong> {{ strtoupper($carnet_productor->federacion->nombre) }} </td>
                        </tr>
                        <tr>
                            <td> <strong>CENTRAL:</strong> {{ strtoupper($carnet_productor->central->nombre) }} </td>
                        </tr>
                        <tr>
                            <td> <strong>COMUNIDAD:</strong> {{ strtoupper($carnet_productor->comunidad->nombre) }} </td>
                        </tr>
                        <tr>
                            <td> <strong>NOMBRE:</strong> {{ strtoupper($carnet_productor->persona->nombre.' '.$carnet_productor->persona->primer_apellido.' '.$carnet_productor->persona->segundo_apellido) }} </td>
                        </tr>
                        <tr>
                            <td> <strong>CI.:</strong> {{ $carnet_productor->persona->carnet_identidad.' '.$carnet_productor->persona->departamento_extension->sigla }} </td>    
                        </tr>
                    </table>
                </td>
                <td style="text-align: center;">
                    {!! QrCode::margin(0)->size(80)->generate(
                        strtoupper($carnet_productor->departamento->nombre).'|'.
                        strtoupper($carnet_productor->provincia->nombre).'|'.
                        strtoupper($carnet_productor->persona->nombre.' '.$carnet_productor->persona->primer_apellido.' '.$carnet_productor->persona->segundo_apellido).'|'.
                        $carnet_productor->persona->carnet_identidad.' '.$carnet_productor->persona->departamento_extension->sigla.'|'.
                        $carnet_productor->carnet
                    ); !!}
                </td>
            </tr>
            <tr style="font-size:4.5pt;font-weight:bold;">
                <td align="center"> {{ $carnet_productor->carnet }} </td>
                <td></td>
                <td align="center"> SUP.: {{ number_format($carnet_productor->superficie, 2) }} m<sup>2</sup> </td>
            </tr>
            <tr style="font-size:4.5pt;font-weight:bold;height:30px;vertical-align: bottom;">
                <td colspan="3" align="center" style="border-top-style: dotted;"> FIRMA DE PRODUCTOR </td>
            </tr>
        </table>
        <br>
    </div>
    <div class="page">
        <br>
        <table style="font-size:5pt;" width="100%">
            <tr>
                <td> FECHA EMISION: {{ $carnet_productor->fecha_emision }} </td>
                <td align="right"> FECHA CONCLUSION: {{ $carnet_productor->fecha_conclusion }} </td>
            </tr>
        </table>
    </div>
</body>
</html>