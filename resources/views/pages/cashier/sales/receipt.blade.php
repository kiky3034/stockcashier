@php
    $paperSize = $settings['receipt_paper_size'] ?? '80mm';
    $receiptWidth = $paperSize === '58mm' ? '58mm' : '80mm';
    $showLogo = ($settings['receipt_show_logo'] ?? 'true') === 'true';
    $autoPrint = ($settings['receipt_auto_print'] ?? 'false') === 'true';
    $currency = $settings['currency_prefix'] ?? 'Rp';

    $payment = $sale->payments->first();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $sale->invoice_number }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @page {
            size: {{ $receiptWidth }} auto;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: {{ $receiptWidth }};
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .receipt {
                width: {{ $receiptWidth }} !important;
                max-width: {{ $receiptWidth }} !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
        }

        .receipt {
            width: {{ $receiptWidth }};
            max-width: {{ $receiptWidth }};
            margin: 0 auto;
            font-size: {{ $paperSize === '58mm' ? '11px' : '12px' }};
            line-height: 1.35;
        }

        .dashed-line {
            border-top: 1px dashed #9ca3af;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="no-print mx-auto my-6 flex max-w-sm justify-center gap-2">
        <button type="button"
                id="manualPrintButton"
                class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
            Print
        </button>

        <button type="button"
                id="copyReceiptInvoiceButton"
                data-invoice="{{ $sale->invoice_number }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Copy Invoice
        </button>

        <a href="{{ route('cashier.sales.show', $sale) }}"
           class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </div>

    <div class="receipt bg-white p-3 shadow-sm">
        <div class="text-center">
            @if ($showLogo && ! empty($settings['store_logo']))
                <div class="mb-2 flex justify-center">
                    <img src="{{ asset('storage/' . $settings['store_logo']) }}"
                         alt="Store Logo"
                         class="{{ $paperSize === '58mm' ? 'h-10 w-10' : 'h-14 w-14' }} object-contain">
                </div>
            @endif

            <h1 class="font-bold {{ $paperSize === '58mm' ? 'text-sm' : 'text-base' }}">
                {{ $settings['store_name'] ?? 'StockCashier Store' }}
            </h1>

            @if (! empty($settings['store_address']))
                <p class="text-gray-600">{{ $settings['store_address'] }}</p>
            @endif

            @if (! empty($settings['store_phone']))
                <p class="text-gray-600">Telp: {{ $settings['store_phone'] }}</p>
            @endif

            @if (! empty($settings['store_email']))
                <p class="text-gray-600">{{ $settings['store_email'] }}</p>
            @endif
        </div>

        <div class="my-2 dashed-line"></div>

        <div class="space-y-1">
            <div class="flex justify-between gap-2">
                <span>Invoice</span>
                <span class="text-right">{{ $sale->invoice_number }}</span>
            </div>

            <div class="flex justify-between gap-2">
                <span>Date</span>
                <span class="text-right">{{ $sale->sold_at?->format('d/m/Y H:i') }}</span>
            </div>

            <div class="flex justify-between gap-2">
                <span>Cashier</span>
                <span class="text-right">{{ $sale->cashier->name }}</span>
            </div>

            <div class="flex justify-between gap-2">
                <span>Warehouse</span>
                <span class="text-right">{{ $sale->warehouse->name }}</span>
            </div>

            <div class="flex justify-between gap-2">
                <span>Status</span>
                <span class="text-right">{{ strtoupper(str_replace('_', ' ', $sale->status)) }}</span>
            </div>
        </div>

        <div class="my-2 dashed-line"></div>

        <div class="space-y-2">
            @foreach ($sale->items as $item)
                <div>
                    <div class="font-semibold">
                        {{ $item->product_name }}
                    </div>

                    <div class="text-gray-600">
                        SKU: {{ $item->sku }}
                    </div>

                    <div class="flex justify-between gap-2">
                        <span>
                            {{ number_format($item->quantity, 2, ',', '.') }}
                            {{ $item->unit_name }}
                            x {{ $currency }} {{ number_format($item->unit_price, 0, ',', '.') }}
                        </span>

                        <span class="text-right">
                            {{ $currency }} {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="my-2 dashed-line"></div>

        <div class="space-y-1">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>{{ $currency }} {{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Discount</span>
                <span>{{ $currency }} {{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Tax</span>
                <span>{{ $currency }} {{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
            </div>

            <div class="my-2 dashed-line"></div>

            <div class="flex justify-between font-bold">
                <span>Total</span>
                <span>{{ $currency }} {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Paid</span>
                <span>{{ $currency }} {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>

            <div class="flex justify-between">
                <span>Change</span>
                <span>{{ $currency }} {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>

            @if ($payment)
                <div class="flex justify-between">
                    <span>Payment</span>
                    <span>{{ strtoupper($payment->method) }}</span>
                </div>

                @if ($payment->reference_number)
                    <div class="flex justify-between gap-2">
                        <span>Ref</span>
                        <span class="text-right">{{ $payment->reference_number }}</span>
                    </div>
                @endif
            @endif
        </div>

        @if ($sale->notes)
            <div class="my-2 dashed-line"></div>

            <div>
                <div class="font-semibold">Notes</div>
                <div>{{ $sale->notes }}</div>
            </div>
        @endif

        <div class="my-2 dashed-line"></div>

        <div class="text-center text-gray-600">
            <p>{{ $settings['receipt_footer'] ?? 'Terima kasih sudah berbelanja.' }}</p>
            <p class="mt-1">Barang yang sudah dibeli tidak dapat dikembalikan tanpa struk.</p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function notifyToast(icon, title) {
                if (window.Toast) {
                    Toast.fire({ icon, title });
                    return;
                }

                if (window.Swal) {
                    Swal.fire({ icon, title, timer: 1800, showConfirmButton: false });
                }
            }

            function printReceipt() {
                notifyToast('info', 'Membuka dialog print receipt');
                setTimeout(function () {
                    window.print();
                }, 250);
            }

            const manualPrintButton = document.getElementById('manualPrintButton');

            if (manualPrintButton) {
                manualPrintButton.addEventListener('click', printReceipt);
            }

            const copyButton = document.getElementById('copyReceiptInvoiceButton');

            if (copyButton) {
                copyButton.addEventListener('click', function () {
                    const invoice = copyButton.dataset.invoice || '';

                    if (!invoice) {
                        notifyToast('error', 'Invoice tidak ditemukan');
                        return;
                    }

                    navigator.clipboard.writeText(invoice).then(function () {
                        notifyToast('success', 'Invoice berhasil disalin');
                    }).catch(function () {
                        notifyToast('error', 'Gagal menyalin invoice');
                    });
                });
            }

            @if ($autoPrint)
                window.addEventListener('load', function () {
                    printReceipt();
                });
            @endif
        });
    </script>
</body>
</html>