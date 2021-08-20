<!-- BEGIN: Dark Mode Switcher-->
<div data-url="#"
    class="dark-mode-switcher cursor-pointer shadow-md fixed bottom-0 right-0 box dark:bg-dark-2 border rounded-full w-40 h-12 flex items-center justify-center z-50 mb-10 mr-10"
    style="display:none;">
    <div class="mr-4 text-gray-700 dark:text-gray-300">Dark Mode</div>
    <div class="dark-mode-switcher__toggle border @if (Auth::user()->mode == 'dark') dark-mode-switcher__toggle--active @endif"></div>
</div>
