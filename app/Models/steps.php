<?php
class Step extends Model
{
    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
