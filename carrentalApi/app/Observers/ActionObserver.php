<?php

namespace App\Observers;

use App\Models\ActionLog;
use Auth;

class ActionObserver
{
    private function createActionLog($object, $action) {
        $id = Auth::id();
        if ($id) {
            $actionLog = new ActionLog();
            $actionLog->user_id = $id;
            $actionLog->action = $action;
            $actionLog->action_log_id = $object->id;
            $actionLog->action_log_type = get_class($object);
            $actionLog->data = json_encode($object->getAttributes(), JSON_UNESCAPED_UNICODE);
            $actionLog->save();
        }
    }

    /**
     * Handle to the Action "created" event.
     *
     * @param mixed $instance
     * @return void
     */
    public function created($object)
    {
        $this->createActionLog($object, 'create');
    }

    /**
     * Handle the Action "updated" event.
     *
     * @param  mixed $instance
     * @return void
     */
    public function updated($object)
    {
        $this->createActionLog($object, 'update');
    }

    /**
     * Handle the Action "deleted" event.
     *
     * @param  mixed $instance
     * @return void
     */
    public function deleted($object)
    {
        $this->createActionLog($object, 'delete');
    }
}
