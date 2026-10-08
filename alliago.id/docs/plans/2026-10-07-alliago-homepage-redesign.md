# AlliaGo Exact Homepage Implementation Plan (1:1 with `alliago.pen`)

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement the homepage (`resources/views/landing/home.blade.php`) to match `alliago.pen` **strictly 1:1**, eliminating all non-canvas sections (extra visa catalog grid, marquee, testimonials, FAQ) and rendering solely the 9 authentic canvas sections using modular, reusable Blade components with zero emojis, zero terminal commands, and a non-native calendar.

**Architecture:**
- Root canvas page: `resources/views/landing/home.blade.php` (Outfit font, `#F8FAFC` background).
- Reusable Blade components in `resources/views/components/home/`:
  1. `<x-home.home-header />`: Top Notification Bar (36px `#001D44`) + Main Navbar (72px `#00275A`) with auth & currency selector.
  2. `<x-home.hero />`: Exact `W6lSTk` section (520px) featuring Bali Ulun Danu temple background, twilight gradient overlay, vector sky grid + trajectory arcs, 5 category capsule tabs (`LVMrd`), 1120px white search card (`YR8dA`), bottom action bar (`IkbyN`) with quick pills & "Cari Tiket" button, and `<x-home.calendar-modal />`.
  3. `<x-home.promo-cards />`: Exact `Q9sSRx` (366px) with section header `< >` carousel arrows and 3 clean gradient cards (Malaysia, Ferry, Japan/Korea).
  4. `<x-home.flight-deals />`: Exact `y5Olv` (Domestic, 5 cards) and `K6FvT` (International, 5 cards) utilizing reusable `<x-home.flight-deal-card />`.
  5. `<x-home.packages />`: Exact `HrWjE` (306px) featuring 3 curated tour cards (Bali 3H2M, Lombok 4H3M, Yogyakarta 3H2M) with discount tags, price, and "Lihat Paket".
  6. `<x-home.features />`: Exact `yJJ8K` (481px) with centered heading and 6-card value proposition bento grid.
  7. `<x-home.consultation-banner />`: Exact `c04yD` (268px) with navy gradient card, constellation watermark, official badge, App Store / Google Play buttons, and WhatsApp phone card mockup.
  8. `<x-home.home-footer />`: Exact `b1PhN` (441px) with dark navy `#001D44`, 4 clean columns, 5 airline partner pills, 8 payment method pills, copyright & SVG social links.
  9. `<x-home.floating-support />`: Exact `IQjpw` (56x56 `#004780`) floating WhatsApp chat button.
  10. `<x-home.calendar-modal />`: Reusable non-native custom calendar popover for date inputs.
  11. `<x-home.flight-deal-card />`: Reusable primitive for rendering the 10 flight destination cards with dynamic gradients and SVG vector artwork.

**Tech Stack:** Laravel 11 Blade, Tailwind CSS v4, Alpine.js v3, Lucide SVG Icons, Google Font Outfit.

---

## Canvas Section Inventory vs Current Code Comparison

| Canvas Section | Node ID in `.pen` | Dimensions | Canvas Content | Action Required |
|---|---|---|---|---|
| **Top Notification Bar** | `RrAF3` | 1440x36 | `#001D44`, promo text, WhatsApp link, help, orders, visa, IDR/RM | Refine classes to match `homepage.html` |
| **Main Navbar** | `exwnf` | 1440x72 | `#00275A`, ALLIAGO.ID logo, 6 links, Masuk / Daftar buttons | Match layout and padding `[0px_80px]` |
| **Hero & Search Section** | `W6lSTk` | 1440x520 | Ulun Danu photo + twilight overlay + 5 category tabs + white search card + outside action bar | **Major overhaul**: Remove fake H1/p; add Ulun Danu photo + overlay; move action bar outside card |
| **Promo & Info Section** | `Q9sSRx` | 1440x366 | Title + arrows + 3 cards (Malaysia, Ferry, Japan/Korea) | Remove extraneous divider & card sub-footers |
| **Domestic Deals** | `y5Olv` | 1440x388 | Title + subtitle + arrows + 5 cards (LOP, DPS, YIA, LBJ, SUB) | Keep & extract into reusable `<x-home.flight-deal-card>` |
| **International Deals** | `K6FvT` | 1440x376 | Title + subtitle + arrows + 5 cards (SIN, BKK, NRT, ICN, SYD) | Keep & extract into reusable `<x-home.flight-deal-card>` |
| **Packages Section** | `HrWjE` | 1440x306 | Title + subtitle + 3 cards (Bali, Lombok, Yogyakarta) | Remove 3-item checklist bullets; match 190px card height |
| **Why Choose AlliaGo** | `yJJ8K` | 1440x481 | Centered title + subtitle + 6 bento cards | Remove top pill badge; match exact bento grid |
| **App Download Section** | `c04yD` | 1440x268 | Gradient `#0B488F` $\rightarrow$ `#00275A` + badges + QR phone card | Correct gradient direction; keep App Store/Google Play only |
| **Comprehensive Footer** | `b1PhN` | 1440x441 | `#001D44`, 4 cols, 5 airlines, 8 payment methods, watermark | Remove extra Garuda pill & tags; add subtle route watermark |
| **Floating Support Button** | `IQjpw` | 56x56 | `#004780` rounded circle with chat icon | Extract into reusable `<x-home.floating-support />` |
| **Services (Visa catalog)** | *None* | *N/A* | Not in canvas | **Remove from homepage** (accessible via `/visa`) |
| **Supported Visas Marquee**| *None* | *N/A* | Not in canvas | **Remove from homepage** |
| **Testimonials** | *None* | *N/A* | Not in canvas | **Remove from homepage** |
| **FAQ Accordion** | *None* | *N/A* | Not in canvas | **Remove from homepage** (accessible via `/faq`) |

---

## Detailed Implementation Tasks

### Task 1: Reusable Primitive Components (`calendar-modal.blade.php` & `flight-deal-card.blade.php` & `floating-support.blade.php`)
- **Create:** `resources/views/components/home/calendar-modal.blade.php`
  - Reusable Alpine.js non-native calendar modal with month switcher, quick select buttons, and date cells.
- **Create:** `resources/views/components/home/flight-deal-card.blade.php`
  - Component taking `@props(['city', 'iata', 'price', 'gradient', 'origin' => 'Jakarta ke', 'originCode' => 'CGK', 'destinationCode', 'artwork' => null])`.
  - Reused 10 times across domestic and international rows.
- **Create:** `resources/views/components/home/floating-support.blade.php`
  - Exact `IQjpw` 56x56 `#004780` floating circle with message-circle icon and WhatsApp link.

### Task 2: Exact Hero Section Overhaul (`hero.blade.php`)
- **Modify:** `resources/views/components/home/hero.blade.php`
- Background:
  - Base photo: `bg-[url('https://images.unsplash.com/photo-1544959068-7c75914bf21e?...')]` (Bali Ulun Danu).
  - Twilight overlay: `linear-gradient(180deg, #00275af0 0%, #00275ac7 35%, #4b323c8c 60%, #fe6a0066 80%, #f8fafce6 95%, #F8FAFC 100%)`.
  - Vector Sky Grid lines, trajectory arcs, and waypoint nodes.
- Structure:
  - Remove all artificial H1 headings and paragraphs.
  - Category tabs (`LVMrd`): 5 capsules at y=24 with Tiket Pesawat active (`#FE6A00`), Tiket Ferry, Layanan Visa, Hotel (badge "Populer"), Paket Tour (badge "Hemat").
  - Flight Search Card (`YR8dA`): 1120px white card, rounded 32px, shadow, radio buttons, 5 fields (Berangkat dari, swap, Pergi ke, Tanggal Pergi, Tanggal Pulang, Penumpang & Kelas).
  - Bottom Action Bar (`IkbyN`): Sits **outside** the white card directly on the twilight overlay: quick action pills (Tiket Ferry, Asistensi Visa, Cek Jadwal & Rute, %), Kode Promo button, and solid orange "Cari Tiket" button.

### Task 3: Streamline Promo & Info Section (`promo-cards.blade.php`)
- **Modify:** `resources/views/components/home/promo-cards.blade.php`
- Exact header with carousel arrow buttons `< >`.
- 3 cards matching `homepage.html`:
  1. Liburan ke Malaysia (`#0A2540` to `#1E3A8A`, badge `#FCD34D`, CTA "PESAN SEKARANG")
  2. Tiket Ferry Hemat (`#003366` to `#38BDF8`, badge `#FEF08A`, CTA "CEK JADWAL")
  3. Visa Jepang & Korea (`#0B1528` to `#334155`, badge `#93C5FD`, CTA "KONSULTASI VISA")
- Remove all extra footer dividers and sub-rows.

### Task 4: Refactor Flight Deals (`flight-deals.blade.php`)
- **Modify:** `resources/views/components/home/flight-deals.blade.php`
- Section 1: Domestic Deals (`y5Olv`) with 5 cards: Lombok (LOP), Bali (DPS), Yogyakarta (YIA), Labuan Bajo (LBJ), Surabaya (SUB).
- Section 2: International Deals (`K6FvT`) with 5 cards: Singapore (SIN), Bangkok (BKK), Tokyo Narita (NRT), Seoul Incheon (ICN), Sydney (SYD).
- Implement using the reusable `<x-home.flight-deal-card />`.

### Task 5: Streamline Tour Packages (`packages.blade.php`)
- **Modify:** `resources/views/components/home/packages.blade.php`
- Match `HrWjE`: Title "Paket Liburan & Tour Terkurasi AlliaGo", subtitle.
- 3 clean 190px cards: Bali Getaway 3H2M, Lombok Escape 4H3M, Yogyakarta Culture 3H2M.
- Remove checklist bullets; keep clean title, subtitle, discount badge, price, and navy "Lihat Paket" button.

### Task 6: Match Why Choose AlliaGo Bento Grid (`features.blade.php`)
- **Modify:** `resources/views/components/home/features.blade.php`
- Match `yJJ8K`: Centered heading "Mengapa Memilih Layanan AlliaGo?" with subtitle.
- 6 bento cards with peach icon container (`#FFF5ED`, border `#FED7AA`, text `#FE6A00`) and concise text.

### Task 7: Align App Banner (`consultation-banner.blade.php`)
- **Modify:** `resources/views/components/home/consultation-banner.blade.php`
- Match `c04yD`: Gradient `linear-gradient(90deg, #0B488F 0%, #00275A 100%)`, constellation watermark.
- Buttons: App Store and Google Play (remove WhatsApp button from button group to match canvas).
- Right: Dark mockup phone card with QR code and WhatsApp number.

### Task 8: Refine Comprehensive Footer (`home-footer.blade.php`)
- **Modify:** `resources/views/components/home/home-footer.blade.php`
- Match `b1PhN`: 4 columns, 5 airlines (Lion Air, Batik Air, Wings Air, Super Air Jet, Thai Lion Air), 8 payment methods.
- Route watermark in background, copyright line, SVG social icons.

### Task 9: Assemble Exact Homepage (`home.blade.php`)
- **Modify:** `resources/views/landing/home.blade.php`
- Sequence strictly matching `alliago.pen`:
  ```blade
  <x-home.home-header />
  <main>
      <x-home.hero :countries="$countries" />
      <x-home.promo-cards />
      <x-home.flight-deals />
      <x-home.packages />
      <x-home.features />
      <x-home.consultation-banner />
  </main>
  <x-home.home-footer />
  <x-home.floating-support />
  ```
- No extra non-canvas sections.

---

## Verification Plan
1. Check each section against its exported counterpart in `scratch/pen-export/`:
   - `W6lSTk.png` vs Hero
   - `Q9sSRx.png` vs Promo cards
   - `y5Olv.png` vs Domestic deals
   - `K6FvT.png` vs International deals
   - `HrWjE.png` vs Packages
   - `yJJ8K.png` vs Features
   - `c04yD.png` vs App banner
   - `b1PhN.png` vs Footer
2. Verify zero native calendar inputs (custom non-native popover everywhere).
3. Verify zero emojis (all SVG icons).
4. Verify subpages (`/visa`, `/flights`, `/ferry`, `/faq`, `/about`) remain intact and accessible via header/footer links.
