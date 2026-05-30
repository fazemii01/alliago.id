<x-layouts.app
    title="{{ __('policy.refund_title') }} | Alliago.id"
    description="Pelajari kebijakan refund dan pembatalan layanan visa Alliago.id. Ketentuan pengembalian dana, syarat pembatalan, dan prosedur klaim refund."
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
                    <h1 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-4">{{ __('policy.refund_title') }}</h1>
                    <p class="text-lg text-slate-500 font-medium">{{ __('policy.refund_subtitle') }}</p>
                    <p class="mt-4 text-sm font-semibold text-brand bg-blue-50 py-1.5 px-4 rounded-full inline-block">{{ __('policy.last_updated') }}</p>
                </div>
            </div>

            <!-- Content Section -->
            <div class="container mx-auto px-4 mt-12 max-w-4xl">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-12 prose prose-slate max-w-none prose-headings:font-bold prose-a:text-brand">
                    
                    <p class="font-medium text-lg">{{ __('policy.welcome') }}</p>
                    <p>{{ __('policy.intro') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_1_title') }}</h2>
                    <p>{{ __('policy.section_1_intro') }}</p>
                    <ul class="space-y-2">
                        <li>{{ __('policy.section_1_list_1') }}</li>
                        <li>{{ __('policy.section_1_list_2') }}</li>
                        <li>{{ __('policy.section_1_list_3') }}</li>
                        <li>{{ __('policy.section_1_list_4') }}</li>
                        <li>{{ __('policy.section_1_list_5') }}</li>
                    </ul>
                    <p class="font-semibold text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100 mt-4">{{ __('policy.section_1_outro') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_2_title') }}</h2>
                    <h3 class="text-lg text-slate-800">{{ __('policy.section_2_a_title') }}</h3>
                    <p>{{ __('policy.section_2_a_desc') }}</p>

                    <h3 class="text-lg text-slate-800 mt-6">{{ __('policy.section_2_b_title') }}</h3>
                    <p>{{ __('policy.section_2_b_desc') }}</p>

                    <h3 class="text-lg text-slate-800 mt-6">{{ __('policy.section_2_c_title') }}</h3>
                    <p>{{ __('policy.section_2_c_desc') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_3_title') }}</h2>
                    <p>{{ __('policy.section_3_intro') }}</p>
                    <ul class="space-y-2">
                        <li>{{ __('policy.section_3_list_1') }}</li>
                        <li>{{ __('policy.section_3_list_2') }}</li>
                        <li>
                            {{ __('policy.section_3_list_3') }}
                            <ul class="mt-2 space-y-1">
                                <li>{{ __('policy.section_3_list_3_a') }}</li>
                                <li>{{ __('policy.section_3_list_3_b') }}</li>
                                <li>{{ __('policy.section_3_list_3_c') }}</li>
                                <li>{{ __('policy.section_3_list_3_d') }}</li>
                                <li>{{ __('policy.section_3_list_3_e') }}</li>
                            </ul>
                        </li>
                        <li>{{ __('policy.section_3_list_4') }}</li>
                        <li>{{ __('policy.section_3_list_5') }}</li>
                    </ul>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_4_title') }}</h2>
                    <p>{{ __('policy.section_4_intro') }}</p>
                    <ul class="space-y-2">
                        <li>{{ __('policy.section_4_list_1') }}</li>
                        <li>{{ __('policy.section_4_list_2') }}</li>
                        <li>{{ __('policy.section_4_list_3') }}</li>
                        <li>{{ __('policy.section_4_list_4') }}</li>
                    </ul>
                    <p class="font-semibold text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100 mt-4">{{ __('policy.section_4_outro') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_5_title') }}</h2>
                    <ol class="space-y-2 list-decimal ml-4">
                        <li>{{ __('policy.section_5_list_1') }}</li>
                        <li>{{ __('policy.section_5_list_2') }}</li>
                        <li>{{ __('policy.section_5_list_3') }}</li>
                    </ol>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_6_title') }}</h2>
                    <p>{{ __('policy.section_6_intro') }}</p>
                    <ul class="space-y-2">
                        <li>{{ __('policy.section_6_list_1') }}</li>
                        <li>{{ __('policy.section_6_list_2') }}</li>
                        <li>{{ __('policy.section_6_list_3') }}</li>
                    </ul>
                    <p class="text-slate-600 mt-4">{{ __('policy.section_6_outro') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_7_title') }}</h2>
                    <p>{{ __('policy.section_7_desc') }}</p>

                    <h2 class="text-2xl mt-10 mb-4 text-slate-900">{{ __('policy.section_8_title') }}</h2>
                    <p class="font-bold mb-1">{{ __('policy.section_8_desc_1') }}</p>
                    <p>{{ __('policy.section_8_desc_2') }}</p>

                </div>
            </div>
            
        </div>
        <x-home.footer />
    </div>
</x-layouts.app>
