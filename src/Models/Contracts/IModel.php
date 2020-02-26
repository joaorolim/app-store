<?php

namespace BET\Models\Contracts;

interface IModel
{
    public function getTable();
    public function getFieldList();
    public function getPk();
    public function getCreatedAt();
    public function setCreatedAt( $created_at );
    public function getUpdatedAt();
    public function setUpdatedAt( $updated_at );
    public function getQtd();
    public function setQtd( $qtd );
}
