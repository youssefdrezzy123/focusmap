<?php
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Goal extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function goals()
    {
        return $this->hasMany(\Goal::class);
    }
    public function steps()
    {
        return $this->hasMany(Step::class);
    }
    public function updateProgress()
{
    $completedSteps = $this->steps()->where('completed', true)->count();
    $totalSteps = $this->steps()->count();
    
    $this->progress = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    $this->save();
}
}