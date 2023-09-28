<?php

namespace App\Http\Controllers;

use App\Models\wirecode;
use App\Models\wirecode_active;
use App\Models\member_activate_wire;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WirecodeController extends Controller
{
    private $cname = "WirecodeController";

    public function index()
    {
        $tbl = wirecode::with(['product'])->get();
        return response()->json($tbl);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        try {
            wirecode::create($request->all());
            return $this->index();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $tbl = wirecode::with(['product'])->where("id", $id)->get();
        return response()->json($tbl);
    }

    public function search_data(Request $request)
    {
        $by = $request->by;
        $search = $request->search;
        $tbl = wirecode::with(['product'])->where($by, 'like', '%' . $search . '%')->get();
        return response()->json($tbl);
    }
    public function search_active_wire(Request $request)
    {
        $date = $request->date;
        $tbl = wirecode_active::with('wirecode')
            ->where("start_date", "<=", $date)
            ->where("end_date", ">=", $date)
            ->get();
        return response()->json($tbl);
    }
    public function fetch_wirecode_active(Request $request)
    {
        $tbl = wirecode_active::with('wirecode')
            ->orderBy('start_date', 'desc')
            ->take(20)
            ->get();;
        return response()->json($tbl);
    }

    public function fetch_member_active_wire(Request $request)
    {
        $tbl = member_activate_wire::with(['member.member_entry', 'order', 'wirecode_active.wirecode'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();
        return response()->json($tbl);
    }
    public function search_member_active_wire(Request $request)
    {
        $date = $request->date;
        $tbl = member_activate_wire::with(['member.member_entry', 'order', 'wirecode_active.wirecode'])
            ->whereHas("wirecode_active", function ($query) use ($date) {
                $query->where("start_date", "<=", $date);
                $query - where("end_date", ">=", $date);
            })
            ->get();
        return response()->json($tbl);
    }

    public function edit(wirecode $wirecode)
    {
        //
    }

    public function update(Request $request)
    {
        try {
            $cmd  = wirecode::findOrFail($request->id);
            $input = $request->all();
            $cmd->fill($input)->save();

            return $this->show($request->id);
        } catch (\Exception $ex) {

            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            wirecode::destroy($id);

            return $this->index();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function destroy1($id)
    {
        try {
            $tbl1 = wirecode::findOrFail($id);
            wirecode::destroy($id);

            return $this->index();
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function generateWireCode($date)
    {
        $start_date = new Carbon($date);
        $end_date = $start_date->copy()->addDays(6);

        $randomWire = wirecode::where('status', 'Active')
            ->inRandomOrder()
            ->first();

        $wirecode_active = new wirecode_active;
        $wirecode_active->wirecode_id = $randomWire->id;
        $wirecode_active->start_date = $start_date;
        $wirecode_active->end_date = $end_date;
        $wirecode_active->timestamps = false;
        $wirecode_active->save();

        return "ok";
    }

    public function get_active_wire()
    {
        //return "ccc";
        $date = new Carbon();
        $tbl = wirecode_active::with('wirecode')
            ->where("start_date", "<=", $date)
            ->where("end_date", ">=", $date)
            ->first();
        return $tbl;
    }
}
