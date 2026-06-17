<?php if ( ! defined( 'ABSPATH' ) ) exit;?>


<?php
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin navigation parameter.
$id = isset($_GET['id']) ? absint(wp_unslash($_GET['id'])) : null;

$skriptx_congen_promptId = $id;
?>

<div class="wrap">
    <form class="prompt" method="POST">
        <input type="hidden" id="promptId" name="promptId" value="<?php echo esc_attr($skriptx_congen_promptId); ?>">

        <div class="form-group">
            <label for="prompt">Prompt Text</label>
            <textarea name="prompt" id="prompt" class="w-100" rows="4" required minlength="10" maxlength="500"
                placeholder="Enter prompt to generate content"></textarea>
        </div>

        <div class="form-group">
            <label for="language">Language</label>

            <input type="text" name="language" id="language" class="w-100" required minlength="3" maxlength="50"
                placeholder="Enter language to generate content" />
        </div>

        <div>
            <h4>Frequency content to be generated</h4>

            <div class="flex justify-start items-start gap-2">
                <div class="form-group">
                    <label for="hours">Hours</label>
                    <select id="hours" name="hours" aria-label="Select hours" required></select>
                </div>

                <div class="form-group">
                    <label for="mins">Minutes</label>
                    <select id="mins" name="hours" aria-label="Select mins" required></select>
                </div>
            </div>

            <p class="frequency-text"></p>
        </div>

        <div class="flex justify-end items-center">
            <button type="submit" class="button button-primary">Submit</button>
        </div>
    </form>
</div>