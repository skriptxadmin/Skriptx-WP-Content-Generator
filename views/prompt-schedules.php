<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$id = filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT );


$skriptx_congen_promptId = absint( $id );

?>

<div class="wrap">
    <div class="flex justify-between items-start">
        <h1 class="wp-heading-inline">Prompt Schedules</h1>

        <a href="<?php echo esc_url(admin_url('admin.php?page=skriptx-congen--prompts')); ?>"
           class="page-title-action">
            All Prompts
        </a>
    </div>
</div>

<div class="clear"></div>

<div class="wrap">
    <div class="postbox">
        <div class="postbox-header">
            <h2>Schedules</h2>
        </div>

        <div class="inside">
            <table
                data-prompt-id="<?php echo esc_attr($skriptx_congen_promptId); ?>"
                class="wp-list-table widefat fixed striped table-view-list skriptx-congen-schedules">

                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Post ID</th>
                        <th>Started At</th>
                        <th>Completed At</th>
                        <th>Error Message</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                </tbody>

            </table>
        </div>
    </div>
</div>