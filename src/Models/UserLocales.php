<?php

namespace Alorse\TranslationManager\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * App\UserLocale
 *
 * @property-read \App\User $user
 * @mixin \Eloquent
 */
class UserLocales extends Eloquent
{
    protected $table = 'ltm_user_locales';

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('laravel-translation-manager.default_connection'));
    }
}

