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
        <table style="background: green;width:360px; margin: 0 0 0 -8px;color: #fff;font-weight:bold;">
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
        <div style="text-align:center;font-size:8pt;font-weight:bold;">CARNET DE COMERCIALIZADOR AL DETALLE</div>
        <table width="100%" style="font-size:5pt;margin: 0 0 0 -5px;line-height:5px;">
            <tr>
                <td><img width="60" src="{{ public_path().'/'.$carnet_comercializador->persona->fotografia }}" alt=""/></td>
                <td width="200">
                    <table>                        
                        <tr>
                            <td> <strong>CI.:</strong> {{ $carnet_comercializador->persona->carnet_identidad.' '.$carnet_comercializador->persona->departamento_extension->sigla }} </td>    
                        </tr>
                        <tr>
                            <td> <strong>NOMBRE:</strong> {{ strtoupper($carnet_comercializador->persona->nombre.' '.$carnet_comercializador->persona->primer_apellido.' '.$carnet_comercializador->persona->segundo_apellido) }} </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: center;">
                    {!! QrCode::margin(0)->size(80)->generate(
                        strtoupper($carnet_comercializador->persona->nombre.' '.$carnet_comercializador->persona->primer_apellido.' '.$carnet_comercializador->persona->segundo_apellido).'|'.
                        $carnet_comercializador->persona->carnet_identidad.' '.$carnet_comercializador->persona->departamento_extension->sigla.'|'.
                        $carnet_comercializador->fecha_emision.'-'.$carnet_comercializador->fecha_conclusion
                    ); !!}
                </td>
            </tr>
            <tr style="font-size:4.5pt;font-weight:bold;">
                <td align="center"> </td>
                <td></td>
                <td align="center"> </td>
            </tr>
            <tr style="font-size:4.5pt;font-weight:bold;height:30px;vertical-align: bottom;">
                <td colspan="3" align="center" style="border-top-style: dotted;"> </td>
            </tr>
        </table>
        <br>
    </div>
    <div class="page">
        <br>
        <table style="font-size:5pt;" width="100%">
            <tr>
                <td> FECHA EMISION: {{ $carnet_comercializador->fecha_emision }} </td>
                <td align="right"> FECHA CONCLUSION: {{ $carnet_comercializador->fecha_conclusion }} </td>
            </tr>
        </table>
    </div>
</body>
</html>