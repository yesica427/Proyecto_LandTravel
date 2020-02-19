<?php

namespace App\Tours;

use React\MySQL\QueryResult;
use App\Responses\JsonResponse;
use function React\Promise\reject;

use function React\Promise\resolve;
use React\MySQL\ConnectionInterface;
use App\Tours\Exceptions\TipoDoesntExist;

final class Storage
{

    private $connection;

    function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function getAllTours()
    {
        return $this->connection->query('SELECT * FROM tours')
            ->then(
                function (QueryResult $result) {
                    if (!empty($result->resultRows)) {
                        return JsonResponse::OK(["data" => $result->resultRows]);
                    }
                }
            );
    }

    public function getOneTour(string $id)
    {
        return $this->connection->query('SELECT * FROM tours Where idTour = ?', [$id])
            ->then(
                function (QueryResult $result) {
                    if (!empty($result->resultRows)) {
                        return JsonResponse::OK(["data" => $result->resultRows]);
                    }
                }
            );
    }

    public function searchTour()
    {
    }

    public function createTour(
        string $nombre_t,
        string $fecha_i,
        string $estado,
        int $cantidad_p,
        float $precio_t,
        int $cupo,
        string $idTipo
    ) {
        return $this->checkTipoTour($idTipo)
            ->then(
                function () use ($nombre_t, $fecha_i, $estado, $cantidad_p, $precio_t, $cupo, $idTipo) {
                    $this->connection->query(
                        'INSERT INTO tour(nombreTour, fechaInicio, estado, cantidadPersonas, perceioTour, cupo, TipoTour_idTipoTour) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)',
                        [$nombre_t, $fecha_i, $estado, $cantidad_p, $precio_t, $cupo, $idTipo]
                    );
                    return new Tour($nombre_t, $fecha_i, $cantidad_p, $precio_t, $cupo, $estado);
                }
            );
    }

    public function createTourById(
        string $nombre_t,
        string $fecha_i,
        string $estado,
        int $cantidad_p,
        float $precio_t,
        int $cupo,
        string $idTipo,
        string $id
    ) {
        return $this->checkTipoTour($idTipo)
            ->then(
                function () use ($id, $nombre_t, $fecha_i, $estado, $cantidad_p, $precio_t, $cupo, $idTipo) {
                    return $this->connection->query(
                        'INSERT INTO tour(idTour, nombreTour, fechaInicio, estado, cantidadPersonas, perceioTour, cupo, TipoTour_idTipoTour) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)',
                        [$id, $nombre_t, $fecha_i, $estado, $cantidad_p, $precio_t, $cupo, $idTipo]
                    )->then(
                        function () {
                            return JsonResponse::CREATED();
                        }
                    );
                }
            );
    }

    public function updateOrCreate(
        string $nombre_t,
        string $fecha_i,
        string $estado,
        int $cantidad_p,
        float $precio_t,
        int $cupo,
        string $idTipo,
        string $id
    ) {
        return $this->checkTourExist($id)
            ->then(
                function (QueryResult $result) use ($nombre_t, $fecha_i, $estado, $cantidad_p, $precio_t, $cupo, $idTipo, $id) {
                    if (empty($result->resultRows)) {
                        return $this->createTourById(
                            $nombre_t,
                            $fecha_i,
                            $estado,
                            $cantidad_p,
                            $precio_t,
                            $cupo,
                            $idTipo,
                            $id
                        );
                    }

                    return $this->updateTour(
                        $id,
                        $nombre_t,
                        $fecha_i,
                        $estado,
                        $cantidad_p,
                        $precio_t,
                        $cupo,
                        $idTipo
                    );
                }
            );
    }

    public function checkTipoTour(string $idTipo)
    {
        return $this->connection->query(
            'SELECT 1 FROM tipotour WHERE idTipoTour = ?',
            [$idTipo]
        )
            ->then(
                function (QueryResult $result) {
                    return !empty($result->resultRows) ? resolve() : reject(new TipoDoesntExist());
                }
            );
    }

    public function checkTourExist(string $id)
    {
        return $this->connection->query('SELECT 1 FROM tour WHERE idTour = ?', [$id]);
    }

    public function updateTour(
        string $id,
        string $nombre_t,
        string $fecha_i,
        string $estado,
        int $cantidad_p,
        float $precio_t,
        int $cupo,
        string $idTipo
    ) {
        return $this->connection->query(
            'UPDATE tour SET

            `nombreTour` = ?,
            `fechaInicio` = ?,
            `estado` = ?,
            `cantidadPersonas` = ?,
            `perceioTour` = ?,
            `cupo` = ?,
            `TipoTour_idTipoTour` = ?
            WHERE `idTour` = ? ',
            [$nombre_t, $fecha_i, $estado, $cantidad_p, $precio_t, $cupo, $idTipo, $id]
        )->then(
            function () use ($nombre_t, $fecha_i, $estado, $cantidad_p, $precio_t, $cupo, $idTipo, $id) {
                return JsonResponse::ACCEPTED();
            }
        );
    }

    public function deleteTour(string $id)
    {
        return $this->checkTourExist($id)
            ->then(
                function (QueryResult $result) use ($id) {
                    if (!empty($result->resultRows)) {
                        return $this->connection->query('DELETE FROM tour WHERE idTour = ?', [$id]);
                    }
                }
            );
    }
}
