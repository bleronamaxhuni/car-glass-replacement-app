<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Vendor - Car Glass Replacement</title>
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
            background: radial-gradient(circle at top left, #1e293b, #020617);
            border-radius: 1.5rem;
            padding: 2rem;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(15,23,42,0.9);
        }
        h1 {
            margin: 0 0 0.75rem;
            font-size: 1.6rem;
        }
        p {
            margin: 0 0 1.5rem;
            color: #9ca3af;
        }
        .summary {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 1rem;
            background: rgba(15,23,42,0.85);
            border: 1px solid rgba(148,163,184,0.4);
        }
        .summary-item {
            min-width: 140px;
        }
        .summary-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }
        .summary-value {
            font-size: 0.95rem;
            font-weight: 500;
            margin-top: 0.15rem;
        }
        .options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
        .option {
            border-radius: 1rem;
            padding: 1rem;
            background: linear-gradient(145deg, rgba(30,64,175,0.9), rgba(30,64,175,0.3));
            border: 1px solid rgba(129,140,248,0.35);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .option-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 0.5rem;
        }
        .vendor-name {
            font-weight: 600;
        }
        .price {
            font-size: 1.15rem;
            font-weight: 700;
        }
        .meta {
            font-size: 0.85rem;
            color: #e5e7eb;
            margin-bottom: 0.75rem;
        }
        .meta span {
            display: inline-block;
            margin-right: 0.75rem;
        }
        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.25rem;
        }
        button {
            border: none;
            border-radius: 9999px;
            padding: 0.45rem 1.25rem;
            background: linear-gradient(to right, #22c55e, #16a34a);
            color: white;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
        }
        form {
            margin: 0;
        }
    </style>
</head>
<body>
<div class="card">
    <h1>Select a vendor</h1>
    <p>We found several options for replacing your <strong>{{ $glassType->name }}</strong>. Choose the one that best matches your priorities.</p>

    <div class="summary">
        <div class="summary-item">
            <div class="summary-label">Car</div>
            <div class="summary-value">{{ $car->make }} {{ $car->model }} {{ $car->year }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Body type</div>
            <div class="summary-value">{{ $car->carBodyType->name }}</div>
        </div>
        <div class="summary-item">
            <div class="summary-label">Glass</div>
            <div class="summary-value">{{ $glassType->name }}</div>
        </div>
    </div>

    <div class="options">
        @foreach($vendorOptions as $option)
            <div class="option">
                <div>
                    <div class="option-header">
                        <div class="vendor-name">{{ $option->vendor->name }}</div>
                        <div class="price">{{ number_format($option->price, 2) }} €</div>
                    </div>
                    <div class="meta">
                        <span>Warranty: {{ $option->warranty_time }} months</span>
                        <span>Delivery: {{ $option->delivery_time }} days</span>
                    </div>
                </div>
                <div class="actions">
                    <form method="POST" action="{{ route('quotes.store') }}">
                        @csrf
                        <input type="hidden" name="car_id" value="{{ $car->id }}">
                        <input type="hidden" name="glass_type_id" value="{{ $glassType->id }}">
                        <input type="hidden" name="vendor_glass_price_id" value="{{ $option->id }}">
                        <button type="submit">Use this vendor</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>

