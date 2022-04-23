<div>
    <div class="float-start mb-4 me-3">
        <input 

            type="text" 

            class="form-control rounded-pill bg-white"

            wire:model="term"
        />
    </div>
    <div class="float-start mb-4">
        <button type="button"  class="btn btn-info rounded-pill"> Filter</button>
    </div>
    <div class="float-end mb-4">
      <a href="{{route('quotations.create')}}" type="button"  class="btn btn-primary rounded-pill"><i class="bi bi-plus"></i> Add Quotation</a>
    </div>
    <table class="table text-center">
        <thead class="bg-info ">
            <tr>
                <th>Project Title</th>
                <th>PR Number</th>
                <th>Deadline</th>
                <th>Bidding Date</th>
                <th>Status</th>
                <th width="280px">Actions</th>
            </tr>
        </thead>
        @foreach ($quotations as $key => $quotation)
        <tr>
            <td>{{ $quotation->project_title }}</td>
            <td>{{ $quotation->pr_number }}</td>
            <td>{{ $quotation->deadline }}</td>
            <td>{{ $quotation->bidding_date }}</td>
            <td>
            @if($quotation->status == "pending")
                <div class="p-0 alert alert-warning fw-bold">Pending</div>
            @elseif($quotation->status == "approved")
                <div class="p-0 alert alert-success fw-bold">Approved</div>
            @else
                <div class="p-0 alert alert-danger fw-bold">Cancelled</div>
            @endif


            </td>
            <td>
                <div class="btn-group">
                    <a href="{{ route('quotations.show', $quotation->id) }}" type="button" class="btn btn-secondary rounded-3 py-1 px-3 bg-primary text-white">View</a>
                    <button type="button" class="btn btn-secondary  px-2 dropdown-toggle dropdown-toggle-split bg-white text-primary border" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                        <li><a class="dropdown-item"  href="{{ route('quotations.edit',$quotation->id) }}">Edit</a></li>
                        <li><a class="dropdown-item" href="#" wire:click="destroy({{ $quotation->id }})" >Delete</a></li>
                    </ul>
                </div>                             
            </td>
        </tr>
        @endforeach
    </table>
    <div class="pt-2">
        {{ $quotations->links() }}
    </div>
</div>
