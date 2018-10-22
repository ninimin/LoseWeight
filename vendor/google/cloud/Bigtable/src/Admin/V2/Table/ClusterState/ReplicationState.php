<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/bigtable/admin/v2/table.proto

namespace Google\Cloud\Bigtable\Admin\V2\Table\ClusterState;

/**
 * Table replication states.
 *
 * Protobuf type <code>google.bigtable.admin.v2.Table.ClusterState.ReplicationState</code>
 */
class ReplicationState
{
    /**
     * The replication state of the table is unknown in this cluster.
     *
     * Generated from protobuf enum <code>STATE_NOT_KNOWN = 0;</code>
     */
    const STATE_NOT_KNOWN = 0;
    /**
     * The cluster was recently created, and the table must finish copying
     * over pre-existing data from other clusters before it can begin
     * receiving live replication updates and serving Data API requests.
     *
     * Generated from protobuf enum <code>INITIALIZING = 1;</code>
     */
    const INITIALIZING = 1;
    /**
     * The table is temporarily unable to serve Data API requests from this
     * cluster due to planned internal maintenance.
     *
     * Generated from protobuf enum <code>PLANNED_MAINTENANCE = 2;</code>
     */
    const PLANNED_MAINTENANCE = 2;
    /**
     * The table is temporarily unable to serve Data API requests from this
     * cluster due to unplanned or emergency maintenance.
     *
     * Generated from protobuf enum <code>UNPLANNED_MAINTENANCE = 3;</code>
     */
    const UNPLANNED_MAINTENANCE = 3;
    /**
     * The table can serve Data API requests from this cluster. Depending on
     * replication delay, reads may not immediately reflect the state of the
     * table in other clusters.
     *
     * Generated from protobuf enum <code>READY = 4;</code>
     */
    const READY = 4;
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ReplicationState::class, \Google\Cloud\Bigtable\Admin\V2\Table_ClusterState_ReplicationState::class);

