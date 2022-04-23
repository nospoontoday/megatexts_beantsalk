<div class="table-responsive py-4">
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>S/N</th>
                <th>ISBN-13</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Title/ED</th>
                <th>Pub Yr</th>
                <th>QTY</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total Amount</th>
                <th>Subject</th>
            </tr>
        </thead>
        @foreach ($products as $key => $product)
        @if($product->type_id == 1)
        <tr>
            <td>
            {{ $key + 1 }}
            </td>
            <td>{{ $product->printBook->isbn_13 ?? null }}</td>
            <td>{{ $product->printBook->author->name ?? null }}</td>
            <td>{{ $product->printBook->publisher->name ?? null }}</td>
            <td>{{ $product->title }}</td>
            <td>{{ $product->printBook->publication_year ?? null }}</td>
            <td>{{ $product->pivot->quantity ?? null }}</td>
            <td>{{ $product->pivot->price ?? null }}</td>
            <td>{{ $product->pivot->discount }}</td>
            <td>{{ ($product->pivot->price * $product->pivot->quantity) - $product->pivot->discount }}</td>
            <td>{{ $product->subject ?? null }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>