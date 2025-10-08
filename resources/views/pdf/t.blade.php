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
            width: 500px;
            position: absolute;
            left: -203px;
            top: 430px;
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
            width: 180px;
            position: absolute;
            left: -38px;
            top: 210px;
            transform: rotate(-90deg);
            color: #D8A300;
            font-weight: 700;
            font-size: 20px;
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

        body,
        .page {
            background: #ffffff !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    </style>
</head>
<body id="main">
    <div class="page" id="page" style="height: 25cm;">
        <div class="folio">@yield('id')</div>
        <div class="vertical-text">@yield('type')</div>

        <!-- Header -->
        <div class="header">
            <div class="info">
                <strong>Dirección: Urb. Dios es Amor, M-c16- 10</strong><br>
                Telf. 25265721 Cel. 70415397 - 73819319<br>
                Email: <u>solumind,or @gmail.com</u><br>
                <br>
                <strong>Fecha Emisión:</strong> {{ now()->format('d/m/Y H:i') }}<br>
                @yield('extra')
            </div>
            <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png'))) }}"
                class="logo" alt="Logo" style="width: 400px;border-radius: 5px;">
        </div>

        <!-- Datos -->
        <div class="details">
            @yield('info')
        </div>

        <!-- Tabla -->
        @yield('content')
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
        document.addEventListener('DOMContentLoaded', () => {
            const element = document.getElementById('main');
            window.html2pdf().from(element).set({
                margin: 0,
                filename: "{{ Str::uuid() }}",
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    orientation: "portrait",
                    unit: "mm",
                    format: "letter"
                }
            }).outputPdf('blob').then((pdfBlob) => {
                const pdfURL = URL.createObjectURL(pdfBlob);
                document.getElementById('page').style.visibility = 'hidden';
                let tab = window.open(pdfURL, '_blank');
                setTimeout(() => {
                    if (tab == null) {
                        window.Swal.fire({
                            icon: 'warning',
                            title: 'Sin redirección?',
                            html: 'por favor habilite la <a href="https://support.google.com/chrome/answer/95472">redirección</a> para este sitio'
                        }).then((result) => {
                            if (result.isConfirmed) window.location.reload()
                        });
                    } else {
                        window.close();
                    }
                }, 1000);
            });
        })
    </script>
</body>
</html>
