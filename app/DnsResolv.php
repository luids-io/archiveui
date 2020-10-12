<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class DnsResolv extends Model
{
    protected $collection = 'resolvs';
    protected $dates = ['timestamp'];
}