<footer class="mt-auto border-t border-slate-200 bg-white">
    <div class="flex flex-col items-center justify-between gap-2 px-4 py-4 text-center text-xs text-slate-500 sm:flex-row sm:px-6 sm:text-left lg:px-8">
        <p>
            &copy; {{ date('Y') }} <span class="font-bold text-slate-700">LavaSoft</span>.
            Todos los derechos reservados.
        </p>

        <p class="font-medium">
            Versión {{ config('app.version', '1.0.0') }}
        </p>
    </div>
</footer>