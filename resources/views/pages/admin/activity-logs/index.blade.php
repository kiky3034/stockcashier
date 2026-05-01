<x-layouts.app :title="__('Activity Logs')">
    @php
        $totalLogsOnPage = $logs->count();
        $uniqueEventsOnPage = $logs->pluck('event')->unique()->count();
        $systemLogsOnPage = $logs->whereNull('user_id')->count();
    @endphp

    <div class="space-y-6 p-4 sm:p-6">
        <x-page-header
            title="Activity Logs"
            description="Pantau riwayat aktivitas penting, audit user, transaksi, stok, dan perubahan sistem."
        />

        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.stat-card
                label="Logs on Page"
                :value="number_format($totalLogsOnPage, 0, ',', '.')"
                description="Jumlah log pada halaman ini."
                tone="sky"
            />

            <x-ui.stat-card
                label="Unique Events"
                :value="number_format($uniqueEventsOnPage, 0, ',', '.')"
                description="Jenis event yang muncul di halaman ini."
                tone="slate"
            />

            <x-ui.stat-card
                label="System Logs"
                :value="number_format($systemLogsOnPage, 0, ',', '.')"
                description="Log tanpa user terkait."
                tone="amber"
            />
        </div>

        <x-ui.card>
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_260px_260px_auto] lg:items-end">
                <div>
                    <label for="search" class="block text-sm font-semibold text-slate-700">Search</label>
                    <input type="text"
                           id="search"
                           name="search"
                           value="{{ $search }}"
                           placeholder="Cari deskripsi, event, atau subject..."
                           class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                </div>

                <div>
                    <label for="event" class="block text-sm font-semibold text-slate-700">Event</label>
                    <select id="event"
                            name="event"
                            class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                        <option value="">All Events</option>
                        @foreach ($events as $eventOption)
                            <option value="{{ $eventOption }}" @selected($event === $eventOption)>
                                {{ str_replace('_', ' ', ucwords($eventOption, '_')) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="user_id" class="block text-sm font-semibold text-slate-700">User</label>
                    <select id="user_id"
                            name="user_id"
                            class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 shadow-sm transition focus:border-sky-400 focus:bg-white focus:ring-sky-100">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected($userId == $user->id)>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="inline-flex flex-1 items-center justify-center rounded-2xl bg-sky-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-100 lg:flex-none">
                        Filter
                    </button>

                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-100 lg:flex-none">
                        Reset
                    </a>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card padding="p-0" class="overflow-hidden">
            <div class="flex flex-col gap-2 border-b border-slate-100 bg-gradient-to-r from-sky-50 to-white p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Activity Timeline</h2>
                    <p class="mt-1 text-sm text-slate-500">Daftar aktivitas terbaru berdasarkan filter yang dipilih.</p>
                </div>

                <div class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-sky-700 shadow-sm ring-1 ring-sky-100">
                    {{ $logs->total() ?? $logs->count() }} total logs
                </div>
            </div>

            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">User</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Event</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Description</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Subject</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">IP</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($logs as $log)
                            @php
                                $eventLabel = str_replace('_', ' ', ucwords($log->event, '_'));
                                $propertiesJson = $log->properties ? json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;
                            @endphp

                            <tr class="transition hover:bg-sky-50/40">
                                <td class="whitespace-nowrap px-4 py-3 text-sm text-slate-600">
                                    <div class="font-medium text-slate-900">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-500">{{ $log->created_at->format('H:i') }}</div>
                                </td>

                                <td class="px-4 py-3 text-sm text-slate-600">
                                    <div class="font-medium text-slate-900">{{ $log->user?->name ?? 'System' }}</div>
                                    @if ($log->user?->email)
                                        <div class="text-xs text-slate-500">{{ $log->user->email }}</div>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                        {{ $eventLabel }}
                                    </span>
                                </td>

                                <td class="max-w-md px-4 py-3 text-sm text-slate-700">
                                    <div class="line-clamp-2">{{ $log->description ?? '-' }}</div>
                                </td>

                                <td class="px-4 py-3 text-sm text-slate-600">
                                    @if ($log->subject_type)
                                        <div class="font-medium text-slate-900">{{ class_basename($log->subject_type) }}</div>
                                        <div class="text-xs text-slate-500">ID: {{ $log->subject_id }}</div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $log->ip_address ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    @if ($log->properties)
                                        <button type="button"
                                                title="View properties"
                                                data-activity-properties="{{ base64_encode($propertiesJson) }}"
                                                data-activity-event="{{ $eventLabel }}"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-500">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 ring-1 ring-slate-100">
                                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 8v4l3 3" />
                                            <circle cx="12" cy="12" r="10" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 font-medium text-slate-700">Belum ada activity log.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 lg:hidden">
                @forelse ($logs as $log)
                    @php
                        $eventLabel = str_replace('_', ' ', ucwords($log->event, '_'));
                        $propertiesJson = $log->properties ? json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null;
                    @endphp

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="inline-flex rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100">
                                    {{ $eventLabel }}
                                </span>
                                <div class="mt-2 text-sm font-semibold text-slate-900">{{ $log->description ?? '-' }}</div>
                                <div class="mt-1 text-xs text-slate-500">
                                    {{ $log->created_at->format('d M Y H:i') }} · {{ $log->user?->name ?? 'System' }}
                                </div>
                            </div>

                            @if ($log->properties)
                                <button type="button"
                                        title="View properties"
                                        data-activity-properties="{{ base64_encode($propertiesJson) }}"
                                        data-activity-event="{{ $eventLabel }}"
                                        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <div class="mt-3 grid gap-2 text-xs text-slate-500 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="font-semibold text-slate-700">Subject</div>
                                <div class="mt-1">
                                    @if ($log->subject_type)
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-xl bg-slate-50 p-3">
                                <div class="font-semibold text-slate-700">IP Address</div>
                                <div class="mt-1">{{ $log->ip_address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">
                        Belum ada activity log.
                    </div>
                @endforelse
            </div>

            <div class="border-t border-slate-100 p-4">
                {{ $logs->links() }}
            </div>
        </x-ui.card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            document.querySelectorAll('[data-activity-properties]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const eventName = button.dataset.activityEvent || 'Activity';
                    const encodedProperties = button.dataset.activityProperties || '';
                    let properties = '-';

                    try {
                        properties = atob(encodedProperties);
                    } catch (error) {
                        properties = 'Properties tidak bisa ditampilkan.';
                    }

                    if (!window.Swal) {
                        alert(properties);
                        return;
                    }

                    Swal.fire({
                        title: escapeHtml(eventName),
                        html: `
                            <div style="text-align:left">
                                <div style="margin-bottom:10px;color:#64748b;font-size:13px">
                                    Detail properties activity log.
                                </div>
                                <pre style="max-height:420px;overflow:auto;background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;padding:14px;font-size:12px;line-height:1.5;color:#334155;white-space:pre-wrap">${escapeHtml(properties)}</pre>
                            </div>
                        `,
                        width: '56rem',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#0ea5e9'
                    });
                });
            });
        });
    </script>
</x-layouts.app>
