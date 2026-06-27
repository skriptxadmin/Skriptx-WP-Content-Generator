<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">

    <h1>Skriptx Content Generator</h1>

    <div class="notice notice-warning inline">
        <p><strong>Consent Required</strong></p>
        <p>
            Before you can use Skriptx Content Generator, you must consent to connect
            your website to the Skriptx Content Generation service.
        </p>
    </div>

    <div class="postbox">
        <div class="inside">

            <h2>Information that will be transmitted</h2>

            <p>
                By clicking <strong>I Agree &amp; Generate Secret Key</strong>, the following
                information will be securely transmitted to the Skriptx Content Generation
                service (<strong>congen.skriptx.com</strong>):
            </p>

            <ul style="list-style:disc;padding-left:25px;">
                <li>Site URL (Domain Name)</li>
                <li>Administrator Email Address</li>
                <li>AI prompts you create using this plugin</li>
                <li>Content generation requests initiated by this plugin</li>
            </ul>

            <h2>Why this information is required</h2>

            <ul style="list-style:disc;padding-left:25px;">
                <li>Generate and associate a unique secret key with your website.</li>
                <li>Authenticate your website with the Skriptx Content Generation service.</li>
                <li>Process AI content generation requests.</li>
                <li>Generate AI-powered content on your behalf.</li>
                <li>Return the generated content to your WordPress website.</li>
                <li>Allow scheduled publishing according to your configured schedules.</li>
            </ul>

            <div class="notice notice-info inline">
                <p>
                    <strong>No information will be transmitted until you explicitly provide your consent.</strong>
                </p>
            </div>

            <p>
                <label>
                    <input type="checkbox" id="skriptx_congen_consent">
                    I have read and understand that my Site URL, Administrator Email,
                    AI prompts and content generation requests will be transmitted to
                    the Skriptx Content Generation service for the purpose of generating
                    AI content. I consent to this transmission and understand that the
                    generated content may be automatically published according to my
                    configured schedules.
                </label>
            </p>

            <p>
                <button
                    type="button"
                    class="button button-primary"
                    id="skriptx_generate_key"
                    disabled>
                    I Agree &amp; Generate Secret Key
                </button>
            </p>

            <hr>

            <p>
                By using this service, you acknowledge that you have read and agree to our:
            </p>

            <ul style="list-style:disc;padding-left:25px;">
                <li>
                    <a href="https://congen.skriptx.com/privacy" target="_blank">
                        Privacy Policy
                    </a>
                </li>
                <li>
                    <a href="https://congen.skriptx.com/terms" target="_blank">
                        Terms of Service
                    </a>
                </li>
                <li>
                    <a href="https://congen.skriptx.com/disclaimer" target="_blank">
                        Disclaimer
                    </a>
                </li>
                <li>
                    <a href="https://support.skriptx.com/" target="_blank">
                        Support
                    </a>
                </li>
            </ul>

        </div>
    </div>

</div>
