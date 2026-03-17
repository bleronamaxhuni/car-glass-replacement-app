<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quote Summary - Car Glass Replacement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #020617;
            color: #e5e7eb;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            background: radial-gradient(circle at top left, #1f2937, #020617);
            border-radius: 1.5rem;
            padding: 2rem;
            max-width: 720px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(15,23,42,0.9);
        }
        h1 {
            margin: 0 0 0.75rem;
            font-size: 1.7rem;
        }
        p {
            margin: 0 0 1.5rem;
            color: #9ca3af;
        }
        .section {
            margin-bottom: 1.25rem;
        }
        .label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }
        .value {
            font-size: 0.98rem;
            font-weight: 500;
            margin-top: 0.15rem;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }
        .price-box {
            margin-top: 1.5rem;
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #ecfdf5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .price-main {
            font-size: 1.6rem;
            font-weight: 700;
        }
        .price-sub {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        .footer {
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #9ca3af;
        }
        a {
            color: #a5b4fc;
        }
    </style>
</head>
<body>
<div class="card">
    <h1>Your quote is ready</h1>
    <p>Here is a summary of your car glass replacement request. You can save this quote ID for future reference.</p>

    <div class="grid section">
        <div>
            <div class="label">Quote ID</div>
            <div class="value">#{{ $quote->id }}</div>
        </div>
        <div>
            <div class="label">Requested at</div>
            <div class="value">{{ $quote->requested_at ?? $quote->created_at }}</div>
        </div>
    </div>

    <div class="grid section">
        <div>
            <div class="label">Car</div>
            <div class="value">
                {{ $quote->car->make }} {{ $quote->car->model }} {{ $quote->car->year }}
                ({{ $quote->car->carBodyType->name }})
            </div>
        </div>
        <div>
            <div class="label">Glass</div>
            <div class="value">{{ $quote->glassType->name }}</div>
        </div>
    </div>

    <div class="grid section">
        <div>
            <div class="label">Vendor</div>
            <div class="value">{{ $quote->vendorGlassPrice->vendor->name }}</div>
        </div>
        <div>
            <div class="label">Warranty</div>
            <div class="value">{{ $quote->vendorGlassPrice->warranty_time }} months</div>
        </div>
        <div>
            <div class="label">Estimated delivery</div>
            <div class="value">{{ $quote->vendorGlassPrice->delivery_time }} days</div>
        </div>
    </div>

    <div class="price-box">
        <div>
            <div class="price-main">{{ number_format($quote->final_price, 2) }} €</div>
            <div class="price-sub">Estimated total for parts and service</div>
        </div>
        <div class="price-sub">
            Prices are estimates and may vary slightly based on inspection.
        </div>
    </div>

    <div class="footer">
        Want to start another quote? <a href="{{ route('quotes.create') }}">Back to car selection</a>.
    </div>
</div>
</body>
</html>

