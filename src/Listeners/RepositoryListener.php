<?php

namespace CrCms\Repository\Listeners;

use CrCms\Repository\AbstractRepository;


/**
 * Class RepositoryListener
 * @package CrCms\Repository\Listeners
 */
class RepositoryListener
{

    public function creating(AbstractRepository $repository)
    {
        $data = $repository->getData();

        $schema = $repository->getModel()->getConnection()->select('describe ' . $repository->getModel()->getTable());

        foreach ($schema as $field) {
            if (
                stripos($field->Type, 'int') !== false
                /*||
                stripos($field->Type,'tinyint') !== false ||
                stripos($field->Type,'smallint') !== false ||
                stripos($field->Type,'smallint') !== false ||
                stripos($field->Type,'mediumint') !== false ||
                stripos($field->Type,'bigint') !== false ||
                stripos($field->Type,'integer') !== false*/
            ) {
                if (stripos($field->Type, 'unsigned') === false) {
//                    $data
                }
            }
        }

        dd(
            $repository->getModel()->getConnection()->select('describe ' . $repository->getModel()->getTable())
        );

        echo '<pre>';
        $a = ($repository->getModel()->getConnection()->getSchemaBuilder()->getColumnListing(
            $repository->getModel()->getTable()
        ));
        var_dump(

            $repository->getModel()->getConnection()->select('describe ' . $repository->getModel()->getTable())
        );
        echo '</pre>';
    }

}