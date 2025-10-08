<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proforma</title>
    @vite(['resources/js/pdf.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: #f5f5f5;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .page {
            width: 850px;
            background: white;
            position: relative;
            border: 3px solid #0078D4;
            border-top: 12px solid #D8A300;
            border-bottom: 5px solid #D8A300;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 25px 35px 40px 70px;
        }

        /* Lateral vertical */
        .vertical-text {
            position: absolute;
            left: -60px;
            top: 50%;
            transform: translateY(-50%) rotate(-90deg);
            font-size: 32px;
            font-weight: 900;
            color: #003366;
            letter-spacing: 2px;
            border-left: 5px solid #D8A300;
            padding-left: 10px;
        }

        /* Encabezado */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .header .info {
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .header .logo {
            width: 150px;
            object-fit: contain;
        }

        /* Encabezado de detalles */
        .details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border: 1px solid #0078D4;
            border-left: 5px solid #D8A300;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .details div {
            padding: 8px 10px;
            border-left: 1px solid #0078D4;
        }

        .details div:first-child {
            border-left: none;
        }

        .details h4 {
            font-weight: 700;
            color: #003366;
            font-size: 13px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .details p {
            margin-bottom: 2px;
        }

        /* Tabla principal */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }

        thead {
            background: #D8A300;
            color: white;
        }

        th,
        td {
            border: 1px solid #0078D4;
            padding: 8px;
            vertical-align: top;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        td {
            color: #333;
        }

        /* Totales */
        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            font-size: 13px;
            border-top: 3px solid #0078D4;
            padding-top: 8px;
        }

        .total .literal {
            font-weight: 500;
        }

        .total .amount {
            background: #D8A300;
            color: #fff;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 5px;
        }

        /* Numeración lateral */
        .folio {
            position: absolute;
            left: -65px;
            top: 40px;
            transform: rotate(-90deg);
            color: #D8A300;
            font-weight: 700;
            font-size: 18px;
            letter-spacing: 2px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .page {
                box-shadow: none;
            }

            .vertical-text,
            .folio {
                left: -50px;
            }
        }
    </style>
</head>
<body id="main">
    <div class="page">
        <div class="folio">@yield('id')</div>
        <div class="vertical-text">@yield('type')</div>

        <!-- Header -->
        <div class="header">
            <div class="info">
                <strong>Unit. Dios es Amor, No 34 - Ed. 19</strong><br>
                Tel: 2539377 — CEL: 73889159<br>
                Email: <u>solumind@gmail.com</u><br>
                <br>
                <strong>Fecha:</strong> @yield('date')
            </div>
            <img src="{{ $logo }}" class="logo" alt="Logo" style="width: 400px;border-radius: 5px;">
        </div>

        <!-- Datos -->
        <div class="details">
            @yield('info')
            <!-- <div> -->
            <!--     <h4>Facturables a:</h4> -->
            <!--     <p>Corporación Minera de Bolivia</p> -->
            <!-- </div> -->
            <!-- <div> -->
            <!--     <h4>Entrega a:</h4> -->
            <!--     <p>EMPRESA MINERA HUANUNI</p> -->
            <!-- </div> -->
            <!-- <div> -->
            <!--     <h4>Observaciones:</h4> -->
            <!--     <p>Material entregado en Almacén Enami</p> -->
            <!--     <p>CONDICIONES DE ENTREGA: CIF Oruro</p> -->
            <!--     <p>ORIGEN: CHINA</p> -->
            <!-- </div> -->
        </div>

        <!-- Tabla -->
        @yield('content')
        <!-- <table> -->
        <!--     <thead> -->
        <!--         <tr> -->
        <!--             <th>ITEM</th> -->
        <!--             <th>CANTIDAD</th> -->
        <!--             <th>UNIDAD</th> -->
        <!--             <th>DESCRIPCIÓN</th> -->
        <!--             <th>P. UNIT.</th> -->
        <!--             <th>SUBTOTAL</th> -->
        <!--         </tr> -->
        <!--     </thead> -->
        <!--     <tbody> -->
        <!--         <tr> -->
        <!--             <td>1</td> -->
        <!--             <td>35000</td> -->
        <!--             <td>Metros</td> -->
        <!--             <td> -->
        <!--                 <strong>LÍNEAS DE CAUVILLE (RIELES) de 30 [lbs/yd] de 5 [m] de largo</strong><br><br> -->
        <!--                 <strong>MARCA:</strong> SHANDONG ZEHONG STEEL<br> -->
        <!--                 <strong>PAÍS DE ORIGEN:</strong> CHINA<br><br> -->
        <!--                 <strong>DIMENSIONES:</strong><br> -->
        <!--                 • Peso: 30 [lbs/yd]<br> -->
        <!--                 • Longitud de riel: 5 [m]<br> -->
        <!--                 • Cabeza: C = 42.90 [mm]<br> -->
        <!--                 • Altura: A = 79.59 [mm]<br> -->
        <!--                 • Alma: B = 9.00 [mm]<br> -->
        <!--                 <br> -->
        <!--                 <strong>COMPOSICIÓN QUÍMICA:</strong><br> -->
        <!--                 • Carbono: C ≤ 0.57 %<br> -->
        <!--                 • Silicio: Si ≤ 0.35 %<br> -->
        <!--                 • Manganeso: Mn ≤ 0.75 %<br> -->
        <!--                 • Fósforo: P = 0.029 %<br> -->
        <!--                 • Azufre: S = 0.006 %<br> -->
        <!--             </td> -->
        <!--             <td>198.00</td> -->
        <!--             <td>6,930,000.00</td> -->
        <!--         </tr> -->
        <!--     </tbody> -->
        <!-- </table> -->

        <!-- Total -->
        <!-- <div class="total"> -->
        <!--     <div class="literal">Son: Seis millones novecientos treinta mil 00/100 Bolivianos</div> -->
        <!--     <div class="amount">6.930.000,00</div> -->
        <!-- </div> -->
        <div style="display: flex;justify-content: between;margin-left: -35px; margin-top: 120px;margin-bottom: 40px;">
            <div style="width: 50%;display: flex; justify-content: center;">
                <h5>Firma Cliente</h5>
            </div>
            <div style="width: 50%;display: flex; justify-content: center;">
                <h5>Firma Autorizada</h5>
            </div>
        </div>
    </div>
    <script>
        // document.addEventListener('DOMContentLoaded', () => {
        //     const element = document.getElementById('main');
        //     window.html2pdf().from(element).set({
        //         margin: 0,
        //         filename: "proforma.pdf",
        //         html2canvas: {
        //             scale: 1
        //         },
        //         jsPDF: {
        //             orientation: "portrait",
        //             unit: "mm",
        //             format: "letter"
        //         }
        //     }).save();
        //
        // })
    </script>
</body>
</html>
