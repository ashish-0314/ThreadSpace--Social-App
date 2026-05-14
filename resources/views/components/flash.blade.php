@if (session()->has('success'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-[-10px]"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-[-10px]"
         style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 380px; width: calc(100% - 40px);">
        <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #34d399; padding: 12px 16px; border-radius: 12px; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); box-shadow: 0 10px 30px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: space-between; gap: 12px;">
             <div style="display: flex; align-items: center; gap: 10px;">
                 <i class="fa-solid fa-circle-check" style="font-size: 1.1rem;"></i>
                 <span style="font-size: 0.88rem; font-weight: 600;">{{ session('success') }}</span>
             </div>
             <button @click="show = false" style="background: none; border: none; color: rgba(52, 211, 153, 0.6); cursor: pointer; padding: 4px; font-size: 1.1rem; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(52, 211, 153, 0.6)'">
                 <i class="fa-solid fa-xmark"></i>
             </button>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-[-10px]"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-[-10px]"
         style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 380px; width: calc(100% - 40px);">
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; padding: 12px 16px; border-radius: 12px; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); box-shadow: 0 10px 30px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: space-between; gap: 12px;">
             <div style="display: flex; align-items: center; gap: 10px;">
                 <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem;"></i>
                 <span style="font-size: 0.88rem; font-weight: 600;">{{ session('error') }}</span>
             </div>
             <button @click="show = false" style="background: none; border: none; color: rgba(248, 113, 113, 0.6); cursor: pointer; padding: 4px; font-size: 1.1rem; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(248, 113, 113, 0.6)'">
                 <i class="fa-solid fa-xmark"></i>
             </button>
        </div>
    </div>
@endif
