<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Barcode Display</title>
</head>
<body>
    <h1>Barcode for Item #{{ $itemId }}</h1>

    <div>
        {!! $barcodeSVG !!}
    </div>

    <p><strong>Generated Barcode String:</strong> {{ $barcodeString }}</p>
    <p><strong>Original Barcode String:</strong> {{ $originalBarcodeString }}</p>
</body>
</html>
