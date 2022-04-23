<div class="table-responsive py-4">
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>S/N</th>
                <th>Item Code</th>
                <th>Manufacturer</th>
                <th>Description</th>
                <th>Dimension</th>
                <th>Unit Price</th>
                <th>QTY</th>
                <th>Total Amount</th>
                <th>Discount</th>
                <th>Subject</th>
            </tr>
        </thead>
        @foreach ($products as $key => $product)
        @if($product->type_id == 4)
        <tr>
            <td>
            {{ $key + 1 }}
            </td>
            <td>{{ $product->libraryFixture->item_code ?? null }}</td>
            <td>{{ $product->libraryFixture->manufacturer->name ?? null }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->libraryFixture->dimension ?? null }}</td>
            <td>{{ $product->pivot->price ?? null }}</td>
            <td>{{ $product->pivot->quantity ?? null }}</td>
            <td>{{ $product->pivot->price * $product->pivot->quantity }}</td>
            <td>{{ $product->pivot->discount }}</td>
            <td>{{ $product->subject ?? null }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>