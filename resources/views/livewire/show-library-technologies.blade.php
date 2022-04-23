<div class="table-responsive py-4">
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>S/N</th>
                <th>Item Code</th>
                <th>Developer</th>
                <th>Title</th>
                <th>Subscription Period</th>
                <th>Unit Price</th>
                <th>QTY</th>
                <th>Total Amount</th>
                <th>Vatable Sales</th>
                <th>VAT</th>
                <th>Subject</th>
            </tr>
        </thead>
        @foreach ($products as $key => $product)
        @if($product->type_id == 8)
        <tr>
            <td>
            {{ $key + 1 }}
            </td>
            <td>{{ $product->libraryTechnology->item_code ?? null }}</td>
            <td>{{ $product->libraryTechnology->developer->name ?? null }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->libraryTechnology->subscription_period ?? null }}</td>
            <td>{{ $product->pivot->price ?? null }}</td>
            <td>{{ $product->pivot->quantity ?? null }}</td>
            <td>{{ $product->pivot->price * $product->pivot->quantity }}</td>
            <td>{{ $product->subject ?? null }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>