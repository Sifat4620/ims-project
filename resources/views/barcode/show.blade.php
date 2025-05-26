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
    <p>Barcode String: <strong>{{ $barcodeString }}</strong></p>
</body>
</html>
