<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{   
    // Mendapatkan semua data resources
     public function index(){
        $employees = Employee::all();
    
        if ($employees->isEmpty()) {
            $data = [
                'message' => 'Data is empty',
            ];
        return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Get all resources',
                'data' => $employees
            ];
            return response()->json($data, 200);
        }
    }

    // Mendapatkan detail resource
    public function show($id){  
    // Mencari id resource yang ingin di tampilkan
        $employee = Employee::find($id); 

        if ($employee) {
            $data = [
                'message' => 'Get detail resource', 
                'data' => $employee,
            ];
            // Mengembalikan kode reponse json 200 (berhasil)
            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Resource not found',
            ];
            // Mengembalikan kode response json 404 (not found)
            return response()->json($data, 404);
        }
    }

    // Menambahkan data resource
    public function store(Request $request){
        // validasi data request
        $validatedData = $request->validate([
            "name" => "required",
            "gender" => ["required", Rule::in(['laki-laki', 'perempuan'])], 
            "phone" => "required",
            "address" => "required",
            "email" => "required|email",
            "status" => ["required", Rule::in(['active', 'inactive', 'terminated'])],
            "hired_on" => "required|date_format:d/m/Y"
        ]);

        // Format penanggalan pada hired_on 
        $hired_on = $this->formatDate($validatedData['hired_on']);

        // Jika input tanggal pada hired_on salah, maka akan mengembalikan kode repsonse json 422, dan sebuah message
        if ($hired_on === false) {
            return response()->json(['message' => 'Invalid date format'], 422);
        }

        // Memvalidasi hired_on
        $validatedData['hired_on'] = $hired_on;

        $employee = Employee::create($validatedData);
        // Sukses menambahkan data
        $data = [
            'message' => 'Resource is added succesfully',
            'data' => $employee,
        ];

        // Mengembalikan kode response json 201 (sukses)
        return response()->json($data, 201);
    }

    // Mengupdate data resource
    public function update(Request $request, $id){ 
        // Mencari id resource yang ingin di update
        $employee = Employee::find($id);

        // Mencgecek data resource yang ingin di update
        if ($employee) {
            $input = [
                'name' => $request->input('name') ?? $employee->name,
                'gender' => $request->input('gender') ?? $employee->gender,
                'phone' => $request->input('phone') ?? $employee->phone,
                'address' => $request->input('address') ?? $employee->address,
                'email' => $request->input('email') ?? $employee->email,
                'status' => $request->input('status') ?? $employee->status,
                'hired_on' => $request->input('hired_on') ?? $employee->status,
            ];
            // Mengecek format penanggalan hired_on
            if ($request->has('hired_on') && $request->input('hired_on') !== null) {
                $hired_on = $this->formatDate($request->input('hired_on'));

                if ($hired_on !== false) {
                    $input['hired_on'] = $hired_on;
                } else {
                    return response()->json(['message' => 'Invalid date format'], 422);
                }
            }
            
            $employee->update($input);
            
            // Data resource berhasil di update
            $data = [
                'message' => 'Resource is updated successfully',
                'data' => $employee,
            ];

            return response()->json($data, 200);
        // Jika id yang ingin dimasukkan tidak sesuai maka akan mengembalikan response code json 404 (not found)
        } else {
            $data = [
                'message' => 'Resource not found',
            ];

            return response()->json($data, 404);
        }
    }

    // Menghapus data resource
    public function destroy($id) {
        $employee = Employee::find($id);
        if ($employee) {
            #hapus employee tersebut 
            $employee->delete();
            $data = [
                'message' => 'Resource is deleted successfully'
            ];       
            #mengembalikan data (json) dan kode 200 
            return response()->json     ($data, 200);
        }
        else {
            $data = [
                'message' => 'Resource not found'
            ];       
            return response()->json($data, 404);
        }
    }

    // Mencari data resource berdasarkan nama
    public function search($name)
    {
        return  Employee::where('name', 'like', '%' . $name . '%')->get();
    }
    
    // Menampilkan data resource berdasarkan status (active, inactive, terminated)
    public function status($status){
        $employees = Employee::where('status', $status)->get();

        if ($employees->isEmpty()) {
            $data = [
                'message' => 'Data not found',
            ];
        return response()->json($data, 200);
        } else {
            return response()->json($employees, 200);
        }
    }

    // Function untuk format penanggalan yang nanti akan dipanggil pada function store dan update
    public function formatDate($date)
    {
        $timestamp = strtotime($date);
        return $timestamp !== false ? date('Y-m-d', $timestamp) : false;
    }

}