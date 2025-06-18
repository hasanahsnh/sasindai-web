<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        {!! file_get_contents(public_path('css/invoice.css')) !!}
    </style>
</head>
<body>
    <div class="invoice-wrapper" id="print-area">
        <div class="invoice">
            <div class="invoice-head">
                <div class="invoice-head-top">
                    <div class="invoice-head-top-left text-start">
                        <img src="{{ public_path('images/sasindai_by_thiesa.png') }}" alt="Logo">
                    </div>
                    <div class="invoice-head-top-right text-end">
                        <h3>Rincian Pesanan</h3>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="invoice-head-middle">
                    <div class="text-start">
                        <p><span class="text-bold">Date:</span> {{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-end">
                        <p><span class="text-bold">ID Pesanan:</span> {{ $order['order_id'] }}</p>
                    </div>
                </div>
                <div class="hr"></div>
                <div class="invoice-head-bottom">
                    <div class="invoice-head-bottom-left">
                        <ul>
                            <li class="text-bold">Kepada:</li>
                            <li>{{ $order['namaLengkap'] }}</li>
                            <li>{{ $order['alamat'] }}</li>
                            <li>Telp: {{ $order['no_telp'] }}</li>
                        </ul>
                    </div>
                    <div class="invoice-head-bottom-right text-end">
                        <ul>
                            <li class="text-bold">Pay To:</li>
                            <li>Thiesa</li>
                            <li>{{ $order['kurir'] }} - {{ $order['layanan'] }}</li>
                            <li>{{ $order['metode_pembayaran'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="overflow-view">
                <div class="invoice-body">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-bold">Produk</th>
                                <th class="text-bold">Varian</th>
                                <th class="text-bold">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($order['produk'] as $item)
                                <tr>
                                    <td>{{ $item['namaProduk'] }}</td>
                                    <td>{{ $item['namaVarian'] }}</td>
                                    <td>{{ $item['qty'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
