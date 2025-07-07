<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Item Barcodes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 20px;
            margin: 0;
            font-size: 12px;
        }

        .barcode-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .barcode-container {
            width: 48%;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .barcode-img img {
            max-width: 100%;
            height: auto;
        }

        .barcode-code {
            margin-top: 8px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="barcode-grid">
        @foreach ($items as $item)
            <div class="barcode-container">
                @if ($item['barcode_png'])
                    <div class="barcode-img">
                        <img src="{{ $item['barcode_png'] }}" alt="Barcode for {{ $item['barcode_string'] }}">
                    </div>
                    <div class="barcode-code">{{ $item['barcode_string'] }}</div>
                @else
                    <p>No barcode available</p>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
