<?php

declare(strict_types=1);

namespace Modules\Ticket\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public bool $enable_registration;
    public ?string $site_logo;
    public ?string $enable_social_login;
    public ?string $site_language;
    public ?string $default_role;
    public ?string $enable_login_form;
    public ?string $enable_oidc_login;

    public static function group(): string
    {
        return 'general';
    }
}
