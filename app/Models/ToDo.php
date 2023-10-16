<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ToDo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    /**
     * Remove the specified ToDo if the authenticated user owns it.
     *
     * @param ToDo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function userOwns()
    {
        return $this->user_id === Auth::id();
    }
}
