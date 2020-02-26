<?php

namespace BET\Models\Contracts;

use BET\Models\Services\AbstractServiceModel;

interface IServiceModel
{
    public function count( $date_from = null, $date_to = null );
    public function list( $pagina, $date_from = null, $date_to = null, $orderBy = null );
    public function makeArrayPagination( $pagina );
    public function find();
    public function insert();
    public function insertBatch( string $table, array $arrayFieldsValues ): bool;
    public function update();
    public function delete();
    public function getArrayPaginacao();
    public function closeConn();
}
