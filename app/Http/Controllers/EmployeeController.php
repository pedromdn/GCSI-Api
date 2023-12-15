<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employee = Employee::all();
        return response()->json(['employee' => $employee]);
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id); 

        if ($employee->photo === null) {
            $employee->makeHidden('photo');
        } else {
            $employee->photo = url($employee->photo);
        } 

        return response()->json(['employee' => $employee]);
    }

    public function store(Request $request)
{
    Log::info('store Request Data2:', $request->all());

    $request->validate([
        'name' => 'required|max:50',
        'last_name1' => 'required|max:50',
        'last_name2' => 'required|max:50',
        'company' => 'required|max:100',
        'area' => 'required|max:100',
        'department' => 'required|max:100',
        'position' => 'required|max:100',
        'photo' => 'nullable',
        'status' => 'required|boolean',
    ]);

    $data = $request->all();
    $currentDate = date('Y-m-d');
    $data['startDate'] = $currentDate;

    if ($request->hasFile('photo')) {

        $image = $request->file('photo');
        $imageName = 'collaborator_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/collaborator_images'), $imageName);
        $data['photo'] = 'uploads/collaborator_images/' . $imageName;
    }  

    $employee = Employee::create($data);

    return response()->json(['employee' => $employee], 201);
}

public function update(Request $request, $id)
{
    Log::info('Update Request Data:', $request->all(), $id);
    $employee = Employee::findOrFail($id);

    $request->validate([
        'name' => 'required|max:50',
        'last_name1' => 'required|max:50',
        'last_name2' => 'required|max:50',
        'company' => 'required|max:100',
        'area' => 'required|max:100',
        'department' => 'required|max:100',
        'position' => 'required|max:100',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'status' => 'required|boolean',
    ]);

    $data = $request->except('startDate');

    if ($request->hasFile('photo')) {       

        $image = $request->file('photo');
        $imageName = 'collaborator_' . time() . '.' . $image->getClientOriginalExtension();
        $image->move('uploads/collaborator_images', $imageName);
        $data['photo'] = 'uploads/collaborator_images/' . $imageName;
    }

    $employee->update($data);

    return response()->json(['employee' => $employee]);
}


    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id); // Encuentra el modelo Employee por su ID
    
            $deletedEmployee = $employee->toArray(); // Guarda la información antes de eliminar
    
            $employee->delete();
    
            return response()->json(['employee' => $deletedEmployee], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el colaborador', 'status' => 'error'], 500);
        }
    }
    

}
