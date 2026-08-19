<?php
//declare(strict_types=1);

namespace Pointerp\Controladores;

use Phalcon\Di;
use Phalcon\Mvc\Model\Query;
use Pointerp\Modelos\Claves;
use Pointerp\Modelos\Maestros\Clientes;

class MaestrosController extends ControllerBase  {
  
  public function clientesPorCedulaAction() {
    $ced = $this->dispatcher->getParam('ced');
    $rows = Clientes::find([
      'conditions' => 'identificacion = :ced: AND estado = 0',
      'bind' => [ 'ced' => $ced ]
    ]);
    if ($rows->count() > 0) {
      $this->response->setStatusCode(200, 'Ok');
    } else {
      $this->response->setStatusCode(404, 'Not found');
    }
    $this->response->setContentType('application/json', 'UTF-8');
    $this->response->setContent(json_encode($rows,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    $this->response->send();
  }

  public function clientesPorNombresEstadoAction() {
    $estado = $this->dispatcher->getParam('estado');
    $filtro = $this->dispatcher->getParam('filtro');
    $filtroSP = str_replace('%20', ' ', $filtro);
    $filtroSP = str_replace('  ', ' ',trim($filtroSP));
    // eñes
    $filtroSP = str_replace('%C3%91' , 'Ñ',$filtroSP);
    $filtroSP = str_replace('%C3%B1' , 'ñ',$filtroSP);
    $filtro = str_replace(' ' , '%',$filtroSP) . '%';
    
    $condicion = 'UPPER(nombres) like UPPER(:fil:)';
    if ($estado == 0) {
        $condicion .= ' AND estado = 0';
    }
    $rows = Clientes::find([
      'conditions' => $condicion,
      'bind' => [ 'fil' => $filtro ]
    ]);
    if ($rows->count() > 0) {
      $this->response->setStatusCode(200, 'Ok');
    } else {
      $this->response->setStatusCode(404, 'Not found ' . $filtro);
    }
    $this->response->setContentType('application/json', 'UTF-8');
    $this->response->setContent(json_encode($rows,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    $this->response->send();
  }

}