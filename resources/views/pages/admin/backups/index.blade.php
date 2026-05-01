<x-layouts.app :title="__('Backups')">
    <div class="space-y-6 p-4 sm:p-6 lg:p-8">
        <x-page-header
            title="Backups"
            description="Download backup database StockCashier dalam format SQL."
        >
            <x-slot:actions>
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                    Dashboard
                </a>
            </x-slot:actions>
        </x-page-header>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
            <x-ui.card class="overflow-hidden" padding="p-0">
                <div class="border-b border-slate-100 bg-gradient-to-r from-sky-50 to-cyan-50 p-5 sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <div class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wide text-sky-700 shadow-sm ring-1 ring-sky-100">
                                Manual Backup
                            </div>

                            <h2 class="mt-4 text-xl font-bold text-slate-900">
                                Database Backup
                            </h2>

                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                                File backup berisi struktur tabel dan data database dalam format SQL. Simpan file ini di tempat aman dan jangan dibagikan sembarangan.
                            </p>
                        </div>

                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-sky-500 text-white shadow-lg shadow-sky-500/20">
                            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <path d="M7 10l5 5 5-5" />
                                <path d="M12 15V3" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Format</div>
                            <div class="mt-2 text-lg font-bold text-slate-900">SQL</div>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Struktur tabel dan data.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Scope</div>
                            <div class="mt-2 text-lg font-bold text-slate-900">Database</div>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Semua tabel aplikasi.</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Access</div>
                            <div class="mt-2 text-lg font-bold text-slate-900">Admin</div>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Tercatat di activity log.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('admin.backups.database') }}"
                           id="downloadBackupButton"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <path d="M7 10l5 5 5-5" />
                                <path d="M12 15V3" />
                            </svg>
                            Download Database Backup
                        </a>

                        <button type="button"
                                id="backupInfoButton"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                            Info Backup
                        </button>
                    </div>
                </div>
            </x-ui.card>

            <div class="space-y-6">
                <x-ui.card>
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 9v4" />
                                <path d="M12 17h.01" />
                                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900">Catatan Penting</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">
                                Backup ini cocok untuk tahap awal. Untuk production besar, tetap gunakan backup otomatis dari hosting/server.
                            </p>
                        </div>
                    </div>
                </x-ui.card>

                <x-ui.card>
                    <h3 class="font-bold text-slate-900">Saran Penyimpanan</h3>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex gap-3">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-sky-500"></span>
                            <span>Simpan file backup di storage eksternal atau cloud yang aman.</span>
                        </div>
                        <div class="flex gap-3">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-sky-500"></span>
                            <span>Jangan kirim file SQL lewat channel publik.</span>
                        </div>
                        <div class="flex gap-3">
                            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-sky-500"></span>
                            <span>Lakukan backup rutin sebelum update besar.</span>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('downloadBackupButton');
            const infoButton = document.getElementById('backupInfoButton');

            if (button) {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    if (! window.Swal) {
                        if (confirm('Download backup database sekarang?')) {
                            window.location.href = button.href;
                        }

                        return;
                    }

                    Swal.fire({
                        title: 'Download backup?',
                        text: 'File SQL database akan didownload. Simpan file ini di tempat aman.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, download',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        confirmButtonColor: '#0ea5e9',
                        cancelButtonColor: '#64748b'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (window.Toast) {
                                Toast.fire({
                                    icon: 'info',
                                    title: 'Backup sedang disiapkan...'
                                });
                            }

                            window.location.href = button.href;
                        }
                    });
                });
            }

            if (infoButton) {
                infoButton.addEventListener('click', function () {
                    if (! window.Swal) {
                        alert('Backup berisi struktur tabel dan data database dalam format SQL.');
                        return;
                    }

                    Swal.fire({
                        icon: 'info',
                        title: 'Tentang Database Backup',
                        html: `
                            <div class="text-left text-sm leading-6 text-slate-600">
                                <p>Backup ini menghasilkan file <strong>.sql</strong> yang berisi struktur tabel dan data database.</p>
                                <ul class="mt-3 list-disc space-y-1 pl-5">
                                    <li>Cocok untuk backup manual tahap awal.</li>
                                    <li>Download backup akan tercatat di activity log.</li>
                                    <li>Untuk production besar, tetap gunakan backup otomatis dari server atau hosting.</li>
                                </ul>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#0ea5e9'
                    });
                });
            }
        });
    </script>
</x-layouts.app>
