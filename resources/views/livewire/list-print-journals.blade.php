<div>
    <div class="clearfix">
        <div class="float-start mb-4 me-3">
            <input 
                type="text" 
                class="form-control rounded-pill bg-white"

                wire:model="term"
            />
        </div>
        <div class="float-start mb-4">
            <button button type="button"  class="btn btn-info rounded-pill"> Filter</button>
            @if($selectedRows)
            <a wire:click.prevent="export" class="btn btn-primary rounded-pill" href="#"><i class="bi bi-arrow-down"></i>Export</a>
            <span class="ml-2">selected {{ count($selectedRows) }} {{ Str::plural('Vendor', count($selectedRows)) }}</span>
            @endif
        </div>
        <div class="float-end mb-4">
            <button type="button"  class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addproduct"><i class="bi bi-plus"></i> Add Product</button>
            <button data-bs-toggle="modal" data-bs-target="#import" class="btn btn-primary rounded-pill"><i class="bi bi-arrow-down"></i> Import</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table text-center">
            <thead class="bg-info ">
                <tr>
                    <th>
                        <input wire:model="selectPageRows" type="checkbox" value="" name="print_journal_ids" id="print_journal_ids">
                        <label for="print_journal_ids"></label>
                    </th>
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
            @foreach ($printJournals as $key => $printJournal)
            <tr>
                <td>
                    <input wire:model="selectedRows" type="checkbox" value="{{ $printJournal->id }}" name="print_journal_ids" id="{{ $printJournal->id }}">
                    <label for="{{ $printJournal->id }}"></label>
                </td>
                <td>{{ ($printJournals ->currentpage()-1) * $printJournals ->perpage() + $key + 1 }}</td>
                <td>{{ $printJournal->issn }}</td>
                <td>{{ $printJournal->editor->name ?? null }}</td>
                <td>{{ $printJournal->product->title ?? null }}</td>
                <td>{{ $printJournal->issue_number }}</td>
                <td>{{ $printJournal->product->price ?? null }}</td>
                <td>{{ $printJournal->product->quantity ?? null }}</td>
                <td>{{ $printJournal->total_amount }}</td>  
                <td>{{ $printJournal->product->subject ?? null }}</td>
                <td>
                <div class="btn-group">
                    <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                    </button>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('print.print-journals.show',$printJournal->id) }}">Show</a></li>
                    <li><a class="dropdown-item"  href="{{ route('print.print-journals.edit',$printJournal->id) }}">Edit</a></li>
                    <li><li><a class="dropdown-item" href="#" wire:click="destroy({{ $printJournal->id }})" >Delete</a></li></li>
                    </ul>
                </div>    
                </td>
            </tr>
            @endforeach
        </table>
        <div class="pt-2">
            {!! $printJournals->render() !!}
        </div>
    </div>
</div>
