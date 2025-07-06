<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Item Barcodes</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        .barcode-container {
            text-align: center;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            display: inline-block;
            width: 45%;
        }

        .barcode-img {
            margin-bottom: 10px;
            max-width: 100%;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Selected Item Barcodes</h2>

    @foreach ($items as $index => $item)
        <div class="barcode-container">
            <div class="barcode-img">
                @if ($item['barcode_png'])
                    <img src="{{ $item['barcode_png'] }}" alt="Barcode" />
                @else
                    <p>No barcode available</p>
                @endif
            </div>
            <div><strong>Item ID:</strong> {{ $item['item_id'] }}</div>
            <div><strong>Barcode:</strong> {{ $item['barcode_string'] }}</div>
        </div>

        @if (($index + 1) % 4 == 0)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
