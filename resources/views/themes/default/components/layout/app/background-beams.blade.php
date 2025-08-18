<div class="flex absolute -z-50 top-0 inset-x-0 justify-center overflow-hidden pointer-events-none">
    <div class="w-[108rem] flex-none flex justify-end">
        <picture>
            <source srcset="{{ asset('images/beams/dashboard-dark.avif') }}" type="image/avif">
            <img src="{{ asset('images/beams/dashboard-dark.png') }}" alt=""
                 class="w-[90rem] flex-none max-w-none hidden dark:block" decoding="async"></picture>

        <picture>
            <source srcset="{{ asset('images/beams/dashboard-light.avif') }}" type="image/avif">
            <img src="{{ asset('images/beams/dashboard-light.png') }}" alt=""
                 class="w-[71.75rem] flex-none max-w-none dark:hidden" decoding="async">
        </picture>
    </div>
</div>
