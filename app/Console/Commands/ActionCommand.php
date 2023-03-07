<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ActionCommand extends GeneratorCommand
{
    /**
     * The name of your command.
     * This is how your Artisan's command shall be invoked.
     */
    protected $name = 'make:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Action Class.';

    /**
     * Class type that is being created.
     * If command is executed successfully you'll receive a
     * message like this: $type created successfully.
     * If the file you are trying to create already
     * exists, you'll receive a message
     * like this: $type already exists!
     */
    protected $type = 'Action class';

    /**
     * Specify your Stub's location.
     */
    protected function getStub()
    {
        return  base_path().'/stubs/action.stub';
    }

    /**
     * The root location where your new file should
     * be written to.
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Actions';
    }
}
