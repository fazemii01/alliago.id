# Plan: Airlabs API Migration + Airline Logo Rendering

**Source PRD**: `.claude/prds/airlabs-migration-airline-logos.prd.md`
**Selected Milestone**: All 3 (integrated — 1: Airlabs API, 2: Logo rendering, 3: Fallback)
**Complexity**: Medium

## Summary

Replace `DuffelFlightService` with a new `AirlabsFlightService` that calls Airlabs `/v9/schedules` for flight data. Add `logo_url` to the mapped result using the Airlabs static logo URL pattern (`https://airlabs.co/img/airline/s/{iata_code}.png`). Update the Blade template to render an `<img>` tag with a text fallback when the logo is unavailable.

> **Pricing gap — CONFIRM BEFORE PROCEEDING**: Airlabs `/v9/schedules` is a flight schedule API; it does not return ticket prices or fare breakdowns. The current UI renders `price` and `fare_breakdown` from Duffel. Plan maps `price` / `fare_breakdown` to `null` and hides those UI blocks. Confirm this is acceptable or clarify if a separate pricing source should be added.

## Patterns to Mirror

| Category | Source | Pattern |
|---|---|---|
| Service class | `app/Services/DuffelFlightService.php:1` | `App\Services` namespace, constructor-injected in controller |
| HTTP client | `app/Services/DuffelFlightService.php:291` | `Http::baseUrl()->withHeaders()->timeout()`, protected `client()` method |
| Caching | `app/Services/DuffelFlightService.php:20` | `Cache::remember(key, TTL, fn)` for airport lists |
| Silent fallback | `app/Services/DuffelFlightService.php:174` | `catch (\Throwable) { return fallback; }` on non-critical failure |
| mapOffer shape | `app/Services/DuffelFlightService.php:224` | Array with keys: `airline`, `flight_numbers`, `duration`, `stops`, `price`, etc. |
| Config | `config/services.php:38` | `'airlabs' => ['secret' => env('AIRLABS_SECRET')]` |
| Controller swap | `app/Http/Controllers/FlightTicketController.php:17` | Constructor property promotion, type-hint only |
| Logo in Blade | `resources/views/landing/flights/index.blade.php:617` | Replace `<div>` badge with conditional `<img>` + fallback `<div>` |

## Files to Change

| File | Action | Why |
|---|---|---|
| `app/Services/AirlabsFlightService.php` | CREATE | New service; calls Airlabs `/v9/schedules` and `/v9/suggest` |
| `app/Http/Controllers/FlightTicketController.php` | UPDATE | Swap `DuffelFlightService` → `AirlabsFlightService` (import + constructor + all call sites) |
| `config/services.php` | UPDATE | Add `airlabs.secret` config key |
| `.env.example` | UPDATE | Add `AIRLABS_SECRET=` placeholder |
| `resources/views/landing/flights/index.blade.php` | UPDATE | Render `<img>` logo with onerror fallback; hide price block when null |

## Tasks

### Task 1: Register Airlabs config
- **Action**: Append `'airlabs' => ['secret' => env('AIRLABS_SECRET')]` to `config/services.php` after the `duffel` block. Add `AIRLABS_SECRET=` to `.env.example`.
- **Mirror**: `config/services.php:38-40`
- **Validate**: `php artisan config:clear && php artisan tinker --execute="dump(config('services.airlabs'))"`

### Task 2: Create AirlabsFlightService
- **Action**: Create `app/Services/AirlabsFlightService.php` with the same public interface as `DuffelFlightService`:
  - `tripTypes(): array` — identical return
  - `searchAirports(string $query): array` — calls `GET /v9/suggest?q={query}&api_key={key}`, maps to `[id, name, country_id, label]`
  - `search(array $input): array` — calls `GET /v9/schedules?dep_iata=&arr_iata=&dep_time=&api_key=`, returns `['results' => [...]]`
  - `mapFlight(array $flight): ?array` — same return shape as `mapOffer()`, adds `logo_url` key, sets `price`/`price_value`/`fare_breakdown` to `null`/`[]`
- **Logo URL formula**: `"https://airlabs.co/img/airline/s/{$iataCode}.png"` — static, no extra API call
- **Cache key**: use `airlabs_airports` (not `duffel_airports`) to avoid collision
- **Mirror**: `DuffelFlightService.php` — `client()`, `get()`, `Cache::remember()`, `\Throwable` catch
- **Validate**: `php artisan tinker --execute="dump(app(App\Services\AirlabsFlightService::class)->searchAirports('jakarta'))"`

### Task 3: Swap controller dependency
- **Action**: In `FlightTicketController.php`:
  - Line 5: `use App\Services\DuffelFlightService` → `use App\Services\AirlabsFlightService`
  - Line 17: `DuffelFlightService $duffelFlightService` → `AirlabsFlightService $airlabsFlightService`
  - All `$this->duffelFlightService->` → `$this->airlabsFlightService->`
- **Mirror**: `FlightTicketController.php:5,17`
- **Validate**: `php artisan route:list` — no boot errors

### Task 4: Render logo in Blade + hide price when null
- **Action (logo)**: Replace lines 617-618 in `index.blade.php`:
  ```blade
  @if (!empty($flight['logo_url']))
      <img src="{{ $flight['logo_url'] }}"
           alt="{{ $flight['airline'] }}"
           class="h-10 w-10 rounded-xl object-contain p-1 bg-[#EDF4FF]"
           onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
      <div class="hidden h-10 w-10 items-center justify-center rounded-xl bg-[#EDF4FF] text-xs font-bold text-[#0361fc]">
          {{ strtoupper(substr($flight['airline'], 0, 2)) }}
      </div>
  @else
      <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#EDF4FF] text-xs font-bold text-[#0361fc]">
          {{ strtoupper(substr($flight['airline'], 0, 2)) }}
      </div>
  @endif
  ```
- **Action (price)**: Wrap price block (lines ~654-663) in `@if ($flight['price'])` / `@endif`
- **Mirror**: Existing `@if` / `@forelse` guards already in template
- **Validate**: Load `/flights` search result page in browser; logo renders, fallback badge shows on 404

## Validation

```bash
php artisan config:clear
php artisan tinker --execute="dump(config('services.airlabs.secret'))"
php artisan tinker --execute="dump(app(App\Services\AirlabsFlightService::class)->tripTypes())"
php artisan tinker --execute="dump(app(App\Services\AirlabsFlightService::class)->searchAirports('jakarta'))"
php artisan route:list
```

## Risks

| Risk | Likelihood | Mitigation |
|---|---|---|
| Airlabs `/v9/schedules` returns no price | Confirmed | Hide price block; plan already accounts for this |
| Airlabs `suggest` field names differ from Duffel | Medium | Map explicitly; verify against Airlabs docs response |
| Logo 404 for obscure airlines | Medium | `onerror` JS swaps to 2-letter badge fallback |
| `AIRLABS_SECRET` already in `.env` but wrong value | Low | Test with tinker before controller swap |
| Cached `duffel_airports` key persists | Low | New service uses `airlabs_airports` cache key |

## Acceptance

- [ ] `AirlabsFlightService` passes all tinker smoke tests
- [ ] Controller has zero Duffel references
- [ ] Airline logos render for flights with known IATA codes
- [ ] 2-letter badge fallback shows on logo load error
- [ ] Price block hidden (not broken) when `price === null`
- [ ] `config('services.duffel')` left intact (safe to remove later)
