<div class="table-responsive py-4">
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
            </tr>
        </thead>
        @foreach ($products as $key => $product)
        @if($product->type_id == 2)
        <tr>
            <td>
            {{ $key + 1 }}
            </td>
            <td>{{ $product->printJournal->issn ?? null }}</td>
            <td>{{ $product->printJournal->editor->name ?? null }}</td>
            <td>{{ $product->title ?? null }}</td>
            <td>{{ $product->printJournal->issue_number ?? null }}</td>
            <td>{{ $product->pivot->price ?? null }}</td>
            <td>{{ $product->pivot->quantity ?? null }}</td>
            <td>{{ $product->pivot->price * $product->pivot->quantity }}</td>
            <td>{{ $product->subject ?? null }}</td>
        </tr>
        @endif
        @endforeach
    </table>
</div>