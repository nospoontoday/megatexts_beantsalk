<table class="table text-center">
    <thead class="bg-info ">
        <tr>
            <th>No</th>
            <th>Item Code</th>
            <th>Manufacturer</th>
            <th>Description</th>
            <th>Dimension</th>
            <th>Unit Price</th>
            <th>QTY</th>
            <th>Total Amount</th>
            <th>Vatable Sales</th>
            <th>VAT</th>
            <th>Subject</th>
            <th>Action</th>
        </tr>
    </thead>
    @if(! empty($products))
    @foreach ($products as $key => $product)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $product->libraryFixture->item_code ?? null }}</td>
        <td>{{ $product->libraryFixture->manufacturer->name ?? null }}</td>
        <td>{{ $product->title }}</td>
        <td>{{ $product->libraryFixture->dimension ?? null }}</td>
        <td>{{ $product->vendor_price }}</td>
        <td>{{ $product->vendor_quantity }}</td>
        <td>{{ $product->vendor_total_amount }}</td>
        <td>{{ $product->vendor_vatable_sales }}</td>
        <td>{{ $product->vendor_vat }}</td>
        <td>{{ $product->subject }}</td>
    </tr>
    @endforeach
    @endif
</table>
<div class="pt-2">
    {{-- {{ $vendors->links() }} --}}
</div>