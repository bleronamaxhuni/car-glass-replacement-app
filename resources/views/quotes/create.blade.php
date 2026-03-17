<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Quote - Car Glass Replacement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #0f172a;
            color: #e5e7eb;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: radial-gradient(circle at top left, #1d4ed8, #020617);
            border-radius: 1.5rem;
            padding: 2rem;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(15,23,42,0.9);
        }
        h1 {
            margin: 0 0 0.5rem;
            font-size: 1.75rem;
        }
        p {
            margin: 0 0 1.5rem;
            color: #9ca3af;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        label {
            display: block;
            font-size: 0.875rem;
            margin-bottom: 0.35rem;
            color: #cbd5f5;
        }
        select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(148,163,184,0.4);
            background: rgba(15,23,42,0.9);
            color: #e5e7eb;
        }
        select:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .actions {
            margin-top: 1.5rem;
            display: flex;
            justify-content: flex-end;
        }
        button {
            border: none;
            border-radius: 9999px;
            padding: 0.6rem 1.5rem;
            background: linear-gradient(to right, #6366f1, #ec4899);
            color: white;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 10px 25px -10px rgba(79,70,229,0.8);
        }
        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            box-shadow: none;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            background: rgba(15,23,42,0.75);
            border: 1px solid rgba(148,163,184,0.35);
            font-size: 0.75rem;
            color: #93c5fd;
            margin-bottom: 1rem;
        }
        .status {
            font-size: 0.85rem;
            color: #9ca3af;
            margin-top: 0.5rem;
        }
        .status span {
            color: #a5b4fc;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="badge">
        <span>●</span>
        Guided quote flow
    </div>
    <h1>Car glass replacement quote</h1>
    <p>Select your car details and which glass needs replacement. In the next step, you’ll see vendor options and prices.</p>

    <form method="POST" action="{{ route('quotes.vendor-options') }}">
        @csrf
        @if($errors->any())
            <div class="errors" style="margin-bottom:1rem;padding:0.75rem;border-radius:0.75rem;background:rgba(239,68,68,0.2);border:1px solid rgba(239,68,68,0.5);color:#fca5a5;">
                <ul style="margin:0;padding-left:1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid">
            <div>
                <label for="make">Make</label>
                <select id="make" name="make">
                    <option value="">Select make…</option>
                </select>
            </div>
            <div>
                <label for="model">Model</label>
                <select id="model" name="model" disabled>
                    <option value="">Select model…</option>
                </select>
            </div>
            <div>
                <label for="year">Year</label>
                <select id="year" name="year" disabled>
                    <option value="">Select year…</option>
                </select>
            </div>
            <div>
                <label for="body_type">Body type</label>
                <select id="body_type" name="body_type" disabled>
                    <option value="">Select body type…</option>
                </select>
            </div>
        </div>

        <div class="grid" style="margin-top:1.5rem;">
            <div>
                <label for="glass_type">Glass to replace</label>
                <select id="glass_type" name="glass_type_id">
                    <option value="">Select glass…</option>
                    @foreach(\App\Models\GlassType::all() as $glassType)
                        <option value="{{ $glassType->id }}">{{ $glassType->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="status" id="status">Waiting for your selection…</div>

        <div class="actions">
            <button type="submit" id="next_btn" disabled>
                View vendor options →
            </button>
        </div>
    </form>
</div>

<script>
    const apiBase = '{{ url('/api') }}';

    const makeSelect = document.getElementById('make');
    const modelSelect = document.getElementById('model');
    const yearSelect = document.getElementById('year');
    const bodyTypeSelect = document.getElementById('body_type');
    const glassTypeSelect = document.getElementById('glass_type');
    const statusEl = document.getElementById('status');
    const nextBtn = document.getElementById('next_btn');

    function setStatus(message) {
        statusEl.innerHTML = message;
    }

    function toggleNextButton() {
        const ready =
            makeSelect.value &&
            modelSelect.value &&
            yearSelect.value &&
            bodyTypeSelect.value &&
            glassTypeSelect.value;
        nextBtn.disabled = !ready;
    }

    async function fetchJson(url) {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Request failed with status ' + response.status);
        }
        return await response.json();
    }

    async function loadMakes() {
        setStatus('Loading makes…');
        try {
            const { data } = await fetchJson(`${apiBase}/car/makes`);
            makeSelect.innerHTML = '<option value=\"\">Select make…</option>';
            data.forEach(make => {
                const opt = document.createElement('option');
                opt.value = make;
                opt.textContent = make;
                makeSelect.appendChild(opt);
            });
            setStatus('Select your car make to continue.');
        } catch (e) {
            setStatus('<span>Could not load car data. Please try again later.</span>');
        }
    }

    makeSelect.addEventListener('change', async () => {
        modelSelect.innerHTML = '<option value=\"\">Select model…</option>';
        yearSelect.innerHTML = '<option value=\"\">Select year…</option>';
        bodyTypeSelect.innerHTML = '<option value=\"\">Select body type…</option>';
        modelSelect.disabled = true;
        yearSelect.disabled = true;
        bodyTypeSelect.disabled = true;

        const make = makeSelect.value;
        if (!make) {
            toggleNextButton();
            return;
        }

        setStatus('Loading models…');

        try {
            const { data } = await fetchJson(`${apiBase}/car/models?make=${encodeURIComponent(make)}`);
            data.forEach(model => {
                const opt = document.createElement('option');
                opt.value = model;
                opt.textContent = model;
                modelSelect.appendChild(opt);
            });
            modelSelect.disabled = false;
            setStatus('Now choose the model.');
        } catch (e) {
            setStatus('<span>Could not load models for this make.</span>');
        }

        toggleNextButton();
    });

    modelSelect.addEventListener('change', async () => {
        yearSelect.innerHTML = '<option value=\"\">Select year…</option>';
        bodyTypeSelect.innerHTML = '<option value=\"\">Select body type…</option>';
        yearSelect.disabled = true;
        bodyTypeSelect.disabled = true;

        const make = makeSelect.value;
        const model = modelSelect.value;
        if (!make || !model) {
            toggleNextButton();
            return;
        }

        setStatus('Loading years…');

        try {
            const { data } = await fetchJson(`${apiBase}/car/years?make=${encodeURIComponent(make)}&model=${encodeURIComponent(model)}`);
            data.forEach(year => {
                const opt = document.createElement('option');
                opt.value = year;
                opt.textContent = year;
                yearSelect.appendChild(opt);
            });
            yearSelect.disabled = false;
            setStatus('Choose the year of your car.');
        } catch (e) {
            setStatus('<span>Could not load years for this model.</span>');
        }

        toggleNextButton();
    });

    yearSelect.addEventListener('change', async () => {
        bodyTypeSelect.innerHTML = '<option value=\"\">Select body type…</option>';
        bodyTypeSelect.disabled = true;

        const make = makeSelect.value;
        const model = modelSelect.value;
        const year = yearSelect.value;
        if (!make || !model || !year) {
            toggleNextButton();
            return;
        }

        setStatus('Loading body types…');

        try {
            const { data } = await fetchJson(`${apiBase}/car/body-types?make=${encodeURIComponent(make)}&model=${encodeURIComponent(model)}&year=${encodeURIComponent(year)}`);
            data.forEach(type => {
                const opt = document.createElement('option');
                opt.value = type;
                opt.textContent = type;
                bodyTypeSelect.appendChild(opt);
            });
            bodyTypeSelect.disabled = false;
            setStatus('Finally, choose the body type and glass to replace.');
        } catch (e) {
            setStatus('<span>Could not load body types for this car.</span>');
        }

        toggleNextButton();
    });

    [glassTypeSelect, bodyTypeSelect].forEach(select => {
        select.addEventListener('change', toggleNextButton);
    });

    loadMakes();
</script>
</body>
</html>

