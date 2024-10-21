<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identification Options
    |--------------------------------------------------------------------------
    |
    | These configuration options establish immutable options available throughout
    | the application when working with Identification model instances.
    |
    */
    'identification' => [
        'types' => [
            'document' => 'Document',
            'knowledge' => 'Personally known to me',
            'witness-document' => 'Credible witness with identification',
            'witness-knowledge' => 'Credible witness that is personally known to me',
        ],

        'document_types' => [
            'drivers-license' => 'Driver\'s License',
            'passport' => 'Passport',
            'consular-id' => 'Consular ID',
            'state-id' => 'State ID',
            'other' => 'Other Government-issued Document with Photo and Signature',
        ],

        'statuses' => [
            'pending' => 'Pending',
            'verified' => 'Verified',
        ],

        'verification_types' => [
            'manual' => 'Manual',
            'electronic' => 'Electronic', // TODO: implement electronic verification
        ],
    ],
];
