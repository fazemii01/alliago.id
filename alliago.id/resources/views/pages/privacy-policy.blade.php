<x-layouts.app
    title="{{ __('policy.privacy_title') }} | Alliago.id"
    description="Baca kebijakan privasi Alliago.id mengenai pengumpulan, penggunaan, dan perlindungan data pribadi pengguna layanan visa dan tiket pesawat kami."
>

@push('meta')
<meta name="robots" content="noindex, follow" />
@endpush
    <div class="pt-[73px] md:pt-[81px]">
        <x-home.header />
        
        <div class="page-wrapper pb-20">
            <!-- Header Section -->
            <div class="bg-slate-50 border-b border-slate-200 py-16">
                <div class="container mx-auto px-4 text-center max-w-3xl">
                    <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-4">{{ __('policy.privacy_title') }}</h1>
                    <p class="text-lg text-slate-500 font-medium">{{ __('policy.privacy_subtitle') }}</p>
                    <p class="mt-4 text-sm font-semibold text-brand bg-blue-50 py-1.5 px-4 rounded-full inline-block">{{ __('policy.privacy_last_updated') }}</p>
                </div>
            </div>

            <!-- Content Section -->
            <div class="container mx-auto px-4 mt-12 max-w-4xl">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-bold prose-a:text-brand">
                    
                    <p>{{ __('policy.privacy_intro') }}</p>
                    <p>{{ __('policy.privacy_intro_2') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec1_title') }}</h2>
                    <p>{{ __('policy.privacy_sec1_desc') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec2_title') }}</h2>
                    <p>{{ __('policy.privacy_sec2_desc') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec3_title') }}</h2>
                    <p>{{ __('policy.privacy_sec3_desc') }}</p>
                    <ul class="space-y-2">
                        <li>{{ __('policy.privacy_sec3_list_1') }}</li>
                        <li>{{ __('policy.privacy_sec3_list_2') }}</li>
                        <li>{{ __('policy.privacy_sec3_list_3') }}</li>
                        <li>{{ __('policy.privacy_sec3_list_4') }}</li>
                        <li>{{ __('policy.privacy_sec3_list_5') }}</li>
                        <li>{{ __('policy.privacy_sec3_list_6') }}</li>
                    </ul>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec4_title') }}</h2>
                    <p>{{ __('policy.privacy_sec4_desc') }}</p>
                    <ul class="space-y-2">
                        <li>{{ __('policy.privacy_sec4_list_1') }}</li>
                        <li>{{ __('policy.privacy_sec4_list_2') }}</li>
                        <li>{{ __('policy.privacy_sec4_list_3') }}</li>
                    </ul>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec5_title') }}</h2>
                    <p>{{ __('policy.privacy_sec5_desc') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec6_title') }}</h2>
                    <p>{{ __('policy.privacy_sec6_desc') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec7_title') }}</h2>
                    <p>{{ __('policy.privacy_sec7_desc') }}</p>
                    <ul class="space-y-2">
                        <li>{{ __('policy.privacy_sec7_list_1') }}</li>
                        <li>{{ __('policy.privacy_sec7_list_2') }}</li>
                        <li>{{ __('policy.privacy_sec7_list_3') }}</li>
                        <li>{{ __('policy.privacy_sec7_list_4') }}</li>
                        <li>{{ __('policy.privacy_sec7_list_5') }}</li>
                    </ul>
                    <p class="font-semibold text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100 mt-4">{{ __('policy.privacy_sec7_outro') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.privacy_sec8_title') }}</h2>
                    <p>{{ __('policy.privacy_sec8_desc') }}</p>

                </div>
            </div>
            
        </div>
        <x-home.footer />
    </div>
</x-layouts.app>
