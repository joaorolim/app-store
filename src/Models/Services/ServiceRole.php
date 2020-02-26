<?php

namespace BET\Models\Services;

use Slim\Container;
use BET\Models\Services\AbstractServiceModel;
use BET\Models\Contracts\IModel;

class ServiceRole extends AbstractServiceModel
{
    public function __construct( Container $c, IModel $role )
    {
        parent::__construct( $c, $role );
    }

    public function delete()
    {
        $id = $this->getModel()->getId();
        $objServicePermission = $this->c->ServicePermission;

        $this->db->beginTransaction();

        if ( $objServicePermission->deletePermissionsByRole( $id ) ) {

            if ( parent::delete() ) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }

        }

        $this->db->rollback();
        return false;
    }

}
