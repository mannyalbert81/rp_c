<?php
/**
 * Created by PhpStorm.
 * User: svycar
 * Date: 23/4/18
 * Time: 14:41
 */

namespace Shara;

use Shara\Comprobantes\Comprobante;

class ComprobantesController
{
    private $config;

    public function __construct(array $config)
    {
        // zona horaria
        date_default_timezone_set('America/Bogota');
        $this->config = $config;
    }

    public function getConsultarAutorizacion($clave)
    {

        //CREAR COMPROBANTE
        $firma = array(
            'file' => $this->config["pathfirma"],
            'pass' => $this->config["passfirma"]);

        $comprobante = new Comprobante($this->config);
        $comprobante->setClaveAcesso($clave);

        $resp = $comprobante->autorizacionIndividualSri('01');


        /*if ($resp['error'] === false && $resp['autorizado'] === true) {
            $resp = $comprobante->generarPdf($this->twig, '01');
        }*/

        return $resp;
    }

    /**
     * Consultar Autorizacion XML method
     * @param string $clave clave de acceso
     *
     * @return string
     */
    public function autorizacionXml($clave)
    {
        try {
            //AUTORIZAR SRI
            if (strlen($clave) != 49)
                return array('error' => true, 'mensaje' => 'longitud clave de acceso mal estructura');

            $tipoComprobante = substr($clave, 8, 2);

            sleep(2);
            $comprobante = new Comprobante($this->config);
            $comprobante->setClaveAcesso($clave);
            $resp = $comprobante->autorizacionIndividualSri($tipoComprobante);

            if ($resp['error'] === false && $resp['autorizado'] === true) {
                //generar pdf y envio mail cliente
                return $resp;

            } else
                return array('error' => true, 'estado' => $resp["estado"], 'mensaje' => $resp["mensaje"]);

        } catch (\Exception $ex) {
            return array('error' => true, 'estado' => 'excepcion: '.$ex->getLine(), 'mensaje' => $ex->getMessage());
        }

    }

    /**
     * Enviar XML method
     * @param string $clave clave de acceso
     *
     * @return string
     */
    public function enviarXml($clave)
    {

    try {
            if (file_exists($this->config['firmados'] . DIRECTORY_SEPARATOR . $clave . ".xml") === false)
                return array('error' => true, 'mensaje' => 'documento firmado no existe');

            if (strlen($clave) != 49)
                return array('error' => true, 'mensaje' => 'longitud clave de acceso mal estructura');

            $tipoComprobante = substr($clave, 8, 2);

            //ENVIAR SRI
            $comprobante = new Comprobante($this->config);
            $comprobante->setClaveAcesso($clave);
            $resp = $comprobante->envioIndividualSri($tipoComprobante);

            if ($resp != null) {

                //if (!unlink($this->config['generados'] . DIRECTORY_SEPARATOR . $clave . '.xml')) {
                //    $resp['error'] = true;
                //    $resp['mensaje'] = 'error al eliminar el documento: ' . $this->config['generados'] . DIRECTORY_SEPARATOR . $clave . '.xml';
                //}
            }

        } catch (\Exception $ex) {
            return array('error' => true, 'mensaje' => $ex->getMessage());
        }
        return $resp;

    }

    /**
     * Crear validar firmar XML method
     * @param string $xmlString string xml del comprobante
     * @param string $clave clave de acceso
     *
     * @return string
     */
    public function validarFirmarXml($xmlString, $clave)
    {

        try {

            $xmlString = trim($xmlString);

            if (strlen($clave) != 49)
                return array('error' => true, 'mensaje' => 'longitud clave de acceso mal estructura');

            $tipoComprobante = substr($clave, 8, 2);

            $pathXsdName = "";
            //VALIDAR XML
            switch ($tipoComprobante){
                case '01':
                    $pathXsdName = $this->config['xsd'] . DIRECTORY_SEPARATOR . 'factura.xsd';
                    break;
                case '04':
                    $pathXsdName = $this->config['xsd'] . DIRECTORY_SEPARATOR . 'notaCredito.xsd';
                    break;
                case '06':
                    $pathXsdName = $this->config['xsd'] . DIRECTORY_SEPARATOR . 'guiaRemision.xsd';
                    break;
                case '07':
                    $pathXsdName = $this->config['xsd'] . DIRECTORY_SEPARATOR . 'comprobanteRetencion.xsd';
                    break;
            }

            $resp = Comprobante::validarXml($xmlString, $pathXsdName);

            if ($resp != null) return $resp;

            if (file_put_contents($this->config['generados'] . DIRECTORY_SEPARATOR . $clave . ".xml", $xmlString) === false)
                return array('error' => true, 'mensaje' => 'no se puede crear el archivo xml en: ' . $this->config['generados']);


            $firma = array(
                'file' => $this->config['pathFirma'],
                'pass' => $this->config['passFirma'],
                'generados' => $this->config['generados'],
                'firmados' => $this->config['firmados']
            );

            $resp = Comprobante::firmarComprobante($firma, $clave);

            return $resp;


        } catch (\Exception $ex) {
            return array('error' => true, 'mensaje' => $ex->getMessage());
        }

    }

}