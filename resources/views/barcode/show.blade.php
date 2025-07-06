<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Barcode Display</title>
</head>
<body>
    <h1>Barcode for Item #{{ $item->id }}</h1>

    <h3>Generated Barcode (Random Serial):</h3>
    <div>{!! $generatedBarcodeSVG !!}</div>
    <p><strong>Barcode String:</strong> {{ $generatedBarcodeString }}</p>

    <hr>

    <h3>Original Barcode (Original Serial):</h3>
    <div>{!! $originalBarcodeSVG !!}</div>
    <p><strong>Barcode String:</strong> {{ $originalBarcodeString }}</p>
</body>
</html>
