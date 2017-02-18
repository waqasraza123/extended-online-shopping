<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Laptop;
use Illuminate\Http\Request;
use Session;

class LaptopController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $laptop = Laptop::paginate(25);

        return view('shopkeepers.laptop.index', compact('laptop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('shopkeepers.laptop.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        Laptop::create($requestData);

        Session::flash('flash_message', 'Laptop added!');

        return redirect('products/laptop');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $laptop = Laptop::findOrFail($id);

        return view('shopkeepers.laptop.show', compact('laptop'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $laptop = Laptop::findOrFail($id);

        return view('shopkeepers.laptop.edit', compact('laptop'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        
        $requestData = $request->all();
        
        $laptop = Laptop::findOrFail($id);
        $laptop->update($requestData);

        Session::flash('flash_message', 'Laptop updated!');

        return redirect('products/laptop');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Laptop::destroy($id);

        Session::flash('flash_message', 'Laptop deleted!');

        return redirect('products/laptop');
    }
}
