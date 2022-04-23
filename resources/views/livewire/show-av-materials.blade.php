<div class="table-responsive py-4">
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>No</th>
                <th>Item Code</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Title</th>
                <th>Pub Yr</th>
                <th>QTY</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total Amount</th>
                <th>Subject</th>
            </tr>
        </thead>
        @foreach ($products as $key => $product)
        @if($product->type_id == 3)
        <tr>
            <td>
            {{ $key + 1 }}
            </td>
            <td>{{ $product->avMaterial->item_code ?? null }}</td>
            <td>{{ $product->avMaterial->author->name ?? null }}</td>
            <td>{{ $product->avMaterial->publisher->name ?? null }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->avMaterial->publication_year ?? null }}</td>
            <td>{{ $product->pivot->quantity ?? null }}</td>
            <td>{{ $product->pivot->price ?? null }}</td>
            <td>{{ $product->pivot->discount ?? null }}</td>
            <td>{{ $product->pivot->price * $product->pivot->quantity }}</td>
            <td>{{ $product->subject ?? null }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>