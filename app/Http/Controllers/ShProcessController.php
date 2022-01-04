<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\PurchaseRequisitions;
use App\Models\RequisitionDetails;
use App\Models\RequisitionAuthorizations;
use App\Models\CommentsOnRequisitions;
use App\Models\Levels;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class ShProcessController  extends Controller
{
    public function __construct()
    {
        //
    }
    //** Agrega el proveedor a la orden de compra */
    public function addProvider(Request $req)
    {
        try {
            PurchaseOrder::where('id', $req->id)->update([
                'provider_id' => $req->provider_id,
            ]);
            $response['success'] = true;
            $response['message'] = "Se Agrego el Proveedor";
        } catch (\Throwable $e) {
            $response['success'] = false;
            $response['message'] = "Error: " . $e->getMessage();
        }
        return $response;
    }
    //** Aprueba o rechaza la requisicion */
    public function approveOrDenyRequisition(Request $req)
    {
        try {

            if ($req->type == 1) {
                PurchaseRequisitions::where('id', $req->id)->update([
                    'status' => 2,
                ]);
                RequisitionAuthorizations::create([
                    'purchase_requisition_id' => $req->id,
                    'user_id' => $req->user_id,
                    'status' => 1,
                    'created_by' => $req->user_id,
                ]);
                $order = PurchaseOrder::create([
                    "purchase_requisition_id" => $req->id,
                    "status" => 1,
                    "created_by" => $req->user_id,
                ]);
                $requistion_details = RequisitionDetails::where('purchase_requisition_id', $req->id)
                    ->select('id', 'product_id', 'unit_price', 'quantity', 'subtotal')->get();
                foreach ($requistion_details as $r) {
                    $purchase_order_details =  PurchaseOrderDetail::create([
                        "purchaseorder_id" => $order->id,
                        "purchaserequisitions_id" => $req->id,
                        "product_id" => $r->product_id,
                        "unit_price" => $r->unit_price,
                        "quantity" => $r->quantity,
                        "total" => $r->subtotal,
                        "status" => 1,
                    ]);
                }
                $subtotal = PurchaseOrderDetail::where('purchaseorder_id', $order->id)->sum('tbl_sho_purchaseorderdetails.unit_price');
                $total = $subtotal + ($subtotal  * .16);
                PurchaseOrder::where('id', $order->id)->update([
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);
                $response['success'] = true;
                $response['message'] = "Se aprobo la requicisión: " . $req->id;
            } else {
                PurchaseRequisitions::where('id', $req->id)->update([
                    'status' => 0,
                ]);
                /*          RequisitionAuthorizations::create([
                    'purchase_requisition_id' => $req->id,
                    'user_id' => $req->user_id,
                    'status' => 1,
                    'created_by' => $req->user_id,
                ]); */
                $response['success'] = true;
                $response['message'] = "Se rechazó la requicisión: " . $req->id;
            }
        } catch (\Throwable $e) {
            $response['success'] = false;
            $response['message'] = "Error: " . $e->getMessage();
        }
        return $response;
    }
    //** Autoriza o rechaza la orden de compra */
    public function authorizeOrDeny(Request $req)
    {
        try {

            if ($req->type == 1) {
                PurchaseOrder::where('id', $req->id)->update([
                    'status' => 2,
                    'authorization_date' => Carbon::now(),
                    'authorization_by' => $req->user_id,
                ]);
                /*        RequisitionAuthorizations::create([
                    'purchase_requisition_id' => $req->id,
                    'user_id' => $req->user_id,
                    'status' => 1,
                    'created_by' => $req->user_id,
                ]); */
                /* $order = PurchaseOrder::create([
                    "purchase_requisition_id" => $req->id,
                    "status" => 1,
                    "created_by" => $req->user_id,
                ]); */
                $response['success'] = true;
                $response['message'] = "Se aprobo la requicisión: " . $req->id;
            } else {
                PurchaseOrder::where('id', $req->id)->update([
                    'status' => 0,
                ]);
                $response['success'] = true;
                $response['message'] = "Se rechazó la requicisión: " . $req->id;
            }
        } catch (\Throwable $e) {
            $response['success'] = false;
            $response['message'] = "Error: " . $e->getMessage();
        }
        return $response;
    }
    //** Trae las requisiciones por area y que estan por autorizar */
    public function getPurchaseRequsitionsByArea(Request $req)
    {

        $response = ['success' => false, 'message' => "No se encontrarón registros"];
        $level = Levels::select('id', 'area_id', 'user_id')->where('user_id', $req->user_id)->get();
        $purchase_requisitions = PurchaseRequisitions::Leftjoin('users as mod', 'mod.id', 'Tbl_Sho_PurchaseRequisitions.updated_by')
            ->Leftjoin('users as created', 'created.id', 'Tbl_Sho_PurchaseRequisitions.created_by')
            ->Leftjoin('Tbl_Cat_Areas as area', 'area.id', 'Tbl_Sho_PurchaseRequisitions.area_id')
            ->where('Tbl_Sho_PurchaseRequisitions.area_id', $level[0]->area_id)
            ->where('Tbl_Sho_PurchaseRequisitions.status', 1)
            ->select('Tbl_Sho_PurchaseRequisitions.id', 'area.id as area_id', 'area.description as desc_area', 'Tbl_Sho_PurchaseRequisitions.comments', 'Tbl_Sho_PurchaseRequisitions.status', 'mod.username as updated_by', 'Tbl_Sho_PurchaseRequisitions.updated_at', 'created.username as created_by', 'Tbl_Sho_PurchaseRequisitions.created_at')->get();

        if (count($purchase_requisitions) > 0) {
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['purchase_requisitions'] = $purchase_requisitions;

        return $response;
    }
    //** Trae los detalles de la orden de compra */
    public function getPurchaseOrdersDetails(Request $req)
    {
        $id      = $req->id;
        $area_id = $req->area_id;

        $purchase_order_details = PurchaseOrderDetail::Leftjoin('users as mod', 'mod.id', 'Tbl_Sho_PurchaseOrderDetails.updated_by')
            ->Leftjoin('users as created', 'created.id', 'Tbl_Sho_PurchaseOrderDetails.created_by')
            ->Leftjoin('Tbl_Cat_Products as product', 'product.id', 'Tbl_Sho_PurchaseOrderDetails.product_id')
            ->where('Tbl_Sho_PurchaseOrderDetails.purchaseorder_id', $id)
            ->where('Tbl_Sho_PurchaseOrderDetails.status', 1)
            ->select(
                'Tbl_Sho_PurchaseOrderDetails.id',
                'product.id as product_id',
                'product.description as desc_product',
                'Tbl_Sho_PurchaseOrderDetails.unit_price',
                'Tbl_Sho_PurchaseOrderDetails.quantity',
                'Tbl_Sho_PurchaseOrderDetails.status',
                'mod.username as updated_by',
                'Tbl_Sho_PurchaseOrderDetails.updated_at',
                'created.username as created_by',
                'Tbl_Sho_PurchaseOrderDetails.created_at'
            )
            ->get();
        $subtotal = PurchaseOrderDetail::where('purchaseorder_id', $id)->sum('Tbl_Sho_PurchaseOrderDetails.unit_price');

        $total = $subtotal + ($subtotal  * .16);
        if (count($purchase_order_details) > 0) {
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['purchase_order_details']    = $purchase_order_details;
        $response['subtotal']                  = $subtotal;
        $response['iva']                       = '16%';
        $response['total']                     = $total;

        return $response;
    }
    public function getPurchaseRequsitions(Request $req)
    {

        try {
            $user = User::select('id', 'role_id')->where('id', $req->user_id)->get();
            if ($user[0]->role_id == 0) {
                $purchase_requisitions = PurchaseRequisitions::Leftjoin('users as mod', 'mod.id', 'Tbl_Sho_PurchaseRequisitions.updated_by')
                    ->Leftjoin('users as created', 'created.id', 'Tbl_Sho_PurchaseRequisitions.created_by')
                    ->Leftjoin('Tbl_Cat_Areas as area', 'area.id', 'Tbl_Sho_PurchaseRequisitions.area_id')
                    ->orderBy('Tbl_Sho_PurchaseRequisitions.status', 'desc')
                    ->select('Tbl_Sho_PurchaseRequisitions.id', 'area.id as area_id', 'area.description as desc_area', 'Tbl_Sho_PurchaseRequisitions.comments', 'Tbl_Sho_PurchaseRequisitions.status', 'mod.username as updated_by', 'Tbl_Sho_PurchaseRequisitions.updated_at', 'created.username as created_by', 'Tbl_Sho_PurchaseRequisitions.created_at')->get();
            } else {
                $level = Levels::select('id', 'area_id', 'user_id')->where('user_id', $req->user_id)->get();
                $purchase_requisitions = PurchaseRequisitions::Leftjoin('users as mod', 'mod.id', 'Tbl_Sho_PurchaseRequisitions.updated_by')
                    ->Leftjoin('users as created', 'created.id', 'Tbl_Sho_PurchaseRequisitions.created_by')
                    ->Leftjoin('Tbl_Cat_Areas as area', 'area.id', 'Tbl_Sho_PurchaseRequisitions.area_id')
                    ->where('Tbl_Sho_PurchaseRequisitions.area_id', $level[0]->area_id)
                    ->orderBy('Tbl_Sho_PurchaseRequisitions.status', 'desc')
                    ->select('Tbl_Sho_PurchaseRequisitions.id', 'area.id as area_id', 'area.description as desc_area', 'Tbl_Sho_PurchaseRequisitions.comments', 'Tbl_Sho_PurchaseRequisitions.status', 'mod.username as updated_by', 'Tbl_Sho_PurchaseRequisitions.updated_at', 'created.username as created_by', 'Tbl_Sho_PurchaseRequisitions.created_at')->get();
            }

            if (count($purchase_requisitions) > 0) {
                $response['success'] = true;
                $response['message'] = "Se encontrarón registros";
            } else {
                $response = ['success' => false, 'message' => "No se encontrarón registros"];
            }

            $response['purchase_requisitions'] = $purchase_requisitions;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => "Error: " . $e->getMessage()];
        }



        return $response;
    }

    public function savePurchaseRequisition(Request $req)
    {

        $response = [
            'success' => false,
            'message' => "No se guardó correctamente"
        ];

        $id              = $req->id;
        $state_id        = $req->state_id;
        $municipality_id = $req->municipality_id;
        $name            = $req->name;
        $rfc             = $req->rfc;
        $address         = $req->address;
        $cp              = $req->cp;
        $phone           = $req->phone;
        $status          = $req->status;
        $user_id         = $req->user_id;

        if ($id == null || $id == 'undefined' || $id == '') {
            try {
                PurchaseRequisitions::create([
                    'state_id'       => $state_id,
                    'municipality_id' => $municipality_id,
                    'name'           => $name,
                    'rfc'            => $rfc,
                    'address'        => $address,
                    'cp'             => $cp,
                    'phone'          => $phone,
                    'status'         => $status,
                    'updated_by'     => $user_id,
                    'created_by'     => $user_id
                ]);

                $response['success'] = true;
                $response['message'] = "Se guardó registro.";
            } catch (\Throwable $e) {
                $response['success'] = false;
                $response['message'] = "No se guardó registro.";
            }
        } else {
            if ($id) {
                try {
                    PurchaseRequisitions::where('id', $id)->update([
                        'state_id'       => $state_id,
                        'municipality_id' => $municipality_id,
                        'name'           => $name,
                        'rfc'            => $rfc,
                        'address'        => $address,
                        'cp'             => $cp,
                        'phone'          => $phone,
                        'status'         => $status,
                        'updated_by'     => $user_id
                    ]);

                    $response['success'] = true;
                    $response['message'] = "Se guardó registro.";
                } catch (\Throwable $e) {
                    $response['success'] = false;
                    $response['message'] = "No se guardó registro.";
                }
            }
        }

        return $response;
    }

    public function deletePurchaseRequisition(Request $req)
    {

        $response = ['success' => false, 'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            PurchaseRequisitions::where('id', $id)->update([
                'status' => 0,
            ]);

            $response['success'] = true;
            $response['message'] = "Se desactivó registro.";
        } catch (\Throwable $e) {
            $response['success'] = false;
            $response['message'] = "No se desactivó registro.";
        }

        return $response;
    }

    public function getRequisitionDetails(Request $req)
    {

        $response = ['success' => false, 'message' => "No se encontrarón registros"];

        $id      = $req->id;
        $area_id = $req->area_id;

        $requistion_details = RequisitionDetails::Leftjoin('users as mod', 'mod.id', 'Tbl_Sho_RequisitionDetails.updated_by')
            ->Leftjoin('users as created', 'created.id', 'Tbl_Sho_RequisitionDetails.created_by')
            ->Leftjoin('Tbl_Cat_Products as product', 'product.id', 'Tbl_Sho_RequisitionDetails.product_id')
            ->where('Tbl_Sho_RequisitionDetails.purchase_requisition_id', $id)
            ->where('Tbl_Sho_RequisitionDetails.status', 1)
            ->select(
                'Tbl_Sho_RequisitionDetails.id',
                'product.id as product_id',
                'product.description as desc_product',
                'Tbl_Sho_RequisitionDetails.unit_price',
                'Tbl_Sho_RequisitionDetails.quantity',
                'Tbl_Sho_RequisitionDetails.subtotal',
                'Tbl_Sho_RequisitionDetails.status',
                'mod.username as updated_by',
                'Tbl_Sho_RequisitionDetails.updated_at',
                'created.username as created_by',
                'Tbl_Sho_RequisitionDetails.created_at'
            )
            ->get();
        $subtotal = RequisitionDetails::where('purchase_requisition_id', $id)->sum('Tbl_Sho_RequisitionDetails.unit_price');

        $total = $subtotal + ($subtotal  * .16);
        $users = RequisitionAuthorizations::where('purchase_requisition_id', $id)->select('user_id')->get();

        $levels = [];

        if (count($users) > 0) {

            foreach ($users as $user) {
                $levels = Levels::join('users as user', 'user.id', 'Tbl_Cat_Levels.id')
                    ->where('Tbl_Cat_Levels.area_id', $area_id)
                    ->where('Tbl_Cat_Levels.status', 1)
                    /* ->where('Tbl_Cat_Levels.user_id',$req->user_id); */
                    // ->whereNotIn('Tbl_Cat_Levels.user_id', [$user->user_id])
                    ->orderBy('level', 'desc')
                    ->select("Tbl_Cat_Levels.id", "Tbl_Cat_Levels.area_id", "Tbl_Cat_Levels.user_id", "user.name", "user.firstname", "user.img", "Tbl_Cat_Levels.position", "Tbl_Cat_Levels.level", "Tbl_Cat_Levels.status", "Tbl_Cat_Levels.created_at", "Tbl_Cat_Levels.created_by", "Tbl_Cat_Levels.updated_at", "Tbl_Cat_Levels.updated_by")
                    ->get();
            }
        }

        if (count($requistion_details) > 0) {
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['requistion_details']        = $requistion_details;
        $response['levels']                    = $levels;
        $response['subtotal']                  = $subtotal;
        $response['iva']                       = '16%';
        $response['total']                     = $total;

        return $response;
    }

    //** Trae las ordenes de compra */
    public function getPurchaseOrders(Request $req)
    {
        try {
            $purchase_orders = PurchaseOrder::Leftjoin('users as mod', 'mod.id', 'Tbl_Sho_PurchaseOrders.updated_by')
                ->Leftjoin('users as created', 'created.id', 'Tbl_Sho_PurchaseOrders.created_by')
                ->Leftjoin('Tbl_Cat_Areas as area', 'area.id', 'Tbl_Sho_PurchaseOrders.area_id')
                ->Leftjoin('Tbl_Cat_Providers as provider', 'provider.id', 'Tbl_Sho_PurchaseOrders.provider_id')
                ->orderBy('Tbl_Sho_PurchaseOrders.status', 'desc')
                ->select(
                    'Tbl_Sho_PurchaseOrders.id',
                    'provider.name as provider',
                    'Tbl_Sho_PurchaseOrders.provider_id',
                    'Tbl_Sho_PurchaseOrders.subtotal',
                    'Tbl_Sho_PurchaseOrders.total',
                    'Tbl_Sho_PurchaseOrders.authorization_date',
                    'Tbl_Sho_PurchaseOrders.authorization_by',
                    'area.id as area_id',
                    'area.description as desc_area',
                    'Tbl_Sho_PurchaseOrders.status',
                    'mod.username as updated_by',
                    'Tbl_Sho_PurchaseOrders.updated_at',
                    'created.username as created_by',
                    'Tbl_Sho_PurchaseOrders.created_at'
                )
                ->get();
            if (count($purchase_orders) > 0) {
                $response['success'] = true;
                $response['message'] = "Se encontraron registros";
                $response['data'] = $purchase_orders;
            } else {
                $response['success'] = false;
                $response['message'] = "No se encontraron registros";
                $response['data'] = [];
            }
        } catch (\Throwable $e) {
            $response['success'] = false;
            $response['message'] = "Error: " . $e->getMessage();
            $response['data'] = [];
        }

        return $response;
    }

    public function savePurchaseRequsition(Request $req)
    {

        $response = ['success' => false, 'message' => "No se guardó correctamente"];

        $id                   = $req->id;
        $area_id              = $req->area_id;
        $status               = $req->status;
        $requisition_comments = $req->requisition_comments;
        $code                 = $req->code;
        $description          = $req->description;
        $quantity             = $req->quantity;
        $price                = $req->price;
        $added_concepts       = $req->added_concepts;
        $user_id              = $req->user_id;

        if ($id == null || $id == 'undefined' || $id == '') {
            try {
                $purchase_requisitions = PurchaseRequisitions::create([
                    "area_id"    => $area_id,
                    "comments"   => $requisition_comments,
                    "status"     => $status,
                    "created_by" => $user_id,
                    "updated_by" => $user_id,
                ]);

                if ($purchase_requisitions) {

                    foreach ($added_concepts as $concepts) {

                        RequisitionDetails::create([
                            "purchase_requisition_id" => $purchase_requisitions->id,
                            "product_id"              => $concepts['product_id'],
                            "unit_price"              => $concepts['price'],
                            "quantity"                => $concepts['quantity'],
                            "subtotal"                => $concepts['subtotal'],
                            "status"                  => 1,
                            "created_by"              => $user_id,
                            "updated_by"              => $user_id,
                        ]);
                    }
                }

                $response['success'] = true;
                $response['message'] = "Se guardó registro.";
            } catch (\Throwable $e) {
                $response['success'] = false;
                $response['message'] = "No se guardó registro.";
            }
        } else {
            if ($id) {
                try {
                    PurchaseRequisitions::where("id", $id)->update([
                        "area_id"    => $area_id,
                        "comments"   => $requisition_comments,
                        "status"     => $status,
                        "updated_by" => $user_id
                    ]);

                    if (count($added_concepts) > 0) {

                        RequisitionDetails::where("purchase_requisition_id", $id)->update([
                            "status"                  => 0,
                            "updated_by"              => $user_id,
                        ]);

                        foreach ($added_concepts as $concepts) {

                            if ($concepts['id'] != null) {
                                RequisitionDetails::where("purchase_requisition_id", $id)->where("id", $concepts['id'])->update([
                                    "unit_price"              => $concepts['price'],
                                    "quantity"                => $concepts['quantity'],
                                    "subtotal"                => $concepts['subtotal'],
                                    "status"                  => 1,
                                    "updated_by"              => $user_id,
                                ]);
                            } else {
                                RequisitionDetails::create([
                                    "purchase_requisition_id" => $id,
                                    "product_id"              => $concepts['product_id'],
                                    "unit_price"              => $concepts['price'],
                                    "quantity"                => $concepts['quantity'],
                                    "subtotal"                => $concepts['subtotal'],
                                    "status"                  => 1,
                                    "created_by"              => $user_id,
                                    "updated_by"              => $user_id,
                                ]);
                            }
                        }
                    }
                    $response['success'] = true;
                    $response['message'] = "Se guardó registro.";
                } catch (\Throwable $e) {
                    $response['success'] = false;
                    $response['message'] = "No se guardó registro.";
                }
            }
        }

        return $response;
    }

    public function getConversation(Request $req)
    {

        $response = ['success' => false, 'message' => "No se encontró conversacion"];

        $requisition_id = $req->requisition_id;
        $level_id       = $req->level_id;
        $area_id        = $req->area_id;

        $conversation = CommentsOnRequisitions::join('users as user', 'user.id', 'Tbl_Sho_CommentsOnRequisitions.user_id')
            ->Leftjoin('users as mod', 'mod.id', 'Tbl_Sho_CommentsOnRequisitions.updated_by')
            ->Leftjoin('users as created', 'created.id', 'Tbl_Sho_CommentsOnRequisitions.created_by')
            ->join('Tbl_Cat_Levels as level', 'level.user_id', 'user.id')
            ->orderBy('Tbl_Sho_CommentsOnRequisitions.id', 'ASC')
            ->where('Tbl_Sho_CommentsOnRequisitions.purchase_requisition_id', $requisition_id)->where('Tbl_Sho_CommentsOnRequisitions.level_id', $level_id)->where('Tbl_Sho_CommentsOnRequisitions.status', 1)->where('level.area_id', $area_id)
            ->select('Tbl_Sho_CommentsOnRequisitions.id', 'Tbl_Sho_CommentsOnRequisitions.level_id', 'Tbl_Sho_CommentsOnRequisitions.user_id', 'level.position', 'user.name', 'user.firstname', 'user.img', 'Tbl_Sho_CommentsOnRequisitions.comments', 'Tbl_Sho_CommentsOnRequisitions.created_at', 'mod.username as updated_by', 'Tbl_Sho_CommentsOnRequisitions.updated_at', 'created.username as created_by')
            // ->groupBy('Tbl_Sho_CommentsOnRequisitions.id','level.position')
            ->get();

        $response['conversation'] = $conversation;

        if (count($conversation) > 0) {
            $response['success'] = true;
            $response['message'] = "Se encontraron conversaciónes";
        }

        return $response;
    }

    public function saveConversation(Request $req)
    {

        $response = ['success' => false, 'message' => "No se envió el mensaje correctamente"];

        $requisition_id = $req->requisition_id;
        $message        = $req->message;
        $level_id       = $req->level_id;
        $area_id        = $req->area_id;
        $user_id        = $req->user_id;

        try {
            CommentsOnRequisitions::create([
                'purchase_requisition_id' =>  $requisition_id,
                'level_id'                =>  $level_id,
                'user_id'                 =>  $user_id,
                'comments'                =>  $message,
                'status'                  =>  1,
                'created_by'              =>  $user_id,
                'updated_by'              =>  $user_id,
            ]);

            $response['success'] = true;
            $response['message'] = "Se envió el mensaje";
        } catch (\Throwable $e) {
            $response['success'] = false;
            $response['message'] = "No se envió registro.";
        }

        return $response;
    }

    public function deletePurchaseRequsition(Request $req){

        $response = ['success' => false ,'message' => "No se desactivo correctamente"];

        $id = $req->id;

        try {
            PurchaseRequisitions::where('id',$id)->update([
                'status'=>0,
            ]);

            $response['success'] = true;
            $response['message'] = "Se desactivó registro.";

        }catch(Exception $e){
            $response['success'] = false;
            $response['message'] = "No se desactivó registro.";

        }

        return $response;
    }
}
