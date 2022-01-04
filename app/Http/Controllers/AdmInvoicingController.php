<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class AdmInvoicingController extends Controller
{
    
    public function __construct()
    {
        //
    }

    public function getWorkOrders(Request $req){
        $response = ['success' => true];

        $work_orders = collect(DB::connection('erp')->select("SELECT 
        wo.id AS 'id', wo.type_bomb_id, wo.customer_id, wo.brand_id, wo.model_id, wo.size,
        wo.stock, wo.exit_pass,wo.rpm, wo.hp, wo.evaluation, wo.set, wo.status, wo.total_length_quantity, wo.total_length_description,
        wo.total_diameter_quantity, wo.total_diameter_description, wo.total_weight_quantity, wo.total_weight_description, tb.name AS 'bomb',
        c.name AS 'customer', c.rfc,  b.name AS 'brand', m.name AS 'model', wo.created_at,
        c.id as customer_id,
        /*RAND()*(100-1)+1 as total,
        RAND()*(100-1)+1 as unit_price,
        RAND()*(100-1)+1 as subtotal,
        RAND()*(10-1)+1 as iva,*/
        100 as amount,
        100 as unit_price,
        100 as subtotal,
        16 as iva,
        1 as quantity,
        0 as discount,
        0 as retention,
        '002' as retention_tax,
        'Tasa' as factor_retention,
        '002' as transfer_tax,
        'Tasa' as factor_transfer,
        '0.160000' as transfer_free,
        0 as discount,
        '78101803' as product_key,
        'SERV101' as local_key,
        'E48' as unit_key,
        'SERVICIO' as unit,
        'REPARACION DE BOMBA' as concept,
        0 as selected
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
        WHERE
            wo.status and wo.invoiced = 0 
            and date(wo.created_at) >= date('$req->start_date') and date(wo.created_at) <= date('$req->end_date')"));

        $response['data'] = $work_orders;

        return response()->json($response);
    }

    public function stamp(Request $req){
        header('Content-Type: text/html; charset=UTF-8');
        $response = ['success' => true];

        $payment_method = $req->payment_method;
        $cfdi = $req->cfdi;
        $payment_way = $req->payment_way;
        $payment_conditions = $req->payment_conditions;
        $digits = $req->digits;
        $apply_retentions = $req->retentions;
        $comments = $req->comments;
        $preview_mode = $req->preview;
        $work_orders = $req->work_orders;
        $customer_rfc = $req->rfc;
        $customer_name = $req->customer;
        $customer_id = $req->customer_id;
        $user_id = $req->user_id;

        $config = collect(DB::select("SELECT 
        GROUP_CONCAT( if(config_name = 'RFCissuing',string_value,NULL) ) AS 'RFCissuing',
        GROUP_CONCAT( if(config_name = 'CompanyName',string_value,NULL) ) AS 'CompanyName',
        GROUP_CONCAT( if(config_name = 'fileCer',string_value,NULL) ) AS 'fileCer',
        GROUP_CONCAT( if(config_name = 'fileKey',string_value,NULL) ) AS 'fileKey',
        GROUP_CONCAT( if(config_name = 'certificateNumber',string_value,NULL) ) AS 'certificateNumber',
        GROUP_CONCAT( if(config_name = 'dirFilesPem',string_value,NULL) ) AS 'dirFilesPem',
        GROUP_CONCAT( if(config_name = 'dirFilessCFDI',string_value,NULL) ) AS 'dirFilessCFDI',
        GROUP_CONCAT( if(config_name = 'dirFilesGraf',string_value,NULL) ) AS 'dirFilesGraf',
        GROUP_CONCAT( if(config_name = 'dirFilesXsd',string_value,NULL) ) AS 'dirFilesXsd',
        GROUP_CONCAT( if(config_name = 'invUserName',string_value,NULL) ) AS 'invUserName',
        GROUP_CONCAT( if(config_name = 'invPassword',string_value,NULL) ) AS 'invPassword',
        GROUP_CONCAT( if(config_name = 'postalCode',string_value,NULL) ) AS 'postalCode',
        GROUP_CONCAT( if(config_name = 'currency',string_value,NULL) ) AS 'currency',
        GROUP_CONCAT( if(config_name = 'keyTaxRegime',string_value,NULL) ) AS 'keyTaxRegime',
        GROUP_CONCAT( if(config_name = 'dirRoot',string_value,NULL) ) AS 'dirRoot'
        FROM Tbl_Sys_Configs"))->first();

        $senda_PEMS = $config->dirFilesPem;
        $senda_CFDI = $config->dirFilessCFDI . $customer_id . '/';
        $senda_grafs = $config->dirFilesGraf;
        $senda_XDS = $config->dirFilesXsd;
        $postal_code = $config->postalCode;
        $currency = $config->currency;
        $key_tax_regime = $config->keyTaxRegime;
        $dir_root = storage_path($config->dirRoot);
        $username = $config->invUserName;
        $password = $config->invPassword;

        $certificate_number = $config->certificateNumber;
        $file_cer = $config->fileCer;
        $file_key = $config->fileKey;

        $transmitter_rfc = $config->RFCissuing;
        $transmitter_name = $config->CompanyName;

        if (!file_exists($dir_root.$senda_CFDI)) {
            mkdir($dir_root.$senda_CFDI, 0777, true);
        }

        $invoice_serie = 'BO';
        $invoice_folio = Invoice::max('id') + 1;
        $invoice_number = $invoice_serie.$invoice_folio; 
        $invoice_type_voucher = 'I';
        $tasa_iva = 16;
        $subtotal = 0;
        $discount = 0;
        $IVA = 0;
        $total = 0;
        $invoice_date = date("Y-m-d")."T".date("H:i:s");
        $exchante_rate = 1;
        $total_retention_taxes = 0;
        $total_transfered_taxes = 0;

        foreach($work_orders as $work_order){
            $retention = (float)$work_order['retention'];
            $retention = $retention > 0 ? ($retention / 100) : $retention;
           
            $subtotal += $work_order['amount'];
            $total_transfered_taxes += round($work_order['amount'] * 0.160000,2);
            if($apply_retentions){
                $total_retention_taxes += round($work_order['amount'] * $retention,2);
            }
        }

        $subtotal = number_format($subtotal,2,'.','');
        $total = $subtotal - $discount + $total_transfered_taxes - $total_retention_taxes;

        if (strlen($customer_rfc)==12){
            $customer_rfc = " ".$customer_rfc; 
        }

        $xml = new \DOMdocument('1.0', 'UTF-8');
        $root = $xml->createElement("cfdi:Comprobante");
        $root = $xml->appendChild($root);

        $original_string = '||';

        self::loadAtt($root, array("xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd",
            "xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/3",
            "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance"
        ), $original_string);

        self::loadAtt($root, array(
            "Version"=>"3.3",
            "Serie"=>$invoice_serie,
            "Folio"=>$invoice_folio,
            "Fecha"=>$invoice_date,
            "FormaPago"=>$payment_way,
            "NoCertificado"=>$certificate_number,
            "CondicionesDePago"=>$payment_conditions,
            "SubTotal"=>$subtotal,
            "Descuento"=>$discount,
            "Moneda"=>$currency,
            "TipoCambio"=>$exchante_rate,
            "Total"=>$total,
            "TipoDeComprobante"=>$invoice_type_voucher,
            "MetodoPago"=>$payment_method,
            "LugarExpedicion"=>$postal_code
        ), $original_string);

        $transmitter = $xml->createElement("cfdi:Emisor");
        $transmitter = $root->appendChild($transmitter);
        self::loadAtt($transmitter, array("Rfc"=>$transmitter_rfc,
                "Nombre"=>$transmitter_name,
                "RegimenFiscal"=>$key_tax_regime
            ),$original_string
        );

        $receiver = $xml->createElement("cfdi:Receptor");
        $receiver = $root->appendChild($receiver);
        self::loadAtt($receiver, array("Rfc"=>$customer_rfc,
                "Nombre"=>$customer_name,
                "UsoCFDI"=>$cfdi
            ),$original_string
        );

        $concepts = $xml->createElement("cfdi:Conceptos");
        $concepts = $root->appendChild($concepts);
                
        foreach($work_orders as $work_order){
            $retention = (float)$work_order['retention'];
            $retention = $retention > 0 ? ($retention / 100) : $retention;

            $concept = $xml->createElement("cfdi:Concepto");
            $concept = $concepts->appendChild($concept);
            self::loadAtt($concept, array(
                "ClaveProdServ"=>$work_order['product_key'],
                "NoIdentificacion"=>$work_order['local_key'],
                "Cantidad"=>$work_order['quantity'],
                "ClaveUnidad"=>$work_order['unit_key'],
                "Unidad"=>$work_order['unit'],
                "Descripcion"=>$work_order['concept'],
                "ValorUnitario"=>number_format($work_order['unit_price'],2,'.',''),
                "Importe"=>number_format($work_order['amount'],6,'.',''),
                "Descuento"=>number_format($work_order['discount'],2,'.','')
                ),$original_string
            );

            $taxes = $xml->createElement("cfdi:Impuestos");
            $taxes = $concept->appendChild($taxes);

            $transfers = $xml->createElement("cfdi:Traslados");
            $transfers = $taxes->appendChild($transfers);

            $transfer = $xml->createElement("cfdi:Traslado");
            $transfer = $transfers->appendChild($transfer);

            if ($work_order['factor_transfer']=="Exento"){
                self::loadAtt($transfer, array(
                       "Base"=>number_format($work_order['amount'],2,'.',''),
                       "Impuesto"=>$work_order['transfer_tax'],
                       "TipoFactor"=>$work_order['factor_transfer']
                    ),$original_string
                );
            }else{
                self::loadAtt($transfer, array(
                       "Base"=>number_format($work_order['amount'],2,'.',''),
                       "Impuesto"=>$work_order['transfer_tax'],
                       "TipoFactor"=>$work_order['factor_transfer'],
                       "TasaOCuota"=>$work_order['transfer_free'],
                       "Importe"=>number_format(round($work_order['amount'] * 0.160000,2),2,'.','')
                    ),$original_string
                );
            }

            if($apply_retentions){
                $retentions = $xml->createElement("cfdi:Retenciones");
                $retentions = $taxes->appendChild($retentions);
                
                $retention = $xml->createElement("cfdi:Retencion");
                $retention = $retentions->appendChild($retention);
                
                self::loadAtt($Retencion, array(
                        "Base"=>number_format($work_order['amount'],2,'.',''),
                        "Impuesto"=>$work_order['retention_taz'],
                        "TipoFactor"=>$work_order['factor_retention'],
                        "TasaOCuota"=>number_format($retention,6,'.',''),
                        "Importe"=>number_format(($work_order['amount'] * $retention),2,'.','')
                    ),$original_string
                );
            }

        }

        $total_taxes = $xml->createElement("cfdi:Impuestos");
        $total_taxes = $root->appendChild($total_taxes);
        if($apply_retentions){

            $total_retentions = $xml->createElement("cfdi:Retenciones");
            $total_retentions = $total_taxes->appendChild($total_retentions);
        
            $total_retention = $xml->createElement("cfdi:Retencion");
            $total_retention = $total_retentions->appendChild($total_retention);
        
            self::loadAtt($total_retention, array(
                    "Impuesto"=>"002",
                    "Importe"=>number_format($$total_retention_taxes,2,'.','')
                ),$original_string
            );
        
            self::loadAtt($total_taxes, array(
                    "TotalImpuestosRetenidos"=>number_format($total_retention_taxes,2,'.','')
                )
            );
        }

        $total_transfers = $xml->createElement("cfdi:Traslados");
        $total_transfers = $total_taxes->appendChild($total_transfers);

        $total_transfer = $xml->createElement("cfdi:Traslado");
        $total_transfer = $total_transfers->appendChild($total_transfer);

        self::loadAtt($total_transfer, array(
            "Impuesto"=>"002",
            "TipoFactor"=>"Tasa",
            "TasaOCuota"=>"0.160000",
            "Importe"=>number_format($total_transfered_taxes,2,'.','')
            ),$original_string
        );

        self::loadAtt($total_taxes, array(
            "TotalImpuestosTrasladados"=>number_format($total_transfered_taxes,2,'.','')
            ),$original_string
        );

        $complement = $xml->createElement("cfdi:Complemento");
        $complement = $root->appendChild($complement);
     
        $original_string .= "|";

        //$keyid = openssl_get_privatekey(file_get_contents($senda_PEMS.$file_key));
        $keyid = openssl_get_privatekey(file_get_contents($dir_root.$senda_PEMS.$file_key));
        openssl_sign($original_string, $crypttext, $keyid, OPENSSL_ALGO_SHA256);
        openssl_free_key($keyid);

        $seal = base64_encode($crypttext);

        $file = $dir_root.$senda_PEMS.$file_cer;
        $data = file($file);
        $certificate = "";
        $load=false;
        for ($i=0; $i<sizeof($data); $i++){
            if (strstr($data[$i],"END CERTIFICATE")) $load=false;
            if ($load) $certificate .= trim($data[$i]);

            if (strstr($data[$i],"BEGIN CERTIFICATE")) $load=true;
        }

        $root->setAttribute("Sello",$seal);
        $root->setAttribute("Certificado",$certificate);

        $name_file_invoice = $dir_root.$senda_CFDI."Pre_Factura".$invoice_number.".xml";
        $cfdi_xml = $xml->saveXML();
        $xml->formatOutput = true;
        $xml->save($name_file_invoice); // Guarda el archivo .XML (sin timbrar) en el directorio predeterminado.
        unset($xml);

        chmod($name_file_invoice, 0777);

        if($preview_mode){
            $response['nameFileXML'] = "Pre_Factura".$invoice_number.".xml";
            $response['nameFilePDF'] = "Pre_Factura".$invoice_number.".pdf";
            
            return response()->json($response);
        }

        $xml2 = new \DOMDocument();
        $xml2->loadXML($cfdi_xml);

        $xml_cfdi_base64 = base64_encode($cfdi_xml);

        $process  = curl_init('https://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl');

        #== 12.4 Datos de acceso al servidor de producción =========================
        #$process = curl_init('https://facturacion.finkok.com/servicios/soap/stamp.wsdl');

        $cfdixml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:ns0="http://schemas.xmlsoap.org/soap/envelope/"
    xmlns:ns1="http://facturacion.finkok.com/stamp"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
    <SOAP-ENV:Header/>
    <ns0:Body>
        <ns1:stamp>
            <ns1:xml>$xml_cfdi_base64</ns1:xml>
            <ns1:username>$username</ns1:username>
            <ns1:password>$password</ns1:password>
        </ns1:stamp>
    </ns0:Body>
</SOAP-ENV:Envelope>
XML;

        $file_soap = $dir_root.$senda_CFDI."DatosEnvio_Factura_".$invoice_number.".xml";

        if (file_exists ($file_soap)==true){
            unlink($file_soap);
        }

        $fp = fopen($file_soap,"a");
        fwrite($fp, $cfdixml);
        fclose($fp);
        chmod($file_soap, 0777);

        $response_pack = self::curlCall($process, $cfdixml);

        $var_xml = new \DOMDocument();
        $var_xml->loadXML($response_pack);
        $var_xml->save($dir_root.$senda_CFDI . "RespServ_Factura_" . $invoice_number . ".xml");
        chmod($dir_root.$senda_CFDI . "RespServ_Factura_" . $invoice_number . ".xml", 0777);

        $response_pack = $var_xml->getElementsByTagName('xml');

        $response['response_pack'] = $response_pack;

        $node_value = '';

        foreach($response_pack as $node){
            $node_value = $node->nodeValue;
        }

        if($node_value != ""){
            $name_file_XML = "Factura_" . $invoice_number . ".xml";
            $name_file_PDF = "Factura_" . $invoice_number . ".pdf";

            $xmlt = new \DOMDocument();
            $xmlt->loadXML($node_value);
            $xmlt->save($dir_root.$senda_CFDI.$name_file_XML);
            chmod($dir_root.$senda_CFDI.$name_file_XML, 0777);

            $docXML = new \DOMDocument();
            $docXML->load($dir_root.$senda_CFDI.$name_file_XML);

            $voucher = $docXML->getElementsByTagName("TimbreFiscalDigital");

            foreach($voucher as $v){
                $version = $v->getAttribute('Version');
                $seal_SAT      = $v->getAttribute('SelloSAT');
                $cert_SAT       = $v->getAttribute('NoCertificadoSAT');
                $seal_CFDI      = $v->getAttribute('SelloCFD');
                $stamped_date      = $v->getAttribute('FechaTimbrado');
                $uuid       = $v->getAttribute('UUID');
            }

            //Guardamos la factura en base
            $invoice = Invoice::create([
                'customer_id' => $customer_id,
                'serie' => $invoice_serie,
                'invoice_number' => $invoice_folio,
                'uuid' => $uuid,
                'amount' => $total,
                'comments' => $comments,
                'digits' => $digits,
                'payment_method' => $payment_method,
                'payment_way' => $payment_way,
                'cfdi' => $cfdi,
                'stamp_date' => $stamped_date,
                'num_cer_sat' => $cert_SAT,
                'xml' => $name_file_XML,
                'status' => 1,
                'created_by' => $user_id
            ]);

            $response['name_file_xml'] = $name_file_XML;
            $response['original_string'] = $original_string;
            $response['invoice'] = $invoice;
        }

        return response()->json($response);
    }

    function loadAtt(&$node, $attr, &$original_string){
        $remove = array('sello'=>1,'noCertificado'=>1,'certificado'=>1);
        foreach ($attr as $key => $val){
            $val = preg_replace('/\s\s+/', ' ', $val);
            $val = trim($val);
            if (strlen($val)>0){
                $val = str_replace("|","/",$val);
                $node->setAttribute($key,$val);
                if (!isset($remove[$key]))
                    if (substr($key,0,3) != "xml" &&
                        substr($key,0,4) != "xsi:")
                        $original_string .= $val . "|";
            }
        }
    }

    public function curlCall($url, $params){
        curl_setopt($url, CURLOPT_HTTPHEADER, array('Content-Type: text/xml',' charset=utf-8'));
        curl_setopt($url, CURLOPT_POSTFIELDS, $params);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POST, true);
        curl_setopt($url, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($url, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($url);
        curl_close($url);

        return $response;
    }

    public function getXML(Request $req){
        $response = ['success' => false];

        $config = collect(DB::select("SELECT 
        GROUP_CONCAT( if(config_name = 'dirFilessCFDI',string_value,NULL) ) AS 'dirFilessCFDI',
        GROUP_CONCAT( if(config_name = 'dirRoot',string_value,NULL) ) AS 'dirRoot'
        FROM Tbl_Sys_Configs"))->first();

        $invoice = Invoice::where('id', $req->invoice_id)->first();

        if($invoice){
            if($invoice->xml != null){
                $dir_xml = storage_path($config->dirRoot) . $config->dirFilessCFDI . $invoice->customer_id . "/" . $invoice->xml; 
                //return $dir_xml;
                if (file_exists ($dir_xml)==true){
                    $xml = file_get_contents($dir_xml);

                    $response['success'] = true;
                    $response['message'] = 'Archivo encontrado';
                    $response['xml_base64'] = base64_encode($xml);
                    $response['invoice'] = $invoice;
                }else{
                    $response['message'] = 'Archivo no encontrado';
                    $response['dir_xml'] = $dir_xml;
                }
            }else{
                $response['message'] = 'Factura sin XML';
            }
        }

        return response()->json($response); 
    }
}
