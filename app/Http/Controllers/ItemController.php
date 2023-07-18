<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockMovement;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    public function index()
    {

    }

    public function count()
    {
        try{
            $countItem = Item::where('is_active', 1)->count();

            return response()->json([
                'success'   => true,
                'message'   => 'Count item data',
                'item'      => $countItem,
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function inflow()
    {
        try{
            $countInflow = StockMovement::where('transaction_type', 1)->count();

            return response()->json([
                'success'   => true,
                'message'   => 'Count inflow data',
                'inflow'    => $countInflow,
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function outflow()
    {
        try{
            $countOutflow = StockMovement::where('transaction_type', 2)->count();

            return response()->json([
                'success'   => true,
                'message'   => 'Count outflow data',
                'outflow'   => $countOutflow,
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function all(Request $request)
    {
        try{
            $data = Item::where('is_active', 1)->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('package', function($row) {
                        $packaging = $row->packaging;
                        $qty_per_packaging = $row->qty_per_packaging;
                        $spanPackaging = '<div>' . $qty_per_packaging . ' ' . $packaging .'</div>';
                        return $spanPackaging;
                    })
                    ->addColumn('stock', function($row) {
                        $spanQtyStock = '<div>' . $row->qty_stock .' Pcs</div>';
                        return $spanQtyStock;
                    })
                    ->addColumn('action', function($row) {
                        $actionBtn = '<div><a class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id .'"><i class="fa-solid fa-edit"></i></a> <a class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id .'"><i class="fa-solid fa-trash"></i></a></div>';
                        return $actionBtn;
                    })
                    ->rawColumns(['package', 'stock', 'action'])
                    ->make(true);

        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try{
            $getQtyStock = Item::select('qty_stock')->findOrFail($id);
            return response()->json([
                'success'   => true,
                'qty_stock' => $getQtyStock->qty_stock,
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try{
            $validated = Validator::make($request->all(), [
                'item_code'         => 'required|unique:items',
                'item_name'         => 'required',
                'packaging'         => 'required',
                'qty_per_packaging' => 'required|numeric'
            ],
            [
                'item_code.required'        => 'Item code must be filled.',
                'item_code.required'        => 'Item code was registered.',
                'item_name.required'        => 'Item name must be filled.',
                'packaging.required'        => 'Packaging must be filled.',
                'qty_per_packaging.required'=> 'Qty per packaging must be filed.',
                'qty_per_packaging.numeric' => 'Unumeric for qty per packaging input.'
            ]);

            if($validated->fails()){
                return response()->json([
                    'success'   => false,
                    'message'   => 'Something went wrong with your input.',
                    'errors'    => $validated->errors(),
                ], 400);
            }

            $increment = intval(substr($request->item_code, 4) + 1);
            $next_item_code = 'ITM' . str_pad($increment, 5, '0', STR_PAD_LEFT);

            Item::create([
                'item_code'         => $request->item_code,
                'item_name'         => $request->item_name,
                'unit_type'         => 'Pcs',
                'packaging'         => $request->packaging,
                'qty_per_packaging' => $request->qty_per_packaging,
                'created_by'        => $request->creator,
                'updated_by'        => $request->creator,
            ]);

            return response()->json([
                'success'   => true,
                'item_code' => $next_item_code,
                'message'   => 'New item successfully added.',
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try{
            Item::where('id', $id)->update([
                'item_name'         => $request->item_name,
                'packaging'         => $request->packaging,
                'qty_per_packaging' => $request->qty_per_packaging,
                'updated_by'        => $request->creator,
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Item successfully updated.',
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function import(Request $request)
    {
        try{
            $file = $request->file('file_import');
            $extension = strtolower($file->getClientOriginalExtension());

            if($extension == 'xlsx'){
                $render = new Xlsx();
            }elseif($extension == 'xls'){
                $render = new Xls();
            }else{
                return response()->json([
                    'success'   => false,
                    'message'   => 'File is not excel.',
                ], 422);
            }

            $spredsheet = $render->load($file);
            $data = $spredsheet->getActiveSheet()->toArray();

            foreach($data as $x => $row){
                if($x == 0){
                    continue;
                }

                $item_code = $row[0];
                $item_name = $row[1];
                $unit_type = $row[2];
                $packaging = $row[3];
                $qty_per_packaging = $row[4];

                $checkItemCode = Item::where('item_code', $item_code)->first();

                if($checkItemCode){
                    return response()->json([
                        'success'   => false,
                        'message'   => "That is an item code that has been registered.",
                    ], 422);
                }

                Item::create([
                    'item_code'         => $item_code,
                    'item_name'         => $item_name,
                    'unit_type'         => $unit_type,
                    'packaging'         => $packaging,
                    'qty_per_packaging' => $qty_per_packaging,
                    'created_by'        => $request->creator,
                    'updated_by'        => $request->creator,
                ]);
            }

            return response()->json([
                'success'   => true,
                'check'     => $checkItemCode,
                'message'   => 'File successfully imported',
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function delete(Request $request)
    {
        try{
            Item::where('id', $request->item_id)->update([
                'updated_by'    => $request->creator,
                'is_active'     => 2,
            ]);

            StockMovement::where('item_id', $request->item_id)->update([
                'updated_by'    => $request->creator,
                'is_active'     => 2,
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'Item was successfully deleted',
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function adjustment(Request $request)
    {
        try{
            $item = Item::select('item_code', 'item_name')->where('id', $request->item_id)->first();

            $validated = Validator::make($request->all(), [
                'transaction_date'  => 'required|date',
                'transaction_no'    => 'required',
                'transaction_type'  => 'required',
                'item_id'           => 'required',
                'qty_stock'         => 'required|numeric',
                'qty_adjust'        => 'required|numeric',
            ],
            [
                'transaction_date.required' => 'Transaction date must be filled.',
                'transaction_date.date'     => 'Transaction date input must be date format.',
                'transaction_no.required'   => 'Transaction ID must be filled.',
                'transaction_no.unique'     => 'Transaction ID has already registered.',
                'transaction_type.required' => 'Movement type must be selected.',
                'item_id.required'          => 'Item must be selected.',
                'qty_stock.required'        => 'Qty item stock must be filed.',
                'qty_stock.numeric'         => 'Unumeric for qty item stock input.',
                'qty_adjust.required'       => 'Qty item adjust must be filed.',
                'qty_adjust.numeric'        => 'Unumeric for qty item adjust input.'
            ]);

            if($validated->fails()){
                return response()->json([
                    'success'   => false,
                    'message'   => 'Something went wrong with your input.',
                    'errors'    => $validated->errors(),
                ], 400);
            }

            $qty_stock = intval($request->qty_stock);
            $qty_adjust = intval($request->qty_adjust);

            if($request->transaction_type == 1){
                $success = true;
                $status = 200;
                $qty = $qty_stock + $qty_adjust;
                $message = 'Item was successfully adjusted.';
            }elseif($request->transaction_type == 2){
                if($qty_stock > $qty_adjust){
                    $success = true;
                    $status = 200;
                    $qty = $qty_stock - $qty_adjust;
                    $message = 'Item was successfully adjusted.';
                }elseif($qty_stock == $qty_adjust){
                    $success = true;
                    $status = 200;
                    $qty = $qty_stock - $qty_adjust;
                    $message = 'Item was successfully adjusted.';
                }elseif($qty_stock < $qty_adjust){
                    $success = false;
                    $status = 422;
                    $qty = null;
                    $message = 'Input failed. Qty item adjust is greater than qty item stock. Try Again.';
                }
            }

            if($success){
                Item::where('id', $request->item_id)->update([
                    'qty_stock'     => $qty,
                    'updated_by'    => $request->creator,
                ]);

                StockMovement::create([
                    'item_id'           => $request->item_id,
                    'transaction_no'    => $request->transaction_no,
                    'transaction_date'  => $request->transaction_date,
                    'transaction_type'  => $request->transaction_type,
                    'qty'               => $request->qty_adjust,
                    'description'       => $request->description,
                    'created_by'        => $request->creator,
                    'updated_by'        => $request->creator,
                ]);
            }

            return response()->json([
                'success'       => $success,
                'message'       => $message,
                'updated_qty'   => $qty,
                'item'          => $item->item_code . ' ' . $item->item_name,
            ], $status);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function filter(Request $request)
    {
        try{
            $validated = Validator::make($request->all(), [
                'start_date'    => 'required|date',
                'end_date'      => 'required|date',
            ],
            [
                'start_date.required'   => 'Start date must be selected.',
                'end_date.required'     => 'End date must be selected.',
            ]);

            if($validated->fails()){
                return response()->json([
                    'success'   => false,
                    'message'   => 'Something went wrong with your input.',
                    'errors'    => $validated->errors(),
                ], 400);
            }

            if($request->transaction_type == 0){
                if($request->item_id == 0){
                    $data = StockMovement::join('items', 'items.id', '=', 'stock_movements.item_id')
                                        ->whereBetween('transaction_date', [$request->start_date, $request->end_date])
                                        ->where('stock_movements.is_active', 1)
                                        ->get();
                }else{
                    $data = StockMovement::join('items', 'items.id', '=', 'stock_movements.item_id')
                                        ->whereBetween('transaction_date', [$request->start_date, $request->end_date])
                                        ->where('item_id', $request->item_id)
                                        ->where('stock_movements.is_active', 1)
                                        ->get();
                }
            }else{
                if($request->item_id == 0){
                    $data = StockMovement::join('items', 'items.id', '=', 'stock_movements.item_id')
                                        ->whereBetween('transaction_date', [$request->start_date, $request->end_date])
                                        ->where('transaction_type', $request->transaction_type)
                                        ->where('stock_movements.is_active', 1)
                                        ->get();
                }else{
                    $data = StockMovement::join('items', 'items.id', '=', 'stock_movements.item_id')
                                        ->whereBetween('transaction_date', [$request->start_date, $request->end_date])
                                        ->where('transaction_type', $request->transaction_type)
                                        ->where('item_id', $request->item_id)
                                        ->where('stock_movements.is_active', 1)
                                        ->get();
                                    }
            }

            // $data = $item->filterItem($arrFilter);

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function($row) {
                        $transactionDate = date('d-m-Y', strtotime($row->transaction_date));
                        return $transactionDate;
                    })
                    ->addColumn('item', function($row) {
                        $itemCode = $row->item_code;
                        $itemName = $row->item_name;

                        $item = '<div>' . $itemCode . ' - ' . $itemName . ' </div>';
                        return $item;
                    })
                    ->addColumn('type', function($row) {
                        if($row->transaction_type == 1){
                            $type = '<div>In</div>';
                        }else{
                            $type = '<div>Out</div>';
                        }
                        return $type;
                    })
                    ->addColumn('qty', function($row) {
                        $qty = '<div>' . $row->qty . ' Pcs</div>';
                        return $qty;
                    })
                    ->rawColumns(['date', 'item', 'type', 'qty', 'description'])
                    ->make(true);

        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }
}
