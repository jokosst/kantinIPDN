<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $item->nama }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 16px;
            color: #111;
        }

        .no-print {
            margin-bottom: 12px;
        }

        .btn {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            border: 1px solid #ccc;
            background: #f5f5f5;
            color: #111;
            margin-right: 8px;
            cursor: pointer;
        }

        .barcode-sheet {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 33mm);
            gap: 3mm;
            align-items: start;
            /* margin-left:-10px; */
        }

        .barcode-row {
            border: 1px dashed #ccc;
            width: 33mm;
            height: 25mm;
            padding: 1.5mm;
            text-align: center;
            page-break-inside: avoid;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .item-name {
            font-size: 7px;
            font-weight: 900;
            margin-bottom: 1mm;
            line-height: 1.1;
            margin-top: 0px;
        }

        .item-code {
            margin-top: 4px;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .item-price {
            margin-top: 1mm;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.1;
        }

        .barcode-img {
            max-width: 100%;
            height: 9mm;
            object-fit: contain;
        }

        @media print {
            @page {
                margin: 5mm;
            }

            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }

            /* .barcode-sheet {
                width: fit-content;
                margin: 0 auto;
            } */

            .barcode-row {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <a href="{{ url('master/item') }}" class="btn">Kembali</a>
        <button type="button" class="btn" onclick="window.print()">Cetak</button>
    </div>

    <div class="barcode-sheet">
        @for ($i = 1; $i <= 3; $i++)
            <div class="barcode-row">
                <div class="item-name">{{ $item->nama }}</div>
                <img src="{{ $barcodeBase64 }}" alt="Barcode {{ $item->kode }}" class="barcode-img">
                <!-- <div class="item-code">{{ $item->kode }}</div> -->
                <div class="item-price">Rp {{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}</div>
            </div>
        @endfor
    </div>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
