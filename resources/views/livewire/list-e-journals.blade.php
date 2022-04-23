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
            <button type="button"  class="btn btn-info rounded-pill"> Filter</button>
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
                <input wire:model="selectPageRows" type="checkbox" value="" name="e_journal_ids" id="e_journal_ids">
                <label for="e_journal_ids"></label>
              </th>
              <th>No</th>
              <th>E-ISSN</th>
              <th>Editor</th>
              <th>Publisher</th>
              <th>Platform</th>
              <th>Access Model</th>
              <th>Title</th>
              <th>Frequency</th>
              <th>Subscription Period</th>
              <th>Unit Price</th>
              <th>QTY</th>
              <th>Total Amount</th>
              <th>Subject</th>
              <th>Action</th>
            </tr>
          </thead>
          @foreach ($eJournals as $key => $eJournal)
          <tr>
            <td>
              <input wire:model="selectedRows" type="checkbox" value="{{ $eJournal->id }}" name="e_journal_ids" id="{{ $eJournal->id }}">
              <label for="{{ $eJournal->id }}"></label>
            </td>
            <td>{{ ($eJournals ->currentpage()-1) * $eJournals ->perpage() + $key + 1 }}</td>
            <td>{{ $eJournal->e_issn }}</td>
            <td>{{ $eJournal->editor->name }}</td>
            <td>{{ $eJournal->publisher->name }}</td>
            <td>{{ $eJournal->platform->name }}</td>
            <td>{{ $eJournal->accessModel->name }}</td>
            <td>{{ $eJournal->product->title }}</td>
            <td>{{ $eJournal->frequency }}</td>
            <td>{{ $eJournal->subscription_period }}</td>
            <td>{{ $eJournal->product->price }}</td>
            <td>{{ $eJournal->product->quantity }}</td>
            <td>{{ $eJournal->total_amount }}</td>
            <td>{{ $eJournal->product->subject }}</td>
            <td>
              <div class="btn-group">
                <button type="button" class="btn bg-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                  Action
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ route('digital.e-journals.show',$eJournal->id) }}">Show</a></li>
                  <li><a class="dropdown-item"  href="{{ route('digital.e-journals.edit',$eJournal->id) }}">Edit</a></li>
                  <li><a class="dropdown-item" href="#" wire:click="destroy({{ $eJournal->id }})">Delete</a></li>
                </ul>
              </div>    
            </td>
          </tr>
        @endforeach
          </table>
          <div class="p-4">
            {{ $eJournals->links() }}
          </div>
      </div>
</div>
