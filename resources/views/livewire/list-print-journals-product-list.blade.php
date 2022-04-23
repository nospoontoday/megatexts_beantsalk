<table class="table text-center">
    <thead class="bg-info ">
        <tr>
            <th>No</th>
            <th>ISSN</th>
            <th>Editor</th>
            <th>Title</th>
            <th>Issue No.</th>
            <th>Unit Price Per Issue</th>
            <th>QTY</th>
            <th>Total Amount</th>
            <th>Subject</th>
            <th>Action</th>
        </tr>
    </thead>
    @if(! empty($products))
    @foreach ($products as $key => $product)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $product->printJournal->issn ?? null }}</td>
        <td>{{ $product->printJournal->editor->name ?? null }}</td>
        <td>{{ $product->title }}</td>
        <td>{{ $product->printJournal->issue_number ?? null }}</td>
        <td>{{ $product->vendor_price }}</td>
        <td>{{ $product->vendor_quantity }}</td>
        <td>{{ $product->vendor_total_amount }}</td>  
        <td>{{ $product->subject }}</td>
    </tr>
    @endforeach
    @endif
</table>
<div class="pt-2">
    {{-- {{ $vendors->links() }} --}}
</div>