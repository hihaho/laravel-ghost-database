<?php

return [
    /**
     * The disk where your database exports will be stored
     */
    'disk' => 'ghost-db-exports',

    /**
     * This is the default connection that will be used for exports
     * If this is null it will use database.default
     */
    'export_connection' => null,

    /**
     * The default connection for the ghost database.
     * Exports will (by default) be imported to this database
     */
    'default_connection' => 'ghost-database',
];
