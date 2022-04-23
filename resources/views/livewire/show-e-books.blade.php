<div class="table-responsive py-4">
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>S/N</th>
                <th>E-ISBN</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Platform</th>
                <th>Access Model</th>
                <th>Title</th>
                <th>Pub Yr</th>
                <th>QTY</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Subject</th>
            </tr>
        </thead>
        @foreach ($products as $key => $product)
        @if($product->type_id == 5)
        <tr>
            <td>
            {{ $key + 1 }}
            </td>
            <td>{{ $product->eBook->e_isbn ?? null }}</td>
            <td>{{ $product->eBook->author->name ?? null }}</td>
            <td>{{ $product->eBook->publisher->name ?? null }}</td>
            <td>{{ $product->eBook->platform->name ?? null }}</td>
            <td>{{ $product->eBook->accessModel->name ?? null }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->eBook->publication_year ?? null }}</td>
            <td>{{ $product->pivot->quantity ?? null }}</td>
            <td>{{ $product->pivot->price ?? null }}</td>
            <td>{{ $product->pivot->price * $product->pivot->quantity }}</td>
            <td>{{ $product->subject ?? null }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>