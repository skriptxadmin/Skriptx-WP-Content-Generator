<?php if ( ! defined( 'ABSPATH' ) ) exit;?>

<div class="wrap">
    <div class="flex justify-between items-start">
        <h1 class="wp-heading-inline">Prompts</h1>

        <a href="<?php echo esc_url(admin_url('admin.php?page=skriptx-congen--prompts&view=create&id=0')); ?>"
           class="page-title-action">
            New Prompt
        </a>
    </div>
</div>

<div class="clear"></div>

<div class="wrap">
    <table class="wp-list-table widefat fixed striped table-view-list skriptx-congen-prompts">
        <thead>
            <tr>
                <th>Prompt</th>
                <th>Language</th>
                <th>Frequency</th>
                <th>Image</th>
                <th>Last Run</th>
                <th>Next Run</th>
                <th>Total Runs</th>
                <th>Active</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        </tbody>
    </table>
</div>