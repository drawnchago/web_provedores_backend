<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationConcept;
use App\Models\WorkOrder;
use App\Models\User;

class SalesProcessController extends Controller
{
    
    public function __construct()
    {
        //
    }

    public function getQuotations(Request $req){
        $response = ['success' => true];

        $quotations = Quotation::from('Tbl_Sales_Quotations as q')->with('customer')->with('concepts')->whereRaw("date(q.created_at) between date('$req->start_date') and date('$req->end_date')")
        ->orderBy('q.updated_at','desc')          
        ->get()
        ->map(function($row){

            $row->created = User::where('id',$row->created_by)->first()->name;
            $order_ids = WorkOrder::where('quotation_id',$row->id)->select(DB::raw("GROUP_CONCAT(id) as ids"))->first();
            $row->order_ids = array_map('intval', explode(',',$order_ids->ids)); 

            return $row;
        });

        $response['info'] = $quotations;

        return response()->json($response);
    }

    public function saveQuotation(Request $req){
        $response = ['success' => true, 'message' => 'Cotización guardada exitosamente'];

        try{
            $quotation = Quotation::updateOrCreate([
                'id' => $req->id
            ], $req->all());

            if($req->order_ids){
                WorkOrder::where('quotation_id',$quotation->id)
                ->update([
                    'quotation_id' => null
                ]);

                WorkOrder::whereIn('id',$req->order_ids)
                ->update([
                    'quotation_id' => $quotation->id
                ]);
            }
    
            //Borramos conceptos en caso de que sea actualizar
            QuotationConcept::where('quotation_id',$quotation->id)->delete();
            foreach($req->concepts as $concept){
                $concept['quotation_id'] = $quotation->id;
                $concept['created_by'] = $quotation->created_by;
                $concept['updated_by'] = $quotation->updated_by;
                QuotationConcept::create($concept);
            }

            $quotation = Quotation::with('customer')->with('concepts')->where('id',$quotation->id)->first();

            $response['quotation'] = $quotation;
    
        }catch(\Exception $e){
            $response['success'] = false;
            $response['message'] = 'Ha ocurrido un error al guardar la cotización';
            $response['error'] = $e->getMessage();
        }
        return response()->json($response);
    }

    public function getLastFolioQuotation(){
        $response = ['success' => true];

        $id = Quotation::max('id') + 1;

        $response['id'] = $id;

        return response()->json($response);
    }

    public function deleteQuotation(){
        $response = ['success' => true];

        Quotation::where('id',$req->id)->update([
            'status' => 0
        ]);

        $response['message'] = 'Cotización eliminada con éxito';

        return response()->json($response);
    }

    public function getWorkOrdersNotAssigned(Request $req){
        $response = ['success' => true];

        $orders = WorkOrder::from('Tbl_Op_WorkOrders as wo')->join('Tbl_Op_WorkOrderEquipmentEntrys as e','e.id','wo.entry_id')
        ->whereRaw($req->edit ? "(quotation_id is null || quotation_id = $req->quotation_id)" : 'quotation_id is null')->where('customer_id',$req->customer_id)
        ->select('wo.id','e.folio_equipment')
        ->get();

        $response['orders'] = $orders;

        return response()->json($response);
    }
    
}
