<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\Vendor;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.salesOrder.index');
    }

    public function add_print_journals()
    {
        return view('auth.salesOrder.add_print_journals');
    }

    public function add_av_materials()
    {
        return view('auth.salesOrder.add_av_materials');
    }

    public function add_library_fixtures()
    {
        return view('auth.salesOrder.add_library_fixtures');
    }

    public function add_ebooks()
    {
        return view('auth.salesOrder.add_ebooks');
    }

    public function add_ejournals()
    {
        return view('auth.salesOrder.add_ejournals');
    } 

    public function add_online_databases()
    {
        return view('auth.salesOrder.add_online_databases');
    }
    public function add_library_technologies()
    {
        return view('auth.salesOrder.add_library_technologies');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $segment = $request->segment(2);
        $branches = Branch::get();

        switch ($segment) {
            case 'print-books':
                $componentName = 'list-print-book-sales-orders';
                $title = 'Print Books';
                break;
            case 'print-journals':
                $componentName = 'list-print-journal-sales-orders';
                $title = 'Print Journals';
                break;
            case 'av-materials':
                $componentName = 'list-av-material-sales-orders';
                $title = 'AV Materials';
                break;
            case 'library-fixtures':
                $componentName = 'list-library-fixture-sales-orders';
                $title = 'Library Fixtures';
                break;
            case 'e-books':
                $componentName = 'list-e-book-sales-orders';
                $title = 'EBooks';
                break;
            case 'e-journals':
                $componentName = 'list-e-journal-sales-orders';
                $title = 'EJournals';
                break;
            case 'online-databases':
                $componentName = 'list-online-database-sales-orders';
                $title = 'Online Databases';
                break;
            case 'library-technologies':
                $componentName = 'list-library-technology-sales-orders';
                $title = 'Library Technologies';
                break;
            default:
                $componentName = '';
                $title = '';
                break;
        }

        return view('auth.salesOrder.create', compact('branches', 'componentName', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salesOrder = SalesOrder::with([
                'customer',
                'branch',
                'customer.presentAddress',
                'customer.contact',
                'products',
                'products.printBook',
                'products.printJournal',
                'products.AVMaterial',
                'products.libraryFixture',
                'products.eBook',
                'products.eJournal',
                'products.onlineDatabase',
                'products.libraryTechnology',
            ])
            ->where('id', $id)
            ->first();

        return view('auth.salesOrder.show', compact('salesOrder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
