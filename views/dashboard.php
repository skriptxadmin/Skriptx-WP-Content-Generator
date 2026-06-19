<?php if ( ! defined( 'ABSPATH' ) ) exit;?>

<div class="wrap">
    <div class="postbox">
        <div class="postbox-header">
            <h2>Dashboard</h2>
        </div>

        <div class="inside">
            <div class="flex justify-between items-start">
                <div>
                    <ul>
                        <li>Next prompter at: <span class="ajax-prompter"></span></li>
                             <li>Next scheduler at:<span class="ajax-scheduler"></span></li>
                             <li>Next jobber at:<span class="ajax-jobber"></span></li>
                             <li>Next image at:<span class="ajax-imgmaker"></span></li>
                             <li>Server Time:<span class="ajax-current"></span></li>
                             <li>Secret Key:<span class="ajax-secret"></span></li>
                             <li>Database:<span class="ajax-db"></span></li>
                    </ul>
                </div>
                <div class="flex justify-end items-center">
                    <button class="button regenerate-secret-key">Regenerate Secret Key</button>
                    <button class="button factory-reset">Factory Reset</button>
                </div>
            </div>
            <div class="stats-grid">

                <div class="stat-card">
                    <h4 class="ajax-success"></h4>
                    <h6 >Success</h6>
                </div>

                <div class="stat-card">
                    <h4 class="ajax-error"></h4>
                    <h6>Error</h6>
                </div>

                <div class="stat-card">
                    <h4 class="ajax-queued"></h4>
                    <h6>Queued</h6>
                </div>

                <div class="stat-card">
                    <h4 class="ajax-running"></h4>
                    <h6>Running</h6>
                </div>

            </div>
        </div>
    </div>
</div>
