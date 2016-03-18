<?php
use App\Model\UserModel;

$app->group('/inf/', function () {

    $this->get('test', function ($req, $res, $args) {
        return $res->getBody()
                   ->write('Hello Users');
    });

    $this->get('usuarios[{desde},{hasta}]', function ($req, $res, $args) {
        $db = new UserModel('sa','t1c9gvd$','delphinus_etravel_3f','SqlServer','192.168.8.19');
        $resultado = $db->getData("select
l.nom_locacion, h.nom_hotel ,hora_pickup,r.fecha_servicio, coalesce(nullif(pax,0),0)+coalesce(nullif(paxn,0),0) as pax, coalesce(nullif(r.pax_incentivos_finales,0),0) as acomp, r.folio_reserva
from rsrv_reservas as r inner join rsrv_hoteles as h on r.id_hotel = h.id_hotel inner join
ventas_locaciones as l on r.id_locacion = l.id_locacion
where r.fecha_servicio >= ?, r.fecha_servicio <= ? and r.hora_pickup not in ('NA') and r.hora_pickup is not null and r.status=3");

        return $res
           ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $resultado
            )
        );
    });

});
