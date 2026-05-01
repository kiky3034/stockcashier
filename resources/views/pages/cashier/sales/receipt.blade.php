<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $sale->invoice_number }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .receipt {
                box-shadow: none !important;
                border: none !important;
            }
        }

        .receipt {
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="no-print mx-auto my-6 flex max-w-sm justify-center gap-2">
        <button onclick="window.print()"
                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
            Print
        </button>

        <a href="{{ route('cashier.sales.show', $sale) }}"
           class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </div>

    <div class="receipt bg-white p-4 text-sm shadow-sm">
        <div class="text-center">
            <h1 class="text-lg font-bold">{{ $settings['store_name'] ?? 'StockCashier Store' }}</h1>

            @if (! empty($settings['store_address']))
                <p class="text-xs text-gray-600">{{ $settings['store_address'] }}</p>
            @endif

            @if (! empty($settings['store_phone']))
                <p class="text-xs text-gray-600">Telp: {{ $settings['store_phone'] }}</p>
            @endif

            @if (! empty($settings['store_email']))
                <p class="text-xs text-gray-600">{{ $settings['store_email'] }}</p>
            @endif

            <p class="mt-1 text-xs text-gray-600">Sales Receipt</p>
        </div>

        <div class="my-3 border-t border-dashed border-gray-400"></div>

        <div class="space-y-1 text-xs">
            <div class="flex justify-between">
                <span>Invoice</span>
                <span>{{ $sale->invoice_number }}</span>
            </div>

            <div class="flex justify-between">
                <span>Date</span>
                <span>{{ $sale->sold_at?->format('d/m/Y H:i') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Cashier</span>
                <span>{{ $sale->cashier->name }}</span>
            </div>

            <div class="flex justify-between">
                <span>Warehouse</span>
                <span>{{ $sale->warehouse->name }}</span>
            </div>

            <div class="flex justify-between">
                <span>Status</span>
                <span>{{ strtoupper($sale->status) }}</span>
            </div>
        </div>

        <div class="my-3 border-t border-dashed border-gray-400"></div>

        <div class="space-y-2">
            @foreach ($sale->items as $item)
                <div>
                    <div class="font-medium">{{ $item->product_name }}</div>

                    <div class="flex justify-between text-xs text-gray-600">
                        <span>
                            {{ number_format($item->quantity, 2, ',', '.') }}
                            {{ $item->unit_name }}
                            x Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                        </span>

                        <span>
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="my-3 border-t border-dashed border-gray-400"></div>

        <div class="space-y-1 text-xs">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Discount</span>
                <span>Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Tax</span>
                <span>Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between border-t border-dashed border-gray-400 pt-2 text-sm font-bold">
                <span>Total</span>
                <span>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Paid</span>
                <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Change</span>
                <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="my-3 border-t border-dashed border-gray-400"></div>

        <div class="text-center text-xs text-gray-600">
            <p>{{ $settings['receipt_footer'] ?? 'Terima kasih sudah berbelanja.' }}</p>
            <p>Barang yang sudah dibeli tidak dapat dikembalikan tanpa struk.</p>
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            // Aktifkan baris ini kalau ingin auto print saat halaman dibuka.
            // window.print();
        });
    </script>
</body>
</html>