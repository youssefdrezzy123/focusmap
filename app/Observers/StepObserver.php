<?php

namespace App\Observers;

use App\Models\Step;

class StepObserver
{
    /**
     * Handle the Step "created" event.
     *
     * @param  \App\Models\Step  $step
     * @return void
     */
    public function created(Step $step)
    {
        //
    }

    /**
     * Handle the Step "updated" event.
     *
     * @param  \App\Models\Step  $step
     * @return void
     */
    public function updating(Step $step)
    {
        if ($step->isDirty('due_date')) {
            // Envoyer une notification si la date change
        }
        
        if ($step->isDirty('completed') && $step->completed) {
            // Notifier lorsque une étape est complétée
        }
    }

    /**
     * Handle the Step "deleted" event.
     *
     * @param  \App\Models\Step  $step
     * @return void
     */
    public function deleted(Step $step)
    {
        //
    }

    /**
     * Handle the Step "restored" event.
     *
     * @param  \App\Models\Step  $step
     * @return void
     */
    public function restored(Step $step)
    {
        //
    }

    /**
     * Handle the Step "force deleted" event.
     *
     * @param  \App\Models\Step  $step
     * @return void
     */
    public function forceDeleted(Step $step)
    {
        //
    }
}
