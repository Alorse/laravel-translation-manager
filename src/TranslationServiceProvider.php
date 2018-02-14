<?php namespace Alorse\TranslationManager;

use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;

class TranslationServiceProvider extends BaseTranslationServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLoader();
        
        $db_driver = config('database.default');

        if ($db_driver === 'pgsql') {
            $translatorRepository = 'Alorse\TranslationManager\Repositories\PostgresTranslatorRepository';
        } else {
            $translatorRepository = 'Alorse\TranslationManager\Repositories\MysqlTranslatorRepository';
        }

        $this->app->bind(
            'Alorse\TranslationManager\Repositories\Interfaces\ITranslatorRepository',
            $translatorRepository
        );

        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new \Alorse\TranslationManager\Translator($app, $loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            if ($app->bound(\Alorse\TranslationManager\ManagerServiceProvider::PACKAGE)) {
                $trans->setTranslationManager($app[\Alorse\TranslationManager\ManagerServiceProvider::PACKAGE]);
            }

            return $trans;
        });
    }
}
