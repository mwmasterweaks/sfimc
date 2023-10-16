<?php

namespace App\Http\Controllers;

use App\Models\wirecode;
use App\Models\wirecode_active;
use App\Models\member_activate_wire;
use App\Models\wirecode_gen;
use App\Models\wirecode_list;
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
                $query->where("end_date", ">=", $date);
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

    public function generateWireCode($date) //generate active wire
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

    public function fetch_wirecode_gen()
    {
        $tbl = wirecode_gen::with(['wirecode_list', 'center', 'wirecode'])
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();;
        return response()->json($tbl);
    }

    public function issue_wirecode(Request $request)
    {
        try {
            $id = $request->CodeID;
            $issued_to = $request->IssuedToMemberEntryID;
            $issued_by = Session('ADMIN_ACCOUNT_ID');
            DB::beginTransaction();

            //check first if not use
            $check = wirecode_list::where("id", $id)
                ->where("status", "available")
                ->first();

            if ($check != null) {
                wirecode_list::where("id", $id)
                    ->update([
                        "issued_to" => $issued_to,
                        "issued_by" => $issued_by,
                        "status" => "issued"
                    ]);
                //update wirecode gen used +1
                wirecode_gen::where("id", $check->wirecode_gen_id)
                    ->increment("code_issued");

                $RetVal['Response'] = "success";
                $RetVal['ResponseMessage'] = "Codes has been issued successfully.";
            } else {
                $RetVal['Response'] = "failed";
                $RetVal['ResponseMessage'] = "Code has already been used";
            }

            DB::commit();
            return response()->json($RetVal);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }
    public function fetch_wirecode_list(Request $request)
    {

        $Limit = config('app.ListRowLimit');
        $PageNo = $request["PageNo"];
        $CenterID = 0;
        if ($request["CenterID"]) {
            $CenterID = $request["CenterID"];
        }
        $status = "";
        if ($request["Status"]) {
            $status = $request["Status"];
        }
        $SearchText = $request["SearchText"];

        $query = wirecode_list::with(['wirecode_gen.center', 'issued_to.member_entry', 'issued_by'])
            ->orderBy('status')
            ->orderBy('id');
        if ($CenterID > 0) {
            $query->whereHas('wirecode_gen', function ($qqq) use ($CenterID) {
                $qqq->where('center_id', $CenterID);
            });
        }

        if ($status != "") {
            $query->where('status', $status);
        }

        if ($SearchText != '') {
            $query->where('code', 'like', "%" . $SearchText . "%");
        }
        if ($Limit > 0) {
            $query->limit($Limit);
            $query->offset(($PageNo - 1) * $Limit);
        }
        return response()->json($query->get());
    }
    public function wirecode_activate(Request $request)
    {
        try {
            DB::beginTransaction();
            //code...

            $code = $request->code;
            $memberID = Session('MEMBER_ID');
            $datenow = new Carbon();
            $RetVal['Response'] = "";
            $RetVal['ResponseMessage'] = "";
            //get active wirecode in wirecode_active
            $active_wire = wirecode_active::with(["wirecode"])
                ->where('start_date', "<=", $datenow->toDateString())
                ->where('end_date', ">=", $datenow->toDateString())
                ->first();
            //$wirecode = $active_wire->wirecode;

            //get wirecode_gen and wirecode_list base on code
            $wirecode_list = wirecode_list::with(['wirecode_gen'])
                ->where("code", $code)
                ->first();
            // $wirecode_gen = wirecode_gen::with(['wirecode_list'])
            //     ->whereHas("wirecode_list", function ($query) use ($code) {
            //         $query->where("code", $code);
            //     })
            //     ->first();
            //return $wirecode_gen;
            if ($wirecode_list != null) {
                $wirecode_gen = $wirecode_list->wirecode_gen;

                //check if wirecode_gen wirecode_id = wirecode_active wirecode_id
                if ($wirecode_gen->wirecode_id == $active_wire->wirecode_id) {
                    //check and (update wirecode_list status to used) if status in wirecode_list in not used
                    $check_update = wirecode_list::where('id', $wirecode_list->id)
                        ->where('status', '!=', 'used')
                        ->update(['status' => 'used']);

                    if ($check_update > 0) {
                        //Records were updated
                        //update wirecode_gen code_used + 1  
                        wirecode_gen::where("id", $wirecode_gen->id)
                            ->increment("code_used");
                        //insert member_activate_wire
                        $member_activate_wire = new member_activate_wire;
                        $member_activate_wire->memberID = $memberID;
                        $member_activate_wire->wirecode_active_id = $active_wire->id;
                        $member_activate_wire->wirecode_list_id = $wirecode_list->id;
                        $member_activate_wire->save();
                        $RetVal['Response'] = "success";
                        $RetVal['ResponseMessage'] = "Code activated successfuly";
                    } else {
                        // No records were updated
                        $RetVal['Response'] = "failed";
                        $RetVal['ResponseMessage'] = "Code provided already used";
                    }
                } else {
                    //this is not the active code
                    $RetVal['Response'] = "failed";
                    $RetVal['ResponseMessage'] = "The code provided is not the active wirecode on this week";
                }
            } else {
                $RetVal['Response'] = "failed";
                $RetVal['ResponseMessage'] = "The code provided is not valid";
            }
            DB::commit();
            return response()->json($RetVal);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function generate_wire(Request $request) //generate wirecode
    {
        try {
            DB::beginTransaction();

            $datenow = new Carbon();
            $count = $request->code_count;

            $wirecode_gen = new wirecode_gen;
            $wirecode_gen->wirecode_id = $request->wirecode_id;
            $wirecode_gen->wirecode_active_id = $request->wirecode_active_id;
            $wirecode_gen->date_gen = $datenow;
            $wirecode_gen->center_id = $request->center_id;
            $wirecode_gen->created_by = $request->created_by;
            $wirecode_gen->code_count = $count;
            $wirecode_gen->save();

            for ($x = 0; $x < $count; $x++) {
                $code = $this->random_string(12);
                $wirecode_list = new wirecode_list;
                $wirecode_list->wirecode_gen_id = $wirecode_gen->id;
                $wirecode_list->code = strtoupper($code);
                $wirecode_list->timestamps = false;
                $wirecode_list->save();
            }
            DB::commit();
            return "ok";
        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function get_active_wire()
    {
        //return "ccc";
        $date = new Carbon();
        $tbl = wirecode_active::with('wirecode')
            ->where("start_date", "<=", $date->toDateString())
            ->where("end_date", ">=", $date->toDateString())
            ->first();

        if ($tbl != null) {
            $member_activate_wire = member_activate_wire::where('memberID', Session('MEMBER_ID'))
                ->where('wirecode_active_id', $tbl->id)
                ->first();
            if ($member_activate_wire != null)
                $tbl->member_activate_wire = $member_activate_wire;
            else
                $tbl->member_activate_wire = null;
        }
        return $tbl;
    }

    function random_string($length)
    {
        return substr(sha1(time() . "" . random_int(100000, 999999)), 5, $length);
    }
}
