<?php
// Global configuration for the CRM

// Google OAuth 2.0 credentials
const GOOGLE_CLIENT_ID = 'YOUR_GOOGLE_CLIENT_ID';
const GOOGLE_CLIENT_SECRET = 'YOUR_GOOGLE_CLIENT_SECRET';
const GOOGLE_REDIRECT_URI = 'https://yourdomain.com/auth/callback.php';

// Google service account credentials JSON file path
const SERVICE_ACCOUNT_FILE = __DIR__ . '/google_api/service_account.json';

// Google Sheet ID used as the CRM database
const SHEET_ID = 'YOUR_GOOGLE_SHEET_ID';

// Lead status options used throughout the app
$LEAD_STATUSES = [
    'New',
    'Contacted',
    'Follow-Up',
    'Scheduled Visit',
    'Visited',
    'Booked',
    'Not Interested',
    'Invalid Lead',
    'Converted'
];

// Gmail address that will send notification emails
const NOTIFY_FROM = 'yourcompany@gmail.com';
const NOTIFY_FROM_NAME = 'KCR Leads';

?>
