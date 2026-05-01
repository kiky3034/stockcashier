<x-layouts.app :title="__('Activity Logs')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Activity Logs</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Riwayat aktivitas penting di StockCashier.
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid gap-3 md:grid-cols-4">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari aktivitas..."
                       class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">

                <select name="event"
                        class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                    <option value="">All Events</option>
                    @foreach ($events as $eventOption)
                        <option value="{{ $eventOption }}" @selected($event === $eventOption)>
                            {{ str_replace('_', ' ', ucwords($eventOption, '_')) }}
                        </option>
                    @endforeach
                </select>

                <select name="user_id"
                        class="rounded-lg border-gray-300 text-sm focus:border-gray-900 focus:ring-gray-900">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected($userId == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit"
                            class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Filter
                    </button>

                    <a href="{{ route('admin.activity-logs.index') }}"
                       class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">User</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Event</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Description</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Subject</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">IP</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $log->created_at->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $log->user?->name ?? 'System' }}
                                </td>

                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                        {{ str_replace('_', ' ', ucwords($log->event, '_')) }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-gray-700">
                                    <div>{{ $log->description ?? '-' }}</div>

                                    @if ($log->properties)
                                        <button type="button"
                                                data-activity-properties="{{ base64_encode(json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) }}"
                                                data-activity-event="{{ str_replace('_', ' ', ucwords($log->event, '_')) }}"
                                                class="mt-2 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            View properties
                                        </button>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    @if ($log->subject_type)
                                        <div>{{ class_basename($log->subject_type) }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $log->subject_id }}</div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada activity log.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $logs->links() }}
            </div>
        </div>
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

                    Swal.fire({
                        title: escapeHtml(eventName),
                        html: `
                            <div style="text-align:left">
                                <div style="margin-bottom:10px;color:#6b7280;font-size:13px">
                                    Detail properties activity log.
                                </div>
                                <pre style="max-height:420px;overflow:auto;background:#f9fafb;border:1px solid #e5e7eb;border-radius:12px;padding:12px;font-size:12px;line-height:1.5;color:#374151;white-space:pre-wrap">${escapeHtml(properties)}</pre>
                            </div>
                        `,
                        width: '56rem',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#111827'
                    });
                });
            });
        });
    </script>

</x-layouts.app>