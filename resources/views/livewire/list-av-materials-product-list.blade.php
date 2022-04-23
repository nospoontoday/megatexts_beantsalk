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
            <th>Action</th>
        </tr>
    </thead>
    @if(! empty($products))
    @foreach ($products as $key => $product)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $product->avMaterial->issn ?? null }}</td>
        <td>{{ $product->avMaterial->author->name ?? null }}</td>
        <td>{{ $product->avMaterial->publisher->name ?? null }}</td>
        <td>{{ $product->title }}</td>
        <td>{{ $product->avMaterial->publication_year }}</td>
        <td>{{ $product->vendor_quantity }}</td>
        <td>{{ $product->vendor_price }}</td>
        <td>{{ $product->vendor_total_amount }}</td>  
        <td>{{ $product->subject }}</td>
    </tr>
    @endforeach
    @endif
</table>
<div class="pt-2">
    {{-- {{ $vendors->links() }} --}}
</div>