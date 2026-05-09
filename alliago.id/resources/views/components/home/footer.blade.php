<footer class="px-4 py-14 bg-[#071427] text-white">
  <div class="container mx-auto grid md:grid-cols-4 gap-10">
    <div>
      <div class="flex items-center gap-3 mb-5">
        <img src="/images/alliago-logo.jpeg" alt="AlliaGo" class="w-10 h-10 rounded-xl object-cover">
        <div><p class="font-bold text-lg leading-none">Alliago.id</p><p class="text-[10px] font-bold  tracking-[0.22em] text-slate-400">{{ __('common.footer_visa_assistance') }}</p></div>
      </div>
      <p class="text-slate-400 text-sm leading-relaxed font-medium">{{ __('common.footer_tagline') }}</p>
    </div>
    <div><h4 class="font-bold mb-5">{{ __('common.footer_services') }}</h4><ul class="space-y-3 text-sm text-slate-400 font-medium"><li><a href="#" class="hover:text-white">{{ __('home.footer_visa_japan') }}</a></li><li><a href="#" class="hover:text-white">{{ __('home.footer_visa_korea') }}</a></li><li><a href="#" class="hover:text-white">{{ __('home.footer_visa_australia') }}</a></li><li><a href="#" class="hover:text-white">{{ __('home.footer_visa_schengen') }}</a></li></ul></div>
    <div><h4 class="font-bold mb-5">{{ __('common.footer_company') }}</h4><ul class="space-y-3 text-sm text-slate-400 font-medium"><li><a href="#" class="hover:text-white">{{ __('common.footer_about_us') }}</a></li><li><a href="#" class="hover:text-white">{{ __('common.footer_how_it_works') }}</a></li><li><a href="{{ route('pages.refund_policy') }}" class="hover:text-white">{{ __('common.footer_refund_policy') }}</a></li><li><a href="{{ route('pages.privacy_policy') }}" class="hover:text-white">{{ __('common.footer_privacy_policy') }}</a></li></ul></div>
    <div><h4 class="font-bold mb-5">{{ __('common.footer_contact') }}</h4><p class="text-sm text-slate-400 font-medium leading-relaxed mb-4">{{ __('home.footer_contact_label') }}<br>{{ __('home.footer_email_label') }}</p><a href="https://wa.me/6281334455616" class="inline-flex bg-white text-slate-950 rounded-2xl px-5 py-3 text-xs font-bold tracking-wide font-semibold">{{ __('common.btn_contact_us') }}</a></div>
  </div>
  <div class="container mx-auto mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between gap-4 text-xs text-slate-500 font-bold">
    <p>{{ __('common.footer_rights') }}</p>
  </div>
</footer>
