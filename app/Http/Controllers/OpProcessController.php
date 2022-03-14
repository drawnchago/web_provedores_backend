<?php

namespace App\Http\Controllers;

use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Validation;
use App\Models\ModelBomb;
use App\Models\TypesBomb;
use App\Models\BrandBomb;
use App\Models\Customers;
use App\Models\User;
use App\Models\Role;
use App\Models\KindOfPerson;
use App\Models\CFDI;
use App\Models\Bank;
use App\Models\Xml;

class OpProcessController extends Controller
{
    public function __construct()
    {
        //
    }

    // public function getProviders(Request $req){

    //     $response = ['success' => false ,'message' => "No se encontrarón registros"];

    //     $rows = db::connection('avalanz')->select("SELECT * FROM DADOSADV.ZWP200");

    //     if(count($rows) > 0){
    //         $response['success'] = true;
    //         $response['message'] = "Se encontrarón registros";
    //     }

    //     $response['rows'] = $rows;

    //     return $response;
    // }

    public function getValidationsXml(Request $req){

        $response = ['success' => false ,'message' => "No se encontrarón registros"];

        $rows = Validation::where('status',1)->orderBy('id', 'asc')->get();

        if(count($rows) > 0){
            $response['success'] = true;
            $response['message'] = "Se encontrarón registros";
        }

        $response['rows'] = $rows;

        return $response;
    }

    public function checkAndSaveFile(Request $req){

        $response = ['success' => true ,'message' => "!Carga Exitosa¡"];

        if (!empty($_FILES)) {

              //NOMBRE DEL ARCHIVO
              $fileName = $_FILES['file']['name'];
              //TIPO DE ARCHIVO
              $fileType = $_FILES['file']['type'];
              

              try{
                  //GUARDAMOS EL ARCHIVO EN EL DIRECTORIO
                  $tempFile   = $_FILES['file']['tmp_name'];
                  $targetFile = 'C:\doc_web\\'.$fileName;
                //   $adapter    = Storage::disk('sftp_cnci')->getDriver()->getAdapter();
                //   $adapter->disconnect();
                //   $adapter->connect();
  
                //   if($adapter != null){
  
                    $exists = move_uploaded_file($tempFile,$targetFile);

                      //$exists = file_exists('C:\doc_web\\dummy.xml');
                      //$file = file_get_contents($tempFile, "rb");
                     // Storage::disk('sftp_cnci')->put($targetFile, $file);
  

                     if($exists){

                        if($fileType != "text/xml"){
                            return $response;
                        }

                        $xml = file_get_contents($targetFile, "rb");

                        $DOM = new \DOMDocument('1.0', 'utf-8');
                        $DOM->preserveWhiteSpace = FALSE;
                        $DOM->loadXML($xml);

                        $UUID             = "";
                        $noCertificadoSAT = "";
                        $selloCFD         = "";
                        $selloSAT         = "";
                            
                        $params = $DOM->getElementsByTagName('Comprobante');
                        foreach ($params as $param) {
                            $moneda              = $param->getAttribute('Moneda');
                            $tipo_comprobante    = $param->getAttribute('TipoDeComprobante');
                            $folio               = $param->getAttribute('Folio');
                            $forma_pago          = $param->getAttribute('FormaPago');
                            $no_certificado      = $param->getAttribute('Certificado');
                            $subTotal            = $param->getAttribute('SubTotal');
                            $tipo_cambio         = $param->getAttribute('TipoCambio');
                            $metodo_pago         = $param->getAttribute('MetodoPago');
                            $lugar_expedicion    = $param->getAttribute('LugarExpedicion');
                            $fecha               = $param->getAttribute('Fecha');
                            $version             = $param->getAttribute('Version');
                            $total               = $param->getAttribute('Total');
                        }

                        
                        $params = $DOM->getElementsByTagName('Emisor');
                        $i=0;
                        foreach ($params as $param) {
                            if ($i==0){
                            $emisor_nom          = $param->getAttribute('Nombre');
                            $emisor_regimen      = $param->getAttribute('RegimenFiscal');
                            $emisor_rfc          = $param->getAttribute('Rfc');
                            }
                            $i++;
                        }   
                        
                        $params = $DOM->getElementsByTagName('Receptor');
                        $i=0;
                        foreach ($params as $param) {
                            if ($i==0){
                            $receptor_Nom        = $param->getAttribute('Nombre');
                            $receptor_RFC        = $param->getAttribute('Rfc');
                            $receptor_UsoCFDI    = $param->getAttribute('UsoCFDI');
                            }
                            $i++;
                        }   

                        $params = $DOM->getElementsByTagName('TimbreFiscalDigital');
                        foreach ($params as $param) {
                            $UUID                   = $param->getAttribute('UUID');
                            $no_certificado_SAT     = $param->getAttribute('NoCertificadoSAT');
                            $sello_CFDI             = $param->getAttribute('SelloCFD');
                            $sello_SAT              = $param->getAttribute('SelloSAT');
                        }    
                        
                        
                        $i=0; $ImpTot = 0; $SumaImportes = 0;
                        $params = $DOM->getElementsByTagName('Concepto');
                        foreach ($params as $param) {
                            $ArrayClaveProdServ[$i]      = $param->getAttribute('ClaveProdServ');
                            $ArrayPreUni[$i]             = $param->getAttribute('ValorUnitario');
                            $ArrayClaveUnidad[$i]        = $param->getAttribute('ClaveUnidad');
                            $ArrayNoIdentificacion[$i]   = $param->getAttribute('NoIdentificacion');
                            $ArrayImporte[$i]            = $param->getAttribute('Importe');
                            $ArrayCant[$i]               = $param->getAttribute('Cantidad');
                            $ArrayArtSer[$i]             = $param->getAttribute('Descripcion');
                            $ArrayUnidad[$i]             = $param->getAttribute('Unidad');
                            // $ArrayUniMed[$i]             = $param->getAttribute('Unidad');
                            $SumaImportes                = $SumaImportes + $ArrayImporte[$i];
                            $i++;

                        }       
                        
                        $ImporteTotalIVA            = 0;
                        $ImporteTotalIEPS           = 0;
                        $ultimoImporteIVA           = 0;
                        $ultimoImporteIEPS          = 0;

                        $ImporteTotalIVARet         = 0;
                        $ultimoImporteIVARet        = 0; 
                        
                    $TotImpuestos = 0;     
                    $params = $DOM->getElementsByTagName('Traslado');
                    foreach ($params as $param) {

                        $TotImpuestos               =  $TotImpuestos + (float) $param->getAttribute('Importe');

                        // IVA
                        if ($param->getAttribute('Impuesto')=="002"){ 
                            $ImporteTotalIVA        = $ImporteTotalIVA + (float) $param->getAttribute('Importe');
                            $ultimoImporteIVA       = $param->getAttribute('Importe');
                        }
                        // IEPS
                        if ($param->getAttribute('Impuesto')=="003"){ 
                            $ImporteTotalIEPS       = $ImporteTotalIEPS + $param->getAttribute('Importe');
                            $ultimoImporteIEPS      = $param->getAttribute('Importe');
                        }
                    }

                    //RETENCIONES
                    $TotRetencion = 0;
                    $paramsR = $DOM->getElementsByTagName('Retencion');
                    foreach ($paramsR as $paramR) {
                        $TotRetencion               =  $TotRetencion + (float) $paramR->getAttribute('Importe');
                        // IVA
                        if ($paramR->getAttribute('Impuesto')=="002"){
                            $ImporteTotalIVARet     = $ImporteTotalIVARet + (float) $paramR->getAttribute('Importe');
                            $ultimoImporteIVARet    = $paramR->getAttribute('Importe');
                        }

                        $ImporteTotalIVARet         = $ImporteTotalIVARet - $ultimoImporteIVARet;
                        $ImporteTotalIVA            = $ImporteTotalIVA - $ultimoImporteIVA;
                        $ImporteTotalIEPS           = $ImporteTotalIEPS - $ultimoImporteIEPS;  
                    }
                        
                    $CadOri = "||".$UUID."|".$fecha."|".$selloCFD."|".$no_certificado."||";
                        
                    ## 3. CREAR ARCHIVO .PNG CON CODIGO BIDIMENSIONAL
                    $CadImpTot                  = self::ProcesImpTot($total);

                    $xml = Xml::updateOrCreate(
                        ['folio'=> $folio],
                        [
                            'nombre_receptor'   => $receptor_Nom,
                            'rfc_receptor'      => $receptor_RFC,
                            'cfdi_receptor'     => $receptor_UsoCFDI,
                            'moneda'            => $moneda,
                            'tipo_comprobante'  => $tipo_comprobante,
                            'forma_pago'        => $forma_pago,
                            'no_certificado'    => $no_certificado,
                            'subtotal'          => $subTotal,
                            'tipo_cambio'       => $tipo_cambio,
                            'metodo_pago'       => $metodo_pago,
                            'lugar_expedicion'  => $lugar_expedicion,
                            'fecha'             => $fecha,
                            'version'           => $version,
                            'total'             => $total,
                            'uuid'              => $UUID,
                            'no_certificado_sat'=> $no_certificado_SAT,
                            'sello_cfdi'        => $sello_CFDI,
                            'sello_sat'         => $sello_SAT,
                            'cadOri'            => $CadOri,
                            'CadImpTot'         => $CadImpTot,
                            // 'created_at'=>
                            // 'updated_at'=>
                            // 'updated_by'=>

                        ]
                    );

                    //<<COMPROBANTE>>
                    $response['success']              =  true;
                    $response['id']                   = $xml->id;
                    $response['moneda']               = $moneda;
                    $response['tipo_comprobante']     = $tipo_comprobante;
                    $response['folio']                = $folio;
                    $response['forma_pago']           = $forma_pago;
                    $response['no_certificado']       = $no_certificado;
                    $response['subTotal']             = $subTotal;
                    $response['tipo_cambio']          = $tipo_cambio;
                    $response['metodo_pago']          = $metodo_pago;
                    $response['lugar_expedicion']     = $lugar_expedicion;
                    $response['fecha']                = $fecha;
                    $response['version']              = $version;
                    $response['total']                = $total;
                    //<<EMISOR>>
                    $response['emisor_nom']            = $emisor_nom;
                    $response['emisor_regimen']        = $emisor_regimen;
                    $response['emisor_rfc']            = $emisor_rfc;
                    //<<RECEPTOR>>
                    $response['receptor_Nom']          = $receptor_Nom;
                    $response['receptor_RFC']          = $receptor_RFC;
                    $response['receptor_UsoCFDI']      = $receptor_UsoCFDI;
                    //<<TIMBREFISCALDIGITAL>>
                    $response['UUID']                  = $UUID;
                    $response['no_certificado_SAT']    = $no_certificado_SAT;
                    $response['sello_CFDI']            = $sello_CFDI;
                    $response['sello_SAT']             = $sello_SAT;
                    //<<CONCEPTO>>
                    $response['ArrayClaveProdServ']    = $ArrayClaveProdServ;
                    $response['ArrayPreUni']           = $ArrayPreUni;
                    $response['ArrayNoIdentificacion'] = $ArrayNoIdentificacion;
                    $response['ArrayImporte']          = $ArrayImporte;
                    $response['ArrayCant']             = $ArrayCant;
                    $response['ArrayArtSer']           = $ArrayArtSer;
                    $response['ArrayUnidad']           = $ArrayUnidad;
                    $response['SumaImportes']          = $SumaImportes;
                    $response['ArrayClaveUnidad']      = $ArrayClaveUnidad;
                    $response['ArrayClaveUnidad']      = $ArrayClaveUnidad;
                    //<<TRASLADO>>
                    $response['TotImpuestos']          = $TotImpuestos;
                    $response['ImporteTotalIVA']       = $ImporteTotalIVA;
                    $response['ultimoImporteIVA']      = $ultimoImporteIVA;
                     //<<RETENCION>>
                    $response['TotRetencion']          = $TotRetencion;
                    $response['ImporteTotalIVARet']    = $ImporteTotalIVARet;
                    $response['ImporteTotalIVA']       = $ImporteTotalIVA;
                    $response['ImporteTotalIEPS']      = $ImporteTotalIEPS;
                    //<<EXTRAS>>
                    $response['CadOri']                = $CadOri;
                    $response['CadImpTot']             = $CadImpTot;

                    }else{
                        $response['success']           = false;
                        $response['message']           = "No se cargo correctamente, intente nuevamente";
                    }
  
              }catch(Exception $e){
                  $response[] = ['success' => false, 'error' => $e->getMessage()];
              }
        }else{
            $response['success']           = false;
            $response['message']           = "No se cargo correctamente, intente nuevamente";
        }
  
          return response()->json($response);
    }

    public function ProcesImpTot($ImpTot){
        $ImpTot = number_format($ImpTot, 4); // <== Se agregó el 30 de abril de 2017.
        $ArrayImpTot = explode(".", $ImpTot);
        $NumEnt = $ArrayImpTot[0];
        $NumDec = self::ProcesDecFac($ArrayImpTot[1]);

        return $NumEnt.".".$NumDec;
    }

    public function ProcesDecFac($Num){
        $FolDec = "";
        if ($Num < 10){$FolDec = "00000".$Num;}
        if ($Num > 9 and $Num < 100){$FolDec = $Num."0000";}
        if ($Num > 99 and $Num < 1000){$FolDec = $Num."000";}
        if ($Num > 999 and $Num < 10000){$FolDec = $Num."00";}
        if ($Num > 9999 and $Num < 100000){$FolDec = $Num."0";}
        return $FolDec;
    }
}
