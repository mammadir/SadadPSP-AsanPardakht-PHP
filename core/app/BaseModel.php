<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\jDate;

class BaseModel extends Model
{
    /**
     * @return bool|string
     */
    public function getJalaliCreatedAtAttribute()
    {
        return jDate::forge($this->created_at)->format('datetime');
    }

    /**
     * @return string
     */
    public function getFullJalaliCreatedAtAttribute()
    {
        return jDate::forge($this->created_at)->format('%d %B %Y') . ' ' . lang('lang.time') . ' ' . jDate::forge($this->created_at)->format('time');
    }

    /**
     * @return string
     */
    public function getFullJalaliUpdatedAtAttribute()
    {
        return jDate::forge($this->updated_at)->format('%d %B %Y') . ' ' . lang('lang.time') . ' ' . jDate::forge($this->updated_at)->format('time');
    }
}
