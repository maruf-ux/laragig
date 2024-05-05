<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;

class ListingController extends Controller
{
    public function index(Request $request)
    {

        $lists = Listing::latest()->filter(request(['tag', 'search']))->paginate(5);
        return view('website.listing.index', compact('lists'));
    }

    public function show(Listing $listing)
    {
        return view('website.listing.show', compact('listing'));
    }

    public function create()
    {
        return view('website.listing.create');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        $listing = Listing::create($formFields);

        return redirect('/')->with('message', 'Created a listing Successfully');
    }


    public function edit(Listing $listing)
    {
        return view('website.listing.edit', compact('listing'));
    }

    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403, 'unauthorized Action');
        }

        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return back()->with('message', 'listing Updated Successfully');
    }


    public function destroy(Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403, 'unauthorized Action');
        }

        $listing->delete();
        return redirect('/')->with('message', 'The Listing Deleted Successfully');
    }


    public function manage()
    {
        return view('website.listing.manage', [
            'listings' => auth()->user()->listings()->get()
        ]);
    }
}
