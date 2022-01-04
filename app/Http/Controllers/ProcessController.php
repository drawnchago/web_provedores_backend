<?php

namespace App\Http\Controllers;

use App\Models\BombPiece;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\WorkOrder;
use App\Models\InspectionPiece;
use App\Models\WorkOrderDetail;
use App\Models\EntryEquipment;
use App\Models\ExitEquipment;
use App\Models\ImgEquipment;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ProcessController extends Controller
{
    //** Guarda la hoja de entrada y orden de trabajo */
    public function saveWorkOrder(Request $req)
    {
        if ($req->id == null || $req->id == '' || $req->id == 'null') {
            //! Crea la orden del trabajo (Inicial)
            try {
                //! Crea la orden de entrada
                $entry =  EntryEquipment::create([
                    'position_order' => $req->position_order,
                    'entry_date' => $req->entry_date,
                    'user_id' => $req->user_id,
                    'zone' => $req->zone,
                    'equipment_description' => $req->equipment_description,
                    'folio_equipment' => $req->folio_equipment,
                    'place' => $req->place,
                    'type' => $req->type,
                    'description_entry' => $req->description_entry,
                    'comments_coditions' => $req->comments_coditions,
                    'equipment_application' => $req->equipment_application,
                    'handling_fluid' => $req->handling_fluid,
                    'work_temperature' => $req->work_temperature,
                    'exposed_pressure' => $req->exposed_pressure,
                    'number_or_folio_requisition' => $req->number_or_folio_requisition,
                    'created_by' => $req->created_by,
                    'priority_id' => $req->priority_id,
                    'applicant' => $req->applicant,
                    'witness' => $req->witness,
                ]);

                $order =  WorkOrder::create([
                    'type_bomb_id' => $req->type_bomb_id,
                    'customer_id' => $req->customer_id,
                    'brand_id' => $req->brand_id,
                    'model_id' => $req->model_id,
                    'size' => $req->size,
                    'stock' => $req->stock,
                    'exit_pass' => $req->exit_pass,
                    'rpm' => $req->rpm,
                    'hp' => $req->hp,
                    'evaluation' => $req->evaluation,
                    'set' => $req->set,
                    'total_length_quantity' => $req->total_length_quantity,
                    'total_length_description' => $req->total_length_description,
                    'total_diameter_quantity' => $req->total_diameter_quantity,
                    'total_diameter_description' => $req->total_diameter_description,
                    'total_weight_description' => $req->total_weight_description,
                    'total_weight_quantity' => $req->total_weight_quantity,
                    'status' => 3,
                    'entry_id' => $entry->id,
                    'created_by' => $req->created_by
                ]);
                if (!empty($_FILES)) {
                    foreach ($req->file as $file) {
                        $name = $file->getClientOriginalName();
                        $file->storeAs('upload/HojaEntrada-' . $order->id, $name);
                        ImgEquipment::create([
                            'order_id' => $order->id,
                            'type' => 1,
                            'path' => 'upload/HojaEntrada-' . $entry->id . '/' . $name,
                            'created_by' => $req->created_by
                        ]);
                    }
                }
                //!Crea la orden de trabajo con detalle
                WorkOrderDetail::create([
                    'order_id' => $order->id,
                    'status' => 3,
                    'created_by' => $req->created_by
                ]);
                $response = ['success' => true, 'message' => 'Se creo la orden de trabajo'];
            } catch (\Throwable $e) {
                $response = ['success' => false, 'message' => 'No se creo la orden de trabajo '];
            }
        } else {
            //! Actualiza la orden del trabajo (Inicial)
            try {
                WorkOrder::where('id', $req->id)->update([
                    'type_bomb_id' => $req->type_bomb_id,
                    'customer_id' => $req->customer_id,
                    'brand_id' => $req->brand_id,
                    'model_id' => $req->model_id,
                    'size' => $req->size,
                    'stock' => $req->stock,
                    'exit_pass' => $req->exit_pass,
                    'rpm' => $req->rpm,
                    'hp' => $req->hp,
                    'evaluation' => $req->evaluation,
                    'set' => $req->set,
                    'total_length_quantity' => $req->total_length_quantity,
                    'total_length_description' => $req->total_length_description,
                    'total_diameter_quantity' => $req->total_diameter_quantity,
                    'total_diameter_description' => $req->total_diameter_description,
                    'total_weight_quantity' => $req->total_weight_quantity,
                    'total_weight_description' => $req->total_weight_description,
                    'updated_by' => $req->user_id,

                ]);
                $response = ['success' => true, 'message' => 'Se actualizo la ordean de trabajo'];
            } catch (\Throwable $e) {
                $response = ['success' => false, 'message' => $e->getMessage()];
            }
        }
        return response()->json($response);
    }
    //** Guarda la hoja de salida */
    public function saveExitSheet(Request $req)
    {

       
        if ($req->id == null || $req->id == '' || $req->id == 'null') {
            try {
                $work_order = WorkOrder::where('id', $req->work_order_id)->get();
                $entry = EntryEquipment::where('id', $req->work_order_id)->get();
                $exit = ExitEquipment::create([
                    'invoice_or_referral' => $req->invoice_or_referral,
                    'exit_pass' => $work_order[0]->exit_pass,
                    'user_id' => $req->user_id,
                    'zone' => $entry[0]->zone,
                    'exit_date' => $req->exit_date,
                    'equipment_folio' => $entry[0]->folio_equipment,
                    'drips' => $req->drips,
                    'order' => $req->order,
                    'type' => $req->type,
                    'material_description' => $req->material_description,
                    'test_pressure' => $req->test_pressure,
                    'leakage' => $req->leakage,
                    'arrow_end_dimension' => $req->arrow_end_dimension,
                    'threads' => $req->threads,
                    'screws_cooling_lines' => $req->screws_cooling_lines,
                    'armed' => $req->armed,
                    'keyhole' => $req->keyhole,
                    'levels' => $req->levels,
                    'packaging' => $req->packaging,
                    'observations' => $req->observations,
                    'applicant' => $req->applicant,
                    'witness' => $req->witness,
                    'created_by' => $req->created_by,
                ]);
                if (!empty($_FILES)) {
                    foreach ($req->file as $file) {
                        $name = $file->getClientOriginalName();
                        $file->storeAs('upload/HojaSalida-' . $req->work_order_id, $name);
                        ImgEquipment::create([
                            'order_id' => $req->work_order_id,
                            'path' => 'upload/HojaSalida-' . $exit->id . '/' . $name,
                            'type' => 2,
                            'created_by' => $req->created_by
                        ]);
                    }
                }
                WorkOrder::where('id', $req->work_order_id)->update([
                    'exit_id' => $exit->id,
                    'status' => 4,
                ]);
                $response = ['success' => true, 'message' => 'Se creo la hoja de salida '];
            } catch (\Throwable $e) {
                $response = ['success' => true, 'message' => 'No se creo la hoja de salida ' . $e->getMessage()];
            }
        } else {
            # code...
        }
        return response()->json($response);
    }

    //** Guarda una pieza de una bomba  */
    public static function saveNewPiece(Request $req)
    {
        try {
            $req->type == 1 ? $type = 'Bomba' :  $type = 'Partes Motor';
            BombPiece::create([
                'type_bomb_id' => $req->type_bomb_id,
                'name' => $req->name,
                'type_piece' => $type,
                'status' => 1,
                'created_by' => $req->user_id,
            ]);
            $response = ['success' => true, 'message' => 'Se creo la pieza'];
        } catch (\Throwable $e) {
            $response = ['success' => true, 'message' => 'error', 'e: ' => $e->getMessage()];
        }
        return $response;
    }

    //** Inspeccion de las piezas */
    public function saveInspecionPiece(Request $req)
    {
        /*  return $req; */
        $inspection_pieces = $req->all();

        foreach ($inspection_pieces  as  $p) {
            if ($p['yes'] ==  'true') {
                $p['yes'] = 1;
            } elseif ($p['yes'] ==  'false') {
                $p['yes'] = 0;
            }

            if ($p['no'] ==  'true') {
                $p['no'] = 1;
            } elseif ($p['no'] ==  'false') {
                $p['no'] = 0;
            }
            if ($p['repair'] ==  'true') {
                $p['repair'] = 1;
            } elseif ($p['repair'] ==  'false') {
                $p['repair'] = 0;
            }
            if ($p['supply'] ==  'true') {
                $p['supply'] = 1;
            } elseif ($p['supply'] ==  'false') {
                $p['supply'] = 0;
            }
            if ($p['demand'] ==  'true') {
                $p['demand'] = 1;
            } elseif ($p['demand'] ==  'false') {
                $p['demand'] = 0;
            }
            if ($p['stock'] ==  'true') {
                $p['stock'] = 1;
            } elseif ($p['stock'] ==  'false') {
                $p['stock'] = 0;
            }



            if (!isset($p['id'])) {
                //! Crea la inspeccion de la pieza 
                try {
                    InspectionPiece::create([
                        'piece_bomb_id' => $p['piece_bomb_id'],
                        'yes' => $p['yes'],
                        'no' => $p['no'],
                        'repair' => $p['repair'],
                        'demand' => $p['demand'],
                        'supply' => $p['supply'],
                        'demand' => $p['demand'],
                        'stock' => $p['stock'],
                        'order_detail_id' => $p['order_detail_id'],
                        /*  'materials' => $p->materials, */
                        'status' => 1,
                        /* 'created_by' => $p['user_id'], */
                    ]);
                    $order_detail = WorkOrderDetail::where('id', $p['order_detail_id'])->get();
                    $work_order = WorkOrder::where('id', $order_detail[0]->order_id)->update([
                        'status' => 5
                    ]);
                    $response = ['success' => true, 'message' => 'Se creo la inspeccion de la pieza '];
                } catch (\Throwable $e) {
                    $response = ['success' => false, 'message' => $e];
                    $response['message'] = "No se creo la inspeccion de la pieza  " . $e;
                }
            } else {
                //! Actualiza la orden del trabajo (Inicial)
                try {
                    InspectionPiece::where('id', $p->id)->update([
                        'piece_bomb_id' => $p->piece_bomb_id,
                        'yes' => $p->yes,
                        'no' => $p->no,
                        'repair' => $p->repair,
                        'demand' => $p->demand,
                        'stock' => $p->stock,
                        'materials' => $p->materials,
                        'status' => $p->status,
                        'updated_by' => $p->user_id,

                    ]);
                    $response = ['success' => true, 'message' => 'Se actualizo la inspeccion de la pieza'];
                } catch (\Throwable $e) {
                    $response = ['success' => false, 'message' => $e];
                    $response['message'] = "No se actualizo la inspeccion de la pieza";
                }
            }
        }
        return response()->json($response);
    }
    //** Obtiene las imagenes de la orden de trabajo */
    public function getWorkOrderImage(Request $req)
    {
        try {
            # code...
        } catch (\Throwable $e) {
            # code...
        }
    }
    //** Obtiene todas las ordenes de trabajo */
    public function getAllWorkOrders()
    {
        $response = ['success' => true];

        $work_orders = collect(DB::connection('erp')->select("SELECT 
            wo.id AS 'id',
            wo.type_bomb_id,
            wo.customer_id,
            wo.brand_id,
            wo.model_id,
            wo.size,
            wo.stock,
            wo.exit_pass,
            wo.rpm,
            wo.hp,
            wo.evaluation,
            wo.set,
            wo.status,
            wo.total_length_quantity,
            wo.total_length_description,
            wo.total_diameter_quantity,
            wo.total_diameter_description,
            wo.total_weight_quantity,
            wo.total_weight_description,
            eo.id as 'entry_id',
            eo.position_order,
            eo.entry_date,
            eo.user_id as 'entry_user_id',
            eo.zone,
            eo.equipment_description,
            eo.place,
            eo.type,
            eo.description_entry,
            eo.comments_coditions,
            eo.equipment_application,
            eo.handling_fluid,
            eo.work_temperature,
            eo.exposed_pressure,
            eo.number_or_folio_requisition,
            eo.status as 'entry_status',
            eo.priority_id,
            tb.name AS 'bomb',
            c.name AS 'customer',
            b.name AS 'brand',
            m.name AS 'model',
            wo.created_at
        FROM
            Tbl_Op_WorkOrders wo
                INNER JOIN
            Tbl_Op_TypesBomb tb ON wo.type_bomb_id = tb.id
                INNER JOIN
            Tbl_Op_Customers c ON wo.customer_id = c.id
                INNER JOIN
            Tbl_Op_Brands b ON wo.brand_id = b.id
                INNER JOIN
            Tbl_Op_Models m ON wo.model_id = m.id
                INNER JOIN
                Tbl_Op_WorkOrderEquipmentEntrys eo ON wo.id = eo.id
        WHERE
            wo.status != 2"));

        $response['data'] = $work_orders;

        return response()->json($response);
    }
    //** Obtiene las ordenes de entrada */
    public function getOrdersEntry()
    {
        try {
            $order_sheets = collect(DB::connection('erp')->select("SELECT 
            ee.id,
            ee.position_order,
            ee.folio_equipment,
            ee.entry_date,
            CASE
                WHEN ee.zone = 1 THEN 'Norte'
                WHEN ee.zone = 2 THEN 'Monclova'
                WHEN ee.zone = 3 THEN 'Saltillo'
                ELSE 'N/A'
            END AS 'zone',
            ee.equipment_description,
            ee.place,
            CASE
                WHEN ee.type = 1 THEN 'Reparacion'
                WHEN ee.type = 2 THEN 'Mantenimiento'
                WHEN ee.type = 3 THEN 'Muestra'
                WHEN ee.type = 4 THEN 'Garantia'
                ELSE 'N/A'
            END AS 'type',
            ee.description_entry,
            ee.comments_coditions,
            ee.equipment_application,
            ee.handling_fluid,
            ee.work_temperature,
            ee.exposed_pressure,
            ee.number_or_folio_requisition,
            ee.priority_id,
            ee.created_at,
            wo.exit_pass,
            u.name as 'user',
            c.name as 'customer'
           /*  i.path,
            i.type as 'type_img' */
        FROM
            Tbl_Op_WorkOrderEquipmentEntrys ee
                INNER JOIN
            Tbl_Op_WorkOrders wo ON wo.entry_id = ee.id
                INNER JOIN
            users u ON u.id = ee.created_by
                INNER JOIN
            Tbl_Op_Customers c on c.id = wo.customer_id
               /*  INNER JOIN 
                Tbl_Op_ImgEnquipment i on wo.id = i.order_id */
              "));


            $response = ['success' => true, 'message' => 'Se encontraron ordenes de entrada'];
            $response['data'] = $order_sheets;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }
    //** Obtiene la hojas de salida */
    public function getAllExitSheet()
    {
        try {
            $exit_sheets = ExitEquipment::all();
            $response = ['success' => true, 'message' => 'Se encontraron ordenes de entrada'];
            $response['data'] = $exit_sheets;
        } catch (\Throwable $e) {
            $response = ['success' => true, 'message' => 'No se creo la hoja de salida ' . $e->getMessage()];
        }
        return response()->json($response);
    }
    //** Trae la piezas de la bomba con los campos que van a llenar  */
    public function getPiecesByBombId(Request $req)
    {
        try {
            $pieces = collect(DB::connection('erp')->select("SELECT 
            wo.id AS 'work_order_id',
            wo.type_bomb_id AS 'type_bomb_id',
            wod.id AS 'order_detail_id',
            pb.id AS 'piece_bomb_id',
            pb.name AS 'piece',
            pb.type_piece
            FROM
                Tbl_Op_WorkOrders wo
                INNER JOIN
                Tbl_Op_WorkOrderDetail wod ON wo.id = wod.order_id
                INNER JOIN
                Tbl_Op_PiecesBombs pb ON wo.type_bomb_id = pb.type_bomb_id
                where wo.type_bomb_id = $req->bomb_id and pb.type_piece = '$req->type' and wo.id = $req->work_order_id and pb.status = 1"))->map(function ($piece) {
                /*  */
                $piece->yes = 0;
                $piece->no = 0;
                $piece->repair = 0;
                $piece->supply = 0;
                $piece->demand = 0;
                $piece->stock = 0;
                $piece->description = '';
                return $piece;
            });


            $response = ['success' => true, 'message' => 'Se encontraron pieces'];
            $response['pieces'] = $pieces;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $response['pieces'] = [];
        }
        return response()->json($response);
    }
    //** Actualiza las el status de las ordenes de trabajo */
    public function updateStatusOrders(Request $req)
    {
        try {
            WorkOrder::where('id', $req->order_id)->update([
                'status' => 5,
                'updated_by' => $req->user_id,

            ]);
            WorkOrderDetail::where('id', $req->order_id)->update([
                'status' => 4,
                'updated_by' => $req->user_id,

            ]);

            $response = ['success' => true, 'message' => 'Se actualizo la ordean de trabajo'];
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e];
            $response['message'] = "No se actualizo la orden de trabajo";
        }
        return response()->json($response);
    }

    //** Trae las piezas que fueron inspeccionadas en base a la orden id y tipo de pieza (Bomba o motor) */
    public function getPiecesInspection(Request $req)
    {
        try {
            $pieces_inspection = collect(DB::connection('erp')->select("SELECT 
            wod.id,
            pb.name AS 'piece_bomb',
            pi.yes,
            pi.no,
            pi.repair,
            pi.supply,
            pi.demand,
            pi.stock,
            pi.description
        FROM
            Tbl_Op_WorkOrderDetail wod
                INNER JOIN
            Tbl_Op_PiecesInspection pi ON wod.id = pi.order_detail_id
                INNER JOIN
            Tbl_Op_PiecesBombs pb ON pi.piece_bomb_id = pb.id
                AND pb.type_piece = '$req->type_piece'
        WHERE
            wod.order_id = $req->order_id"));
            $response = ['success' => true, 'message' => 'Se encontraron piezas inspeccionadas'];
            $response['pieces_inspection'] = $pieces_inspection;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e];
            $response['message'] = "No se encontraron piezas inspeccionadas";
        }
        return response()->json($response);
    }
    //** Trae todas las ordenes de entrada */
    public function getOrdetSheets()
    {
        try {
            $order_sheets = collect(DB::connection('erp')->select("SELECT
              woe.id,
              woe.position_order,
              woe.entry_date,
              woe.user_id,
              woe.zone,
              woe.equipment_description,
              woe.place,
              woe.type,
              woe.description_entry,
              woe.comments_coditions,
              woe.equipment_application,
              woe.handling_fluid,
              woe.work_temperature,
              woe.exposed_pressure,
              woe.number_or_folio_requisition,
              woe.status,
              woe.created_at,
              woe.created_by,
              woe.updated_at,
              woe.updated_by,
              woe.priority_id
              FROM  Tbl_Op_WorkOrderEquipmentEntrys woe

              "));
            $response = ['success' => true, 'message' => 'Se encontraron ordenes de entrada'];
            $response['data'] = $order_sheets;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e];
        }
        return response()->json($response);
    }

    //** Trae los clientes  */
    public function getCustomers()
    {
        try {
            $customers = collect(DB::connection('erp')->select("SELECT * FROM Tbl_Op_Customers where status = 1"));
            $response = ['success' => true, 'message' => 'Se encontraron clientes'];
            $response['customers'] = $customers;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }

    //** Trae las bombas */
    public static function getBombsActives()
    {

        try {
            $bombs = collect(DB::connection('erp')->select("SELECT * FROM Tbl_Op_TypesBomb where status = 1"));
            $response = ['success' => true, 'message' => 'Se encontraron bombas'];
            $response['bombs'] = $bombs;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }
    //** Trae los modelos */
    public static function getModelsActives()
    {

        try {
            $models = collect(DB::connection('erp')->select("SELECT * FROM Tbl_Op_Models where status = 1"));
            $response = ['success' => true, 'message' => 'Se encontraron modelos'];
            $response['models'] = $models;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }
    //** Trae las bombas */
    public static function getBrandsActives()
    {

        try {
            $brands = collect(DB::connection('erp')->select("SELECT * FROM Tbl_Op_Brands where status = 1"));
            $response = ['success' => true, 'message' => 'Se encontraron bombas'];
            $response['brands'] = $brands;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }

    //**  Trae los datos para el pdf de la hoja de entrada  */
    public function getDataEntry(Request $req)
    {
        try {
            $pdf = collect(DB::connection('erp')->select("SELECT 
                        ee.id,
                        ee.position_order,
                        ee.entry_date,
                        CASE
                            WHEN ee.zone = 1 THEN 'Norte'
                            WHEN ee.zone = 2 THEN 'Monclova'
                            WHEN ee.zone = 3 THEN 'Saltillo'
                            ELSE 'N/A'
                        END AS 'zone',
                        ee.equipment_description,
                        ee.place,
                        ee.type,
                        ee.description_entry,
                        ee.comments_coditions,
                        ee.equipment_application,
                        ee.handling_fluid,
                        ee.work_temperature,
                        ee.work_temperature,
                        ee.number_or_folio_requisition,
                        wo.exit_pass,
                        u.name as 'user',
                        c.name as 'customer'
                    FROM
                        Tbl_Op_WorkOrderEquipmentEntrys ee
                            INNER JOIN
                        Tbl_Op_WorkOrders wo ON wo.entry_id = ee.id
                            INNER JOIN
                        users u ON u.id = ee.created_by
                    inner join
                    Tbl_Op_Customers c on c.id = wo.customer_id
                    where ee.id =$req->entry_id"));
            $response = ['success' => true, 'message' => 'Se encontro el registro', 'pdf' => $pdf];
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }
        return response()->json($response);
    }


    public function getOrdetSheetsByWorkOrderId(Request $req)
    {
        try {
            $order_sheets = collect(DB::connection('erp')->select("SELECT
                woe.id,
                woe.work_order_id,
                woe.position_order,
                woe.entry_date,
                woe.user_id,
                woe.zone,
                woe.equipment_description,
                woe.place,
                woe.type,
                woe.description_entry,
                woe.comments_coditions,
                woe.equipment_application,
                woe.handling_fluid,
                woe.work_temperature,
                woe.exposed_pressure,
                woe.number_or_folio_requisition,
                woe.status,
                woe.created_at,
                woe.created_by,
                woe.updated_at,
                woe.updated_by,
                woe.priority_id
                FROM  Tbl_Op_WorkOrderEquipmentEntrys woe
                INNER JOIN 
                Tbl_Op_WorkOrders wo on woe.work_order_id = wo.id
                WHERE woe.work_order_id = $req->work_order_id
        "));
            $response = ['success' => true, 'message' => 'Se encontraron ordenes de entrada'];
            $response['data'] = $order_sheets;
        } catch (\Throwable $e) {
            $response = ['success' => false, 'message' => $e];
            $response['message'] = "No se encontraron ordenes de entrada";
        }
        return response()->json($response);
    }
}
