<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-brand border border-transparent rounded-[20px] font-semibold text-sm text-white tracking-wide hover:bg-brand-dark focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
