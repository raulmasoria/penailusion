<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:w="urn:schemas-microsoft-com:office:word" xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
    xmlns="http://www.w3.org/TR/REC-html40">
<head>

    <style>

        .text-cabecera{
            font-size:9.0pt;
            font-family:Tahoma,sans-serif;
        }
    </style>
</head>

<body lang="ES" link="blue" vlink="purple">
    <div class="WordSection1">
        <table cellspacing="0" cellpadding="0" width="714" style="width:80%;margin-left:14.15pt">
            <tr>
                <td valign="top" style="width:25%">
                    <p>
                        <span>
                            <img width="193" height="193" id="_x0000_i1025" src="{{ $message->embed($pathToImage) }}">
                        </span>
                    </p>
                </td>
                <td valign="top" style="width:25%">
                    <p>
                        <span class="text-cabecera" style="font-weight:bold;">Asociación Cultural Peña Ilusión</span>
                    </p>
                    <p>
                        <span class="text-cabecera">C/ Postas, 11 Bajo 4 - 42004 SORIA</span>
                    </p>
                    <p>
                        <span class="text-cabecera">E-mail: <a href="mailto:soriailusion@gmail.com">soriailusion@gmail.com</a></span>
                    </p>
                    <p>
                        <span class="text-cabecera">Sitio web: <a href="http://www.soriailusion.com/">www.soriailusion.com</a></span>
                    </p>
                    <p>
                        <span class="text-cabecera">Instagram: <a href="https://www.instagram.com/ilusionsoria">@ilusionsoria</a></span>
                    </p>
                    
                </td>
                <td valign="top" style="width:25%">
                    <p  style="text-align:center">
                        <span class="text-cabecera" style="font-weight:bold;">{{ $user->name ." ". $user->lastname }}</span>
                    </p>
                    <p  style="text-align:center">
                        <span class="text-cabecera">Dirección: {{ $adress->via ." ". $adress->direccion ." ". $adress->piso}}</span>
                    </p>
                    <p  style="text-align:center">
                        <span class="text-cabecera">{{ $adress->cp ." ". $adress->ciudad ." (". $adress->provincia.")" }}</span>
                    </p>
                    <p  style="text-align:center">
                        <span class="text-cabecera">Tel: {{  $user->phone }}</span>
                    </p>
                    <p  style="text-align:center">
                        <span class="text-cabecera">NIF: {{ $user->nif }}</span>
                    </p>
                    <p  style="text-align:center">
                        <span class="text-cabecera">E-mail: {{ $user->email }}</span>
                    </p>                                       
                </td>
                <td valign="top" style="width:25%">
                    <p  style="text-align:center">
                        <span class="text-cabecera" style="font-weight:bold;">¿Son correctos y están completos tus datos?</span>
                    </p>
                    <p  style="text-align:center">
                        <span class="text-cabecera">POR FAVOR, Revisarlos y para actualizarlos contáctanos de la manera que te resulte más cómoda.</span>
                    </p>
                </td>    
            </tr>
        </table>
        
        <p>Estimado/a socio/a:</p>
        <p>Nos complace comunicarle que su cuota para el año <strong>{{$year}}</strong> ha sido pagada con éxito.</p>
        <p>Desde este preciso momento entras a formar parte de la familia naranja, es decir, de la Peña Ilusión. Gracias de corazón por hacernos grandes, esperamos estar a la altura de lo que te ha hecho llegar hasta aquí.</p>
        <p>Si te ha llegado este mensaje por error ponte en contacto con nosotros en la mayor brevedad posible, otro integrante lo estará esperando!<p>
        <p>Sin más, recibe un cordial saludo,</p>
        <p>Junta Directiva Peña Ilusión</p>
            

        
    </div>
</body>

</html>