<div class="table-responsive py-4">
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>S/N</th>
                <th>Publisher</th>
                <th>Platform</th>
                <th>Access Model</th>
                <th>Title</th>
                <th>Subscription Period</th>
                <th>Unit Price</th>
                <th>QTY</th>
                <th>Total Amount</th>
                <th>Subject</th>
            </tr>
        </thead>
        @foreach ($products as $key => $product)
        @if($product->type_id == 7)
        <tr>
            <td>
            {{ $key + 1 }}
            </td>
            <td>{{ $product->onlineDatabase->publisher->name ?? null }}</td>
            <td>{{ $product->onlineDatabase->platform->name ?? null }}</td>
            <td>{{ $product->onlineDatabase->accessModel->name ?? null }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->onlineDatabase->subscription_period ?? null }}</td>
            <td>{{ $product->pivot->price ?? null }}</td>
            <td>{{ $product->pivot->quantity ?? null }}</td>
            <td>{{ $product->pivot->price * $product->pivot->quantity }}</td>
            <td>{{ $product->subject ?? null }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>