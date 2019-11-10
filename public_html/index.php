<?php
header('Content-Type: text/html; charset=UTF-8');

// Obtiene el directorio actual.
$d = dir(getcwd());

// Nombre del directorio y del fichero actual.
$dact = $d->path;
$fact = substr(filter_input(INPUT_SERVER, 'PHP_SELF'), -1) == '/' ? '' : basename(filter_input(INPUT_SERVER, 'PHP_SELF'));

$aDireNo = array('nbproject');              // Directorios a evitar
$aFileNo = array($fact, '.xajax.','.inc.','.idi.','.css','.js');  // Ficheros a evitar
$aAli = array('localhost');                 // Sitios locales a incluir en los alias.
$aDir = array();
$aFil = array();

$dApa = "/etc/apache2/sites-enabled/";  // Sitios de Apache2
$pAnt = filter_input(INPUT_SERVER, 'HTTP_REFERER');

// Funcion para comprobar directorios
function evitarDirectorio( $sNombre ) {
    global $aDireNo, $aFileNo;
    $bEvi  = FALSE;
    $sFile = strtolower($sNombre);
    $fact  = strtolower($fact);
    
    if ( $sFile == '.' || $sFile == '..' ) {
        return TRUE;
    }
    
    foreach ($aFileNo as $sFileNo ) {
        if ( stripos($sFile, $sFileNo) !== FALSE ) { return TRUE; }
    }
    
    foreach ($aDireNo as $sDireNo) {
        if ( strtolower($sDireNo) == $sFile ) { return TRUE; }
    }
    return $bEvi;
}

// Contenido del directorio actual.
while (($file = $d->read()) !== false) {
    if ( !evitarDirectorio($file) ) {
        if (is_dir($file)) {
            $aDir[] = $file;
        } else {
            $aFil[] = $file;
        }
    }
}
$d->close();

// Obtiene los alias.
$dc = dir($dApa);
while (($file = $dc->read()) !== false) {
    if ( !evitarDirectorio($file) ) {
        if (substr($file, -5) == ".conf" ) { 
            $fichero = fopen($dApa.$file,'rb');
            while ( ($linea = fgets($fichero)) !== false) {
                $linea1 = trim($linea);
                if ( substr($linea1, 0, 10) == "ServerName" ) {
                    $aAli[] = trim(substr($linea1, 10)); 
                }
            }
            fclose($fichero);
	    // Quita los posibles duplicados.
	    $aAli = array_unique($aAli);
        }
    }
}
$dc->close();

$sSrvAct = filter_input(INPUT_SERVER, 'HTTP_HOST');
?>
<!DOCTYPE html>
<html> 
    <head>
        <title><?php echo $fact; ?></title>
        <style type="text/css">
            body {margin:20px;background: #EFEFFF;}
            img {vertical-align: middle;}
            div {margin-left: 20px;}
            div.tot {padding: 0px 20px 20px;border:1px solid lightgrey;border-radius: 10px;background: #FFF;box-shadow: 2px 2px 2px #888 inset; }
            div.dir {color:navy;}
            div.fic {color:green;}
            div.ali {color:purple;padding-top: 20px;}
            a {text-decoration: none;color: inherit;}
            h2 {margin-left: 20px;font-style: italic;}
            h2, h3 {color:darkgrey;}
        </style>
    </head>
    <body>
        <h1>
        <?php 
        if ( $pAnt ) {
            echo "<a href=\"$pAnt\" title=\"$pAnt\"><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABHNCSVQICAgIfAhkiAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAANNSURBVDiNtZVNaFxVGIaf75xz782fUvsjGI00BY2IoILGmJks2igRqcH6H4pdSOmmK2sToS6iiBsRhIouTDetYnVRjSJCLbYbFSKdKiiIQZRY04ihNmnoZGbuufdzMXeSMaZjXPTAyzmLj+d7v5d77hFV5Uosc0WogPs/xb37gwERDoRtvv/UqPpGtWt23DcS7r1+U+eHRmzfWgz9J1heEpMbdm/e3HH7q3sGD7RY49K1GGnYeWBYWnPixrtv3Zq/r/vRpsCFa2E2Buefl3bBneq/e0fnnV33Bt9Of07P5kGMo1JecIu5/cE/6sUwG7T69lr2q4JzI+Ed1jSdGNy285rrNt1gJ6Y+BZQkTXhh11vNRgyCILKc5OjY7vXgHbA6uPc5t721ue3ow1ufbjORMjH1CUYs1gR8MXkYIw4jBmssgkFEyG95vHEUueFg37qrN7y8o39Xy/nFKX4+ewZrApwJSDUlTROscRix+NRgMsdJGqMosho4P+IOtm/seGYg/1jL5PlvmJ77aRmoCYGkpLZCRTyJxqDgCInsVXiNWXmDlx0b2jGIkhD7EmVfJBaLotgQwGNQjBXEVL1pqmgJiumFf0WxlP5XzckT5/78/dBHJ9+9dNOGbrquvYeyL5KaEtgKLoKw2RK1WJpaLVFr9Rw0G1KpXB6so5p+/Zrf99fsheFjxw8X14cd9HQ+RBAE2MDgQkMQmSp8CWpxkcFYQYGFc8u8pShEJACiiTf8B7c9VZwZT947cn/v9ra+zielMPsxaio8uOVZnHPYwKCqpF5JfHVHYOYMLSISq2rsMqgDwkzRD+8n392Y55Hjfvyd3F3bNuZv2elOzx5DU3j90CvlcjGOVo4uMDd/FgOEIpLUHNtMrqbfvkz+mP+VocSfPDh/cb4r1zMUaiLEZQ0KY/6B8kUWgHKmUrbXWK7hT2h+moXC28neQuH0yc9OjJcXi6VG5fUrrTlOMvk69xVAfAktjKUvVoZ+mbxUPLKHNI3EUMxcxlmdX6FqFKrqRSSLqtqxrpED3PdHk7HK4MyP6zbL7iRmLhvd18FjYBEoqapK/Y2RKj0Aomx3dRNIJs1UP2UZKKsuvypyucdURCzV79zWqTZNbaIESHQVyN9x5li6vCTOrQAAAABJRU5ErkJggg==\"/></a>&nbsp;";
        }
        echo $fact;
        ?>
        <div style="float:right"><?php echo "<a href=\"http://$sSrvAct\">$sSrvAct</a>"; ?></div>
        </h1>
        <h2><?php echo $dact; ?></h2>
        <div class="tot">  
        <?php
        if ( count($aDir) > 0 || count($aFil) > 0 ) {
            // Recorre los datos de los directorios y ficheros.
            if ( count($aDir) > 0 ) {
                sort($aDir);
                echo "<h3>Directorios</h3><div class=\"dir\">";
                foreach ($aDir as $valor) {
                    echo "<a href=\"$valor\"><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QAAAAAAAD5Q7t/AAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QsKFAImbV4amgAAAjVJREFUOMvVlc1rE0EYh5+Z3dSmGJFaBCvovaYnBVGQ+md4VSkSRKIXL36A4ElRT5LWIgiKWMWzN/UgCIpamyJpe/CgttHatE2aZJPNzushu5tNpId+eHDgNzuz8/LMb96dmYX/rSiA8xfS1zzTuNo5WK/XATCeOTF27/74usHn0mfl9MnhNYMyo3cpFAqHno0//7Bu8JHDR8nn82sGfvr8Edd1EZFQALt6+y6OZEZvdsbbQWNg4ABaW2uC+/v3tvW11liWxZOnj2+kUqmRTCZT+svxmdSwDB07zssZYeLH5j5azF28/vZB+kroOJkc5ParSS6dGsJxvQ2Dbz18cxlogaemsgAslessFGsbgu7r62mlCsBxHJLJwU3v3WrdawdHHW9V0QCVSmVrHFerALNb7nhlZQXdKK62gQPH8a7WXhbwDwMYEYwBYwRjBM9XI6KKU0OLNxMcEBV13PAMtYYBCcCgEET5HQRBNQeVgCgEYXu3za+lJVd7lTKgQ3AyOcjB3Du0pam5pslQhBN0gvDrYGm9Cc3P30WlnYXXgG0DMYDslxw7du4mv1zD8R0HvBAerYVw5kTcpuYaFpZLnr34HqDLBnoAuvcMsD9hkZsvowATTXTY9tPQtI/QNLAtZvg+Oy2lUrn0LTezCsRtoDo5mR3++mhibLN3++r0izvzc/NzwVosoBtIAHG/3eXvGNXKdNvFFbwTwPNV91UFiioCt33FIlA6wKrjafwxAzQiMv/sn/cH5SsZQC/sNk8AAAAASUVORK5CYII=\"/> $valor</a><br />";
                }
                echo "</div>";
            }
            if ( count($aFil) > 0 ) {
                sort($aFil);
                echo "<h3>Archivos</h3><div class=\"fic\">";
                foreach ($aFil as $valor) {
                    echo "<a href=\"$valor\"><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QYYDQg4fMkMKQAAA9ZJREFUOMullMtPVFccxz/nzr1zKY+CDDPDMAqW0LQpFAYCPqhNarpogsZVu7FtGtNd/4GmRm3SFkNtt124c2HaheljUWNi0oW1KKiEh44QEFCeAxfmAfO8M/eeLpR0xhm1id/kJPd3Tu7n9z2/3zlHAAyc6/8GOM1LSEr57VdfnjpTMDlwrl++rAbO9ct8ppofzM/P43A4kFIihEAIAVDwvRPnOcXv9xftoACcDwgGg0WQpxUIBJBSllx7JritrQ1V/W/ZsqyCuJT7F4KFEExMTLywYZ2dnSiK8v/AiqIgpaSjowMATdMAGBxfYHB8ASOWJmvZaA7BzbkR9rX6OerxPB98e+QWB/f3IoRgbGwMgHA8x7XgFo27vXzwbhuv+V2U6zqReIoHiwa3g4sMBa+gWloBuGAf+3sO4PP5EELQ1dWFy9fMX8E4Rw938mlfgLqaMuZmJpl6uMJaAqrK4OO+AG821zO37eH4yYvtzyxFKBRCCMH09DSXBkMce6+dt5trCW+EqGtspbrORzKdJbQ4h2qbKFaK93uasWzJ1RuT54GDRY7zm/co7KDOVcPhnhY0xSanVoIi0FQVp6ZSrqt4XVX4PG7cNRX0tO6mqaH2wPGTF08UgfOPz9TCJt2tjUQjG6xlyvE07EVKsCUgFIRtshpO8Me1e1y9PkK1LnmrpQHgo6JSDA3f5J3eQ49LEk7SVL+LWzPrNL/uwZawc2Lj0TD7Aq0IIZBSsry6RjqVwueuBugucnzwQC8ejwchBGkzh647USo9RONpjG2TZMZiO2lixo2C6+51u5iYmkXXVIDaks0zjMc/ORwKsUSaivQKlarOeqKODc1JzrJJh3MEslkyGZOqygqSySR7/W7WUyZAuAicr9qqMuaXNuhsqad5j49Hi8tcf7CFiZOUWcGPv46jC4svjrWzvb3N6P1ZqGoCuPPc5jVUOxidWsK0H+du2uPnSKeb1fUwm7EEsYzgUcTGMAxisRhlu/xMzYUALpUE7+jzDw+xGYlz4+4Cc8sG6XSa2+OThONZjEiCzWiSza0UsViMtViGSEKyFAoP/3z2kwuAKCiF1+stgJ840sVPvw1j29D1hpfL4zEiphMzZ5Exc+iaYCEqebieZXh0msWpO6eBciCnAti2/d33P5w9Vcq52xaM3pXMLhh0tdRTX/cquq6RTGdZNaL8PbaEsbZKdGbwl39+v7AGVAKZpx9TBdCAMuCVJ9krhHC4+j4783WVq6FbcZZVgBC2bVlmYstYeXjvytCf5y8DG8AyECkFLqWdZHpeMueTeQFIIAUknwwTsP8F8YvP0vXhP+UAAAAASUVORK5CYII=\"/> $valor</a><br />";
                }
                echo "</div>";
            }
        } else {
            echo "<p>El directorio está vacío.</p>";
        }
        ?>
        </div>
        
        <?php
        if ( count($aAli) > 0 ) {
            sort($aAli);
            echo "<h2>Otros sitios web locales</h2><div class=\"tot ali\">";
            foreach ($aAli as $valor) {
                if($valor != $sSrvAct) {
                    echo "<a href=\"http://$valor\"><img src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH1QQaByshe1XNtQAABLRJREFUOMu1lVlsVGUUx393maUt7XSbaelCp3ZKG0rLKpSICwISooaQ+IAEiInxVX2RRB58MuXFGIn64Jsii5qIRCNoRVQWCUpDFJmWpbbo0OlMt5l779y5c2fu/XzooBSCxAe/5J+cLyffLyfnOwv8T0e6l2P7ngMRYBewQQjRBZQDmiRJUeAEsP9Q347r/wn87Ksf7vOoyosrFi2gPRwkWFWOz6OSsXKMT+oM/zHJr1djFBx336G+HS/fF7x9z4E613H6l3e19DyxppO84+J1dLo62jjWfxJ/4xJSRpYSxaXUp3B24BqDw/FzkiRtPdS3I3E7S779IgrOmXWrO3o29UZIaynS0xMYpsWX3/9MfWQZimsT8Mt0hmQqJIMNq9pYsTi8Rgjx2Z0RK7eMbbvff29ZV3jjUw93ouk6a5a0k5hK09y2iNrQfGRZxcrqSNkZTDNL18JWQgE/pSU+Zoxcc2P3pqpLp498NQe8fc+BiMejfrBt8wo6F9TSVFdN/3dnqG9uw+v3oyoyjhDIqo9w0EdbSyOlpSXIsoxH5MBXxuBwvHfx2q0HL50+Mg2gAuRN65WVqzqINFYhyxJHv/6B5b2PIUkgBAhAlmSsqRucuRlH9ldC3uCRBxejqgpNlTLt4TqGhuO7gNf+zrHX73m6rakWyzQ4euxbWrsfQgCuKIIFOK5AVb088+TjrO1poba6ivOXbyBJEqprMT9YCbDhVipUAFlVQrWVZfRHDSpDnWRyDn6vik+VEBI4jouppVjZHUEIQShYQyhYQ2xsnOi1UWI34wRCCxFCLJpTFY6L4vGqVDhJbOHFMHMk01mmDRvDtNHMHKY2iRBizs/XVAWIJaapr6nAoyoAFXMilqBgZPOqbuaoUMYIqB5ik1Ba3TCbBschpwkcx0VR/qlQy7Jobw7y6Tc/sWx1CATGHLCTL8wkp7TgeKGG3pZyIuEmFqbSfH5+FKUsSMFxydl+3vroFNm8y/qeOno6wpimiUeRuT4hCGsmSETnpMLOWmdHYpPM80Ek3ARAVWWAnRt78BujzGgmKT2LrlSjiQC/XB9H13UMw+DkjxdpbA4TT6aQJKl/Dti1869Hh+OEa/ycOH0B13VnnbJEwc4yo2WZ0bOkdYt0xiI2bZNOp9F1HdO0WN3dwsifSYD9cxpkaOB4vH3ppkfzLq1yWQ0FPUFZiY98Pk//ucskc34yVp5M1sbI5vC7GpGQj1Q6TVqdz++xKVIp453De3cevKulB89/8XF9x7qXKsr9Pq/PR6mS58jxU1ycrsLMFTAtm0x2VkmtQE+9S8IqIZl2uHJ1ZPiTN55/AdDuAgPVdiZzgdKGLbZQPFJJgCtjGRKGQM/YmFYey7JQhU1rsJRgQwvR0Wmi0WvJs0ff3p1JT2iADhTuHJshoBFo3Pxc35u1TeH2BxaEqA9WUlFegkdVydkFUnqGxESK0dgEY8O/DZw8vPdd4GZRQ4BzJ1gBmoF6oL6lo3d129L1WwLBhlZ/2TwvsiwLx3GtjJ6diY+ODA0c/ypxIzoIJIAx4Apg/tsGqQQagJqiPQ8oATzMzqN8EaABU8AIMFn03X/nFZvHV5S/CC4ANmAVlb/X478AYzEct5VYdT4AAAAASUVORK5CYII=\"/> $valor</a><br />";
                }
            }
            echo "</div>";
        }
        ?>
    </body>
</html>
