<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'application-dt',
        'course-dt',
        'course_unpub-dt',
        'application_cancel-dt',
        'application_unSubmit-dt',
        'tech_duty-dt',
        'degree-dt',
        'org_rank-dt',
        'admin_duty-dt',
        'region-dt',
        'contact-dt',
        'contact-export',
        'class-dt'
        //
    ];
}
