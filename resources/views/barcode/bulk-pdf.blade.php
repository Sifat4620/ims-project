<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Item Barcodes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            padding: 20px;
            margin: 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
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
            box-sizing: border-box;
            text-align: center;
            page-break-inside: avoid;
        }

        .barcode-img {
            margin-bottom: 10px;
        }

        .barcode-img img {
            max-width: 100%;
            height: auto;
        }

        .item-info {
            margin-top: 5px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h2>Selected Item Barcodes</h2>

    @foreach ($items->chunk(4) as $chunk)
        <div class="barcode-grid">
            @foreach ($chunk as $item)
                <div class="barcode-container">
                    <div class="barcode-img">
                        @if ($item['barcode_png'])
                            <img src="{{ $item['barcode_png'] }}" alt="Barcode for {{ $item['barcode_string'] }}">
                        @else
                            <p>No barcode available</p>
                        @endif
                    </div>
                    {{-- <div class="item-info"><strong>Item ID:</strong> {{ $item['item_id'] }}</div> --}}
                    <div class="item-info"><strong>Barcode:</strong> {{ $item['barcode_string'] }}</div>
                </div>
            @endforeach
        </div>
        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
