<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // LIST
    public function index()
    {
        $agents = Agent::latest()->get();
        return view('backend.pages.fixed.agents.index', compact('agents'));
    }

    // CREATE FORM
    public function create()
    {
        return view('backend.pages.fixed.agents.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['nullable','string','max:20'],
            'email'   => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:255'],
        ]);

        Agent::create($request->only('name','phone','email','address'));

        return redirect()->route('agents.index')->with('success', 'Agent created.');
    }

    // EDIT FORM
    public function edit($id)
    {
        $agent = Agent::findOrFail($id);
        return view('backend.pages.fixed.agents.edit', compact('agent'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['nullable','string','max:20'],
            'email'   => ['nullable','email','max:255'],
            'address' => ['nullable','string','max:255'],
        ]);

        $agent = Agent::findOrFail($id);
        $agent->update($request->only('name','phone','email','address'));

        return redirect()->route('agents.index')->with('success', 'Agent updated.');
    }

    // DELETE
    public function destroy($id)
    {
        Agent::findOrFail($id)->delete();
        return redirect()->route('agents.index')->with('success', 'Agent deleted.');
    }
}
