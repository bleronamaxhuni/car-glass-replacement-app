# Car Glass Replacement Quote App

A Laravel web application that lets users select their car (make, model, year, body type) using data from a mock external API, choose a glass part to replace, view 3–4 vendor options with price, warranty and delivery time, and submit a quote request stored in the database.

## Project Overview

- **Car selection:** Makes, models, years and body types are loaded from a mock external API (in-app service). Users pick their vehicle in a cascading form.
- **Glass selection:** After choosing the car, users pick which glass to replace (e.g. front windshield, rear door glass) from a list of glass types.
- **Vendor options:** The app returns 3–4 replacement options from different vendors, each with price, warranty period and estimated delivery time.
- **Quote request:** The user selects one vendor; a quote is created and a summary is shown (car, glass, vendor, final price, date). Quote data is stored in the database.

The project uses **PSR-4** autoloading, **Composer** for dependencies, **Form Requests** for validation, and a **CarApiClient** & **QuoteService** for business logic. PHPUnit tests cover the car API, vendor options and quote creation.

## Requirements

- PHP 8.3+
- Composer
- MySQL

## Installation

1. **Clone the repository**

   ```bash
   git clone <repository-url>
   cd car-glass-replacement-app
   ```

2. **Install dependencies**

   ```bash
   composer install
   ```

3. **Environment and key**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database**

   - **MySQL:** set `DB_CONNECTION=mysql`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_HOST`, `DB_PORT` in `.env`.

5. **Run migrations and seed data**

   ```bash
   php artisan migrate --seed
   ```

   This creates tables and seeds car body types, glass types, vendors and vendor glass prices.

## Usage

1. **Start the development server**

   ```bash
   php artisan serve
   ```

2. **Open the app**

   In your browser go to `http://localhost:8000` (or the URL shown by `artisan serve`).

3. **Request a quote**

   - Choose **Make** → **Model** → **Year** → **Body type** (dropdowns load from the mock car API).
   - Choose **Glass to replace**.
   - Click **View vendor options**.
   - On the vendor options page, pick one vendor and click **Use this vendor**.
   - Review the quote summary (quote ID, car, glass, vendor, price, date). You can start another quote from the link on the summary page.

## API Documentation

Base URL for API routes: `http://localhost:8000/api` (or your app URL + `/api`).

Successful car list responses always return HTTP **200** with a `data` array (possibly empty when nothing matches the filters). Validation errors use **422** with `message` / `errors`.

### Car data (mock external API)

| Method | Endpoint | Parameters | Description |
|--------|----------|------------|-------------|
| GET | `/api/car/makes` | — | List of car makes. |
| GET | `/api/car/models` | `make` (required) | Models for the given make. |
| GET | `/api/car/years` | `make`, `model` (required) | Years for the given make and model. |
| GET | `/api/car/body-types` | `make`, `model`, `year` (required, integer) | Body types for the given make/model/year. |

**Example: get models**

```http
GET /api/car/models?make=Toyota
```

**Response (200)**

```json
{
  "data": ["Corolla", "RAV4"]
}
```

**Response (200)** – no models for the given make (empty result)

```json
{
  "data": []
}
```

**Response (422)** – validation error (e.g. missing `make`)

```json
{
  "message": "The make field is required.",
  "errors": { "make": ["The make field is required."] }
}
```

### Quote flow (web + optional API-style)

Quote creation is primarily done via the **web** routes (browser form). Request bodies use form encoding or JSON; validation is the same.

| Method | Endpoint | Body / params | Description |
|--------|----------|----------------|-------------|
| POST | `/quotes/vendor-options` | `make`, `model`, `year`, `body_type`, `glass_type_id` | Returns HTML view with vendor options for the selected car and glass. On error, redirects back with validation or business errors. |
| POST | `/quotes` | `car_id`, `glass_type_id`, `vendor_glass_price_id` | Creates a quote and returns HTML summary view. |

**Example: request vendor options (JSON)**

```http
POST /quotes/vendor-options
Content-Type: application/json

{
  "make": "Toyota",
  "model": "Corolla",
  "year": 2019,
  "body_type": "Sedan",
  "glass_type_id": 1
}
```

**Example: create quote (JSON)**

```http
POST /quotes
Content-Type: application/json

{
  "car_id": 1,
  "glass_type_id": 1,
  "vendor_glass_price_id": 1
}
```

Web routes are protected by CSRF when called from the browser; for API-style tools (e.g. Postman) you can use the same URLs with a valid session and CSRF token, or call the API routes below if you add them and they skip CSRF.

### Mock data

- **Car data:** Provided by `App\Services\CarApiClient` (in-memory mock). Makes include Toyota and Volkswagen with sample models, years and body types.
- **Vendor and glass data:** Seeded by `Database\Seeders\GlassTypeSeeder`, `VendorSeeder`, `VendorGlassPriceSeeder`. Run `php artisan migrate --seed` to populate.

## Testing

**Run all tests**

```bash
php artisan test
```

**What is covered**

- **Unit:** `CarApiClient` (makes, models, years, body types; valid and invalid input). `QuoteService` (get vendor options with success / body_type error / no_vendors error; create quote and persist data).
- **Feature:** Car API endpoints (200 with `data`, including empty arrays; 422 when required params missing). Quote flow: POST vendor-options (success, validation, body type error, no vendors); POST quotes (success, validation, invalid IDs).

## Project structure (relevant parts)

- `app/Http/Controllers/QuoteController.php` – quote create form, vendor options, store quote.
- `app/Http/Controllers/Api/CarApiController.php` – car makes, models, years, body types API.
- `app/Services/CarApiClient.php` – mock external car API.
- `app/Services/QuoteService.php` – vendor options and quote creation logic.
- `app/Http/Requests/Quote/` – validation for vendor options and store quote.
- `app/Http/Requests/Api/` – validation for car API.
- `app/Models/` – Car, CarBodyType, GlassType, Vendor, VendorGlassPrice, Quote.
- `database/migrations/` – tables for cars, body types, glass types, vendors, vendor_glass_prices, quotes.
- `database/seeders/` – seed data for body types, glass types, vendors, vendor glass prices.
- `resources/views/quotes/` – create, vendor-options, summary Blade views.
- `tests/Unit/` – CarApiClientTest, QuoteServiceTest.
- `tests/Feature/` – CarApiTest, QuoteFlowTest.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
