@props(['countries' => collect(), 'featuredProducts' => collect()])

<section class="hero-section relative overflow-hidden -mx-[24px] md:mx-0">
  <!-- Hero Background with Texture -->
  <div class="hero-bg-wrapper" aria-hidden="true">
    <!-- Warm gradient base -->
    <div class="hero-gradient"></div>
    
    <!-- Dotted world map SVG overlay — bold like spun.global -->
    <svg class="hero-world-map" viewBox="0 0 1200 600" fill="none" xmlns="http://www.w3.org/2000/svg">
      <!-- Main globe circle (outer) -->
      <circle cx="600" cy="220" r="280" stroke="currentColor" stroke-width="1.5" opacity="0.35" fill="none"/>
      <circle cx="600" cy="220" r="220" stroke="currentColor" stroke-width="1.2" opacity="0.25" fill="none"/>
      <circle cx="600" cy="220" r="150" stroke="currentColor" stroke-width="1" opacity="0.2" fill="none"/>
      
      <!-- Latitude ellipses -->
      <ellipse cx="600" cy="220" rx="280" ry="70" stroke="currentColor" stroke-width="1.2" opacity="0.3" fill="none"/>
      <ellipse cx="600" cy="220" rx="280" ry="140" stroke="currentColor" stroke-width="1" opacity="0.22" fill="none"/>
      <ellipse cx="600" cy="220" rx="280" ry="210" stroke="currentColor" stroke-width="0.8" opacity="0.15" fill="none"/>
      
      <!-- Longitude ellipses -->
      <ellipse cx="600" cy="220" rx="70" ry="280" stroke="currentColor" stroke-width="1.2" opacity="0.3" fill="none"/>
      <ellipse cx="600" cy="220" rx="140" ry="280" stroke="currentColor" stroke-width="1" opacity="0.22" fill="none"/>
      <ellipse cx="600" cy="220" rx="210" ry="280" stroke="currentColor" stroke-width="0.8" opacity="0.15" fill="none"/>
      
      <!-- Cross lines through center -->
      <line x1="320" y1="220" x2="880" y2="220" stroke="currentColor" stroke-width="1" opacity="0.2"/>
      <line x1="600" y1="-60" x2="600" y2="500" stroke="currentColor" stroke-width="1" opacity="0.2"/>

      <!-- Connecting arcs (flight paths) -->
      <path d="M340 200 Q470 60 600 100" stroke="currentColor" stroke-width="1.5" opacity="0.3" fill="none" stroke-dasharray="6 4"/>
      <path d="M600 100 Q730 40 860 160" stroke="currentColor" stroke-width="1.5" opacity="0.3" fill="none" stroke-dasharray="6 4"/>
      <path d="M380 340 Q500 220 680 300" stroke="currentColor" stroke-width="1.2" opacity="0.2" fill="none" stroke-dasharray="5 4"/>
      <path d="M450 120 Q550 180 750 130" stroke="currentColor" stroke-width="1" opacity="0.18" fill="none" stroke-dasharray="4 4"/>
      
      <!-- City / node dots — bigger and bolder -->
      <circle cx="380" cy="170" r="5" fill="currentColor" opacity="0.4"/>
      <circle cx="430" cy="210" r="4" fill="currentColor" opacity="0.35"/>
      <circle cx="520" cy="130" r="6" fill="currentColor" opacity="0.45"/>
      <circle cx="600" cy="100" r="5" fill="currentColor" opacity="0.4"/>
      <circle cx="680" cy="150" r="5.5" fill="currentColor" opacity="0.42"/>
      <circle cx="750" cy="200" r="4" fill="currentColor" opacity="0.35"/>
      <circle cx="820" cy="170" r="5.5" fill="currentColor" opacity="0.4"/>
      <circle cx="860" cy="240" r="4" fill="currentColor" opacity="0.3"/>
      <circle cx="480" cy="280" r="4.5" fill="currentColor" opacity="0.32"/>
      <circle cx="560" cy="300" r="4" fill="currentColor" opacity="0.28"/>
      <circle cx="710" cy="270" r="5" fill="currentColor" opacity="0.35"/>
      <circle cx="350" cy="300" r="4" fill="currentColor" opacity="0.25"/>
      <circle cx="850" cy="130" r="4" fill="currentColor" opacity="0.3"/>
      <circle cx="440" cy="350" r="3.5" fill="currentColor" opacity="0.22"/>
      <circle cx="760" cy="320" r="4" fill="currentColor" opacity="0.25"/>

      <!-- Static rings on key city nodes -->
      <circle cx="520" cy="130" r="14" stroke="currentColor" stroke-width="1" opacity="0.18" fill="none"/>
      <circle cx="680" cy="150" r="12" stroke="currentColor" stroke-width="1" opacity="0.15" fill="none"/>
      <circle cx="820" cy="170" r="14" stroke="currentColor" stroke-width="1" opacity="0.18" fill="none"/>
      <circle cx="600" cy="100" r="11" stroke="currentColor" stroke-width="0.8" opacity="0.14" fill="none"/>
      
      <!-- Scattered smaller dots for texture -->
      @for ($d = 0; $d < 50; $d++)
      <circle cx="{{ 180 + ($d * 37 % 840) }}" cy="{{ 40 + ($d * 23 % 440) }}" r="{{ 1.5 + ($d % 3) * 0.8 }}" fill="currentColor" opacity="{{ 0.08 + ($d % 5) * 0.04 }}"/>
      @endfor
    </svg>
  </div>

  <!-- Hero Content -->
  <div class="relative z-10 pt-32 pb-20 px-6">
    <div class="max-w-5xl mx-auto text-center">
      
      <!-- Live badge -->
      <!-- <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-6">
        <span class="relative flex h-2 w-2">
          <span class="relative inline-flex rounded-full h-2 w-2 bg-pink-brand"></span>
        </span>
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Live processing updates</span>
      </div> -->
      
      <!-- Heading -->
      <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-[1.1] text-slate-950">
        {{ __('home.hero_title_main') }} <br />
        <span class="text-brand">{{ __('home.hero_title_sub') }}</span>
      </h1>
      
      <!-- Subtitle -->
      <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto mb-12 font-medium">
        {{ __('home.hero_subtitle') }}
      </p>

      <!-- Functional Search UI - Pill Search Bar -->
      <form action="{{ route('visa.index') }}" method="GET" class="max-w-3xl mx-auto bg-white p-2 rounded-2xl md:rounded-full shadow-2xl shadow-blue-900/10 border border-slate-100 flex flex-col md:flex-row items-center gap-2">
        
        <!-- From field -->
        <div class="flex-1 w-full flex items-center px-5 gap-3">
          <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <label class="w-full block">
            <span class="block text-[10px] font-bold tracking-wide text-slate-400 text-left">{{ __('home.hero_from') }}</span>
            <select name="from" class="w-full py-1.5 text-slate-700 outline-none font-semibold bg-transparent text-sm">
              @foreach($countries->whereIn('code', ['ID', 'MY', 'SG']) as $c)
              <option value="{{ $c->name }}">{{ $c->name }}</option>
              @endforeach
            </select>
          </label>
        </div>
        
        <!-- Divider -->
        <div class="hidden md:block w-[1px] h-8 bg-slate-200"></div>
        
        <!-- To field -->
        <div class="flex-1 w-full flex items-center px-5 gap-3">
          <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <label class="w-full block">
            <span class="block text-[10px] font-bold tracking-wide text-slate-400 text-left">{{ __('home.hero_to') }}</span>
            <input type="text" name="query" list="hero-countries" placeholder="{{ __('home.hero_destination_placeholder') }}" class="w-full py-1.5 text-slate-700 outline-none font-semibold placeholder:text-slate-300 bg-transparent text-sm" required />
            <datalist id="hero-countries">
              @foreach($countries as $c)
              <option value="{{ $c->name }}"></option>
              @endforeach
            </datalist>
          </label>
        </div>
        
        <!-- Divider -->
        <div class="hidden md:block w-[1px] h-8 bg-slate-200"></div>
        
        <!-- Purpose field -->
        <div class="w-full md:w-auto flex items-center px-5 gap-3">
          <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
          </svg>
          <label class="w-full block">
            <span class="block text-[10px] font-bold tracking-wide text-slate-400 text-left">{{ __('home.hero_purpose') }}</span>
            <select name="purpose" class="w-full py-1.5 text-slate-700 outline-none font-semibold bg-transparent text-sm whitespace-nowrap">
              <option value="tourism">{{ __('home.hero_purpose_tourism') }}</option>
              <option value="business">{{ __('home.hero_purpose_business') }}</option>
              <option value="family">{{ __('home.hero_purpose_family') }}</option>
              <option value="student">{{ __('home.hero_purpose_student') }}</option>
            </select>
          </label>
        </div>
        
        <!-- Search Button -->
        <button type="submit" class="w-full md:w-auto px-8 py-3.5 rounded-xl md:rounded-full bg-brand text-white font-bold transition-all hover:brightness-110 flex items-center justify-center gap-2 shrink-0 shadow-md shadow-blue-500/20">
          {{ __('home.hero_search_btn') }}
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </button>
      </form>

      <!-- Quick category pills -->
      <!-- <div class="flex flex-wrap justify-center gap-3 mt-10">
        @foreach($featuredProducts->take(5) as $product)
        <a href="#services" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-white border border-slate-200/80 text-sm font-semibold text-slate-600 shadow-sm hover:border-blue-200 hover:text-brand hover:shadow-md transition-all duration-200">
          <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          {{ $product->name }}
          <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>
        @endforeach
      </div> -->

      <!-- Trust stats row -->
      <!-- <div class="flex flex-wrap justify-center gap-8 mt-12">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-soft-blue flex items-center justify-center">
            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <div class="text-left">
            <p class="text-xl font-extrabold text-slate-800">{{ $countries->count() }}+</p>
            <p class="text-xs font-semibold text-slate-400">Negara tujuan</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-soft-pink flex items-center justify-center">
            <svg class="w-5 h-5 text-pink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
          </div>
          <div class="text-left">
            <p class="text-xl font-extrabold text-slate-800">4.9</p>
            <p class="text-xs font-semibold text-slate-400">Rating klien</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-soft-blue flex items-center justify-center">
            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          </div>
          <div class="text-left">
            <p class="text-xl font-extrabold text-slate-800">24/7</p>
            <p class="text-xs font-semibold text-slate-400">Support</p>
          </div>
        </div>
      </div> -->

    </div>
  </div>
  
</section>
