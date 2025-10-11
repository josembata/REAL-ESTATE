<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">My Invoices</h2>
    </x-slot>

    <div class="p-6 bg-white shadow rounded">
        @if($invoices->isEmpty())
            <p>No invoices found.</p>
        @else
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 border">Invoice #</th>
                        <th class="px-4 py-2 border">Amount</th>
                        <th class="px-4 py-2 border">Currency</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Issued</th>
                        <th class="px-4 py-2 border">Due</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td class="px-4 py-2 border">{{ $invoice->invoice_number }}</td>
                            <td class="px-4 py-2 border">{{ number_format($invoice->amount_due, 2) }}</td>
                            <td class="px-4 py-2 border">{{ $invoice->currency }}</td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 rounded 
                                    @if($invoice->status === 'unpaid') bg-red-100 text-red-600 
                                    @elseif($invoice->status === 'partially_paid') bg-yellow-100 text-yellow-600 
                                    @else bg-green-100 text-green-600 @endif">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border">
                            {{ \Illuminate\Support\Carbon::parse($invoice->issued_at)->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-2 border">
                            {{ \Illuminate\Support\Carbon::parse($invoice->due_at)->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                <a href="{{ route('invoices.show', $invoice->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                    View
                                </a>
                                <a href="{{ route('invoices.print', $invoice->id) }}" 
                                   target="_blank" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded">
                                    Print
                                </a>
                                <a href="{{ route('invoices.download', $invoice->id) }}" 
                                class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded">
                                Download PDF
                                </a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
