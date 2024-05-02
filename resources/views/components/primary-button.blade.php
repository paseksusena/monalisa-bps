<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-44 py-1 bg-cyan-950 border border-transparent rounded-md font-semibold text-sm mt-3 text-white uppercase tracking-widest hover:bg-cyan-900 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
