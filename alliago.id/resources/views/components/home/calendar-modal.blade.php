{{-- 
    Custom Non-Native Calendar Modal Component
    Strictly adheres to project rules:
    - Zero emojis (Lucide SVGs only)
    - Custom styled Alpine.js calendar (never native <input type="date">)
    - Brand color palette (#00275A, #FE6A00, #F8FAFC, #0F172A)
--}}
<div x-cloak
     x-show="calendarOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     @keydown.escape.window="calendarOpen = false"
     class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-[#001D44]/50 backdrop-blur-sm"
     @click.self="calendarOpen = false">
     
    <div class="relative w-full max-w-[380px] bg-white rounded-[24px] shadow-[0px_20px_50px_rgba(0,39,90,0.25)] border border-slate-200 p-6 overflow-hidden select-none font-['Outfit',sans-serif]"
         x-data="{
             currentMonth: new Date().getMonth(),
             currentYear: new Date().getFullYear(),
             monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
             dayNames: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
             
             prevMonth() {
                 if (this.currentMonth === 0) {
                     this.currentMonth = 11;
                     this.currentYear--;
                 } else {
                     this.currentMonth--;
                 }
             },
             nextMonth() {
                 if (this.currentMonth === 11) {
                     this.currentMonth = 0;
                     this.currentYear++;
                 } else {
                     this.currentMonth++;
                 }
             },
             get daysInMonth() {
                 return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
             },
             get firstDayOfWeek() {
                 return new Date(this.currentYear, this.currentMonth, 1).getDay();
             },
             isPastDate(day) {
                 const target = new Date(this.currentYear, this.currentMonth, day);
                 const today = new Date();
                 today.setHours(0, 0, 0, 0);
                 return target < today;
             },
             isDepartSelected(day) {
                 if (!departDateObj) return false;
                 return departDateObj.getFullYear() === this.currentYear &&
                        departDateObj.getMonth() === this.currentMonth &&
                        departDateObj.getDate() === day;
             },
             isReturnSelected(day) {
                 if (!returnDateObj) return false;
                 return returnDateObj.getFullYear() === this.currentYear &&
                        returnDateObj.getMonth() === this.currentMonth &&
                        returnDateObj.getDate() === day;
             },
             isInRange(day) {
                 if (!departDateObj || !returnDateObj) return false;
                 const target = new Date(this.currentYear, this.currentMonth, day);
                 return target > departDateObj && target < returnDateObj;
             },
             selectDate(day) {
                 if (this.isPastDate(day)) return;
                 const selected = new Date(this.currentYear, this.currentMonth, day);
                 
                 if (activeDateField === 'depart') {
                     departDateObj = selected;
                     departDate = this.formatDate(selected);
                     // If round trip and return is before depart, reset return
                     if (returnDateObj && returnDateObj < selected) {
                         returnDateObj = null;
                         returnDate = '+ Tambah Pulang';
                     }
                     if (tripType === 'round-trip' && !returnDateObj) {
                         activeDateField = 'return';
                         return; // keep modal open to pick return date
                     }
                     calendarOpen = false;
                 } else if (activeDateField === 'return') {
                     if (departDateObj && selected < departDateObj) {
                         // selected is before departure, make it the new departure
                         departDateObj = selected;
                         departDate = this.formatDate(selected);
                         returnDateObj = null;
                         returnDate = '+ Tambah Pulang';
                     } else {
                         returnDateObj = selected;
                         returnDate = this.formatDate(selected);
                         tripType = 'round-trip';
                         calendarOpen = false;
                     }
                 }
             },
             formatDate(d) {
                 const day = String(d.getDate()).padStart(2, '0');
                 const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                 return `${day} ${months[d.getMonth()]} ${d.getFullYear()}`;
             }
         }">
         
        {{-- Calendar Header --}}
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex flex-col">
                <span class="text-[11px] font-semibold tracking-wider uppercase"
                      :class="activeDateField === 'depart' ? 'text-[#FE6A00]' : 'text-[#0B488F]'"
                      x-text="activeDateField === 'depart' ? 'Pilih Tanggal Pergi' : 'Pilih Tanggal Pulang'">
                </span>
                <span class="text-[17px] font-bold text-[#0F172A]"
                      x-text="`${monthNames[currentMonth]} ${currentYear}`">
                </span>
            </div>
            
            <div class="flex items-center gap-1.5">
                <button type="button"
                        @click="prevMonth()"
                        class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100 hover:text-[#00275A] transition-colors"
                        aria-label="Bulan Sebelumnya">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <button type="button"
                        @click="nextMonth()"
                        class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100 hover:text-[#00275A] transition-colors"
                        aria-label="Bulan Selanjutnya">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
        </div>
        
        {{-- Day Names Row --}}
        <div class="grid grid-cols-7 gap-1 pt-3 pb-2 text-center">
            <template x-for="(name, index) in dayNames" :key="index">
                <span class="text-[11px] font-bold"
                      :class="index === 0 ? 'text-rose-500' : 'text-slate-400'"
                      x-text="name">
                </span>
            </template>
        </div>
        
        {{-- Days Grid --}}
        <div class="grid grid-cols-7 gap-1 text-center">
            {{-- Empty prefix cells for day offset --}}
            <template x-for="n in firstDayOfWeek" :key="'blank-' + n">
                <div class="h-9 w-9"></div>
            </template>
            
            {{-- Actual Days --}}
            <template x-for="day in daysInMonth" :key="day">
                <div class="relative flex items-center justify-center h-9 w-9 my-0.5">
                    {{-- In-Range Background highlight (for round-trip) --}}
                    <div x-show="isInRange(day)"
                         class="absolute inset-y-0 inset-x-0 bg-orange-50 z-0"></div>
                    
                    <button type="button"
                            @click="selectDate(day)"
                            :disabled="isPastDate(day)"
                            class="relative z-10 w-8 h-8 rounded-full text-[13px] font-semibold flex items-center justify-center transition-all"
                            :class="{
                                'bg-[#FE6A00] text-white shadow-[0px_4px_12px_rgba(254,106,0,0.35)] font-bold scale-105': isDepartSelected(day) || isReturnSelected(day),
                                'text-slate-300 cursor-not-allowed': isPastDate(day),
                                'text-[#0F172A] hover:bg-slate-100 hover:text-[#FE6A00]': !isPastDate(day) && !isDepartSelected(day) && !isReturnSelected(day) && !isInRange(day),
                                'text-[#C2410C] font-bold': isInRange(day) && !isDepartSelected(day) && !isReturnSelected(day)
                            }"
                            x-text="day">
                    </button>
                </div>
            </template>
        </div>
        
        {{-- Modal Footer --}}
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
            <button type="button"
                    @click="calendarOpen = false"
                    class="text-[12px] font-medium text-slate-500 hover:text-slate-800 transition-colors">
                Batal
            </button>
            <div class="flex items-center gap-2">
                <span class="text-[11px] text-slate-400">Pilih tanggal sesuai rencana Anda</span>
            </div>
        </div>
    </div>
</div>

